<?php
/*******************************************************************************
管理者ID/PASSの管理

	メール送信

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

#=================================================================================
# DB更新の通知
#=================================================================================

# メール内容組み立て(設定情報と雛形の置換)
###########################################

// 本文雛形を読み込み
$mailbody = "
ホームページの管理用ID/管理パスワードが変更されました。

";

// 新管理ID/パスワード通知の指示があったらメールに記載
if($notice == 1):
$mailbody .= "
-------------------------------
新管理ID:{$new_id}
新管理パスワード:{$new_pw}
-------------------------------
";
endif;

// 更新情報
$mailbody .= "
更新日時：".date("Y/m/d H:i:s")."
";

// 件名とフッター
$subject = "管理ID/管理パスワードが変更されました。";

$headers = "Reply-To: ".WEBMST_SHOP_MAIL."\n";
$headers .= "Return-Path: ".WEBMST_SHOP_MAIL."\n";
$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".WEBMST_SHOP_MAIL.">\n";

// メール送信（失敗時：強制終了）
$webmstmail_result = mb_send_mail(WEBMST_SHOP_MAIL,$subject,$mailbody,$headers);
if(!$webmstmail_result)die("Send Mail Error! for WebMaster");

?>
