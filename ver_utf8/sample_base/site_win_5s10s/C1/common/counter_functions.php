<?php
//ファイルのパス指定
//count.txtのパーミッションは666に設定をしてください
$file = str_replace("/common","",dirname(__FILE__))."/count.txt";

//アクセスカウント数を読み込む処理
function ReadCountFile(){
	//ファイルを読み込む
	global $file;
	$fp = fopen($file, "r" ) or exit( "file open error.".$file );
	$count = fgets($fp);
	fclose( $fp );

	return intval($count);

}

//アクセスカウント数を書き込む処理
function WriteFile($cnt){

	//ファイルを読み込む
	global $file;
	$fp = fopen($file, "w" ) or exit( "file open error.".$file );
	flock( $fp, LOCK_EX );
	fputs( $fp, $cnt );
	flock( $fp, LOCK_UN );
	fclose( $fp );

}

function AccessCount(){

	//現在のカウントを取得する
	$cnt = ReadCountFile();

	//アクセスカウントを＋１する
	$cnt++;

	//ファイルにアクセスカウントを書き込む
	WriteFile($cnt);

}

//アクセスカウント数を画像表示する処理（画像を使用しない場合は【ReadCountFile()】から読み込んで表示をしてください）
function PrintCounterImage(){

	$cntImage[0] = "./img/c0.gif";
	$cntImage[1] = "./img/c1.gif";
	$cntImage[2] = "./img/c2.gif";
	$cntImage[3] = "./img/c3.gif";
	$cntImage[4] = "./img/c4.gif";
	$cntImage[5] = "./img/c5.gif";
	$cntImage[6] = "./img/c6.gif";
	$cntImage[7] = "./img/c7.gif";
	$cntImage[8] = "./img/c8.gif";
	$cntImage[9] = "./img/c9.gif";

	//ファイルを読み込む
	$count = ReadCountFile();

	//０詰めする。
	$count = sprintf("%06d",$count);

	//初期化
	$strCounter = "<div id=\"count-img\">";

	//カウンター画像
	$strCounter = "";
	for($i=0;$i<strlen($count);$i++){

		//１文字読み込み
		$num = substr($count,$i,1);

		//タグに整形
		$strCounter .= "<img src=\"{$cntImage[$num]}\" alt=\"{$num}\" >\n";

	}

	$strCounter .= "</div>";

	return $strCounter;

}
?>