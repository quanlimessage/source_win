<?php
/*******************************************************************************
管理ID/PASSの更新

2005/10/31 : Yossee
*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../");exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config.php");	// 設定情報
require_once("dbOpe.php");					// ＤＢ操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>

<script type="text/javascript">
<!--
// 入力チェック
function inputChk(f){

	var flg = false;var error_mes = "";

	if(!f.new_id.value){error_mes += "・新管理IDを入力してください。\n\n";flg = true;}
	else if(!f.new_id.value.match(/^[0-9a-zA-Z]{4,8}$/)){error_mes += "・新管理IDは半角英数字で4文字以上8文字以内で入力してください。\n\n";flg = true;}

	if(!f.new_pw.value){error_mes += "・新管理パスワードを入力してください。\n\n";flg = true;}
	else if(!f.new_pw.value.match(/^[0-9a-zA-Z]{4,8}$/)){error_mes += "・新管理パスワードは半角英数字で4文字以上8文字以内で入力してください。\n\n";flg = true;}

	if(f.new_pw.value != f.new_pw2.value){error_mes += "・新パスワードの指定が正しくありません。\n\n";flg = true;}

	if(flg){window.alert(error_mes);return false;}
	else{return confirm('入力いただいた内容で登録します。\nよろしいですか？');}
}
//-->
</script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
#=============================================================
# 送信ボタンが押された場合の処理
#	１．データチェック（不正ならエラー表示）
#	２．入力されたIDPW情報でデータを更新
#	３．ＯＫの場合メールを送信
#=============================================================
if($_POST["action"] == "update"):

	// 	POSTデータの受取と文字列処理。
	extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

	// 半角英数字に統一
	$new_id	= mb_convert_kana($new_id,"a");
	$new_pw	= mb_convert_kana($new_pw,"a");
	$new_pw2 = mb_convert_kana($new_pw2,"a");

	// IDチェック
	$error_message = "";
	if(empty($new_id)){
		$error_message.="新IDが未入力です。<br>\n";
	}
	elseif(!ereg("^[0-9A-Za-z]{4,8}$",$new_id)){
		$error_message.="IDは半角英数字で4文字以上8文字以内で入力してください。<br>\n";
	}

	// パスワードチェック
	if(empty($new_pw)){
		$error_message.="新パスワードが未入力です。<br>\n";
	}
	elseif(!ereg("^[0-9A-Za-z]{4,8}$",$new_pw)){
		$error_message.="パスワードは半角英数字で4文字以上8文字以内で入力してください。<br>\n";
	}

	// 一致の確認
	if($new_pw != $new_pw2){
		$error_message.="パスワードが確認用に入力したパスワードと一致しません。<br>\n";
	}

	// エラーがあればエラー表示して終了
	if($error_message):

		utilLib::errorDisp($error_message);exit();
	else:

		#--------------------------------------------------------
		# データを更新し、管理宛にメール通知をしてスルー
		#--------------------------------------------------------

		// 新ID／PWでデータ更新（DBに都合が悪い数字はエスケープ）
		$sql = "
		UPDATE
			APP_ID_PASS
		SET
			BO_ID = '".utilLib::strRep($new_id,5)."',
			BO_PW = '".utilLib::strRep($new_pw,5)."'
		WHERE
			(RES_ID = '2')
		";
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("DB更新に失敗しました<hr>{$db_result}");

		// 管理情報（EMAIL）を取得
		$sql = "SELECT EMAIL1 FROM APP_INIT_DATA WHERE(RES_ID = '1')";
		$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		$mailbody = "\nホームページの管理用ID/管理パスワードが変更されました。\n\n";

		// 新管理ID/パスワード通知の指示があったらメールに記載
		if($notice == 1){
			$mailbody .= "-------------------------------\n";
			$mailbody .= "新管理ID:{$new_id}\n";
			$mailbody .= "新管理パスワード:{$new_pw}\n";
			$mailbody .= "-------------------------------\n";
		}

		// 更新情報
		$mailbody .= "\n更新日時：".date("Y/m/d H:i:s")."\n";

		// 件名とフッター
		$subject = "管理ID/管理パスワードが変更されました。";

		$headers = "Reply-To: ".$fetch[0]["EMAIL1"]."\n";
		$headers .= "Return-Path: ".$fetch[0]["EMAIL1"]."\n";
		$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".$fetch[0]["EMAIL1"].">\n";

		// メール送信（失敗時：強制終了）
		$webmstmail_result = mb_send_mail($fetch[0]["EMAIL1"],$subject,$mailbody,$headers);
		if(!$webmstmail_result)die("Send Mail Error! for WebMaster");

		// 完了画面を表示
		echo	"<p class=\"explanatio\"><strong>更新が完了しました</strong></p>\n",
				"<form action=\"./change_idpw.php\" method=\"post\">\n",
				"<input type=\"submit\" value=\"ID/PASS管理トップへ戻る\">\n</form>\n";

	endif;

else:
?>

<form action="../main.php" method="post" target="_self">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ID/PASS管理</p>
<p class="explanation">
▼管理ID、管理パスワードを変更したい場合は下記に新IDと新パスワードを入力後、<strong>「ID/パスワードを更新する」</strong>をクリックしてください。<br>
▼ID/パスワードを変更すると自動的に通知メールが<strong>「管理情報」</strong>で登録した<strong>「管理用メールアドレス」</strong>に送信されます。<br>
▼通知メールに変更後の新ID、新パスワードを確認用に記載するには<strong>「通知メールに新ID・新パスワードを記載する」</strong>へチェックを入れてください。
</p>
<form action="./change_idpw.php" method="post" onSubmit="return inputChk(this);" style="margin:0px;">
<table width="500" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="3" class="tdcolored">
		■管理ID/PASSの変更
		</th>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">新しい管理ID：</td>
		<td class="other-td">
		※半角英数字で4文字以上8文字以内<br>
		<input name="new_id" type="text" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">新しい管理パスワード：</td>
		<td class="other-td">
		※半角英数字で4文字以上8文字以内<br>
		<input name="new_pw" type="password" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">新しい管理パスワード(確認)：</td>
		<td class="other-td">
		※再度新しい管理パスワードを入力してください。<br>
		<input name="new_pw2" type="password" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td class="other-td" colspan="2">
		※管理IDと管理パスワードを変更すると管理用メールアドレス宛にID/パスワードが変更された旨の通知メールが送信されます。<br>
		メール内容に新規IDと新規パスワードを記載する場合はチェックを入れてください。<br>
		<input type="checkbox" name="notice" value="1" id="1"><label for="1"><strong>通知メールに新ID・新パスワードを記載する</strong></label>
		<br><br>
		<input type="submit" value="ID/パスワードを更新する" style="width:150px;">
		</td>
	</tr>
</table>
<br><br>
<input type="hidden" name="action" value="update">
</form>

<?php endif;?>
<div class="footer"></div>
</body>
</html>
