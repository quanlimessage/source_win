<?php
/*******************************************************************************
アパレル対応(カテゴリ+サブカテゴリ)

売り上げ管理
	View：検索条件指定画面（最初に表示する）

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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script language="javascript" src="inputChk.js"></script>
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">売上管理：注文情報検索</p>
<p class="explanation">
▼検索したい情報に合わせて各検索条件を指定してください。<br>
▼何も指定がないと全受注データを抽出します。
</p>
<form action="./" method="post" style="margin:0px;" onSubmit="return inputChk(this);">
<table width="600" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" class="tdcolored">■注文情報検索</th>
	</tr>
	<tr>
		<th class="tdcolored">注文日：</th>
		<td class="other-td">
		<!-- 検索開始年月日 -->
		<select name="start_y">
		<option value="">--</option>
		<?php for($i=2010;$i<=(date("Y")+5);$i++)echo "<option value=\"{$i}\">{$i}</option>\n";?>
		</select>年
		<select name="start_m">
		<option value="">--</option>
		<?php for($i=1;$i<=12;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>月
		<select name="start_d">
		<option value="">--</option>
		<?php for($i=1;$i<=31;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>日
		&nbsp;～&nbsp;
		<!-- 検索終了年月日 -->
		<select name="end_y">
		<option value="">--</option>
		<?php for($i=2010;$i<=(date("Y")+5);$i++)echo "<option value=\"{$i}\">{$i}</option>\n";?>
		</select>年
		<select name="end_m">
		<option value="">--</option>
		<?php for($i=1;$i<=12;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>月
		<select name="end_d">
		<option value="">--</option>
		<?php for($i=1;$i<=31;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>日
		</td>
	</tr>
	<tr>
		<th class="tdcolored">決済種別：</th>
		<td class="other-td">
		<input type="radio" name="search_payment_type" id="spt1" value="1"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 1)?" checked":"";?>>
		<label for="spt1">クレジット決済</label>&nbsp;&nbsp;
		<input type="radio" name="search_payment_type" id="spt2" value="2"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 2)?" checked":"";?>>
		<label for="spt2">銀行振込</label>&nbsp;&nbsp;
		<input type="radio" name="search_payment_type" id="spt3" value="3"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 3)?" checked":"";?>>
		<label for="spt3">代引き</label>&nbsp;&nbsp;
		<!--
		<input type="radio" name="search_payment_type" id="spt4" value="4"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 4)?" checked":"";?>>
		<label for="spt4">コンビニ決済</label>
		-->
		<input type="radio" name="search_payment_type" id="spt6" value="6"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 6)?" checked":"";?>>
		<label for="spt6">PayPal決済</label>&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<th class="tdcolored">購入商品代：</th>
		<td class="other-td">
		<input type="text" size="10" maxlength="20" name="start_sum_price" style="ime-mode:disabled" value="<?php echo ($_SESSION["search_cond"]["start_sum_price"])?$_SESSION["search_cond"]["start_sum_price"]:"";?>">&nbsp;円以上<br>
		<input type="text" size="10" maxlength="20" name="end_sum_price" style="ime-mode:disabled" value="<?php echo ($_SESSION["search_cond"]["end_sum_price"])?$_SESSION["search_cond"]["end_sum_price"]:"";?>">&nbsp;円未満
		</td>
	</tr>
	<tr>
		<th class="tdcolored">決済状況：</th>
		<td class="other-td">
		<input type="radio" name="payment_flg" value="0" id="payment_flg0">&nbsp;<label for="payment_flg0">未決済</label><br>
		<input type="radio" name="payment_flg" value="1" id="payment_flg1">&nbsp;<label for="payment_flg1">決済済み</label><br>
		<input type="radio" name="payment_flg" value="2" id="payment_flg2">&nbsp;<label for="payment_flg2">決済失敗</label>
		</td>
	</tr>
	<tr>
		<th class="tdcolored">配送状況：</th>
		<td class="other-td">
		<input type="radio" name="shipped_flg" value="0" id="shipped_flg0">&nbsp;<label for="shipped_flg0">未配送</label><br>
		<input type="radio" name="shipped_flg" value="1" id="shipped_flg1">&nbsp;<label for="shipped_flg1">配送済み</label>
		</td>
	</tr>
</table>
<input type="submit" value="検索開始" style="width:150px; ">
<input type="hidden" name="status" value="search_result">
</form>
</body>
</html>
