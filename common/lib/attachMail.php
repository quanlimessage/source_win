<?php
/********************************************************************************

添付ファイル付きメール送信関数
sendAttachMail($from,$to,$subject,$body,$attach,$filename);

From, To は、メアドのみを渡す。 "who" とかは駄目。
$subject, $body, $attach, $filename のエンコードは関数内でやるので、
普通に EUC を渡せば OK 。
*********************************************************************************/
function sendAttachMail($from,$to,$subject,$body,$attach,$filename){

	$boundary = "-*-*-*-*-*-*-*-*-Boundary_".uniqid("b");

	// サブジェクトを jis にして、MIME エンコード
	$subject = i18n_mime_header_encode(i18n_convert($subject,"JIS"));

	// 本文を jis に
	$body = i18n_convert($body, "JIS");

	// 添付するデータを、base64 でエンコードして、RFC に適した書式に
	$attach = chunk_split(base64_encode($attach));

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
	fputs($mp,"From: {$from}\n");
	fputs($mp,"To: {$to}\n");
	fputs($mp,"Subject: {$subject}\n");

	// メール本文のパート
	fputs($mp,"--{$boundary}\n");
	fputs($mp,"Content-Type: text/plain; charset=\"ISO-2022-JP\"\n");
	fputs($mp,"\n");
	fputs($mp,"{$body}\n");

	// 添付ファイルのパート
	fputs($mp,"--{$boundary}\n");
	fputs($mp,"Content-Type: application/octet-stream; name=\"{$filename}\"\n");
	fputs($mp,"Content-Transfer-Encoding: base64\n");
	fputs($mp,"Content-Disposition: attachment; filename=\"{$filename}\"\n");
	fputs($mp,"\n");
	fputs($mp,"{$attach}\n");
	fputs($mp,"\n");

	// マルチパートのおわり。
	fputs($mp,"--{$boundary}"."--\n");
	pclose($mp);
}
?>