<?php
/********************************************************************************

HTMLメール送信関数
sendHtmlMail($from,$to,$subject,$body);

From, To は、メアドのみを渡す。 "who" とかは駄目。
$subject, $bodyのエンコードは関数内でやるので、
普通に EUC を渡せば OK 。
*********************************************************************************/

function sendHtmlMail ( $from, $to, $subject, $body) {
$boundary = "-*-*-*-*-*-*-*-*-Boundary_" . uniqid("b");

    ### サブジェクトを jis にして、MIME エンコード
    $subject = i18n_mime_header_encode( i18n_convert($subject, "JIS") );

    ### 本文を jis に
    $body = i18n_convert($body, "JIS");

    ### ファイル名を sjis にして MIME エンコード。
    ### RFC 違反なので日本語ファイル名は使用しないほうがいい。
    ## $filename = i18n_mime_header_encode( i18n_convert($filename, "SJIS") );

    ### メールの送信
    $mp = popen("/usr/sbin/sendmail -f $from $to", "w");

    ########################## メールの組み上げ
    ### 全体のヘッダ
    fputs($mp, "MIME-Version: 1.0\n");
    fputs($mp, "Content-Type: Multipart/Mixed; boundary=\"$boundary\"\n");
    fputs($mp, "Content-Transfer-Encoding:Base64\n");
    fputs($mp, "From: $from\n");
    fputs($mp, "To: $to\n");
    fputs($mp, "Subject: $subject\n");

    ### メール本文のパート
    fputs($mp, "--$boundary\n");
    fputs($mp, "Content-Type: text/html; charset=\"ISO-2022-JP\"\n");
    fputs($mp, "\n");
    fputs($mp, "$body\n");

    ### マルチパートのおわり。
    fputs($mp, "--$boundary" . "--\n");
    pclose($mp);
}
?>