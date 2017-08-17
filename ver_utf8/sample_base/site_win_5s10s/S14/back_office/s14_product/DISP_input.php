<?php
/*******************************************************************************
PICKUP更新プログラム

	DISP：完了画面出力

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
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

	//データベースからデータを取得させる
	$sql = "SELECT * FROM ".S14_PICKUP;

	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//データベースから受け取ったデータを渡す
	$title = $fetch[0]['TITLE'];
	$comment = $fetch[0]['PICKUP_COMMENT'];

if(!$comment){$comment = "まだ設定されていません";}

// 画像再読込み用パラメータ
$time = time();

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo S14_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>

<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>

<span class="page_title"><?php echo S14_TITLE;?></span>

<?php if($message){ echo "<br><span class=\"err\">".$message."</span>\n";} ?>

<br>現在の登録内容

	<table width="650" border="0" cellspacing="2" cellpadding="5">
		<tr>

			<td width="20%">
				<div align="center">

					<p class="other-td"><img src="<?php echo S14_IMG_PATH?>top.jpg?r=<?php echo $time?>"></p>
				</div>
			</td>
			<td class="other-td">
				タイトル：<?php echo $title; ?>
				<br><br>
				<?php echo nl2br($comment); ?>
			</td>
		</tr>
	</table>

	<form action="./" method="post" enctype="multipart/form-data">
		<table width="650" border="1" cellpadding="2" cellspacing="0">
			<tr class="other-td">

				<td width="20%" class="tdcolored"><div align="center">タイトル</div></td>
				<td><input name="title" type="text" value="<?php echo $fetch[0]['TITLE']; ?>" size="50" maxlength="127" style="ime-mode:active"></td>
			</tr>
			<tr class="other-td">

				<td width="20%" class="tdcolored"><div align="center">コメント</div></td>
				<td><textarea name="comment" cols="50" rows="10" id="comment"><?php echo $fetch[0]['PICKUP_COMMENT']; ?></textarea></td>
			</tr>
			<tr>

				<td class="tdcolored"><div align="center">画像</div></td>
				<td class="other-td">
					 <?php echo S14_IMGSIZE_MX;?> px ｘ <?php //echo S14_IMGSIZE_MY." px";?>自動算出
					 <br>
					<input name="thumbnail_file" type="file" id="thumbnail_file" size="50">
				</td>
			</tr>
		</table>

		<p>
			<input type="hidden" name="status" value="edit">

			<input type="submit" name="Submit" value="更新">
		</p>
	</form>

</body>
</html>
