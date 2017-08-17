<?php
/*******************************************************************************
ALL-INTERNET BLOG

更新
View：選択画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
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
<p class="page_title">投稿カテゴリー管理：新規登録</p>
<p class="explanation">
▼登録するメニュー名を入力して「新規登録」をクリックしてください。
</p>
<?php if (!empty($error_message)):?>
<p class="err">
<?php echo $error_message?>
</p>
<?php	endif;?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin:0px;" enctype="multipart/form-data" onSubmit="return inputChk(this);">
<table width="400" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<td colspan="2" class="tdcolored">■新規登録<br>カテゴリーにアイコンを付ける場合には画像をアップロードしてください。</td>
	</tr>
	<tr>
		<td class="tdcolored" width="150">カテゴリー名：</td>
		<td class="other-td">
		<input type="text" name="category_name" size="30" maxlength="200" style="ime-mode:active;">
		</td>
	</tr>
	<tr>
		<td class="tdcolored">アイコン画像：</td>
		<td class="other-td">
		アップロード後画像サイズ：<br><strong>横<?php echo CA_IMGSIZE_SX;?>px×縦<?php echo CA_IMGSIZE_SY;?>px</strong>
		<br>
		<input type="file" name="up_img" value="">
	  </td>
	</tr>
</table>
<br>
<input type="submit" value="新規登録" style="width:150px;">
<input type="hidden" name="status" value="completion">
<input type="hidden" name="regist_type" value="new">
</form>
<p class="page_title">カテゴリー管理：更新</p>
<p class="explanation">
▼既存カテゴリー名を更新したい場合は編集ボタンをクリックしてください。<br>
▼カテゴリーを削除したい場合は削除ボタンを押してください。

</p>

<table border="1" cellpadding="2" cellspacing="0" width="600">
	<tr class="tdcolored">
		<th nowrap>カテゴリー名(エントリー件数)</th>
		<th width="15%">アイコン</th>
		<th width="5%" nowrap>編集</th>
	    <th width="5%" nowrap>削除</th>
	</tr>
<?php
    for ($i=0;$i<count($fetchList);$i++):
        $e_cnt = "";
        $sql_cnt = "SELECT COUNT(RES_ID) AS E_COUNT FROM BLOG_ENTRY_LST WHERE (DEL_FLG = '0') AND (CATEGORY_CODE = '".$fetchList[$i]['CATEGORY_CODE']."')";
        $fetEcnt = $PDO->fetch($sql_cnt);
        $e_cnt = "<strong>".$fetEcnt[0]["E_COUNT"]."</strong>";
?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td align="center">&nbsp;<?php echo (!empty($fetchList[$i]['CATEGORY_NAME']))?$fetchList[$i]['CATEGORY_NAME']."(".$e_cnt.")件":"No Title";?></td>
		<td align="center" valign="middle">
		<?php if (file_exists(IMG_FILE_PATH.$fetchList[$i]['CATEGORY_CODE']."_ca.jpg")):?>
		<a href="<?php echo IMG_FILE_PATH.$fetchList[$i]['CATEGORY_CODE'];?>_ca.jpg" target="_blank">
		<img src="<?php echo IMG_FILE_PATH.$fetchList[$i]['CATEGORY_CODE'];?>_ca.jpg?r=<?php echo rand();?>" alt="画像" border="0" width="<?php echo CA_IMGSIZE_SX;?>" height="<?php echo CA_IMGSIZE_SY;?>">
		</a>
		<?php else:
            echo '&nbsp;';
        endif;?>
		</td>
		<td align="center">
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin:0px;" onSubmit="return inputChk2(this);">
		<input type="hidden" name="category_code" value="<?php echo $fetchList[$i]['CATEGORY_CODE'];?>">
		<input type="hidden" name="status" value="edit">
		<input type="submit" value="編集" style="width:50px;">
		</form>
		</td>
		<td align="center">
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin:0px;" onSubmit="return DelConfim(this);">
		<input type="hidden" name="category_code" value="<?php echo $fetchList[$i]['CATEGORY_CODE'];?>">
		<input type="hidden" name="status" value="completion">
		<input type="hidden" name="regist_type" value="delete">
		<input type="submit" value="削除" style="width:50px;">
		</form>
		</td>
	</tr>
	<?php endfor;?>
</table>

</body>
</html>
