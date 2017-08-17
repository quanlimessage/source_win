<?php
/*******************************************************************************
管理者情報更新プログラム
    ※主にお問い合わせ用のメールアドレス情報を更新する

2005/11/01 Yossee
*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/blog_config.php");    // 設定情報

#===============================================================================
# 更新ボタンが押されたら送信データをチェックし、DBの管理者情報を更新する
#===============================================================================
if ($_POST["action"] == "update"):

    // POSTデータの受取と文字列処理
    extract(utilLib::getRequestParams("post", [8,7,1,4], true));

    // 半角英数字に統一
    $email1 = mb_convert_kana($email1, "a");

    // 入力値のエラーチェック
    $error_mes = "";

    $error_mes .= utilLib::strCheck($email1, 0, "メールアドレス１を入力してください。\n");
    if (!empty($email1)) {
        $e_chk = "";

        $e_chk .= utilLib::strCheck($email1, 1, true);
        $e_chk .= utilLib::strCheck($email1, 4, true);
        $e_chk .= utilLib::strCheck($email1, 5, true);
        $e_chk .= utilLib::strCheck($email1, 6, true);

        if ($e_chk) {
            $error_mes .= "メールアドレス１の入力内容に誤りがあります。<br>\n";
        }
    }

    #--------------------------------------------------------------------
    # 入力内容に不備がある場合はエラー出力で強制終了し、
    # 正常処理が出来た場合は入力データを更新する
    #--------------------------------------------------------------------
    if ($error_message):

        utilLib::errorDisp($error_message);exit();
    else:

        $sql = "
		UPDATE
			BLOG_APP_INIT_DATA
		SET
			EMAIL1 = '".utilLib::strRep($email1, 5)."'
		WHERE
			(RES_ID = '1')
		";
        $PDO->regist($sql);

    endif;

endif;

// DBより全管理者情報を取得する
$sql = "SELECT EMAIL1 FROM BLOG_APP_INIT_DATA WHERE(RES_ID = '1')";
$fetch = $PDO->fetch($sql);

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, true, true, true);
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
▼修正を終えられましたら下記にあります「<strong>確認</strong>」と書かれたボタンを押してください。
</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return inputChk(this);" style="margin:0;">
<table width="400" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<td align="left" class="tdcolored">
		<strong>メールアドレス</strong>：
		<br>
		&nbsp;&nbsp;&nbsp;&nbsp;▼管理ID・パスワード変更時通知アドレス。	  		</td>
	</tr>
	<tr>
		<td align="left"><input name="email1" type="text" size="60" maxlength="100" value="<?php echo ($_POST['email1'])?$_POST['email1']:$fetch[0]['EMAIL1'];?>" style="ime-mode:disabled;"></td>
	</tr>
</table>
<br>
<input type="submit" value="上記の内容で更新" style="width:150px;">
<input type="hidden" name="action" value="update">
</form>
<div class="footer"></div>
</body>
</html>
