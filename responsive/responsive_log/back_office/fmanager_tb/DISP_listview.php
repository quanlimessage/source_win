<?php
/*******************************************************************************
アクセス解析ファイルマネージャー
View：アクセスログファイル一覧表示（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../index.php");exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,false,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<p class="page_title">アクセス解析：ファイルマネージャー</p>
<p class="explanation">
▼<strong>「削除」</strong>をクリックすると登録されている該当ログファイルが削除されます。<br>
▼<strong>削除したファイルは復帰できません。</strong>十分に注意して処理を行ってください。<br><br>
▼<strong>「レポート」</strong>をクリックするとレポートデータ形式でのバックアップを<br>
<strong>「CSV出力」</strong>をクリックするとCSVデータ形式でのバックアップをとることが出来ます。<br>
ファイルを削除する際に用途に合わせてそれぞれの方法でバックアップをとることをお勧めします。
</p>
<table width="600" border="1" cellpadding="2" cellspacing="0">
	<tr class="tdcolored">
		<th width="15%" nowrap>データ日付</th>
		<th nowrap>ファイル名</th>
		<th width="5%" nowrap>ファイルサイズ</th>
		<th width="10%" nowrap>解析レポート出力</th>
		<th width="10%" nowrap>CSV出力</th>
	    <th width="5%" nowrap>削除</th>
	</tr>
<?php
$dir = opendir(ACCESS_PATH);
while($strfile[] = readdir($dir));
rsort($strfile);
reset($strfile);
closedir($dir);
?>
	<?php foreach($strfile as $v):?>
	<?php if(strstr($v,FILE_SUFFIX)):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td align="center">&nbsp;<?php $db_fname = explode("_",$v);
										echo $db_fname[0]."年".$db_fname[1]."月";?></td>
		<td align="center">&nbsp;<?php echo $v;?></td>
		<td align="center">&nbsp;<?php echo round(filesize(ACCESS_PATH.$v)/(1024 * 1024),2)."MB";?></td>
		<td align="center">
		<form method="post" action="file.php" style="margin:0;">
		<input type="submit" value="レポート">
		<input type="hidden" name="filename" value="<?php echo $v;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="csv.php" style="margin:0;">
		<input type="submit" value="CSV出力">
		<input type="hidden" name="filename" value="<?php echo $v;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="./" style="margin:0;" onSubmit="return confirm('このアクセスログファイルを完全に削除します。\nアクセスログファイルの復帰は出来ません。\nよろしいですか？');">
		<input type="submit" value="削除">
		<input type="hidden" name="filename" value="<?php echo $v;?>">
		<input type="hidden" name="action" value="del_file">
		</form>
		</td>
	</tr>
	<?php endif; ?>
	<?php endforeach;?>
</table>
<div class="footer" style="margin-top:5em;"><?php //echo FOOTER_TITLE;?></div>
</body>
</html>
