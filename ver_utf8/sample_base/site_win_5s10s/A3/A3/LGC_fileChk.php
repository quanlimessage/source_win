<?php
/************************************************************************
 お問い合わせフォーム（POST渡しバージョン）
 処理ロジック：エラーチェック
	※POST送信されたデータに対して不備が無いかチェックする

************************************************************************/

// 不正アクセスチェック
if(!$accessChk){
	header("HTTP/1.0 404 Not Found");exit();
}

	for($i = 0; $i < $attachNum;$i++){

		// 選択の有無
		if($_FILES['up_file']['name'][$i]){

			// 画像のインデックス
			$idx = ($i+1);
			// なんらかのエラーが出たとき
			if($_FILES['up_file']['error'][$i] > 0){
				$error_mes .="画像".$idx."は送信することができません。<br><br>\n";
				continue;
			}

			// ファイル形式チェック
			switch($_FILES['up_file']['type'][$i]){
			case "image/jpeg":
			case "image/png":
			case "image/gif":
			case "image/pjpeg":
			case "image/x-png":
				break;
			default:
				$error_mes .= "画像".$idx."は送信できないファイル形式です。<br><br>\n";
				continue;
			}
		}else{

			// 最初の画像をチェック（最低一枚
			if($i == 0) $error_mes .= "画像".$idx."を選択してください。<br><br>\n";
		}
	}

?>
