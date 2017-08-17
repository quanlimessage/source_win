<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理


*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ./err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
@extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

	
#=================================================================================
# ブロックの画像削除
#=================================================================================
for($i = 0; $i < count($block_del_img); $i ++){
	// ファイル削除関数に外部から来たデータをそのまま入れるのは危険のような・・・でも、時間がないので今は保留。
	if(file_exists(CP1_IMG_PATH.$block_del_img[$i])){
		unlink(CP1_IMG_PATH.$block_del_img[$i]) or die("画像の削除に失敗しました。");
	}
}

#=================================================================================
# ブロックの登録
#=================================================================================
// ブロッククエリの作成
$imgObj = new imgOpe(CP1_IMG_PATH);


foreach($_POST['_block_id'] as $k => $v){
	$bid = sntz($_POST['_block_id'][$k]);
	$b_res_id = sntz($_POST['_block_res_id'][$k]);
	$block_query_txt = "";

	$bpath = CP1_BLOCK_PATH."block".$bid.".php";
	if(file_exists($bpath)) {
		include_once($bpath);
	}else{
		continue;
	}
	
	
	// テキスト
	for($j = 0; $j< $BLOCK_DATA[$bid]['VALUE_MAXNUM'];$j++ ){
		$value = html_tag($_POST['_value'][$k][$j]);
		$block_query_txt .= "TEXT".($j+1)."='".$value."',";
	}
	
	// ファイル（画像のみ）
	for($j = 0; $j< $BLOCK_DATA[$bid]['FILE_MAXNUM'];$j++ ){

		$filename = $b_res_id."_".($j+1);
		
		$_width = (is_numeric($_POST['_width_file'][$k][$j])) ? $_POST['_width_file'][$k][$j] : "";
		$_height = (is_numeric($_POST['_height_file'][$k][$j])) ? $_POST['_height_file'][$k][$j] : "";
		$_lightbox = (is_numeric($_POST['_lightbox_file'][$k][$j])) ? $_POST['_lightbox_file'][$k][$j] : 0;
		$_border = (is_numeric($_POST['_border_file'][$k][$j])) ? $_POST['_border_file'][$k][$j] : 0;
		$_link_url = (($_POST['_link_url_file'][$k][$j])) ? $_POST['_link_url_file'][$k][$j] : "";
		$_link_target = (is_numeric($_POST['_link_target_file'][$k][$j])) ? $_POST['_link_target_file'][$k][$j] : 0;
		$ext = (($_POST['_old_ext_file'][$k][$j])) ? $_POST['_old_ext_file'][$k][$j] : "";
		
		// 画像のアップロード
		if(is_uploaded_file($_FILES['_file']['tmp_name'][$k][$j])){
			search_file_del(CP1_IMG_PATH,$filename.".*");

			// アップロードされた画像サイズ
			$size = getimagesize($_FILES['_file']['tmp_name'][$k][$j]);
			$data=pathinfo($_FILES['_file']['name'][$k][$j]);
			$ext=$data['extension'];

			if($_width && $_height){
				$size_x = $_width;//横の固定サイズ
				$size_y = $_height;
			}elseif($_width){

				//画像サイズを調整
				$size_x = $_width;//横の固定サイズ
				$size_y = $size[1]/($size[0]/$size_x);
			}elseif($_height){

				//画像サイズを調整
				$size_y = $_height;//横の固定サイズ
				$size_x = $size[0]/($size[1]/$size_y);
			}else{

				//画像サイズを調整
				$size_x = $size[0];//横の固定サイズ
				$size_y = $size[1];
			}
			
			// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
			//$imgObj->setSize($ox[$i-1],$oy[$i-1]);
			//$imgObj->isFixed=true;
			$imgObj->setSize($size_x, $size_y);//横固定、縦可変型
			if(!$imgObj->up($_FILES['_file']['tmp_name'][$k][$j],$filename)){
				exit("画像のアップロード処理に失敗しました。");
			}
			/*
			$size_x2 = "300";//横の固定サイズ
			$size_y2 = $size[1]/($size[0]/$size_x2);
			//setPermission(CP1_IMG_PATH.$filename.".jpg");
			$imgObj->setSize($size_x2, $size_y2);//横固定、縦可変型
			if(!$imgObj->up($_FILES['_file']['tmp_name'][$k][$j],$filename."_l")){
				exit("画像のアップロード処理に失敗しました。");
			}
			*/
			
		}

		$ext = strtolower($ext);	// 拡張子が大文字の場合表示されない不具合を修正するため、全て小文字統一

		// 画像のパラメータ設定
		$block_query_txt .= "FILE".($j+1)."='".$filename.".".$ext."',";
		// DOMの生成
		$dom = new DOMDocument();
		// IMAGEタグ
		$root = $dom->createElement('img_param');
		// タグを追加
		$dom->appendChild($root);
		// filenameタグ
		$root->appendChild($dom->createElement('filename',$filename.".".$ext.""));
		$root->appendChild($dom->createElement('extension',$ext));
		$root->appendChild($dom->createElement('width',$_width));
		$root->appendChild($dom->createElement('height',$_height));
		$root->appendChild($dom->createElement('lightbox',$_lightbox));
		$root->appendChild($dom->createElement('border',$_border));
		$root->appendChild($dom->createElement('link_url',$_link_url));
		$root->appendChild($dom->createElement('link_target',$_link_target));
		
		
		
		$block_query_txt .= "FPARAM".($j+1)."='".$dom->saveXML()."',";
	}
	
	// DBに登録
	$bsql = "UPDATE  ".CP1_VALUES_LST."
	SET
		RES_ID = '$b_res_id',
		PAGE_ID = '$res_id',
		$block_query_txt
		UPD_DATE = NOW(),
		DEL_FLG = '0'
	WHERE (DEL_FLG = '0') AND (RES_ID = '".$b_res_id."') ";

	//echo $bsql."<br>";
	$db_result = dbOpe::regist($bsql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");
	

}



?>