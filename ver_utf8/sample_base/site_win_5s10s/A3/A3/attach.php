<?php
/********************************************************************************

添付ファイル付きメール送信関数
sendAttachMail($from,$to,$subject,$body,$attach_data,$filename);

From, To は、メアドのみを渡す。 "who" とかは駄目。
$subject, $body, $attach_data, $filename のエンコードは関数内でやるので、
普通に utf8 を渡せば OK 。
*********************************************************************************/

function sendAttachMail($from,$to,$subject,$body,$attach,$filename){

	$boundary = "-*-*-*-*-*-*-*-*-Boundary_".uniqid("b");

	// サブジェクトを jis にして、MIME エンコード
	//$subject = i18n_mime_header_encode(i18n_convert($subject,"JIS"));
	//$subject = i18n_mime_header_encode(i18n_convert($subject,"utf8"));

	//大きいサイズのファイルを送信された場合件名が分割されてしまうので下記の処理をさせる
	$subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($subject,"JIS","UTF-8"))."?=";

	// 本文を jis に
	//$body = i18n_convert($body, "JIS");
	$body = mb_convert_encoding($body, "JIS");//PHPのverが5の場合はこちらを使用
	//$body = i18n_convert($body, "utf8");

	/*
	ファイル名を sjis にして MIME エンコード。
	RFC 違反なので日本語ファイル名は使用しないほうがいい。
	$filename = i18n_mime_header_encode( i18n_convert($filename, "SJIS") );
	*/

	// メールの送信
	$mp = popen("/usr/sbin/sendmail -f $from $to", "w");

	#----------------------------------------------------------
	#メールの組み上げ
	#----------------------------------------------------------
	// 全体のヘッダ
	fputs($mp,"MIME-Version: 1.0\n");
	fputs($mp,"Content-Type: Multipart/Mixed; boundary=\"{$boundary}\"\n");
	fputs($mp,"Content-Transfer-Encoding:Base64\n");
	fputs($mp,"From: ".mb_encode_mimeheader("自動送信メール")."<{$from}>\n");
	fputs($mp,"To: {$to}\n");
	fputs($mp,"Subject: {$subject}\n");

	// メール本文のパート
	fputs($mp,"--{$boundary}\n");
	fputs($mp,"Content-Type: text/plain; charset=\"ISO-2022-JP\"\n");
	fputs($mp,"\n");
	fputs($mp,"{$body}\n");

			// 添付するデータを、base64 でエンコードして、RFC に適した書式に
			for($i=0;$i < count($attach);$i++){
				if($filename[$i]){//ファイル名があれば実行
					$attach_tmp[$i] = chunk_split(base64_encode($attach[$i]));

					// 添付ファイルのパート
					fputs($mp,"--{$boundary}\n");
					fputs($mp,"Content-Type: application/octet-stream; name=\"{$filename[$i]}\"\n");
					fputs($mp,"Content-Transfer-Encoding: base64\n");
					fputs($mp,"Content-Disposition: attachment; filename=\"{$filename[$i]}\"\n");
					fputs($mp,"\n");
					fputs($mp,"{$attach_tmp[$i]}\n");
					fputs($mp,"\n");
				}
			}

	// マルチパートのおわり。
	fputs($mp,"--{$boundary}"."--\n");
	pclose($mp);
}
?>
