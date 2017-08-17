<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理

*******************************************************************************/

#==================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#==================================================================
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

#============================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#============================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// 半角数字に統一（$y:年 $m:月 $d:日）
$y = mb_convert_kana($y,"n");
$m = mb_convert_kana($m,"n");
$d = mb_convert_kana($d,"n");

// MySQLにおいて危険文字をエスケープしておく
$comment = utilLib::strRep($comment,5);

#==================================================================
# 表示日時の設定
# 表示日時のタイムスタンプ作成し、指定があれば指定日時をし
# 無ければ現在日時用文字列を使用する
#==================================================================
if(!empty($y) && !empty($m) && !empty($d)){
	$disp_time = "{$y}-{$m}-{$d} ".date("H:i:s");
}
else{
	$disp_time = date("Y-m-d H:i:s");
}

#==================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#==================================================================
switch($_POST["regist_type"]):
case "update":
//////////////////////////////////////////////////////////
// 対象IDのデータ更新

	// 対象記事IDデータのチェック
	if(!ereg("^([0-9]{10,})-([0-9]{6})$",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

		// 画像ファイル名の決定（POSTで渡された既存の記事ID（$res_id）を使用）
	$for_imgname = $res_id; // POSTで渡された既存記事IDを使用

	// 削除指示がされていたら実行
	if($_POST["regist_type"]=="update" && $del_img == 1){

		if(file_exists(N4_2IMG_PATH.$res_id.".jpg")){
			unlink(N4_2IMG_PATH.$res_id.".jpg") or die("画像の削除に失敗しました。");
		}

	}

	// DB格納用のSQL文
	$sql = "
	UPDATE
		N4_2TICKER
	SET
		COMMENT = '$comment',
		LINKURL = '$linkurl',
		DISP_DATE = '$disp_time',
		DISPLAY_FLG = '$display_flg'
	WHERE
		(RES_ID = '$res_id')
	";

	break;
case "new":
///////////////////////////////////////////////////////////////
// 新規登録

// 画像ファイル名の決定（新しいIDを生成して使用。DB登録時のRES_IDにも使用）
	$res_id = $makeID();
	$for_imgname = $res_id;

	// 現在の登録件数が設定した件数未満の場合のみDBに格納
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM N4_2TICKER WHERE(DEL_FLG = '0')";
	$fetch = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if($fetch[0]["CNT"] < N4_2DBMAX_CNT){
		$sql = "
		INSERT INTO N4_2TICKER(
			RES_ID,COMMENT,LINKURL,DISP_DATE,DISPLAY_FLG
		)
		VALUES(
			'$res_id','$comment','$linkurl','$disp_time','$display_flg'
		)";
	}
	else{
		header("Location: ./");
	}

	break;
default:
	die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

}

#==================================================================
# 共通処理：画像アップロード処理
#==================================================================
// 画像処理クラスimgOpeのインスタンス生成
/*$imgObj = new imgOpe(N4_2IMG_PATH);

// アップロードされた画像ファイルがあればアップロード処理
if(is_uploaded_file($_FILES['up_img']['tmp_name'])){

	// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
	$imgObj->setSize(N4_2IMGSIZE_MX,N4_2IMGSIZE_MY);
	if(!$imgObj->up($_FILES['up_img']['tmp_name'],$for_imgname)){
		exit("詳細画像のアップロード処理に失敗しました。");
	}
}
*/
?>