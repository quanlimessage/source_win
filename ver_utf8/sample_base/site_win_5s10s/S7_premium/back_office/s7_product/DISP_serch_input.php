<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：商品検索画面（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="header"></div>
<table width="400" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="sort.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo TITLE; ?>：新規登録</p>

<p class="explanation">
▼新規データの登録を行う際は、<strong>「新規追加」</strong>をクリックしてください。<br>
▼最大登録件数は<strong><?php echo DBMAX_CNT;?>件</strong>です。
</p>

<?php
#-----------------------------------------------------
# 書込許可（最大登録件数に達していない）の場合に表示
#-----------------------------------------------------
	//最大件数を超えてない場合新規登録が出来るようにする
	if($fetchCNT[0]['CNT'] < DBMAX_CNT):?>
		<form action="./" method="post">
			<input type="submit" value="新規追加" style="width:150px;">
			<input type="hidden" name="act" value="new_entry">
		</form>
	<?php endif;?>

<p class="page_title"><?php echo TITLE; ?>：検索</p>
<p class="explanation">
▼更新設定を行う場合は該当商品を検索します。<br>
▼下記で検索条件を指定してください。条件にマッチした全データがリスト表示されます。<br>
▼何も条件を指定しなければ、全データがリスト表示されます。
</p>
<form action="./" method="post" style="margin:0px;">
<table width="500" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<td colspan="4" class="tdcolored">■登録済み記事検索</td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">カテゴリー：</td>
		<td class="other-td">
			<select name="search_ca">
				<option value="">▼選択してください</option>
				<?php for($i=0,$cnt = count($fetchCA);$i<$cnt;$i++):?>
				<option value="<?php echo $fetchCA[$i]['CATEGORY_CODE'];?>"<?php echo ($_POST['search_ca'] == $fetchCA[$i]['CATEGORY_CODE'])?" selected":"";?>><?php echo $fetchCA[$i]['CATEGORY_NAME'];?>(<?php echo count(${'fetchCA_ca'.$i});?>)</option>
				<?php endfor;?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">タイトル(一部でも可)：</td>
		<td class="other-td"><input type="text" name="search_title" value="<?php echo ($_POST["search_title"])?$_POST["search_title"]:"";?>"></td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">表示/非表示：</td>
		<td class="other-td" colspan="3">
		<input type="radio" name="search_display" value="1" id="d1" checked><label for="d1">指定無し</label><br>
		<input type="radio" name="search_display" value="2" id="d2"><label for="d2">現在表示中</label><br>
		<input type="radio" name="search_display" value="3" id="d3"><label for="d3">現在非表示中</label>
		</td>
	</tr>
</table>
<input type="submit" value="検索開始" style="width:150px;">
<input type="hidden" name="act" value="list">
</form>
</body>
</html>
