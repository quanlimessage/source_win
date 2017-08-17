<?php
/*******************************************************************************
ALL-INTERNET BLOG

更新：既存の登録内容修正：入力画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}

if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, true, true, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">カテゴリー管理：カテゴリー名の更新</p>
<p class="explanation">
▼カテゴリー名を上書きして「更新する」をクリックしてください。
</p>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" onSubmit="return inputChk(this);" style="margin:0px;">
<table width="400" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<td colspan="3" class="tdcolored">
		■更新
		</td>
	</tr>
	<tr>
	  <td width="150" nowrap class="tdcolored">カテゴリー名：</td>
		<td class="other-td">
		<input name="category_name" type="text" size="30" maxlength="200" value="<?php echo $fetch[0]["CATEGORY_NAME"];?>">
	  </td>
	</tr>
	<tr>
		<td class="tdcolored">アイコン画像：</td>
		<td class="other-td">
		<?php if (file_exists(IMG_FILE_PATH.$fetch[0]['CATEGORY_CODE']."_ca.jpg")):?>
		現在表示中の画像<br>
		<img src="<?php echo IMG_FILE_PATH.$fetch[0]['CATEGORY_CODE']."_ca.jpg";?>?r=<?php echo rand();?>" width="<?php echo CA_IMGSIZE_SX;?>" height="<?php echo CA_IMGSIZE_SY;?>">
		<input type="checkbox" name="del_img[]" value="ca" id="del_img"><label for="del_img">この画像を削除</label>
		<br>
		<?php endif;?>
		アップロード後画像サイズ：<br><strong>横<?php echo CA_IMGSIZE_SX;?>px×縦<?php echo CA_IMGSIZE_SY;?>px</strong>
		<br>
		<input type="file" name="up_img" value="">
	  </td>
	</tr>
</table>
<br>
<input type="submit" value="更新する" style="width:150px;">
<input type="hidden" name="status" value="completion">
<input type="hidden" name="regist_type" value="update">
<input type="hidden" name="category_code" value="<?php echo $category_code;?>">
</form>

<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
<input type="submit" value="カテゴリー管理トップへ" style="width:150px;">
</form>
</body>
</html>
