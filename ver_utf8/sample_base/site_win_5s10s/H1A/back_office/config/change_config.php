<?php
/*******************************************************************************
管理情報更新プログラム
	※主にお問い合わせ用のメールアドレス情報を更新する

*******************************************************************************/
session_start();

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config.php");	// 設定情報
require_once("dbOpe.php");					// ＤＢ操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ

#===============================================================================
# 更新ボタンが押されたら送信データをチェックし、DBの管理情報を更新する
#===============================================================================
if($_POST["action"] == "update"):

	// POSTデータの受取と文字列処理
	extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

	// 半角英数字に統一
	$email1 = mb_convert_kana($email1,"a");
	$email2 = mb_convert_kana($email2,"a");

	// 入力値のエラーチェック
	$error_mes = "";

	$error_mes .= utilLib::strCheck($email1,0,"メールアドレスを入力してください。\n");
	if(!empty($email1)){
		$e_chk = "";

		$e_chk .= utilLib::strCheck($email1,1,true);
		$e_chk .= utilLib::strCheck($email1,4,true);
		$e_chk .= utilLib::strCheck($email1,5,true);
		$e_chk .= utilLib::strCheck($email1,6,true);

		if($e_chk)$error_mes .= "メールアドレスの入力内容に誤りがあります。<br>\n";
	}

	/*$error_mes .= utilLib::strCheck($email2,0,"メールアドレス２を入力してください。\n");
	if(!empty($email2)){
		$e_chk = "";

		$e_chk .= utilLib::strCheck($email2,1,true);
		$e_chk .= utilLib::strCheck($email2,4,true);
		$e_chk .= utilLib::strCheck($email2,5,true);
		$e_chk .= utilLib::strCheck($email2,6,true);

		if($e_chk)$error_mes .= "メールアドレス２の入力内容に誤りがあります。<br>\n";
	}*/

	#--------------------------------------------------------------------
	# 入力内容に不備がある場合はエラー出力で強制終了し、
	# 正常処理が出来た場合は入力データを更新する
	#--------------------------------------------------------------------
	if($error_mes):

		utilLib::errorDisp($error_mes);exit();
	else:

		$sql = "
		UPDATE
			APP_INIT_DATA
		SET
			EMAIL1 = '".utilLib::strRep($email1,5)."',
			EMAIL2 = '".utilLib::strRep($email2,5)."'
		WHERE
			(RES_ID = '1')
		";
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("データの更新に失敗しました<hr>{$db_result}");

	endif;

endif;

// DBより全管理情報を取得する
$sql = "SELECT EMAIL1,EMAIL2 FROM APP_INIT_DATA WHERE(RES_ID = '1')";
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="text/css; charset=UTF-8">
<title></title>
<script type="text/javascript">
<!--
// 入力チェック
function inputChk(f){

	var flg = false;var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	if(!f.email1.value){error_mes += "・メールアドレス１を入力してください。\n\n";flg = true;}
	else if(!f.email1.value.match(/^[^@]+@[^.]+\..+/)){error_mes += "・メールアドレス１の形式に誤りがあります。\n\n";flg = true;}

	/*if(!f.email2.value){error_mes += "・メールアドレス２を入力してください。\n\n";flg = true;}
	else if(!f.email2.value.match(/^[^@]+@[^.]+\..+/)){error_mes += "・メールアドレス２の形式に誤りがあります。\n\n";flg = true;}
*/
	if(flg){window.alert(error_mes);return false;}
	else{return confirm('入力いただいた内容で登録します。\nよろしいですか？');}
}
//-->
</script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post" target="_self">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">管理情報の更新</p>
<p class="explanation">
▼修正を行う場合は下記の入力欄の内容を修正してください。<br>
▼修正を終えられましたら下記にあります「<strong>上記の内容で更新</strong>」と書かれたボタンを押してください。
</p>
<form action="./change_config.php" method="post" onSubmit="return inputChk(this);" style="margin:0;">
<table width="400" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<td align="left" class="tdcolored">
		<strong>メールアドレス（会員登録フォーム）</strong>：
		<br>
		&nbsp;&nbsp;&nbsp;&nbsp;▼会員登録フォームの受付アドレスに使用<br>
		&nbsp;&nbsp;&nbsp;&nbsp;▼管理ID・パスワード変更時通知アドレス。	  		</td>
	</tr>
	<tr>
		<td align="left"><input name="email1" type="text" size="60" maxlength="100" value="<?php echo ($_POST['email1'])?$_POST['email1']:$fetch[0]['EMAIL1'];?>" style="ime-mode:disabled;"></td>
	</tr>
</table>
<!--
<table width="400" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<td align="left" nowrap class="tdcolored">
		        <strong>メールアドレス２（代理店向けお問い合わせフォーム）</strong>：<br>
		&nbsp;&nbsp;&nbsp;&nbsp;▼代理店のお問合せフォームの受付アドレスに使用</td>
	</tr>
	<tr>
		<td align="left"><input name="email2" type="text" size="60" maxlength="100" value="<?php //echo ($_POST['email1'])?$_POST['email2']:$fetch[0]['EMAIL2'];?>" style="ime-mode:disabled;"></td>
	</tr>
</table>
-->
<input name="email2" type="hidden" value="<?php echo ($_POST['email1'])?$_POST['email2']:$fetch[0]['EMAIL2'];?>">
<br>
<input type="submit" value="上記の内容で更新" style="width:150px;">
<input type="hidden" name="action" value="update">
</form>
<div class="footer"></div>
</body>
</html>