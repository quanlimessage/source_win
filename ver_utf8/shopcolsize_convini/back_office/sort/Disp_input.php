<?php
/*******************************************************************************
カテゴリ対応
	バックオフィス

商品の更新／新規登録
View：登録済み商品一覧画面（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);

#=============================================================
# カテゴリ情報の取得
#=============================================================
$cate_sql="
SELECT
	CATEGORY_CODE,
	CATEGORY_NAME
FROM
	".CATEGORY_MST."
WHERE
	(DEL_FLG = '0')
";

$fetchCateList = dbOpe::fetch($cate_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//カテゴリー名の横に登録件数を表示させる

for($i=0;$i<count($fetchCateList);$i++){
	${'ca_cnt'.$i} = $fetchCateList[$i]['CATEGORY_CODE'];

	${'sql_ca'.$i} = "
	SELECT
		PRODUCT_ID,
		CATEGORY_CODE
	FROM
		".PRODUCT_LST."
	WHERE
		(CATEGORY_CODE = '${'ca_cnt'.$i}')
	AND
		(DEL_FLG = '0')
	";

	// ＳＱＬを実行
	${'fetchCA_ca'.$i} = dbOpe::fetch(${'sql_ca'.$i},DB_USER,DB_PASS,DB_NAME,DB_SERVER);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="text/css; charset=UTF-8">
<title><?php echo BO_TITLE; ?> Shopping System Back Office</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
<!--
function inputChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "";

	if(!f.category_id.value){
		error_mes += "・カテゴリを選択してください。\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
}//-->
</script>
</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">商品の並び替え：カテゴリ指定</p>
<p class="explanation">▼商品並替を行いたいカテゴリを選択してください。</p>
<form method="post" action="./" style="margin:0;" onSubmit="return inputChk(this);">
■商品並び替え・更新するカテゴリの指定<br>
<br>
<select name="category_id">
<option value="" selected>カテゴリ選択</option>
<?php
for($i=0;$i<count($fetchCateList);$i++){
?>
<option value="<?php echo $fetchCateList[$i]["CATEGORY_CODE"];?>"><?php echo $fetchCateList[$i]["CATEGORY_NAME"];?>(<?php echo count(${'fetchCA_ca'.$i});?>)</option>
<?php
}
?>
</select>
<br><br>
<input type="hidden" name="status" value="search_result">
<input type="submit" name="reg" value="選択">
</form>
</body>
</html>
