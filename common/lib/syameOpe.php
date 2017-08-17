<?php
/***********************************************************************
 写メール操作クラスライブラリ スーパーライト版

 2005.4.11  by Yossee
 ※オリジナルより多次元配列を返すのみの機能に絞ったもの
 ※写メールBBS by ToR 2002/09/25 http://php.s3.to
   上記のソースをベースにPOP3からのメールデータ取得部分のみいただき！
***********************************************************************/

date_default_timezone_set('Asia/Tokyo'); // timezoneの設定

class syameOpe{

#----------------------------------------------------------------------------
# POP3からのデータ取得
#
#	メソッド：getPOP3();
#	引数：受信メールサーバー（POP3サーバー）の設定情報
# 		※HOST , USER , PASS の順に引数を指定
#----------------------------------------------------------------------------
function getPOP3($pop_host,$pop_user,$pop_pass){

	// 引数チェック
	if(empty($pop_host)||empty($pop_user)||empty($pop_pass))die("指定のメール情報を確認してください");

	// POP3サーバにアクセス
	$sock = fsockopen($pop_host,110,$err,$errno,10) or die("POP3サーバに接続できません！");

	$buf = fgets($sock,512);
	if(substr($buf, 0, 3) != '+OK')die($buf);

	$buf = syameOpe::_sendcmd("USER ".$pop_user,$sock);
	$buf = syameOpe::_sendcmd("PASS ".$pop_pass,$sock);

	//STAT -件数とサイズ取得 +OK 8 1234
	$data = syameOpe::_sendcmd("STAT",$sock);
	sscanf($data, '+OK %d %d', $num, $size);


	if($num == "0"):

		 // 件数が0ならバイバイ
		$buf = syameOpe::_sendcmd("QUIT",$sock);
		fclose($sock);
		return false;
	
	else:
	
		// 件数分のデータを取得し配列に格納
		for($i=0;$i<$num;$i++):

			//RETR n -n番目のメッセージ取得（ヘッダ含）
			$no = ($i+1);
			$line = syameOpe::_sendcmd("RETR {$no}",$sock);

			//EOFの.まで読む
			while (!ereg("^\.\r\n",$line)) {
				$line = fgets($sock,512);
				$dat[$i].= $line;
			}

			//DELE n n番目のメッセージ削除
			$data = syameOpe::_sendcmd("DELE {$no}",$sock);

		endfor;

		//バイバイ
		$buf = syameOpe::_sendcmd("QUIT",$sock);
		fclose($sock);

		// 取得した配列データを返す
		return $dat;

	endif;

}

#----------------------------------------------------------------------------
# POP3から取得したデータをバラして多次元配列に変換
#
# メソッド：getQuery(getPOP3メソッドで取得したデータ);
# 戻り値：抽出データを多次元配列で戻す
#----------------------------------------------------------------------------
function getQuery($dat){
	
	// 第１引数チェック
	if(empty($dat) || !is_array($dat))die("第１引数が設定されていないか、データ形式に誤りがあります！");

	// 引数で渡された件数分のデータを抽出処理
	for($i=0;$i<count($dat);$i++):

		// データをヘッダ部と本文に分割
		$subject = $from = $text = $atta = $part = $attach = $tmp = "";
		list($head, $body) = syameOpe::mime_split($dat[$i]);

		// 日付の抽出
		eregi("Date:[ \t]*([^\r\n]+)", $head, $datereg);
		$now = strtotime($datereg[1]);
		if($now == -1) $now = time();
		$head = ereg_replace("\r\n? ", "", $head);

		// サブジェクトの抽出
		if(eregi("\nSubject:[ \t]*([^\r\n]+)",$head,$subreg)){
			$subject = $subreg[1];
			while(eregi("(.*)=\?iso-2022-jp\?B\?([^\?]+)\?=(.*)",$subject,$regs)){	//MIME Bﾃﾞｺｰﾄﾞ
				$subject = $regs[1].base64_decode($regs[2]).$regs[3];
			}
			while(eregi("(.*)=\?iso-2022-jp\?Q\?([^\?]+)\?=(.*)",$subject,$regs)) {//MIME Bﾃﾞｺｰﾄﾞ
				$subject = $regs[1].quoted_printable_decode($regs[2]).$regs[3];
			}
			$subject = htmlspecialchars(mb_convert_encoding($subject,"EUC-JP","JIS,SJIS"));
		}

		// 送信者アドレスの抽出
		if(eregi("From:[ \t]*([^\r\n]+)",$head,$freg)){
			$from = syameOpe::addr_search($freg[1]);
		}
		elseif(eregi("Reply-To:[ \t]*([^\r\n]+)",$head,$freg)){
			$from = syameOpe::addr_search($freg[1]);
		}
		elseif(eregi("Return-Path:[ \t]*([^\r\n]+)",$head,$freg)){
			$from = syameOpe::addr_search($freg[1]);
		}

		// マルチパートならばバウンダリに分割
		if(eregi("\nContent-type:.*multipart/",$head)){

			eregi('boundary="([^"]+)"',$head,$boureg);
			$body = str_replace($boureg[1],urlencode($boureg[1]),$body);
			$part = split("\r\n--".urlencode($boureg[1])."-?-?",$body);

			//multipart/altanative
			if(eregi('boundary="([^"]+)"',$body,$boureg2)){
				$body = str_replace($boureg2[1],urlencode($boureg2[1]),$body);
				$body = eregi_replace("\r\n--".urlencode($boureg[1])."-?-?\r\n","",$body);
				$part = split("\r\n--".urlencode($boureg2[1])."-?-?",$body);
			}

		}
		else{
			// 普通のテキストメール
			$part[0] = $dat[$i];
		}

		// 本文の処理
		foreach($part as $multi):

			list($m_head, $m_body) = syameOpe::mime_split($multi);
			$m_body = ereg_replace("\r\n\.\r\n$", "", $m_body);
			if(!eregi("Content-type: *([^;\n]+)", $m_head, $type))continue;
			list($main, $sub) = explode("/", $type[1]);

			// 本文をデコード
			if(strtolower($main) == "text"){
				if(eregi("Content-Transfer-Encoding:.*base64",$m_head))$m_body = base64_decode($m_body);
				if(eregi("Content-Transfer-Encoding:.*quoted-printable",$m_head))$m_body = quoted_printable_decode($m_body);
				$text = mb_convert_encoding($m_body,"EUC-JP","JIS,SJIS");
				$text = htmlspecialchars($text);
				$text = eregi_replace("([[:digit:]]{11})|([[:digit:]\-]{13})", "", $text);	// 電話番号削除
				$text = eregi_replace("[_]{25,}", "", $text);	// 下線削除
				$text = ereg_replace("Content-type: multipart/appledouble;[[:space:]]boundary=(.*)","",$text);	// mac削除
			}

			#-----------------------------------------------------------------------
			# 添付データをデコードして変数に格納し、ファイル名をユニークにする
			# 	※ファイル名：送信時間をUNIX時間にしたもの＋マイクロ時間を6桁に整形
			#-----------------------------------------------------------------------
			if(eregi("Content-Transfer-Encoding:.*base64", $m_head) && eregi("gif|jpe?g|png",$sub)){
				$tmp = base64_decode($m_body);
				if(eregi("jpe?g",$sub))$sub = "jpg";
				$attach = date("U",$now)."-".sprintf("%06d",(microtime() * 1000000)).".{$sub}";
	    	}

		endforeach;

		// 取得データを多次元配列に格納
		// 2005-04-27 Modify With K.C
		$line[$i]["id"] = $i;
		//$line[$i]["now"] = gmdate("Y.m.d H:i:s", $now+9*3600);
		$line[$i]["now"] = gmdate("Y-m-d H:i:s", $now+9*3600);
		$line[$i]["subject"] = $subject;
		$line[$i]["from"] = $from;
		$line[$i]["text"] = $text;
		$line[$i]["attach"] = $attach;
		$line[$i]["tmp"] = $tmp;

	endfor;

	// 多次元配列に加工されたデータを戻す
	return $line;

}

#----------------------------------------------------------------------------
# プライベートメソッド集	※このクラス内のみで使用
#----------------------------------------------------------------------------

// コマンドー送信！！
function _sendcmd($cmd,$sock){
	fputs($sock,$cmd."\r\n");
	$buf = fgets($sock, 512);
	if(substr($buf, 0, 3) == '+OK'):
    	return $buf;
	else:
		die($buf);
	endif;

	return false;
}

// ヘッダと本文を分割する
function mime_split($data){
	$part = split("\r\n\r\n", $data, 2);
	$part[1] = ereg_replace("\r\n[\t ]+", " ", $part[1]);
	return $part;
}

// メールアドレスを抽出する
function addr_search($addr){
	if(eregi("[-!#$%&\'*+\\./0-9A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+",$addr,$fromreg)):
		return $fromreg[0];
	else:
		return false;
	endif;
}


// Class syameOpeの終了
}
?>