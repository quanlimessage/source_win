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

	////////////////////////////////////////////////////////////////
	//gmailでReply-Toをカンマ区切りでメールを送信すると弾かれる仕様なのでカンマがあった場合は一番最初のメールアドレスを
	//Reply-Toに設定する（この設定はエンドユーザーに送信するメールに対して行う）
	//mb_send_mailのReturn-Pathの設定
		$rt_email = substr($mailto,0,strpos($mailto,","));//メールアドレスがカンマ区切りになっていた場合用にメールアドレスを一つだけ抽出処理
		$rt_email = ($rt_email)?$rt_email:$mailto;//空っぽだった場合はカンマ区切りは無し

#-------------------------------------------------------------------------------------------
# メール送信処理（送信先はindex.phpで設定した$mailto宛）
#-------------------------------------------------------------------------------------------

// Subjectを設定
$subject = "【自動送信メール】Webよりお問い合わせがありました";

// Headerとbodyとsubjectを設定（送信元はお客様 $email）
$headers = "Reply-To: ".$email."\n";
$headers .= "Return-Path: ".$email."\n";
$headers .= "From: ".mb_encode_mimeheader("自動送信メール")."<{$mailto}>\n";
	$rpath = "-f ".$email;//mb_send_mailのReturn-Path を設定

//メールアドレスの入力がある場合は下記の文言を表示する
$disp_words = ($email)?"折り返しご連絡される際は、下記メールアドレス宛にご送信ください。":"";

//メール本文に表示させるフォームのURL
$mbody_url = "http://".$_SERVER["HTTP_HOST"].str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);

// メール本文
$mailbody = "
----本メールはメールサーバーから自動的に送信されています。-----

以下URLのフォームより、お客様からお問い合わせをいただきました。

{$mbody_url}

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
	$mailbody = mbody_auto_lf($mailbody,400);//改行無しで長文を入力された場合の対応処理(区切る数字が短い場合URLなどが途中で区切られてしまう)

//エラーがあれば格納をする、ここでエラーを表示するとデザインが崩れるため
$err_mes = "";

// メール送信実行（結果を取得しコントローラーで次の処理を判断）
if(!empty($mailto) && preg_match("/^(.+)@(.+)\\.(.+)$/",$mailto)){

	$sendmail_result = mb_send_mail($mailto,$subject,$mailbody,$headers,$rpath);

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

	//HTMLメールのテンプレートを読み込む
		$mailbody2 = file_get_contents('../test.html');

	//HTMLのクラス
		$html_mail = new JPHPMailer();

		$html_mail->addTo($mailto2);//エンドユーザーのメールアドレス
		$html_mail->setFrom($mailto,"会社名");//送り主のメールアドレスと会社名
		$html_mail->setSubject($subject2);//件名
		$html_mail->setHtmlBody($mailbody2);//メール本文
		$html_mail->setAltBody("");//特に使わない

		if (!$html_mail->send()){//送信に失敗した場合
			   $err_mes = "確認メール送信に失敗しました<br>\n誠に申し訳ございませんが最初から操作をやり直してください。";
		}

?>
