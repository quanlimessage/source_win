<?php
/**********************************************************************************************************
 汎用ライブラリ集（頻繁に処理するロジックをライブラリ化したもの）

 クラス名：utilLib
 AUTHOR : Yossee

 コンストラクタ：指定なし
 ※new utilLib()でインスタンス作成、もしくはutilLib::method()として使用する

 メソッド一覧（2004/7/5 現在）
	errorDisp()			HTMLでエラー出力。（入力チェック等のエラー出力等が主な用途）
	strRep()			対象文字列に対して指定（モード）に従い文字列処理を行う
	strCheck()			対象文字列に対して指定（モード）に従いデータに不備がないか調べる
	httpHeadersPrint()	引数の指定に応じたHTTPヘッダを出力する
	browserChk()		少数ブラウザの判別（ネットスケープ4.xに代表される問題のあるブラウザチェック）
	limitExec()		期間制限（期間中のみ、もしくは開始時間に合わせて何かを実行させる）
	getRequestParams()	リクエストパラメーター（POSTまたはGET）を受け取り、文字列の置換処理を行う
**********************************************************************************************************/
class utilLib{


#-----------------------------------------------------------------
# ★エラー出力
#	関数名：errorDisp(引数)
#	引数：エラーメッセージ
#-----------------------------------------------------------------
function errorDisp($error){

echo "<html><head><title>Error!!</title></head>";
echo "<body bgcolor=\"#FFFFFF\">";
echo "<h2 align=\"center\">";

	echo "<font color=\"red\">$error</font>"; 

echo "</h2><h2 align=\"center\"></h2>";
echo "<p></p><p align=\"center\">";
echo "<form><div align=\"center\">";
echo "<input type=\"button\" value=\"戻る\" onClick=\"history.back()\"></div>";
echo "</form>";
echo "<p></p><h2 align=\"center\"></h2>";
echo "</body></html>";

}
#-------------------------------------------------------------------------------------------------------------------
# ★文字列処理（対象文字列に対して指定（モード）に従い文字列処理を行います）
#	関数名:	strRep(対象文字列,モード);	※配列対応。	
#	帰り値：モードに応じて以下の処理を加えて返す。対象文字列が配列の場合は配列で返る。
#	
#	0：危険文字削除（\,|,&,@,;,`）2004/3/16 バグ修正と危険文字追加
#	1：HTML特殊文字（<、>、&）を無効化
#	2：改行コードを除去
#	3：改行コードを<br>に置き換え	※結果が“<br />”にしたい（XHTML使用等で）場合、nl2br関数の使用を推奨
#	4：stripslashesで\対策	（※php.iniのmagic_quotes_gpcがONの場合のみ）
#	5：ＤＢへのデータ格納時に必要な文字をエスケープ。
#	6：カンマ(,)を無効化（実体参照）する
#	7：空白のコントロールコードと前後の半角及び全角の空白を強制除去		※2004/2/6と13 見直し修正。
#	8：タグを除去(html&php) ※例外タグが必要な場合は直接strip_tags関数を使用する事。
#-------------------------------------------------------------------------------------------------------------------
function strRep($str,$mode){

	switch($mode):
	
		case 0:		//危険文字削除（,\,|,&）モード
			
			if(!empty($str)){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++):
						$str[$i] = ereg_replace("\\\\","",$str[$i]);
						$str[$i] = ereg_replace("\|","",$str[$i]);
						$str[$i] = ereg_replace("&","",$str[$i]);
						$str[$i] = ereg_replace("\@","",$str[$i]);
						$str[$i] = ereg_replace(";","",$str[$i]);
						$str[$i] = ereg_replace("`","",$str[$i]);
					endfor;
				else:
					$str = ereg_replace("\\\\","",$str);
					$str = ereg_replace("\|","",$str);
					$str = ereg_replace("&","",$str);
					$str = ereg_replace("\@","",$str);
					$str = ereg_replace(";","",$str);
					$str = ereg_replace("`","",$str);
					
				endif;
			}
			
			break;
		case 1:		//HTML特殊文字（<、>、&）を無効化するモード
		
			if(!empty($str)){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++)$str[$i] = htmlspecialchars($str[$i]);
				else:
					$str = htmlspecialchars($str);
				endif;
			}

			break;	
		case 2:		//改行コードを除去するモード

			if(!empty($str)){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++)$str[$i] = ereg_replace("[\r\n]","",$str[$i]);
				else:
					$str = ereg_replace("[\r\n]","",$str);
				endif;
			}

			break;
		case 3:		//改行コードを<br>に置き換えするモード	※置換結果は“<br>”となる（<br />にするならnl2brを使用する）
			
			if(!empty($str)){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++)$str[$i] = ereg_replace("[\r\n]","<br>",$str[$i]);
				else:
					ereg_replace("[\r\n]","<br>",$str);
				endif;
			}

			break;
		case 4:		//stripslashesで\対策するモード（※php.iniのmagic_quotes_gpcがONの場合のみ）
		
			if(!empty($str) && get_magic_quotes_gpc()){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++)$str[$i] = stripslashes($str[$i]);
				else:
					$str = stripslashes($str);
				endif;
			}
		
			break;	
		case 5:		//ＤＢへのデータ格納時に必要な文字をエスケープ。

			if(!empty($str)){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++)$str[$i] = addslashes($str[$i]);
				else:
					$str = addslashes($str);
				endif;
			}

			break;
		case 6:		//カンマ(,)を無効化（実体参照）する

			if(!empty($str)){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++)$str[$i] = str_replace(",","&#44;",$str[$i]);
				else:
					$str = str_replace(",","&#44;",$str);
				endif;
			}

			break;
		case 7:		// 空白のコントロールコードと前後の半角及び全角の空白を強制除去	※2004/2/6 見直し修正

			if(!empty($str)):
				if(is_array($str)){
					for($i=0;$i<count($str);$i++):
						$str[$i] = ereg_replace("^[[:space:]]{0,}","",$str[$i]);
						$str[$i] = ereg_replace("[[:space:]]{0,}$","",$str[$i]);
						$str[$i] = mb_ereg_replace("^(　){0,}","",$str[$i]);
						$str[$i] = mb_ereg_replace("(　){0,}$","",$str[$i]);
						$str[$i] = trim($str[$i]);	# 最後にコントロールコードを除去する
					endfor;
				}
				else{
					$str = ereg_replace("^[[:space:]]{0,}","",$str);
					$str = ereg_replace("[[:space:]]{0,}$","",$str);
					$str = mb_ereg_replace("^(　){0,}","",$str);
					$str = mb_ereg_replace("(　){0,}$","",$str);
					$str = trim($str);	# 最後にコントロールコードを除去する
				}
			endif;

			break;
		case 8:		// タグを除去(html&php)

			if(!empty($str)){
				if(is_array($str)):
					for($i=0;$i<count($str);$i++)$str[$i] = strip_tags($str[$i]);
				else:
					$str = strip_tags($str);
				endif;
			}
	
			break;
	endswitch;

	return $str;	// データのリターン

}
#--------------------------------------------------------------------------------------------------------
# ★不正入力チェック
# 	関数名：strCheck(対象文字列,モード,表示させたい名前);
#	帰り値：不正があった場合のエラーメッセージ（共通）
#
#	※モード一覧
#	0:未入力チェック
#	1:スペースやブランクなどの空白文字チェック	
#	2:数字以外の文字が使われてるか
#	3:半角英数字以外の文字が使われているか
#	4:最後の文字に不正な文字が使われているか
#	5:不正かつ危険な文字が使われているか
#	6:メールアドレスチェック（E-Mailのみ）
#	7:真偽値チェック（真ならエラー）
#	8:真偽値チェック（偽ならエラー）
#	9:null値かどうかのチェック（null値ならエラー）
#				
#		※7～9はメッセージは自由形式。使用例としてはチェックボックスやラジオボタン等（応用が効けば用途は何でもＯＫ）。
#--------------------------------------------------------------------------------------------------------
function strCheck($str,$mode,$mes){
	
	$errmes = "";	//エラーメッセージの初期化
	switch($mode):
	
		case 0:		# 未入力ならエラー
			if($str == ""){$errmes .= "{$mes}<br>\n";}
			break;
		case 1:		# 空白文字を含んでいればエラー
			if(ereg("[[:blank:]]|[[:space:]]",$str)){$errmes .= "{$mes}<br>\n";}
			break;
		case 2:		# 数字以外の文字が使われていたらエラー
			if(ereg("[^0-9]",$str)){$errmes .= "{$mes}<br>\n";}
			break;
		case 3:		# 半角英数字以外の文字が使われていたらエラー
			if(ereg("[^_a-zA-Z0-9]",$str)){$errmes .= "{$mes}<br>\n";}
			break;
		case 4:		# 最後の文字に不正な文字が使われているかどうかのチェック。※URLやメール等。
			if(ereg("\.$|\/$|\@$|,$",$str)){$errmes .= "{$mes}<br>\n";}
			break;
		case 5:		# 不正かつ危険な文字が使われているかどうかのチェック。※URLやメール等。
			if(preg_match("/\||&|\/|\"|\\\/",$str)){$errmes .= "{$mes}<br>\n";}
			break;
		case 6:		# メールアドレスチェック（形式がおかしかったらエラー）
			if(!ereg("^(.+)@(.+)\\.(.+)$",$str)){$errmes .= "{$mes}<br>\n";}
			break;
		//case 6:	#メールアドレスチェック（形式がおかしかったらエラー）
		//	if(!ereg("^[^@]+@[^.]+\..+",$str)){$errmes .= "{$mes}<br>\n";}
		//	break;
		case 7:		# 真偽値チェック：真ならエラー
			if($str == true){$errmes .= "{$mes}<br>\n";}
			break;
		case 8:		# 真偽値チェック：偽ならエラー
			if($str == false){$errmes .= "{$mes}<br>\n";}
			break;
		case 9:		# null値のチェック：null値ならエラー
			if($str == null){$errmes .= "{$mes}<br>\n";}
			break;

	endswitch;

	return $errmes;

}
#--------------------------------------------------------------------------------------------------------
# ★HTTPヘッダ出力
# 	関数名：httpHeadersPrint(引数１,引数２,引数３,引数４,引数５)
#	引数一覧（省略可）
# 		引数１．文字コード（直接記述。省略時はShift_JIS）
#		引数２．CSSとJSの設定（true:表示する faluse:表示しない）
#		引数３．無効の有効期限の有無（true:表示する faluse:表示しない）
#		引数４．キャッシュをさせない（true:表示する faluse:表示しない）
#		引数５．ロボット拒否（true:表示する faluse:表示しない）
#	※引数をすべて省略した場合はコンテントタイプがShift_JIS、言語が日本語のヘッダのみ表示	
#	返り値：なし
#--------------------------------------------------------------------------------------------------------
function httpHeadersPrint($charset="",$css_js_flg=false,$time_flg=false,$cache_flg=false,$robot_flg=false){

	/* 引数１：文字コードの設定（省略時はEUC-JP。ここは必ず表示） */
	if(!$charset)$charset = "EUC-JP";
	header("Content-Type: text/html; charset={$charset}");
	header("Content-Language: ja");

	/* 引数２：CSSとJSの設定 */
	if($css_js_flg){
		header("Content-Style-Type: text/css");
		header("Content-Script-Type: text/javascript");
	}

	/* 引数３： 無効の有効期限と毎アクセス更新されている状態にする	※二重送信およびイタズラ等のささやかな抑制。。。 */
	if($time_flg){
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	}

	/* 引数４：キャッシュさせない */
	if($cache_flg){
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Cache-Control: no-cache, no-store");
		header("Pragma: no-cache");
	}

	/* 引数５：ロボット拒否 */
	if($robot_flg){
		header("Robots: noindex,nofollow");
		header("Robots: noarchive");
	}

}
#--------------------------------------------------------------------------------------------------------
# ★少数ブラウザの判別（ネットスケープ4.xに代表される問題のあるブラウザのチェック）
#	関数名：browserChk()
#	引数：なし
#	返り値：真偽値（NN4.x及び少数や不明なブラウザなら真、標準ブラウザなら偽を返す）
#
# 使用例：
# $result = (browserChk())?"古いブラウザ":"普通のブラウザ";
#	echo $result;
#
# ※標準ブラウザ：IE／Netscape 6 or 7／Opera／safari／Mozilla(Gecko系)とする
#--------------------------------------------------------------------------------------------------------
function  browserChk(){
	$ua_chk = $_SERVER['HTTP_USER_AGENT'];
		
	if(stristr($ua_chk,"MSIE")){$result = false;}
	elseif(stristr($ua_chk,"Opera")){$result = false;}
	elseif(stristr($ua_chk,"Netscape")){$result = false;}
	elseif(stristr($ua_chk,"Safari")){$result = false;}
	elseif(stristr($ua_chk,"Gecko")){$result = false;}
	else{$result = true;}

	return $result;
}
#-------------------------------------------------------------------------------------------------------------
# ★期間制限（期間中のみ、もしくは開始時間に合わせて何かを実行させる）
#	関数名：limitExec($arg1,$arg2);
# 	引数１：開始時間	※年月日時分秒をそれぞれ配列で指定
# 	引数２：終了時間	※年月日時分秒をそれぞれ配列で指定（省略可。その場合は開始時間を過ぎた時の処理のみ）
# 	返り値：真偽値（期間中なら真を返し期間外なら偽を返す）
#-------------------------------------------------------------------------------------------------------------
function limitExec($start_arr,$end_arr=""){

	/* 開始時間のエラー処理をし、エラーがない場合は桁を揃えてデータを整形しておく */
	if(empty($start_arr)){die("開始時間が設定されていません");}
	elseif(is_array($start_arr)){$st = sprintf("%04d",$start_arr[0]);for($i=1;$i<=5;$i++)$st .= sprintf("%02d",$start_arr[$i]);}
	else{die("開始時間は年月日時分秒をそれぞれ配列で指定してください");}

	/* 終了時間が設定されている場合、エラー処理をし、エラーがなければ桁を揃えてデータを整形しておく */		
	if(!empty($end_arr)):
		if(is_array($end_arr)){$et = sprintf("%04d",$end_arr[0]);for($i=1;$i<=5;$i++)$et .= sprintf("%02d",$end_arr[$i]);}
		else{die("終了時間を設定する場合は年月日時分秒をそれぞれ配列で指定してください");}
	endif;

	/* 現在時刻を取得し、判定する（終了時間の有無で処理条件を変更する） */
	$nt = date("YmdHis");
	if(empty($end_arr)):

		$result = ($nt > $st)?true:false;
		return $result;

	else:
	
		$result = (($nt > $st) && ($nt < $et))?true:false;
		return $result;
	
	endif;

}
#------------------------------------------------------------------------------------------------------------------------------
# ★リクエストパラメーター（POSTまたはGET）を受け取り、文字列の置換処理を行う
#	関数名：getRequestParams(引数１,引数２,引数３)
#
#	引数一覧（引数はすべて省略可）
#		引数１：リクエストパラメーターのタイプ（POSTまたはGET）を指定する。省略または空白時はPOSTを受け取る。
#		引数２：このクラス内メソッドである“strRepメソッド”を使用して文字列置換。※utilLib::strRep(&$e,$mode)となっているソースがそれです。
#				引数は処理するモードを配列で指定する。省略または空白時は置換処理を行わない
#		引数３：半角カナ→全角カナへの処理（マルチバイト関数または国際化対応関数で処理を選択可）。
#				省略時はマルチバイト関数で処理を行う。明確にこの処理を行いたくない場合は、引数を“false”にする（""は不要）
#
#	戻り値：リクエストパラメーターを配列で戻す。単一の変数にしたい場合はextract関数の引数にこの関数を使用すればよい。
#
#	使用例：extract(getRequestParams("post",array(8,7,1,4),true));	
#			※POSTで受け取り、文字列置換（8,7,1,4）とマルチバイト関数で半角カナ→全角カナに置換し、変数に変換。
#
#			$array_get_data = getRequestParams("GET","","i");
#			※GETで受け取り、文字列置換を行わず、国際化対応関数で半角カナ→全角カナに置換し、結果を配列で取得。
#
#			$array_post_data = getRequestParams("","",false);
#			※POSTで受け取り、何も処理をせず変数に変換。$array_post_data = $_POST;と同じなので全く意味無い処理となる。
#------------------------------------------------------------------------------------------------------------------------------
function getRequestParams($type = "",$rep_mode = array(),$HanToZen = true){

	/* 一応関数内で使用する配列を初期化 */	
	$method_type = array();
	$param = array();

	/* POSTかGETを選択（引数省略はPOST） */
	switch($type):
		case "get": $method_type = $_GET;break;
		case "GET": $method_type = $_GET;break;
		case "post": $method_type = $_POST;break;
		case "POST": $method_type = $_POST;break;
		default: $method_type = $_POST;
	endswitch;

	/* リクエストパラメーターを与えられた引数を元に置換及び変換処理を行って配列で戻す */
	foreach($method_type as $k=>$e):

		# このクラス内メソッドのstrRepメソッドを使用した文字列置換（指定したモードの件数分処理）
		if(!empty($rep_mode))foreach($rep_mode as $mode)utilLib::strRep(&$e,$mode);
	
		# 半角カナ→全角カナへの処理（マルチバイト関数または国際化対応関数で処理を選択可）
		if(is_string($e) && $HanToZen):
			switch($HanToZen):
				case "m":$e = mb_convert_kana($e,"KV");break;
				case "i":$e = i18n_ja_jp_hantozen($e,"KV");break;
				default:$e = mb_convert_kana($e,"KV");break;
			endswitch;
		endif;

		$params[$k] = $e;
	endforeach;

	return $params;
}




// クラスutilLibの終了
}
?>