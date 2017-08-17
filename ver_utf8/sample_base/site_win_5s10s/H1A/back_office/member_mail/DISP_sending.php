<?php
/*******************************************************************************
会員メール配信

メール送信中画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);

if($sending_check){

	// ログファイル
	$new_log_count = file("./tmp/sendmailCLI_log.log");

	$old_log_count = ($_SESSION['old_log_count']+1);

	$st = count($new_log_count) - 1;
	for($i=$st;$i<count($new_log_count);$i++){
		list($time, $check, $msg,) = explode("\t", $new_log_count[$i]);
	}

	if(count($new_log_count) == $old_log_count && $check){

		if($check == 0){
			$_SESSION['check_msg'] = $msg;
		}

		header("Location: ./?err_check={$check}&status=completed");
	}

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?> System Back Office</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<body>
<form action="../main.php" method="post" target="_self">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title"><?php echo MEMBER_TITLE;?>：メルマガ送信画面</p>
<table width="100%" height="280" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<td><div align="center" class="page_title">
			送信中....
		</div><br><div align="center">送信完了したかどうか、送信チェックボタンを押して、チェックしてください。</div></td>
    </tr>
	<tr>
		<td height="38"><div align="center">
			<form name="form1" method="post" action="./">
				<input type="submit" name="Submit" value="送信チェック">
                <input name="status" type="hidden" id="status" value="send_check">
                <input name="sending_check" type="hidden" id="sending_check" value="1">
            </form>
		</div></td>
	</tr>
</table>
</body>
</html>
