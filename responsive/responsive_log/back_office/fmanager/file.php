<?php
// 設定ファイル＆共通ライブラリの読み込み
	session_start();
	require_once("../../common/INI_logconfig.php");		// 設定ファイル
	require_once("util_lib.php");					// 汎用処理クラスライブラリ
	require_once("dbOpe.php");

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

// POSTデータの受け取りと共通な文字列処理
	if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4),true));}

	// $filename = "2010_05_access_log_db";

// 月別データの取得
	$SQLITE = access_log_start($filename);

/*
// 日別ユニークアクセス数取得

	$day_u_sql = "
	SELECT
		INS_DATE,
		strftime('%Y', INS_DATE) AS Y,
		strftime('%m', INS_DATE) AS M,
		strftime('%d', INS_DATE) AS D,
		count(*) AS CNT
	FROM
		".ACCESS_LOG."
	WHERE
		(UNIQUE_FLG == '1')
	GROUP BY
		strftime('%Y%m%d', INS_DATE)
	ORDER BY
		strftime('%Y%m%d', INS_DATE) ASC
	";

	$fetch_day_u = $SQLITE -> fetch($day_u_sql);

// 時間別ユニークアクセス数取得
	$time_u_sql = "
	SELECT
		strftime('%H', TIME) AS TIME,
		COUNT(*) AS CNT
	FROM
		".ACCESS_LOG."
	WHERE
		(UNIQUE_FLG == '1')
	GROUP BY
		strftime('%H', TIME)
	ORDER BY
		TIME ASC
	";

	$cnt_time = $SQLITE -> fetch($time_u_sql);

	// 時間(1〜24)をインデックスキーに置き換え(表示用に24個要素配列に)
	foreach($cnt_time as $k => $v){
		$key = $v["TIME"];

		$key = sprintf("%02d",$key);

		$fetch_time_u[$key] = $v["CNT"];
	}

// 曜日別ユニークアクセス数取得
	$dayofweek_u_sql = "
	SELECT
		strftime('%w', INS_DATE) AS DAYOFWEEK,
		COUNT(*) AS CNT
	FROM
		".ACCESS_LOG."
	WHERE
		(UNIQUE_FLG == '1')
	GROUP BY
		strftime('%w', INS_DATE)
	ORDER BY
		strftime('%w', INS_DATE) ASC
	";

	$cnt_dayofweek = $SQLITE -> fetch($dayofweek_u_sql);

	// 曜日(0〜6)をインデックスキーに置き換え(表示用に7個要素配列に)
	foreach($cnt_dayofweek as $k => $v){
		$key = $v["DAYOFWEEK"];
		$fetch_dayofweek_u[$key] = $v["CNT"];
	}
*/

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#-------------------------------------------------------------
utilLib::httpHeadersPrint("EUC-JP",true,false,false,true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-jp" />
<title>アクセス解析レポート</title>
<style type="text/css">
<!--
.style1 {font-size: 10px}
.style2 {font-size: 12px}
.style3 {font-size: 14px}
.table1 {
	border: 1px solid #000000;
}
.style4 {
	font-size: 10px;
	color: #0000CC;
}
.paper_seo {font-size: 12px;  height: 940px; width: 640px; left: 0px; top: 0px; border: ridge 1px black;}
.titles {font-size: 24px; text-align: center; font-weight: bold;}
-->
</style>
</head>

<body>
<input type="button" name="button" value="レポート出力" onClick="window.print();">

<table class="paper_seo">
    <tr>
	  <td width="640" valign="top" height="902">
	  <table width="100%" height="290">
	   <tr>
		<td>
		 <table width="100%" border="0" height="25">
          <tr>
            <td width="350"><span class="style2"><u><?php echo date('Y.m.d', mktime(0,0,0,(date("n")),date("j"),date("Y")));?></span></td>
          </tr>
        </table>
		<br>
		<?php $db_fname = explode("_",$filename);?>
		<table width="100%" class="titles" cellpadding="5">
          <tr>
            <td class="titles"><?php echo $db_fname[0];?>年<?php echo $db_fname[1];?>月アクセス解析レポート</td>
          </tr>
        </table>
        <br>
        <table width="100%" border="0">
          <tr>
		  <!-- 長い会社名対応でwidthの値を変更 -->
		  <?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ページビュー
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 全データ取得のSQL
			$total_sql = "
			SELECT
				ID
			FROM
			 ".ACCESS_LOG."
			";

			//$total_u_sql = $total_sql."WHERE (UNIQUE_FLG == '1')";
			//$fetch_u = $SQLITE -> fetch($total_u_sql);

		//表示
		  ?><td align="left" valign="bottom" colspan="3"><span class="style2"><strong>PV（ページビュー）：</strong><?php
			$fetch = $SQLITE -> fetch($total_sql);
			echo count($fetch);

		//もう不必要なデータを削除
			unset($fetch);
	    ?>
	    <br><!--<strong>ユニークPV：</strong><?php //echo count($fetch_u);?><br>-->

	    <?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//来訪者数
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$total_uu_sql = $total_sql."WHERE (USER_FLG == '1')";
		$fetch_uu = $SQLITE -> fetch($total_uu_sql);

	    ?><strong>来訪者数：</strong><?php
	    echo count($fetch_uu);

		//もう不必要なデータを削除
		unset($fetch_uu);
	    ?></span></td>
		<!--<td>&nbsp;</td>-->
		</tr>
<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//日別アクセス数
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 日別アクセス数取得
			$day_sql = "
			SELECT
				INS_DATE,
				strftime('%Y', INS_DATE) AS Y,
				strftime('%m', INS_DATE) AS M,
				strftime('%d', INS_DATE) AS D,
				count(*) AS CNT
			FROM
				".ACCESS_LOG."
			GROUP BY
				strftime('%Y%m%d', INS_DATE)
			ORDER BY
				strftime('%Y%m%d', INS_DATE) ASC
			";

			$fetch_day = $SQLITE -> fetch($day_sql);

			$fetch_max = 0;
			for($i=0;$i<count($fetch_day);$i++){
				if($fetch_max <= $fetch_day[$i]['CNT'])$fetch_max = $fetch_day[$i]['CNT'];
			}

			/*$fetch_u_max = 0;
			for($i=0;$i<count($fetch_day_u);$i++){
				if($fetch_u_max <= $fetch_day_u[$i]['CNT'])$fetch_u_max = $fetch_day_u[$i]['CNT'];
			}*/

			// 日別来訪者数取得

				$day_uu_sql = "
				SELECT
					INS_DATE,
					strftime('%Y', INS_DATE) AS Y,
					strftime('%m', INS_DATE) AS M,
					strftime('%d', INS_DATE) AS D,
					count(*) AS CNT
				FROM
					".ACCESS_LOG."
				WHERE
					(USER_FLG == '1')
				GROUP BY
					strftime('%Y%m%d', INS_DATE)
				ORDER BY
					strftime('%Y%m%d', INS_DATE) ASC
				";

				$fetch_day_uu = $SQLITE -> fetch($day_uu_sql);

		?>
		<tr style="margin-top:10px;">
			<td width="33%" valign="top"><span class="style2"><strong>日別アクセス数：</strong></span>
		<table>
			<?php for($i=0;$i<count($fetch_day);$i++):?>
			<tr style="margin:0px;padding:0px;">
				<td width="40" class="style1" style="margin:0px;padding:0px;">&nbsp;<?php echo $fetch_day[$i]["D"];?>日</td>
				<td width="200" style="margin:0px;padding:0px;">
				<?php $width_uu = @round($fetch_day_uu[$i]['CNT']/$fetch_max * 100 );?>
	  			<img src="images/bar_uu.gif" width="<?php echo $width_uu*0.8;?>" height="8" align="left">
				<?php //$width_u = @round($fetch_day_u[$i]['CNT']/$fetch_max * 100 );?>
	  			<!--<img src="images/bar_u.gif" width="<?php echo ($width_u - $width_uu)*0.8;?>" height="8" align="left">-->
	  			<?php $width = @round($fetch_day[$i]['CNT']/$fetch_max * 100);?>
	  			<img src="images/bar.gif" width="<?php echo ($width - $width_uu)*0.8;?>" height="8" align="left">
				&nbsp;<span style="color:#0000EE;font-size:10px;"><?php echo $fetch_day_uu[$i]['CNT'];?></span>&nbsp;<!--<span style="color:#0000EE;font-size:10px;"><?php //echo $fetch_day_u[$i]['CNT'];?></span>&nbsp;--><span style="color:#FF00FF;font-size:10px;"><?php echo $fetch_day[$i]['CNT'];?></span>&nbsp; </td>
			</tr>
			<?php endfor; ?>
		</table>
		<?php
		//もう不必要なデータを削除
		unset($fetch_day);
		unset($fetch_day_uu);
		?>

<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//時間別アクセス数
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			// 時間別アクセス数取得
				$time_sql = "
				SELECT
					strftime('%H', TIME) AS TIME,
					COUNT(*) AS CNT
				FROM
					".ACCESS_LOG."
				".$where_term."
				GROUP BY
					TIME
				ORDER BY
					TIME ASC
				";

				$cnt_time = $SQLITE -> fetch($time_sql);

				// 時間(1〜24)をインデックスキーに置き換え(表示用に24個要素配列に)
				foreach($cnt_time as $k => $v){
					$key = $v["TIME"];

					$key = sprintf("%02d",$key);

					$fetch_time[$key] = $v["CNT"];
				}
				unset($cnt_time);

			$fetch_max = 0;
			for($i=0;$i<23;$i++){
				$i = sprintf("%02d",$i);
				if($fetch_max <= $fetch_time[$i])$fetch_max = $fetch_time[$i];
			}
			/*$fetch_u_max = 0;
			for($i=0;$i<23;$i++){
				$i = sprintf("%02d",$i);
				if($fetch_u_max <= $fetch_time_u[$i])$fetch_u_max = $fetch_time_u[$i];
			}*/

			// 時間別来訪者数取得
				$time_uu_sql = "
				SELECT
					strftime('%H', TIME) AS TIME,
					COUNT(*) AS CNT
				FROM
					".ACCESS_LOG."
				WHERE
					(USER_FLG == '1')
				GROUP BY
					TIME
				ORDER BY
					TIME ASC
				";

				$cnt_time = $SQLITE -> fetch($time_uu_sql);

				// 時間(1〜24)をインデックスキーに置き換え(表示用に24個要素配列に)
				foreach($cnt_time as $k => $v){
					$key = $v["TIME"];

					$key = sprintf("%02d",$key);

					$fetch_time_uu[$key] = $v["CNT"];
				}

			//もう不必要なデータを削除
				unset($cnt_time);

			?>
			</td>
				<td width="33%" valign="top"><span class="style2"><strong>時間別アクセス数：</strong></span>
			<table>
			<?php for($i=0;$i<=23;$i++):?>
			<tr>
				<td width="40" class="style1" style="margin:0px;padding:0px;">&nbsp;<?php echo $i;?>時</td>
				<td width="200" style="margin:0px;padding:0px;"><?php $i = sprintf("%02d",$i);?>
				<?php $width_uu = @round($fetch_time_uu[$i]/$fetch_max * 100 );?>
	  			<img src="images/bar_uu.gif" width="<?php echo $width_uu*0.8;?>" height="8" align="left">
				<?php //$width_u = @round($fetch_time_u[$i]/$fetch_max * 100 );?>
	  			<!--<img src="images/bar_u.gif" width="<?php echo ($width_u - $width_uu)*0.8;?>" height="8" align="left">-->
	  			<?php $width = @round($fetch_time[$i]/$fetch_max * 100);?>
	  			<img src="images/bar.gif" width="<?php echo ($width - $width_uu)*0.8;?>" height="8" align="left">
				&nbsp;<span style="color:#0000EE;font-size:10px;"><?php echo $fetch_time_uu[$i];?></span>&nbsp;<!--<span style="color:#0000EE;font-size:10px;"><?php //echo $fetch_time_u[$i];?></span>&nbsp;--><span style="color:#FF00FF;font-size:10px;"><?php echo $fetch_time[$i];?></span>&nbsp; </td>
			</tr>
			<?php endfor; ?>
		</table>
		</td>
		<?php
			//もう不必要なデータを削除
			unset($fetch_time);
			unset($fetch_time_uu);?>

<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//曜日別アクセス数
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				// 曜日別アクセス数取得
					$dayofweek_sql = "
					SELECT
						strftime('%w', INS_DATE) AS DAYOFWEEK,
						COUNT(*) AS CNT
					FROM
						".ACCESS_LOG."
					".$where_term."
					GROUP BY
						strftime('%w', INS_DATE)
					ORDER BY
						strftime('%w', INS_DATE) ASC
					";

					$cnt_dayofweek = $SQLITE -> fetch($dayofweek_sql);

					// 曜日(0〜6)をインデックスキーに置き換え(表示用に7個要素配列に)
					foreach($cnt_dayofweek as $k => $v){
						$key = $v["DAYOFWEEK"];
						$fetch_dayofweek[$key] = $v["CNT"];
					}

			$fetch_max = 0;
			for($i=0;$i<=6;$i++){
				if($fetch_max <= $fetch_dayofweek[$i])$fetch_max = $fetch_dayofweek[$i];
			}

			/*$fetch_u_max = 0;
			for($i=0;$i<=6;$i++){
				if($fetch_u_max <= $fetch_dayofweek_u[$i])$fetch_u_max = $fetch_dayofweek_u[$i];
			}*/

				// 曜日別来訪者数取得

					$dayofweek_uu_sql = "
					SELECT
						strftime('%w', INS_DATE) AS DAYOFWEEK,
						COUNT(*) AS CNT
					FROM
						".ACCESS_LOG."
					WHERE
						(USER_FLG == '1')
					GROUP BY
						strftime('%w', INS_DATE)
					ORDER BY
						strftime('%w', INS_DATE) ASC
					";

					$cnt_dayofweek = $SQLITE -> fetch($dayofweek_uu_sql);

					// 曜日(0〜6)をインデックスキーに置き換え(表示用に7個要素配列に)
					foreach($cnt_dayofweek as $k => $v){
						$key = $v["DAYOFWEEK"];
						$fetch_dayofweek_uu[$key] = $v["CNT"];
					}

			//もう不必要なデータを削除
					unset($cnt_dayofweek);

			?>
			<td rowspan="2" width="33%" valign="top"><span class="style2"><strong>曜日別アクセス数：</strong></span>
		<table>
			<?php for($i=0;$i<=6;$i++):?>
			<tr>
				<td width="25" class="style1" style="margin:0px;padding:0px;">
				<?php // 曜日数値を判別して曜日を出力
				switch ($i):
					case 0:
						echo "日";
						break;
					case 1:
						echo "月";
						break;
					case 2:
						echo "火";
						break;
					case 3:
						echo "水";
						break;
					case 4:
						echo "木";
						break;
					case 5:
						echo "金";
						break;
					case 6:
						echo "土";
						break;
				endswitch;

				?>
				</td>
				<td width="200" style="margin:0px;padding:0px;">
				<?php $width_uu = @round($fetch_dayofweek_uu[$i]/$fetch_max * 100 );?>
	  			<img src="images/bar_uu.gif" width="<?php echo $width_uu*0.8;?>" height="8" align="left">
				<?php //$width_u = @round($fetch_dayofweek_u[$i]/$fetch_max * 100 );?>
	  			<!--<img src="images/bar_u.gif" width="<?php echo ($width_u - $width_uu)*0.8;?>" height="8" align="left">-->
	  			<?php $width = @round($fetch_dayofweek[$i]/$fetch_max * 100);?>
	  			<img src="images/bar.gif" width="<?php echo ($width - $width_uu)*0.8;?>" height="8" align="left">
				&nbsp;<span style="color:#0000EE;font-size:10px;"><?php echo $fetch_dayofweek_uu[$i];?></span>&nbsp;<!--<span style="color:#0000EE;font-size:10px;"><?php //echo $fetch_dayofweek_u[$i];?></span>&nbsp;--><span style="color:#FF00FF;font-size:10px;"><?php echo $fetch_dayofweek[$i];?></span>&nbsp; </td>
			</tr>
			<?php endfor;

			//もう不必要なデータを削除
				unset($fetch_dayofweek);
				unset($cnt_dayofweek);
				unset($fetch_dayofweek_uu);
				?>
		</table>
		<br>

		<span class="style2"><strong>検索エンジン別アクセス数BEST5</strong></span><br><br>
		<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//検索エンジン
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 検索エンジン数取得
			$engine_sql = "
			SELECT
				ENGINE,
				COUNT(*) AS CNT
			FROM
				".ACCESS_LOG."
			GROUP BY
				ENGINE
			HAVING
		   (ENGINE != \"\")
			ORDER BY
				CNT DESC
			LIMIT
				0 , 5
			";

			$fetchENGINE = $SQLITE -> fetch($engine_sql);

		for($i=0;$i<count($fetchENGINE);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetchENGINE[$i]['ENGINE'];?>（<?php echo $fetchENGINE[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetchENGINE);
		?>
		<br>
		<span class="style2"><strong>検索キーワード別アクセス数BEST10</strong></span><br><br>
		<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//キーワード
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 検索文字列取得
				$q_sql = "
				SELECT
					QUERY_STRING,
					COUNT(*) AS CNT
				FROM
					".ACCESS_LOG."
				GROUP BY
					QUERY_STRING
				HAVING
			   (QUERY_STRING != \"\")
				ORDER BY
					CNT DESC
				LIMIT
					0 , 10
				";

				$fetchQuery = $SQLITE -> fetch($q_sql);

		for($i=0;$i<count($fetchQuery);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetchQuery[$i]['QUERY_STRING'];?>（<?php echo $fetchQuery[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetchQuery);
		?>
		<br>
		<span class="style2"><strong>ブラウザ別アクセス数BEST3</strong></span><br><br>
		<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ブラウザ
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// ブラウザ取得用
			$bro_sql = "
			SELECT
				BROWSER,
				COUNT(*) AS CNT
			FROM
				".ACCESS_LOG."
			GROUP BY
				BROWSER
			ORDER BY
				CNT DESC
			LIMIT
				0 , 3
			";

			$fetch_bro = $SQLITE -> fetch($bro_sql);

		for($i=0;$i<count($fetch_bro);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetch_bro[$i]['BROWSER'];?>（<?php echo $fetch_bro[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetch_bro);
		?>
		<br>
		<span class="style2"><strong>OS別アクセス数BEST3</strong></span><br><br>
		<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ＯＳ別
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// OS取得用
				$os_sql = "
				SELECT
					OS,
					COUNT(*) AS CNT
				FROM
					".ACCESS_LOG."
				GROUP BY
					OS
				ORDER BY
					CNT DESC
				LIMIT
					0 , 3
				";

				$fetch_os = $SQLITE -> fetch($os_sql);

		for($i=0;$i<count($fetch_os);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetch_os[$i]['OS'];?>（<?php echo $fetch_os[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetch_os);
		?>
		<br>
		<span class="style2"><strong>地域別アクセス数BEST10</strong></span><br><br>
		<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//地域別
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 地域別取得用
				$state_sql = "
				SELECT
					STATE,
					COUNT(*) AS CNT
				FROM
					".ACCESS_LOG."
				GROUP BY
					STATE
				ORDER BY
					CNT DESC
				LIMIT
					0 , 10
				";

				$fetch_state = $SQLITE -> fetch($state_sql);

			for($i=0;$i<count($fetch_state);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetch_state[$i]['STATE'];?>（<?php echo $fetch_state[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetch_state);

		?>
			</td>
          </tr>
		<tr>
	   <td colspan="2">
		<span class="style2"><strong>ページ別アクセス数BEST3</strong></span><br><br>
		<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ページ別（ベスト３）
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// ページ別アクセス数取得（上位３つ）
			$url_b_sql = "
			SELECT
				PAGE_URL,
				COUNT(*) AS CNT
			FROM
				".ACCESS_LOG."
			GROUP BY
				PAGE_URL
			ORDER BY
				CNT DESC
			LIMIT
				0 , 3
			";

			$fetchURL_b = $SQLITE -> fetch($url_b_sql);

			for($i=0;$i<count($fetchURL_b);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetchURL_b[$i]['PAGE_URL'];?>（<?php echo $fetchURL_b[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetchURL_b);
		?>
		<br>
		<span class="style2"><strong>ページ別アクセス数ワースト3</strong></span><br><br>
		<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ページ別（ワースト３）
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// ページ別アクセス数取得（下位３つ）
				$url_w_sql = "
				SELECT
					PAGE_URL,
					COUNT(*) AS CNT
				FROM
					".ACCESS_LOG."
				GROUP BY
					PAGE_URL
				ORDER BY
					CNT ASC
				LIMIT
					0 , 3
				";

				$fetchURL_w = $SQLITE -> fetch($url_w_sql);

		for($i=0;$i<count($fetchURL_w);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetchURL_w[$i]['PAGE_URL'];?>（<?php echo $fetchURL_w[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetchURL_w);
		?>
		</td>
		</tr>

        </table>
	   </td>
	  </tr>
		<tr>
		<td>
		<span class="style2"><strong>リファラー別アクセス数BEST3</strong></span><br><br>
		<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//リファラー
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// リファラー取得用

			$ref_sql = "
			SELECT
				REFERER,
				COUNT(*) AS CNT
			FROM
				".ACCESS_LOG."
			GROUP BY
				REFERER
			ORDER BY
				CNT DESC
			LIMIT 1 , 3
			";

			$fetch_ref = $SQLITE -> fetch($ref_sql);

		for($i=0;$i<count($fetch_ref);$i++):?>
		<?php echo ($i + 1);?>：<?php echo $fetch_ref[$i]['REFERER'];?>（<?php echo $fetch_ref[$i]['CNT'];?>件）<br>
		<?php endfor;
			//もう不必要なデータを削除
			unset($fetch_ref);
		?>
	   </td>
	 </tr>
	</table>
		<br>
		ALL INTERNET 　総合窓口&nbsp;&nbsp;           STAGE GROUP（ステージグループ）<br>
		〒101-0061 東 京 都 千 代 田 区 三 崎 町 ２−４−１&nbsp;&nbsp;&nbsp;T U G - I ビ ル ３ F <br>
		TEL:03-5210-3788　　FAX:03-5210-3799
		</td>
    </tr>
  </table>
</body>
</html>
