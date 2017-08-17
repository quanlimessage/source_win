<?php
/*---------------------------------------------------------
    katochanCalendar Ver2.0.1
    Copy right (C) 2001 katochanpe.com 
    All rights reserved.
    with PHP4.04
    Ver1.0.0 2001/05/27
    Ver2.0.0 2001/06/01
        全面変更
    Ver2.0.1 2001/09/21
        コンストラクタの当日の取得方法の変更
    m-katoh@katochanpe.com
	
	2005/5/4 このクラスを改造 by Yossee
-----------------------------------------------------------*/
class calendarOpe{


var $calArray;          //カレンダの配列
var $today;             //本日
var $DisposalYear;      //処理をしている年
var $DisposalMonth;     //処理をしている月

/*-----------------------------------------------
コンストラクタ	※当日の取得
------------------------------------------------*/
function calendarOpe() {
	$this->today = time() + 9 * 60 * 60;
}

/*-----------------------------------------------
年月の1日の曜日No
	メソッド：getFirstNum()
------------------------------------------------*/
function getFirstNum() {
	return date("w",mktime(0,0,0,$this->DisposalMonth,1,$this->DisposalYear));
}

/*-----------------------------------------------
年月の日数
	メソッド：getMonthDays()
------------------------------------------------*/
function getMonthDays() {
	return date("t",mktime(0,0,0,$this->DisposalMonth,1,$this->DisposalYear));
}

/*-----------------------------------------------
カレンダの2次元配列を作成する
	メソッド：makeCalendar($year = "", $month = "")
	$year：カレンダを作成する年
	$month：カレンダを作成する月
------------------------------------------------*/
function makeCalendar($year = "", $month = "") {
 
	//処理年の決定
	empty($year)?$this->DisposalYear = date("Y",$this->today):$this->DisposalYear = $year;

	//処理月の決定
	empty($month)?$this->DisposalMonth = date("n",$this->today):$this->DisposalMonth = $month;

	//処理日の初期値
	$DisposalDay = 1;

	$topCount = 0;                      //1日のスタート位置のカウント
	$topPos = $this->getFirstNum();     //1日のスタート位置
	$endPos = $this->getMonthDays();    //処理する年月の日数(終了位置)
    
	//日を配列に格納する
	for ($i=0;$i<=5;$i++){	//週のループ

		for ($j=0;$j<=6;$j++){	//曜日のループ

			#-----------------------------------------------------------
			# 1日のスタート位置のカウントが1日のスタート位置以上
			# 処理日がその月の日数以下の時
			#-----------------------------------------------------------
			if (($topCount >= $topPos) and ($DisposalDay <= $endPos)){
				$this->calArray[$i][$j] = $DisposalDay;
				$DisposalDay++;
			}
			else {
				$this->calArray[$i][$j] = "";
			}

			//1日のスタート位置のカウント加算
		    $topCount++;

		    //終了位置（条件に該当したら一番外のループ外へ）
		    if (($DisposalDay > $endPos) and ($j == 6))break 2;

		}

	}

	// 結果を取得した多次元配列を返す（汎用的に使用できるように追加 by Yossee）
	return $this->calArray;

}

/*--------------------------------------------------------------------
カレンダの表示
	メソッド：CalDsp()
※クラスとして使用するには用途が限られるので使用しない（確認程度）
--------------------------------------------------------------------*/
function CalDsp() {
	print("<TABLE>\n");
	foreach ($this->calArray as $week) {
		print ("<TR>\n");
		foreach ($week as $day) {
			print "<TD style=text-align:right;>" . $day . "</TD>\n";
		}
		print ("</TR>\n");
	}
	print("</TABLE>\n");
}


// カレンダークラスの終了
}
?>