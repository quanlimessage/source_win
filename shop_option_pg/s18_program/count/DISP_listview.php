<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
View：登録内容一覧表示（最初に表示する）

2006/11/22 Eric Tam
*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../index.php");exit();
}
if(!$accessChk){
	header("Location: ../index.php");exit();
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
<title>売上カウント</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>

	<form action="../main.php" method="post">
		<input type="submit" value="管理画面トップへ" style="width:150px;">
	</form>

	<p class="page_title">売上カウント</p>
	<p class="explanation">
		▼他の月の売り上げを表示した場合は、【月間】で表示したい月を選択してください。
	</p>

	<table width="250" cellpadding="0" cellspacing="1" border="0" bgcolor="#000000">
		<tr bgcolor="#FFFFFF">
			<td align="left" style="padding:5px;"><img src="images/bar.gif" width="50" height="10" align="absmiddle">・・売上金額<br><img src="images/bar_u.gif" width="50" height="10" align="absmiddle">・・購入者数<br><img src="images/bar_uu.gif" width="50" height="10" align="absmiddle">・・販売商品数（個数）</td>
		</tr>
	</table>
	<br>

	<table width="300" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<th width="40%" align="center" class="tdcolored">月間</th>

			<td width="60%" align="left">
				<form name="form1" method="post" action="./" style="margin:0px;">
				  <select name="year_month" id="year_month" onChange="javascript:submit();">
						<?php foreach($yearmonth_fetch as $k=>$v){ ?>
							<option value="<?php echo $v[YM];?>" <?php if($_POST[year_month] == $v[YM]){ echo "selected"; } ?>><?=$v[Y];?>年<?=$v[M];?>月</option>
						<?php } ?>
				  </select>
				</form>
			</td>
		</tr>
	</table>
	<br>

	<table width="600" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="6" align="center" class="tdcolored">総合計</th>

		</tr>

	  <tr>
		<td width="14%" align="center" class="tdcolored">売上金額</td>
			<td width="15%" align="left"><?php echo ($total_price_fetch[0][TOTAL_SUM_PRICE])?number_format($total_price_fetch[0][TOTAL_SUM_PRICE])."円":"0円";?></td>

		<td width="14%" align="center" class="tdcolored">購入者数</td>
			<td width="15%" align="left"><?php echo ($total_customer_fetch[0][CNT])?$total_customer_fetch[0][CNT]."人":"0人";?></td>

		<td width="17%" align="center" class="tdcolored">販売商品数（個数）</td>
			<td width="15%" align="left"><?php echo ($total_quantity_fetch[0][TOTAL_QUANTITY])?$total_quantity_fetch[0][TOTAL_QUANTITY]."個":"0個";?></td>
	  </tr>
	</table>
	<br>

	<table width="600" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="2" align="center" class="tdcolored">時間別</th>

		</tr>

			<?php

				//合計数を格納する(パーセントを算出する為)
					$pnum = $total_price_fetch[0]['TOTAL_SUM_PRICE'];//売り上げ金額
					$cnum = $total_customer_fetch[0]['CNT'];//購入者数
					$qnum = $total_quantity_fetch[0]['TOTAL_QUANTITY'];//販売商品数

			for($i=0,$j=0;$j<=23;$j++):?>
				<tr>
				  <td width="20%" align="center" class="tdcolored"><?php echo $j;?>時</td>
				  <td width="80%" align="left">
				  <?php

				  //同じ時間の場合は処理をする
				  	if($fetch_time[$i]['HOUR_TIME'] == $j){

				 	 //売上金額
							$width = @round($fetch_time[$i]['TOTAL_PRICE'] / $pnum * 100); // カウント数÷全カウント数でパーセンテージ取得

							?>
								<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
							<?php echo ($fetch_time[$i]['TOTAL_PRICE'])?"(".number_format($fetch_time[$i]['TOTAL_PRICE'])."円)":"0円";?>
					<br>

				  <?php
				  	//購入者数
							$width = @round($fetch_time[$i]['CNT'] / $cnum * 100); // カウント数÷全カウント数でパーセンテージ取得

							?>
							<img src="images/bar_u.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
							<?php echo ($fetch_time[$i]['CNT'])?"(".$fetch_time[$i]['CNT']."人)":"0人";?>
						<br>

				  <?php
				  	//販売商品数（個数）
							$width = @round($fetch_time[$i]['TOTAL_QUANTITY'] / $qnum * 100); // カウント数÷全カウント数でパーセンテージ取得

							?>
							<img src="images/bar_uu.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
							<?php echo ($fetch_time[$i]['TOTAL_QUANTITY'])?"(".$fetch_time[$i]['TOTAL_QUANTITY']."個)":"0個";?>

				<?php
					//次の配列データに移行
					$i++;

					}else{//同じ時間で無い場合の処理

					//全て０の数値で表記
					echo "&nbsp;0円<br>&nbsp;0人<br>&nbsp;0個";

					}?>

				</td>
			  </tr>
	<?php endfor;?>
	</table>
	<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">日別</th>

			</tr>

		<?php for($i=0;$i<count($fetch_day);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored"><?=$fetch_day[$i]["M"];?>月<?=$fetch_day[$i]["D"];?>日</td>
			  <td width="80%" align="left">
					<?php
					//売上金額
						$width = @round($fetch_day[$i]['TOTAL_PRICE'] / $pnum * 100);
					?>
					<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
					<?php echo "(".number_format($fetch_day[$i]['TOTAL_PRICE'])."円)";?>
				<br>

					<?php
					//購入者数
						$width = @round($fetch_day[$i]['CNT'] / $cnum * 100);
					?>
					<img src="images/bar_u.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
					<?php echo "(".$fetch_day[$i]['CNT']."人)";?>
				<br>

					<?php
					//販売商品数（個数）
						$width = @round($fetch_day[$i]['TOTAL_QUANTITY'] / $qnum * 100);
					?>
					<img src="images/bar_uu.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
					<?php echo "(".$fetch_day[$i]['TOTAL_QUANTITY']."個)";?>

			  </td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">曜日別</th>

			</tr>
		<?php

			//表示する曜日を格納
			$week = array("日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日");

			//【j】は曜日を格納、【i】はデータの位置を格納
			for($j=0,$i=0;$j<=6;$j++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
				<?php

					// 曜日数値を判別して曜日を出力
					echo $week[$j];?>
			  </td>
			  <td width="80%" align="left">
						<?php

						//曜日が合っていれば表示処理をする
							if($fetch_week[$i]['DAYOFWEEK'] == ($j + 1)){

								//売上金額
									$width = @round($fetch_week[$i]['TOTAL_PRICE'] / $pnum * 100); // カウント数÷全カウント数でパーセンテージ取得(グラフ表示用)
								?>
								<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
								<?php echo ($fetch_week[$i]['TOTAL_PRICE'])?"(".number_format($fetch_week[$i]['TOTAL_PRICE'])."円)":"0円";?>
							<br>

								<?php
								//購入者数
									$width = @round($fetch_week[$i]['CNT'] / $cnum * 100); // カウント数÷全カウント数でパーセンテージ取得(グラフ表示用)
								?>
								<img src="images/bar_u.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%

								<?php echo ($fetch_week[$i]['CNT'])?"(".$fetch_week[$i]['CNT']."人)":"0人";?>
							<br>

								<?php
								//販売商品数（個数）
									$width = @round($fetch_week[$i]['TOTAL_QUANTITY'] / $qnum * 100); // カウント数÷全カウント数でパーセンテージ取得(グラフ表示用)
								?>
								<img src="images/bar_uu.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
								<?php echo ($fetch_week[$i]['TOTAL_QUANTITY'])?"(".$fetch_week[$i]['TOTAL_QUANTITY']."個)":"0個";

								//データの位置を次に移行
								$i++;

							}

						//曜日が無かった場合は全て０を表示
							else{

								echo "&nbsp;0円<br>&nbsp;0人<br>&nbsp;0個";

							}
							?>
			</td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">カテゴリー別の売上金額</th>

			</tr>
				<?php for($j=0;$j < count($category_fetch);$j++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?php

					//有効なカテゴリーを表示する　VIEW_ORDER　昇順
					//カテゴリー名を表示
					echo $category_fetch[$j]['CATEGORY_NAME'];
				?>
			  </td>

			  	<td width="80%" align="left">
						<?php

						//カテゴリーコードと同じ場合表示をする（カテゴリーの並び順が変わっている場合正常に表示できない場合があるので全てのカテゴリを調べながら表示する）
						for($i=0,$c_flg=0;(($i < count($fetch_cate)) && ($c_flg == 0));$i++){

							if($category_fetch[$j]['CATEGORY_CODE'] == $fetch_cate[$i]['CATEGORY_CODE']){
							$c_flg = 1;//フラグを立てる
							$width = @round($fetch_cate[$i]['TOTAL_PRICE'] / $pnum * 100);
						?>
						<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
						<?php echo "(".number_format($fetch_cate[$i]['TOTAL_PRICE'])."円)";?>

						<?php
							}
						}
						//何も購入されてなければ０円
						if($c_flg == 0){
						echo "&nbsp;0%(0円)";

						}?>
				</td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">売れ筋トップ１０（売り上げ金額）</th>

			</tr>

		<?php for($i=0;$i < count($fetch_bestten_sell);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?=$i+1;?>
			  </td>
			  <td width="80%" align="left">
			  商品記号：<?php echo $fetch_bestten_sell[$i]['PART_NO'];?><br> 商品名&nbsp;&nbsp;&nbsp;：<?php echo $fetch_bestten_sell[$i]['PRODUCT_NAME'];?>（<?php echo number_format($fetch_bestten_sell[$i]['TOTAL_PRICE']);?>円）
			  </td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">売れ筋トップ１０（注文件数）</th>

			</tr>

		<?php for($i=0;$i < count($fetch_bestten_order);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?=$i+1;?>
			  </td>
			  <td width="80%" align="left">
			  商品記号：<?php echo $fetch_bestten_order[$i]['PART_NO'];?><br> 商品名&nbsp;&nbsp;&nbsp;：<?php echo $fetch_bestten_order[$i]['PRODUCT_NAME'];?>（<?php echo $fetch_bestten_order[$i]['CNT'];?>件）
			  </td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">売れ筋トップ１０（購入数）</th>

			</tr>

		<?php for($i=0;$i < count($fetch_bestten_access);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?=$i+1;?>
			  </td>
			  <td width="80%" align="left">
			  商品記号：<?php echo $fetch_bestten_access[$i]['PART_NO'];?><br> 商品名&nbsp;&nbsp;&nbsp;：<?php echo $fetch_bestten_access[$i]['PRODUCT_NAME'];?>（<?php echo $fetch_bestten_access[$i]['TOTAL_QUANTITY'];?>個）
			  </td>
		  </tr>
		<?php endfor;?>
		</table>

</body>
</html>