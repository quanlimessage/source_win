<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理

*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// MySQLにおいて危険文字をエスケープしておく
$title = utilLib::strRep($title,5);
//$content = utilLib::strRep($content,5);

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$content = html_tag($_POST['content']);

#==================================================================
# 更新する内容をここで記述をする
# フィールドの追加・変更はここで修正
#==================================================================
	$sql_update_data = "
		TITLE = '$title',
		CONTENT = '$content',
		DISP_DATE = NOW(),
		DISPLAY_FLG = '$display_flg',
		POSITION_FLG = '$position_flg',
		DEL_FLG = '0'
	";

#=================================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch($_POST["regist_type"]):
case "update":
//////////////////////////////////////////////////////////
// 対象IDのデータ更新

	// 対象記事IDデータのチェック
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	// 画像ファイル名の決定（POSTで渡された既存の記事ID（$res_id）を使用）
	$for_imgname = $res_id; // POSTで渡された既存記事IDを使用

	// 削除指示がされていたら実行
	if($_POST["regist_type"]=="update" && $del_img == 1){

		if(file_exists(S3B_IMG_PATH.$res_id.".jpg")){
			unlink(S3B_IMG_PATH.$res_id.".jpg") or die("画像の削除に失敗しました。");
		}

	}

	// DB格納用のSQL文
	$sql = "
	UPDATE
		".S3B_PRODUCT_LST."
	SET
		$sql_update_data
	WHERE
		(RES_ID = '$res_id')
	";

	break;

case "new":
//////////////////////////////////////////////////////////////////
// 新規登録

	// 画像ファイル名の決定（新しいIDを生成して使用。DB登録時のRES_IDにも使用）
	$res_id = $makeID();
	$for_imgname = $res_id;

	// 現在の登録件数が設定した件数未満の場合のみDBに格納
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".S3B_PRODUCT_LST." WHERE(DEL_FLG = '0')";
	$fetchCNT = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if($fetchCNT[0]["CNT"] < S3B_DBMAX_CNT):

		#-----------------------------------------------------------------
		#	VIEW_ORDER用の値を作成
		#		※現在登録されている記事データ中の最大VIEW_ORDER値を取得
		#		  それに1足したものを$view_orderに格納して使用
		#		※登録場所チェックが入っていたらVIEW_ORDER値を全て1繰上げ
		#		　$view_orderに1をセットし結果的に登録を一番上にする
		#-----------------------------------------------------------------

		if($_POST["regist_type"]=="new" && $ins_chk == 1){
			$vosql ="UPDATE ".S3B_PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1";
			if(!empty($vosql)){
				$db_result = dbOpe::regist($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");
			}
			$view_order = 1;
		}
		else{
			$vosql = "SELECT MAX(VIEW_ORDER) AS VO FROM ".S3B_PRODUCT_LST." WHERE(DEL_FLG = '0')";
			$fetchVO = dbOpe::fetch($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			$view_order = ($fetchVO[0]["VO"] + 1);
		}

		$sql = "
		INSERT INTO ".S3B_PRODUCT_LST."
			SET
				RES_ID = '$res_id',
				VIEW_ORDER = '$view_order',
				$sql_update_data
		";

	else:
		header("Location: ./");
	endif;

	break;
default:
	die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

}

#=================================================================================
# 共通処理；画像アップロード処理
#=================================================================================
// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(S3B_IMG_PATH);

// アップロードされた画像ファイルがあればアップロード処理
if(is_uploaded_file($_FILES['up_img']['tmp_name'])){

	// アップされてきた画像のサイズを計る
		$size = getimagesize($_FILES['up_img']['tmp_name']);

	//画像サイズを調整
		$size_x = S3B_IMGSIZE_MX;//横の固定サイズ
		$size_y = $size[1]/($size[0]/$size_x);

	// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
		$imgObj->setSize($size_x, $size_y);//横固定、縦可変型
		//$imgObj->setSize(S3B_IMGSIZE_MX,S3B_IMGSIZE_MY);
		//$imgObj->isFixed=true;

	if(!$imgObj->up($_FILES['up_img']['tmp_name'],$for_imgname)){
		exit("画像のアップロード処理に失敗しました。");
	}
}
?>
