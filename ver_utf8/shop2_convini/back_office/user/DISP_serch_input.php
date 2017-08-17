<?php
/*******************************************************************************
アパレル対応(カテゴリ)

ユーザーの検索
View：検索条件設定画面（最初に表示する）

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

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ユーザ管理：購入者情報検索画面</p>
<p class="explanation">
▼「名前」は一部指定でも検索可能です。<br>
▼検索項目を全て空欄で検索すると全情報を抽出します。
</p>
<form action="./" method="post" style="margin:0px; ">
<table width="500" border="0" cellpadding="5" cellspacing="2">
	<tr align="center">
		<td colspan="2" class="tdcolored">■顧客情報検索</td>
	</tr>
	<tr>
		<th class="tdcolored" width="150">ユーザーID：</th>
		<td class="other-td"><input type="text" name="search_customer_id" value="" size="30" style="ime-mode:disabled"></td>
	</tr>
	<tr>
		<th class="tdcolored" width="150">受注番号(受付番号)：</th>
		<td class="other-td"><input type="text" name="search_order_id" value="" size="30" style="ime-mode:disabled"></td>
	</tr>
	<tr>
		<th class="tdcolored">名前(フリガナ)：<br>一部でも可</th>
		<td class="other-td">
		姓&nbsp;<input type="text" name="search_kana_1" value="" size="15"><br>
		名&nbsp;<input type="text" name="search_kana_2" value="" size="15">
		</td>
	</tr>
	<tr>
		<th class="tdcolored">名前(漢字)：<br>一部でも可</th>
		<td class="other-td">
		姓&nbsp;<input type="text" name="search_name_1" value="" size="15"><br>
		名&nbsp;<input type="text" name="search_name_2" value="" size="15">
		</td>
	</tr>
	<tr>
		<th class="tdcolored">メールアドレス：</th>
		<td class="other-td"><input type="text" name="search_email" value="" size="30" style="ime-mode:disabled"></td>
	</tr>
	<tr>
		<th class="tdcolored">居住都道府県名：</th>
		<td class="other-td">
		<select name="search_state">
			<option value="no">----</option>
			<?php for($i=0;$i<count($pref_list);$i++):?>
			<option value="<?php echo $i;?>"><?php echo $pref_list[$i];?></option>
			<?php endfor;?>
		</select>
		</td>
	</tr>
	<tr>
		<th class="tdcolored">合計購入額：</th>
		<td class="other-td"><input type="text" name="search_total_sum" value="" size="20" style="ime-mode:disabled">&nbsp;円以上</td>
	</tr>
</table>
<input type="submit" value="検索開始" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>
</body>
</html>
