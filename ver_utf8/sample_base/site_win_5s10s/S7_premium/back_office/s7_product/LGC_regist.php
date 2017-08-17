<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理

*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

//カテゴリー情報の取得
$sql = "
	SELECT
		CATEGORY_CODE,CATEGORY_NAME,VIEW_ORDER
	FROM
		".S7_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
";
// ＳＱＬを実行
$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// MySQLにおいて危険文字をエスケープしておく
$title = utilLib::strRep($title,5);
$title_tag = utilLib::strRep($title_tag,5);

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$content = html_tag($_POST['content']);
$detail_content = html_tag($_POST['detail_content']);
$youtube = html_tag($_POST['youtube']);

#-----------------------------------------------------------------
#	VIEW_ORDER用の値を作成
#		※現在登録されている記事データ中の最大VIEW_ORDER値を取得
#		  それに1足したものを$view_orderに格納して使用
#		※登録場所チェックが入っていたらVIEW_ORDER値を全て1繰上げ
#		　$view_orderに1をセットし結果的に登録を一番上にする
#-----------------------------------------------------------------
		//複製する時のVIEW_ORDER
		if($_POST["copy_type"]=="new"){
			$vosql_old = "SELECT VIEW_ORDER AS VO FROM ".S7_PRODUCT_LST." WHERE (RES_ID = '$res_id') AND (DEL_FLG = '0')";
			$fetchVO_old = dbOpe::fetch($vosql_old,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			$view_order_old = $fetchVO_old[0]["VO"];

			$vosql ="UPDATE ".S7_PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1 WHERE (CATEGORY_CODE = '$ca') AND (VIEW_ORDER > $view_order_old)";
			if(!empty($vosql)){
				$db_result = dbOpe::regist($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");
			}
			$view_order = ($fetchVO_old[0]["VO"] + 1);
		}elseif($_POST["ins_chk"]=="1"){
		//トップ登録チェックを入れる時
			$vosql ="UPDATE ".S7_PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1 WHERE (CATEGORY_CODE = '$ca')";
			if(!empty($vosql)){
				$db_result = dbOpe::regist($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");
			}
			$view_order = 1;
		}
		else{
		//新規登録
			$vosql = "SELECT MAX(VIEW_ORDER) AS VO FROM ".S7_PRODUCT_LST." WHERE (CATEGORY_CODE = '$ca') AND (DEL_FLG = '0')";
			$fetchVO = dbOpe::fetch($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			$view_order = ($fetchVO[0]["VO"] + 1);
		}

#==================================================================
# 更新する内容をここで記述をする
# フィールドの追加・変更はここで修正
#==================================================================
	$sql_update_data = "
		CATEGORY_CODE = '$ca',
		TITLE = '$title',
		CONTENT = '$content',
		DETAIL_CONTENT = '$detail_content',
		TITLE_TAG = '$title_tag',
		YOUTUBE = '$youtube',
		IMG_FLG = '$img_flg',
		DISP_DATE = NOW(),
		DISPLAY_FLG = '$display_flg',
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

	// 削除指示がされていたら実行(複数)
	if($_POST["regist_type"]=="update" && $del_img){
		foreach($del_img as $k => $v){
		 	search_file_del(IMG_PATH,$res_id."_".$v.".*");
		}
	}

/*	if($_POST["regist_type"]=="update" && $_POST["ins_chk"]=="1"){
		$vosql ="UPDATE ".S7_PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1 WHERE (CATEGORY_CODE = '$ca')";
		if(!empty($vosql)){
			$db_result = dbOpe::regist($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result)die("DB登録失敗しました<hr>{$db_result}");
		}
		$view_order = "VIEW_ORDER = '1',";
	}
*/

	// DB格納用のSQL文
	$sql = "
	UPDATE
		".S7_PRODUCT_LST."
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

	// 現在の登録件数が設定した件数未満の場合のみDBに格納
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".S7_PRODUCT_LST." INNER JOIN ".S7_CATEGORY_MST." ON (".S7_PRODUCT_LST.".CATEGORY_CODE = ".S7_CATEGORY_MST.".CATEGORY_CODE) WHERE(".S7_PRODUCT_LST.".DEL_FLG = '0')AND(".S7_CATEGORY_MST.".DEL_FLG = '0')";
	$fetchCNT = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//最大登録件数に達していない、そして、カテゴリーが存在している場合登録をする
	if(($fetchCNT[0]["CNT"] < DBMAX_CNT) && count($fetchCA)):

		$sql = "
		INSERT INTO ".S7_PRODUCT_LST."
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
$imgObj = new imgOpe(IMG_PATH);

// 画像ファイル名の決定(記事のIDを使用)
$for_imgname = $res_id;

// 設定ファイルの画像最大登録枚数分ループ
for($i=1;$i<= IMG_CNT;$i++):

// アップロードされた画像ファイルがあればアップロード処理
if(is_uploaded_file($_FILES['up_img']['tmp_name'][$i])){

	//古いファイルは拡張子をワイルドカードにして削除（拡張子が違っている場合古いファイルは上書きで消えない為）
	search_file_del(IMG_PATH,$for_imgname."_".$i.".*");

	// アップされてきた画像のサイズを計る
	$size = getimagesize($_FILES['up_img']['tmp_name'][$i]);

	//画像サイズを調整
	$size_x = $ox[$i-1];//横の固定サイズ
	$size_y = $size[1]/($size[0]/$size_x);

	// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
	//$imgObj->setSize($ox[$i-1],$oy[$i-1]);
	//$imgObj->isFixed=true;
	$imgObj->setSize($size_x, $size_y);//横固定、縦可変型

	if(!$imgObj->up($_FILES['up_img']['tmp_name'][$i],$for_imgname."_".$i)){
		exit("画像のアップロード処理に失敗しました。");
	}

}
endfor;

?>
