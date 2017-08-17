<?php
/*******************************************************************************

並び替えプログラム
    ※sort.php（このファイル自身）とsort.jsの２つのファイルで構成

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
# $_POST['action']があれば新しく並び変えた順番に更新する
#===============================================================================
if (($_POST['action'] == "update")&&(!empty($_POST['new_view_order']))):

    // POSTデータの受け取りと共通な文字列処理
    extract(utilLib::getRequestParams("post", [8,7,1,4]));

    #===============================================================================
    # １．並び順が格納されたhiddenデータをタブをデリミタにしてバラす（配列に格納）
    #	・対象のhiddenデータ：$new_view_order（RES_IDがタブ区切りになっている）
    #	・新しいVIEW_ORDERの番号：$voの要素番号に1を足したもの
    #	・他のhiddenデータ：$category_code（対象のカテゴリーコード）
    #		※カテゴリ分類する場合のみ発生します。デフォルトではつけていません。
    #
    # ２．並び替えを更新するＳＱＬを発行（バラした件数分設定する）
    #===============================================================================
    $vo = explode("\t", $new_view_order);

    for ($i=0;$i<count($vo);$i++) {
        $sql[$i] = "
		UPDATE
			BLOG_ENTRY_LST
		SET
			VIEW_ORDER = '".($i+1)."'
		WHERE
			(RES_ID = '".$vo[$i]."')
		AND
			(CATEGORY_CODE = '".$ca."')
		AND

			(DEL_FLG = '0')
		";
    }

    // ＳＱＬを実行
    $PDO->regist($sql);

    // 最後に並び替えのトップへ飛ばす
    //header("Location: {$_SERVER['PHP_SELF']}");

endif;

#===============================================================================
# 現在の表示順に商品リストを表示
#===============================================================================

$ca = $_POST["ca"];

// 現在の並び順でデータを取得
$sql = "
SELECT
	RES_ID,TITLE,VIEW_ORDER,DISPLAY_FLG
FROM
	BLOG_ENTRY_LST
WHERE
	(DEL_FLG = '0')
AND
	(CATEGORY_CODE = '".$ca."')
ORDER BY
	VIEW_ORDER ASC
";
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
<script language="JavaScript" src="sort.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br>
<br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="./" method="post">
		<input type="submit" value="記事リスト画面へ戻る" style="width:150px;">
		<input type="hidden" name="ca" value="<?php echo $ca;?>">
		</form>
		</td>
	</tr>
</table>

<p class="page_title">更新プログラムの並び替え</p>
<p class="explanation">
▼現在表示されている並び順です。<br>
▼変更したい記事を選択し、下記の「↑UP」「↓DOWN」を使用して並び替えを行ってください。<br>
▼最後に「上記の並び替え順で更新」をクリックすると並び替えの変更が適用されます。
</p>
<?php
if (!$fetch):
    echo "<strong>登録されている記事はありません。</strong><br><br>";
else:
?>
<div>現在の登録記事数：&nbsp;<strong><?php echo count($fetch); ?></strong>&nbsp;件</div>
<table width="700" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="50%" valign="top">
		<table width="100%" border="1" cellpadding="2" cellspacing="0" style="height:inherit;">
			<tr class="tdcolored">
				<th nowrap class="back2">タイトル</th>
				<th width="10%" nowrap class="back2">記事画像</th>
				<th width="20%" class="back2">現在の<br>表示順</th>
			</tr>
			<?php for ($i=0;$i<count($fetch);$i++):?>
			<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
				<td align="center">&nbsp;<?php	echo $fetch[$i]['TITLE']; ?></td>
				<td align="center">
					<?php if (file_exists(IMG_FILE_PATH.$fetch[$i]['RES_ID'].'_1.jpg')):?>
						<img src="<?php echo IMG_FILE_PATH.$fetch[$i]['RES_ID'];?>_1.jpg?r=<?php echo rand();?>" border="0" width="<?php echo IMGSIZE_SX;?>" height="<?php echo IMGSIZE_SY;?>">
					<?php else:?>
						&nbsp;
					<?php endif;?>
				</td>
				<td align="center"><?php echo $fetch[$i]['VIEW_ORDER'];?></td>
			</tr>
			<?php endfor; ?>
	  </table>
	</td>
	<td width="57%" align="left" valign="top">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<form name="change_sort" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin:0;">
			<div style="float:left;margin-right:0.5em">
				<select name="nvo" size="<?php echo (count($fetch) > 20)?20:count($fetch);// sizeは20件を基準にしておく?>">
				<?php
                for ($i=0;$i<count($fetch);$i++) {
                    echo "<option value=\"{$fetch[$i]['RES_ID']}\">{$fetch[$i]['VIEW_ORDER']}：{$fetch[$i]['TITLE']}</option>\n";
                }?>
				</select>
			</div>

			<div style="float:left;">
				<input type="button" value="↑UP" onClick="moveUp();" style="width:70px;"><br>
				<input type="button" value="↓DOWN" onClick="moveDn();" style="width:70px;">
			</div>

			<div style="clear:left;">
				<input type="button" value="上記の並び替え順で更新" style="margin-top:0.5em;width:150px;" onClick="change_sortSubmit();">
				<input type="hidden" name="action" value="update">
				<input type="hidden" name="new_view_order" value="">
				<input type="hidden" name="ca" value="<?php echo $ca;?>">

			</div>
			</form>
			</td>
		</tr>
	</table>
	</td>
</tr>
</table>
<?php endif; ?>
<div class="footer"></div>
</body>
</html>
