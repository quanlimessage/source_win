<?php
/*******************************************************************************
会員メール配信 バックオフィス（MySQL対応版）
Logic：一括送信

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("Location: ../"); exit();
}

#-------------------------------------------------------------------------------------------
# メール送信処理
#-------------------------------------------------------------------------------------------

		// Subjectを設定
		$subject = $SUBJECT;

		set_time_limit(600);

		// メール送信実行（結果を取得しコントローラーで次の処理を判断）

			// メール本文
			$mailbody = file_get_contents('http://samplepg.zeeksdg.net/new_sample_base_update/site_win_5s10s_update/H1A_html_test/test.html');

			//$mailbody = $COMMENT . "\n" . $company_info;

			$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする

			$subject = html_char_conv($subject);//htmlspecialcharsの文字処理が行われているので実態参照されている文字を直す
			$mailbody = html_char_conv($mailbody);//htmlspecialcharsの文字処理が行われているので実態参照されている文字を直す

		//$mailbody=str_replace("\r\n", "\n", $mailbody);
		//$mailbody=str_replace("\r", "\n", $mailbody);

		$bgMail = new bgMailDB($subject, $mailbody, $company_name, $web_mail);
		$bgMail->sendmail($fetchCustList, "EMAIL");

		header("Location: ./?status=send_check");

?>
