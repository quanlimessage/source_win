<?php
/*******************************************************************************
更新プログラム

	View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
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
<title><?php echo CALENDAR_TITLE;?></title>
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
	border-top-width: 1px;
	border-left-width: 1px;
	border-bottom-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-left-style: solid;
	border-bottom-style: solid;
	border-right-style: solid;
	border-top-color: #CCCCCC;
	border-left-color: #CCCCCC;
	border-bottom-color: #FFFFFF;
	border-right-color: #FFFFFF;
}

div#waku2 td {
	border-bottom:solid 1px #CCCCCC;
	border-right:solid 1px #CCCCCC;
	text-align:center;
	font-size:12px;
	background:#FFFFFF;
	vertical-align:top;
	padding-top:5px;
}

div#waku2 th {
	border-bottom:solid 1px #999999;
	border-right:solid 1px #999999;
	text-align:center;
	font-size:12px;
	color:#FFFFFF;
	background:#999999;
	font-weight: normal;
}

input#register_btn {
	background-color : #55A8F9;
	color : #000000;
}

input#update_btn {
	background-color : #FF6366;
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
▼休日設定をするには、「営業」ボタンをクリックし、ボタンを青から赤に変更してください。元に戻す場合は、もう一度クリックし、ボタンを青に戻してください。<br>
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
  <!--
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
  -->
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
<?php
// カレンダー表示
require_once 'Calendar/Month/Weekdays.php';

// インスタンスを生成（第3引数に“0”を指定すると月曜始まりにできる。省略時：日曜始まり）

$Month = new Calendar_Month_Weekdays($y,$m,0);
$Month->build();
?>
<a name="scl"></a>
<div id="waku2">
	<table width="600" height="400" border="0" cellspacing="0" cellpadding="2" class="kakoi">
		<tr bgcolor="#EC9704">

			<th height="30" colspan="7"><?php echo $y;?>年<?php echo $Month->thisMonth();?>月のカレンダー</th>
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

	// デフォルトは青色で表示
	if($fetch[0][DAY_.$Day->thisDay()] != "1"){
		$day_event = $Day->thisDay() . "<br><input type=\"submit\" value=\"営業\" id=\"register_btn\">";
	}else{
		$day_event = $Day->thisDay() . "<br><input type=\"submit\" value=\"休日\" id=\"update_btn\">";
	}

	//ACTIONを取得する
	$action = GetActionMode(urlencode($fetch[0]["SCHEDULE_ID"]) , $Day->thisDay() , $id);

	//IDを取得する
	$id = GetID(urlencode($fetch[0]["SCHEDULE_ID"]) , $Day->thisDay() , $id);

	$day_event = "
		<form action=\"./#scl\" method=\"post\" style=\"margin:0px;\">
		<input type=\"hidden\" name=\"m\" value=\"".$m."\">
		<input type=\"hidden\" name=\"y\" value=\"".$y."\">
		<input type=\"hidden\" name=\"id\" value=\"".$id."\">
		<input type=\"hidden\" name=\"action\" value=\"".$action."\">
		<input type=\"hidden\" name=\"scid\" value=\"".urlencode($fetch[0]["SCHEDULE_ID"])."\">
		<input type=\"hidden\" name=\"d\" value=\"".$Day->thisDay()."\">
	".$day_event."</form>";

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