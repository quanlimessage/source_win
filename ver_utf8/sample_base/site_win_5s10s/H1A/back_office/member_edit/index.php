<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
メインコントローラー

*******************************************************************************/
// セッション管理スタート(検索指定情報管理)
session_start();
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

// 不正アクセスチェックのフラグ
$accessChk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_H1A.php");	// 共通設定情報
require_once("util_lib.php");				// 汎用処理クラスライブラリ
require_once('imgOpe.php');					// 画像アップロードクラスライブラリ

//もし、POSTに何も無くてGETでデータが送られていた場合はGETを入れる
if(($_GET) && (!$_POST)){$_POST["action"] = $_GET["action"];}

#===============================================================================
# $_POST["action"]の内容により処理を分岐
#===============================================================================
switch($_POST["action"]):
case "completion":

	include("LGC_inputChk.php");

	if(!$error_mes){//エラーが発生してない場合　登録処理を行う

		// データ登録処理を行い一覧へ戻る
		include("LGC_regist.php");
		header("Location: ./?action=list");

	}else{//エラーが発生した場合　入力画面へ戻る

		if($regist_type == "new"){
			include("DISP_newInput.php");
		}else{
			include("DISP_update.php");
		}

	}

	break;
case "update":
//////////////////////////////////////////////////
//	更新画面出力

	include("LGC_getDB-data.php");
	include("DISP_update.php");

	break;
case "new_entry":
//////////////////////////////////////////////////
//	新規登録画面出力

	include("DISP_newInput.php");

	break;
case "del_data":
/////////////////////////////////////////////////
//	対象データの 削除

	include("LGC_del.php");

case "search_result": case "list":
/////////////////////////////////////////////////
// DBより情報を取得し、一覧表示する

	include("LGC_getDB-data.php");
	include("DISP_listview.php");
	break;

default:
/////////////////////////////////////////////////
//	検索画面を表示

	//Sessionを初期化
	$_SESSION["send_comf"] = array();
	$_SESSION["search_cond"] = array();
	include("DISP_serch_input.php");

endswitch;

?>
