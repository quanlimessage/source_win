<?php
/*******************************************************************************
更新プログラム

	View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>管理画面</title>
<script type="text/javascript" src="input_check.js"></script>
<script type="text/javascript">

// 削除ボタンがクリックされた際の確認
function del_chk(){
		return confirm('この月のデータを完全に削除します。\nこのデータの復帰は出来ません。\nよろしいですか？');
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>
<style type="text/css">
<!--
/* テキスト入力 */
select {
	color : #000000 ;
	background-color : #CCCCCC ;
	line-height : 1.1 ;
}

.kakoi {
	border: solid 1px #333333;
}

div#waku2 td {
	border-bottom:solid 1px #333333;
	border-right:solid 1px #333333;
	text-align:center;
	font-size:12px;
	background:#FFFFFF;
	vertical-align:top;
	padding-top:5px;
}

div#waku2 th {
	border-bottom:solid 1px #333333;
	border-right:solid 1px #333333;
	text-align:center;
	font-size:12px;
	color:#FFFFFF;
	background:#333333;
	font-weight: normal;
}

input#register_btn {
	background-color : #FF6366;
	color : #000000;
}

input#update_btn {
	background-color : #55A8F9;
	color : #000000;
}

-->
</style>

<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title"><?php echo CALENDAR_TITLE;?></p>
<p class="explanation">
▼スケジュールを登録するには「登録する」または「更新する」をクリック後、<br>
　必要事項を入力してください。<br>
▼実際にホームページに表示されるのは今月です。</p>
<?php if(!empty($error_message)):?>
<p class="err"><?php echo $error_message;?></p>
<?php endif;?>
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td colspan="2">
	<h4>現在登録している年月</h4>
	<form name="frm_select" action="./" method="post" style="margin:0px;" onSubmit="if(document.frm_select.schedule_id.value==''){alert('現在登録している年月をお選びください。');return false;}">
			<select name="schedule_id" size="7" style="width:120px">
					<?php for($i=0;$i<count($fetch_month);$i++):?>
					<option value="<?php echo $fetch_month[$i]["SCHEDULE_ID"];?>"><?php echo $fetch_month[$i]["YEAR"];?>年<?php echo $fetch_month[$i]["MONTH"];?>月</option>
					<?php endfor;?>
			</select>
			<input type="submit" name="submit" value="登録している年月を直接表示">
	<input type="hidden" name="id_select" value="1">
	</form>	</td>
  </tr>
  <tr>
	<td colspan="2" heigth="10">&nbsp;</td>
  </tr>

  <tr>
	<td align="left">
	<form action="./" method="post" style="margin:0px;">
	<input type="submit" name="submit" value="前年へ">
	<input type="hidden" name="m" value="<?php echo $m;?>">
	<input type="hidden" name="y" value="<?php echo $y-1;?>">
	</form>	</td>

	<td align="right">
	<form action="./" method="post" style="margin:0px;">
	<input type="submit" name="submit" value="次年へ">
	<input type="hidden" name="m" value="<?php echo $m;?>">
	<input type="hidden" name="y" value="<?php echo $y+1;?>">
	</form>	</td>
  </tr>

  <tr>
	<td align="left">
	<form action="./" method="post" style="margin:0px;">
	<input type="submit" name="submit" value="前の月へ">
	<input type="hidden" name="m" value="<?php echo $m-1;?>">
	<input type="hidden" name="y" value="<?php echo $y;?>">
	</form>	</td>
	<td align="right">
	<form action="./" method="post" style="margin:0px;">
	<input type="submit" name="submit" value="次の月へ">
	<input type="hidden" name="m" value="<?php echo $m+1;?>">
	<input type="hidden" name="y" value="<?php echo $y;?>">
	</form>	</td>
  </tr>
</table>

<!-- サブウィンドウからPOSTされる。 -->
<form name="frm" action="./" method="post" style="margin:0px;">
	<input type="hidden" name="m" value="<?php echo $m;?>">
	<input type="hidden" name="y" value="<?php echo $y;?>">
</form>

<br>
<!--<form action="./" method="post" enctype="multipart/form-data" onSubmit="return inputChk(this);" style="margin:0px;">-->
<?
// カレンダー表示
require_once 'Calendar/Month/Weekdays.php';

// インスタンスを生成（第3引数に“0”を指定すると月曜始まりにできる。省略時：日曜始まり）

$Month = new Calendar_Month_Weekdays($y,$m,0);
$Month->build();
?>
<div id="waku2">
	<table width="600" height="400" border="0" cellspacing="0" cellpadding="2" class="kakoi">
		<tr bgcolor="#EC9704">

			<th height="30" colspan="7"><?php echo $y;?>年<?php echo $Month->thisMonth();?>月のスケジュール情報</th>
		</tr>
		<tr>

			<td width="80" height="20">日</td>
			<td width="80">月</td>
			<td width="80">火</td>
			<td width="80">水</td>
			<td width="80">木</td>
			<td width="80">金</td>
			<td width="80">土</td>
		</tr>
<?php
$i = 1;

while($Day = $Month->fetch()):

	if($Day->isFirst())echo "<tr>\n";

	// 各プルダウンメニューのselectedチェック
	$select1 = "";
	$select2 = "";
	$select3 = "";
	$select4 = "";

	// デフォルトは日付のみ表示
	if($fetch[0][DAY_.$Day->thisDay()] != "1"){
		$day_event = $Day->thisDay() . "<br><form action=\"./event_entry/\" method=\"post\"><input type=\"submit\" value=\"登録する\">
		<input type=\"hidden\" name=\"scid\" value=\"".urlencode($fetch[0]["SCHEDULE_ID"])."\">
		<input type=\"hidden\" name=\"d\" value=\"".urlencode($Day->thisDay())."\">
		</form>";
	}else{
		$day_event = $Day->thisDay() . "<br><form action=\"./event_entry/\" method=\"post\"><input type=\"submit\" value=\"更新する\">
		<input type=\"hidden\" name=\"scid\" value=\"".urlencode($fetch[0]["SCHEDULE_ID"])."\">
		<input type=\"hidden\" name=\"d\" value=\"".urlencode($Day->thisDay())."\">
		</form>";
	}

	/*
	このやり方だとIE7,IE8でポップアップで開いた時にID,PWが引き継げなくてエラーになる
	if($fetch[0][DAY_.$Day->thisDay()] != "1"){
		$day_event = $Day->thisDay() . "<br><input type=\"button\" value=\"登録する\" onClick=\"MM_openBrWindow('./event_entry/?scid=".urlencode($fetch[0]["SCHEDULE_ID"])."&d=".urlencode($Day->thisDay())."','pop','scrollbars=yes,width=600,height=600,resizable=yes')\" id=\"register_btn\">";
	}else{
		$day_event = $Day->thisDay() . "<br><input type=\"button\" value=\"更新する\" onClick=\"MM_openBrWindow('./event_entry/?scid=".urlencode($fetch[0]["SCHEDULE_ID"])."&d=".urlencode($Day->thisDay())."','pop','scrollbars=yes,width=600,height=600,resizable=yes')\" id=\"update_btn\">";
	}
	*/
	echo ($Day->isEmpty())?"<td height=\"70\">&nbsp;</td>\n":"<td height=\"70\"><b>".$day_event."</b><br>
	</td>\n";
	if($Day->isLast())echo "</tr>\n";

endwhile;
?>

	</table>
	<br>
</div>

<!--
<input type="submit" value="更新する" style="width:150px;">
<input type="hidden" name="status" value="completion">
<input type="hidden" name="regist_type" value="update">
<input type="hidden" name="year" value="<?php echo $y;?>">
<input type="hidden" name="month" value="<?php echo $m;?>">

</form>
-->
<form action="./" method="post" onSubmit="return del_chk(this);">
	<input type="hidden" name="status" value="del_data">
	<input type="hidden" name="schedule_id" value="<?php echo $fetch[0]["SCHEDULE_ID"];?>">
	<input type="submit" value="この月の情報を削除する" style="width:150px;">
</form>

<!--
<form action="./" method="post">
	<input type="submit" value="記事リスト画面へ戻る" style="width:150px;">
</form>
-->
</body>
</html>