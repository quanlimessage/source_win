<?php
/*******************************************************************************
管理情報更新プログラム
	※主にお問い合わせ用のメールアドレス情報を更新する

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();;
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_N6_1.php");	// 設定情報
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
	$pop3_host = mb_convert_kana($pop3_host,"a");
	$user_name = mb_convert_kana($user_name,"a");
	$pass = mb_convert_kana($pass,"a");

	// 入力値のエラーチェック
	$error_mes = "";

	$error_mes .= utilLib::strCheck($pop3_host,0,"POP3ホストを入力してください。\n");
	$error_mes .= utilLib::strCheck($user_name,0,"アカウント名を入力してください。\n");
	$error_mes .= utilLib::strCheck($pass,0,"パスワードを入力してください。\n");

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
			POP3 = '".utilLib::strRep($pop3_host,5)."',
			USER_NAME = '".utilLib::strRep($user_name,5)."',
			PASS = '".utilLib::strRep($pass,5)."'
		WHERE
			(RES_ID = '1')
		";
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("データの更新に失敗しました<hr>{$db_result}");

	endif;

endif;

// DBより全管理情報を取得する
$sql = "SELECT POP3,USER_NAME,PASS FROM APP_INIT_DATA WHERE (RES_ID = '1')";
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
<title><?php echo N6_1MAIL;?></title>
<script type="text/javascript">
<!--
// 入力チェック
function inputChk(f){

	var flg = false;var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	if(!f.pop3_host.value){error_mes += "・POP3ホストを入力してください。\n\n";flg = true;}

	if(!f.user_name.value){error_mes += "・アカウント名を入力してください。\n\n";flg = true;}

	if(!f.pass.value){error_mes += "・パスワードを入力してください。\n\n";flg = true;}

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
<p class="page_title"><?php echo N6_1MAIL;?></p>
<p class="explanation">
▼使用するメールの【POP3ホスト】【アカウント名】【パスワード】を入力してください。<br>
▼修正を終えられましたら下記にあります「<strong>上記の内容で更新</strong>」と書かれたボタンを押してください。
</p>
<form action="./change_config.php" method="post" onSubmit="return inputChk(this);" style="margin:0;">

<table width="500" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th width="100" class="tdcolored">POP3ホスト：</th>
		<td align="left"><input name="pop3_host" type="text" size="60" maxlength="100" value="<?php echo ($_POST['pop3_host'])?$_POST['pop3_host']:$fetch[0]['POP3'];?>" style="ime-mode:disabled;"></td>
	</tr>
	<tr>
		<th width="100" class="tdcolored">アカウント名：</th>
		<td align="left"><input name="user_name" type="text" size="60" maxlength="100" value="<?php echo ($_POST['user_name'])?$_POST['user_name']:$fetch[0]['USER_NAME'];?>" style="ime-mode:disabled;"></td>
	</tr>
	<tr>
		<th width="100" class="tdcolored">パスワード：</th>
		<td align="left"><input name="pass" type="text" id="pass" style="ime-mode:disabled;" value="<?php echo ($_POST['pass'])?$_POST['pass']:$fetch[0]['PASS'];?>" size="20"></td>
	</tr>
</table>
<br>

<input type="submit" value="上記の内容で更新" style="width:150px;">
<input type="hidden" name="action" value="update">
</form>
<div class="footer"></div>
</body>
</html>