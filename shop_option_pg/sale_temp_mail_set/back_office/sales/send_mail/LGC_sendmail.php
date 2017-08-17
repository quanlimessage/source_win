<?php
/************************************************************************
 お問い合わせフォーム（POST渡しバージョン）
 処理ロジック：最終処理（メール送信）

************************************************************************/

// 不正アクセスチェック
if(!$accessChk){
	header("HTTP/1.0 404 Not Found");exit();
}

// 送信先メールアドレス情報を取得
$mailto = WEBMST_SHOP_MAIL;//管理者のメールアドレス
$email = $fetchCUST[0][EMAIL];//購入者のメールアドレス

#-------------------------------------------------------------------------------------------
# メール送信処理（送信先はindex.phpで設定した$mailto宛）
#-------------------------------------------------------------------------------------------

//実態参照の文字をメールで問題なく見れるように補正させる。
$mail_subject = h14s_han2zen($subject);
$mailbody = h14s_han2zen($tmpl_data);

// フッター
$headers = "Reply-To: ".WEBMST_SHOP_MAIL."\n";
$headers .= "Return-Path: ".WEBMST_SHOP_MAIL."\n";
$headers .= "From:".mb_encode_mimeheader(WEBMST_NAME, "JIS", "B", "\n")."<".WEBMST_SHOP_MAIL.">\n";

	$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

//エラーがあれば格納をする、ここでエラーを表示するとデザインが崩れるため
$err_mes = "";

// メール送信実行（結果を取得しコントローラーで次の処理を判断）
if(!empty($email) && ereg("^(.+)@(.+)\\.(.+)$",$email)){

	$sendmail_result = mb_send_mail($email,$mail_subject,$mailbody,$headers);

	if(!$sendmail_result){
		$err_mes = "メール送信に失敗しました。";
	}
}
else{
	$err_mes = "メールを送信する事が出来ませんでした。";
}
?>
