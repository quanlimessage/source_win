<?php

require_once("../../common/config_W3_N3_2.php");	// 設定情報

$errmsg = "";

//post_max_size（サーバー設定：POST送信可能なサイズ上限）を取得
$post_max = ini_get('post_max_size');
$post_max = str_replace("M", "", $post_max);
$post_max = $post_max  * 1024 * 1024;

// 送信しようとしたデータがpost_max_sizeよりも大きい場合
if($_SERVER["CONTENT_LENGTH"] > $post_max){
	//$errmsg .= "post_max_size = ".$post_max."\n";
	//$errmsg .= "CONTENT_LENGTH = ".$_SERVER["CONTENT_LENGTH"]."\n";
	$errmsg .= "送信可能なファイルのサイズを超えています。\n";
}else{

	foreach($_FILES as $file){
		if($file){
			if(is_array($file['size'] )){

				foreach($file['size'] as $size){
					if($size > LIMIT_SIZE * 1024 * 1024){
						$errmsg .= "容量が大きすぎるため、アップロードできません。\n(".(LIMIT_SIZE - 1)."MB以下のファイルのみアップロード可能です。)\n";
					}
				}

				foreach($file['error'] as $error){
					if($error){
						$errmsg .= "このファイルはアップロードすることができません。\n";
					}
				}

			}else{

				if($file['size'] > LIMIT_SIZE * 1024 * 1024){
				$errmsg .= "容量が大きいです。".(LIMIT_SIZE - 1)."MB以下のファイルにしてください。\n";
				}
				if($file['error']){
					$errmsg .= "このファイルはアップロードすることができません。\n";
				}

			}

			// ファイル型式チェック
			/*
			if(!preg_match( "/jpeg/",$file['type'])){
				$errmsg .= "ファイル形式が違います。jpegファイルにしてください。";
			}
			*/
			$errmsg = ($errmsg) ? $errmsg : "success";
		}
	}
}
echo $errmsg;
?>