<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
W系表示用プログラム
Logic：GETで取得したファイル名をダウンロードする

***********************************************************/

$prm_FILE = (isset($_GET["prm"]))?$_GET["prm"]:"";
$prm_path = "./up_img/".$prm_FILE;

if(!$prm_FILE || !preg_match("/^([0-9]{10,})-([0-9]{6}).*$/", $prm_FILE)){
	//prm_FILEが無い、IDの並びに違いがある場合エラー
	header("HTTP/1.0 404 Not Found");exit();
}

if($prm_path):
// ファイルの存在確認

if (!file_exists($prm_path)) {
    //die("Error: File(".$prm_path.") does not exist");
	header("HTTP/1.0 404 Not Found");exit();
}

// オープンできるか確認
if (!($fp = fopen($prm_path, "r"))) {
    //die("Error: Cannot open the file(".$prm_path.")");
	header("HTTP/1.0 404 Not Found");exit();
}
fclose($fp);

// ファイルサイズの確認
if (($content_length = filesize($prm_path)) == 0) {
//die("Error: File size is 0.(".$prm_path.")");
	header("HTTP/1.0 404 Not Found");exit();
}

header("Content-Disposition: attachment; filename=".basename($prm_path)."");
header("Content-Length: ".$content_length);

// ブラウザで開く
//header("Content-Type: application/octet-stream");

// ファイルを読んで出力
    if (!readfile($prm_path)) {
        //die("Cannot read the file(".$prm_path.")");
	header("HTTP/1.0 404 Not Found");exit();
    }
endif;
?>
