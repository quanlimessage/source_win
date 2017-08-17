<?php
/*******************************************************************************
管理者ID/PASSの更新

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_M1.php");	// 設定情報
require_once("dbOpe.php");			// ＤＢ操作クラスライブラリ
require_once("util_lib.php");			// 汎用処理クラスライブラリ

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
<script type="text/javascript" src="inputCheck.js"></script>
<script type="text/javascript">
<!--
// 入力チェック
function inputChk(f){

	var flg = false;var error_mes = "";

	if(!f.new_id.value){error_mes += "・IDを入力してください。\n\n";flg = true;}
	else if(!f.new_id.value.match(/^[0-9a-zA-Z]{4,8}$/)){error_mes += "・IDは半角英数字で4文字以上8文字以内で入力してください。\n\n";flg = true;}

	if(!f.new_pw.value){error_mes += "・パスワードを入力してください。\n\n";flg = true;}
	else if(!f.new_pw.value.match(/^[0-9a-zA-Z]{4,8}$/)){error_mes += "・パスワードは半角英数字で4文字以上8文字以内で入力してください。\n\n";flg = true;}

	if(!f.new_pw2.value){error_mes += "・パスワード（確認用）を入力してください。\n\n";flg = true;}
	if(f.new_pw.value != f.new_pw2.value){error_mes += "・パスワードが確認用に入力したパスワードと一致しません。\n\n";flg = true;}

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

	// IDチェック
	$error_message = "";
	if(empty($new_id)){
		$error_message.="IDが未入力です。<br>\n";
	}

	//入力文字数制限を行う場合処理を行う
		elseif(!ereg("^[0-9A-Za-z]{4,8}$",$new_id)){
		$error_message.="IDは半角英数字で4文字以上8文字以内で入力してください。<br>\n";
	}

	// パスワードチェック
	if(empty($new_pw)){
		$error_message.="パスワードが未入力です。<br>\n";
	}

	//入力文字数制限を行う場合処理を行う
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
		# データを更新し、管理者宛にメール通知をしてスルー
		#--------------------------------------------------------

		// 新ID／PWでデータ更新（DBに都合が悪い数字はエスケープ）
		$sql = "
		UPDATE
			M1_PRODUCT_LST
		SET
			ID = '".utilLib::strRep($new_id,5)."',
			PW = '".utilLib::strRep($new_pw,5)."'
		WHERE
			(RES_ID = '1')
		";
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("DB更新に失敗しました<hr>{$db_result}");

		// 完了画面を表示
		echo	"<p class=\"explanatio\"><strong>更新が完了しました</strong></p>\n",
				"<form action=\"./\" method=\"post\">\n",
				"<input type=\"submit\" value=\"ID/PASS管理トップへ戻る\">\n</form>\n";

	endif;

else:

	//お客様が使用している会員ID,パスワードを抽出
	$sql = "
		SELECT
			RES_ID,ID,PW
		FROM
			M1_PRODUCT_LST
		WHERE
			(RES_ID = '1')
		AND
			(DEL_FLG = '0')
	";

	// ＳＱＬを実行
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>

<form action="../main.php" method="post" target="_self">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title"><?php echo M1_TITLE;?></p>
<p class="explanation">
▼ID、パスワードを更新するには<strong>「ID/パスワードを更新する」</strong>のボタンを押してください。
</p>

<table width="500" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="3" class="tdcolored">
		■現在のID/PASS
		</th>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">現在のID：</td>
		<td class="other-td">&nbsp;
		<?php echo ($fetch[0]['ID'])?$fetch[0]['ID']:"現在設定されておりません";?>
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">現在のパスワード：</td>
		<td class="other-td">&nbsp;
		<?php echo ($fetch[0]['PW'])?$fetch[0]['PW']:"現在設定されておりません";?>
		</td>
	</tr>
</table>
<br>

<form action="./" method="post" onSubmit="return inputChk(this);" style="margin:0px;">

<table width="500" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="3" class="tdcolored">
		■ID/PASSの変更
		</th>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">ID：</td>
		<td class="other-td">
		※半角英数字で4文字以上8文字以内<br>
		<input name="new_id" type="text" size="30" maxlength="20" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">パスワード：</td>
		<td class="other-td">
		※半角英数字で4文字以上8文字以内<br>
		<input name="new_pw" type="text" size="30" maxlength="20" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">パスワード（確認用）：</td>
		<td class="other-td">
		※再度新しい管理パスワードを入力してください。<br>
		<input name="new_pw2" type="text" size="30" maxlength="20" style="ime-mode:disabled;">
		</td>
	</tr>

	<tr>
		<td class="other-td" colspan="2">
		<br>
		<input type="submit" value="ID/パスワードを更新する" style="width:200px;">
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
