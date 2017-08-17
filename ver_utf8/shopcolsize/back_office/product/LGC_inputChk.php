<?php
/*******************************************************************************
アパレル対応ショッピングカート
	ショッピングカートプログラム バックオフィス

商品の登録・更新
Logic：入力データチェック

*******************************************************************************/

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
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#--------------------------------------------------------------------------------
# 現在の登録件数が設定した件数未満の場合のみDBに格納
#--------------------------------------------------------------------------------
if($_POST["regist_type"]=="new"){
	if(PRODUCT_ENTRY_FLG){
		header("Location: ./");
		exit();
	}
}

#--------------------------------------------------------------------------------
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#--------------------------------------------------------------------------------

// 	タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4,5),true));

#------------------------
# 入力文字列変換
#------------------------

// カート非表示フラグの設定（未チェックならデフォルト“0”）
if($cart_close_flg != 1)$cart_close_flg = 0;

// 今回はカテゴリー指定はないので勝手に１を割り当てる
//$category_code = 1;

// 半角数字に統一
$category_code	= mb_convert_kana($category_code,"n");
$selling_price	= mb_convert_kana($selling_price,"n");
$y1				= mb_convert_kana($y1,"n");					// 販売開始年
$m1				= mb_convert_kana($m1,"n");					// 販売開始月
$d1				= mb_convert_kana($d1,"n");					// 販売開始日
$h1				= mb_convert_kana($h1,"n");					// 販売開始時
$y2				= mb_convert_kana($y2,"n");					// 販売終了年
$m2				= mb_convert_kana($m2,"n");					// 販売終了月
$d2				= mb_convert_kana($d2,"n");					// 販売終了日
$h2				= mb_convert_kana($h2,"n");					// 販売終了時

// 半角カナ→全角カナ
$product_name	= mb_convert_kana($product_name,"KV");
$item_details	= mb_convert_kana($item_details,"KV");
//$product_details	= mb_convert_kana($product_details,"KV");

#------------------------
# 文字データエラーチェック
#------------------------
$error_message = "";

# 【文字列長チェック】

// 商品番号
$error_message .= utilLib::strCheck($part_no,0,"商品番号を入力してください。\n");
if( strlen($part_no) > PARTNO_STR_MAX ){$error_message.="商品番号文字列が長過ぎます。<br>\n";}

// カテゴリ
$error_message .= utilLib::strCheck($category_code,0,"カテゴリを選択してください。\n");

// 商品名
$error_message .= utilLib::strCheck($product_name,0,"商品名を入力してください。\n");
if( strlen($product_name) > PRODUCTNAME_STR_MAX ){$error_message.="商品名文字列が長過ぎます。<br>\n";}

// 備考
if( strlen($item_details) > DETAILS_STR_MAX ){$error_message.="備考が長過ぎます。<br>\n";}

// 	カラー・サイズ・在庫数
$Cstr = "";
$Sstr = "";
$STstr = "";
$cnt = 0;
$no_num_flg = false;
foreach($color as $k => $v){
	$Cstr .= $color[$k];
	$color[$k] = mb_convert_kana($color[$k],"KV");

}
foreach($size as $k => $v){
	$Sstr .= $size[$k];
	$size[$k] = mb_convert_kana($size[$k],"KV");

}
foreach($stock as $k => $v){
	$STstr .= $stock[$k];
	$stock[$k] = mb_convert_kana($stock[$k],"a");

}

// カラー・サイズなし、もしくは全て未入力の場合
if(strlen($Cstr) == 0 && strlen($Sstr) == 0){

	// 在庫数の入力個数と数字以外の入力をチェック
	foreach($stock as $k => $v){
		if(strlen($stock[$k]) != 0)$cnt = $cnt + 1;
		if(!empty($stock[$k]) && !is_numeric($stock[$k]))$no_num_flg = true;
	}

	if(strlen($STstr) == 0)$error_message .= "カラー・サイズ・在庫数または在庫数のみを入力してください。<br>\n";
	if($no_num_flg)$error_message .= "在庫数は半角数字で指定してください。<br>\n";
	if($cnt >= 2)$error_message .= "在庫数が複数入力されています。<br>\n";

// カラー・サイズありの場合
}else{

	foreach($stock as $k => $v){
		if(strlen($color[$k]) != 0 || strlen($size[$k]) != 0){
			if(strlen($stock[$k]) == 0)$error_message .= ($k+1)."番目の在庫数を入力してください。<br>\n";
		}else{
			if(strlen($stock[$k]) != 0)$error_message .= ($k+1)."番目のカラー・サイズを入力してください。<br>\n";
		}
		if(!empty($stock[$k]) && !is_numeric($stock[$k]))$no_num_flg = true;
	}

	if($no_num_flg)$error_message .= "在庫数は半角数字で指定してください。<br>\n";

}

// 単価
$error_message .= utilLib::strCheck($selling_price,0,"単価を入力してください。\n");
if( strlen($selling_price)>SELLINGPRICE_STR_MAX ){$error_message.="単価が長過ぎます。<br>\n";}
if(!is_numeric($selling_price)){$error_message.="単価は半角数字で指定してください。<br>\n";}

/////////////////////////////////////
// 販売期間日時設定のエラーチェック

# 販売開始日時セレクトタグのいずれかが選択されてたら日付チェック
if(!empty($y1) || !empty($m1) || !empty($d1) || !empty($h1)):
	if(!checkdate($m1,$d1,$y1))$error_message.="販売開始日時の指定が正しくありません。設定しない場合は選択肢を全て「未設定」にしてください。<br>\n";
endif;
# 販売終了日時セレクトタグのいずれかが選択されてたら日付チェック
if(!empty($y2) || !empty($m2) || !empty($d2) || !empty($h2)):
	if(!checkdate($m2,$d2,$y2))$error_message.="販売終了日時の指定が正しくありません。設定しない場合は選択肢を全て「未設定」にしてください。<br>\n";
endif;

#----------------------------------
# アップロード画像ファイルチェック
#----------------------------------
// 許可ファイル属性
$ftype=array("image/pjpeg","image/jpeg","image/gif","image/png","image/x-png");

# 【サムネイル画像のチェック】

switch ($_POST["regist_type"]):
	case "new":
			///////////////////////////////////////////////////
			// 新規登録の場合はサムネイル画像を必ずアップさせる

			// アップロードファイルの有無
			if(!is_uploaded_file($_FILES["thumbnail_file"]["tmp_name"]))$error_message.="サムネイル画像を正しく指定してください。(必須)<br>\n";
			// 詳細画像1番目のアップロードの有無
			if(!is_uploaded_file($_FILES["product_img_file"]["tmp_name"][1]))$error_message.="商品詳細画像1を正しく指定してください。(必須)<br>\n";

			if(is_uploaded_file($_FILES["thumbnail_file"]["tmp_name"])):
				// 形式チェック
				//if(!in_array($_FILES["thumbnail_file"]["type"],$ftype))$error_message.="サムネイル画像はJPEG・GIF・PNG形式のみ許可されています。<br>\n";
				// バイナリ形式チェック
				$fdata=@getimagesize($_FILES["thumbnail_file"]["tmp_name"]);
				if($fdata["channels"] == 4)$error_message.= "CMYK形式の画像はアップロード出来ません。RGB形式に保存し直してからアップロードしてください。";
			endif;
		break;
	case "update":
			///////////////////////////////////////////////////////////////////
			// 更新登録の場合はサムネイル画像がアップされてた場合のみチェック

			if(is_uploaded_file($_FILES["thumbnail_file"]["tmp_name"])):
				// 形式チェック
				if(!in_array($_FILES["thumbnail_file"]["type"],$ftype))$error_message.="サムネイル画像はJPEG・GIF・PNG形式のみ許可されています。<br>\n";
				// バイナリ形式チェック
				$fdata=@getimagesize($_FILES["thumbnail_file"]["tmp_name"]);
				if($fdata["channels"] == 4)$error_message.= "CMYK形式の画像はアップロード出来ません。RGB形式に保存し直してからアップロードしてください。";
			endif;
		break;
endswitch;

# 【詳細画像のチェック】

// アップされてる詳細画像文のチェック
for($i=1;$i<=PRODUCT_IMG_NUM;$i++):
	if(is_uploaded_file($_FILES['product_img_file']['tmp_name'][$i])){

		// JPEG形式チェック
		//if(!in_array($_FILES["product_img_file"]["type"][$i],$ftype))$error_message.="詳細画像{$i}はJPEG形式のみ許可されています。<br>\n";
		// バイナリ形式チェック
		$fdata=@getimagesize($_FILES["product_img_file"]["tmp_name"][$i]);
		if($fdata["channels"] == 4)$error_message.= "詳細画像{$i}:CMYK形式の画像はアップロード出来ません。RGB形式に保存し直してからアップロードしてください。";

	}
endfor;
?>
