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
$mailto = getInitData("EMAIL1");
$content = getInitData("CONTENT");
#-------------------------------------------------------------------------------------------
# メール送信処理（送信先はindex.phpで設定した$mailto宛）
#-------------------------------------------------------------------------------------------

// Subjectを設定
$subject = "【自動送信メール】Webよりお問い合わせがありました";

// Headerとbodyとsubjectを設定（送信元はお客様 $email）
$headers = "Reply-To: ".$email."\r\n";
$headers .= "Return-Path: ".$email."\r\n";
$headers .= "From: ".mb_encode_mimeheader("自動送信メール")."<{$mailto}>\r\n";

//メールアドレスの入力がある場合は下記の文言を表示する
$disp_words = ($email)?"折り返しご連絡される際は、下記メールアドレス宛にご送信ください。":"";

// メール本文
$mailbody = "
----本メールはメールサーバーから自動的に送信されています。-----

以下URLのフォームより、お客様からお問い合わせをいただきました。

http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."

※このメールに直接ご返信いただくことはできません。
{$disp_words}

●名前
	$name

●フリガナ
	$kana

●性別
	$sex

●郵便番号
	{$zip1} - {$zip2}

●ご住所
	{$state}{$address}

●電話番号
	$tel

●FAX番号
	$fax

●メールアドレス
	$email

●コメント：
$comment

========================================================
";

	$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

//エラーがあれば格納をする、ここでエラーを表示するとデザインが崩れるため
$err_mes = "";

// メール送信実行（結果を取得しコントローラーで次の処理を判断）
if(!empty($mailto) && ereg("^(.+)@(.+)\\.(.+)$",$mailto)){

	$sendmail_result = mb_send_mail($mailto,$subject,$mailbody,$headers);

	if(!$sendmail_result){
		$err_mes = "メール送信に失敗しました<br>\n誠に申し訳ございませんが最初から操作をやり直してください。";
		//utilLib::errorDisp("メール送信に失敗しました<br>\n誠に申し訳ございませんが最初から操作をやり直してください。");
	}
}
else{
	$err_mes = "メールを送信する事が出来ませんでした。<br>\n誠に申し訳ございませんがWebマスター宛に直接メールにて<br>お問い合わせしていただけますようお願い申し上げます";
	//utilLib::errorDisp("メールを送信する事が出来ませんでした。<br>\n誠に申し訳ございませんがWebマスター宛に直接メールにて<br>お問い合わせしていただけますようお願い申し上げます");
}

/************************************************************************
 自動返信メール
************************************************************************/

$mailto2 = $email;
$subject2 = "【{$name}様　お問い合わせありがとうございます。】";
$headers2 = "Reply-To: ".$mailto."\r\n";
$headers2 .= "Return-Path: ".$mailto."\r\n";
$headers2 .= "From: ".mb_encode_mimeheader("会社名", "JIS", "B", "\n")."<{$mailto}>\r\n";

// メール本文
$mailbody2 = "
{$name}様

$content

";
$mailbody2 = str_replace("\r","", $mailbody2);
if(!empty($mailto2)){
 if(ereg("^(.+)@(.+)\\.(.+)$",$mailto2)){

  $sendmail_result = mb_send_mail($mailto2,$subject2,$mailbody2,$headers2);

  if(!$sendmail_result){
   $err_mes = "確認メール送信に失敗しました<br>\n誠に申し訳ございませんが最初から操作をやり直してください。";
   //utilLib::errorDisp("メール送信に失敗しました<br>\n誠に申し訳ございませんが最初から操作をやり直してください。");
  }
 }
 else{
  $err_mes = "確認メールを送信する事が出来ませんでした。<br>\n誠に申し訳ございませんがWebマスター宛に直接メールにて<br>お問い合わせしていただけますようお願い申し上げます";
  //utilLib::errorDisp("メールを送信する事が出来ませんでした。<br>\n誠に申し訳ございませんがWebマスター宛に直接メールにて<br>お問い合わせしていただけますようお願い申し上げます");
 }
}
?>