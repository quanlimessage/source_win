<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）
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
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_H1A.php");	// 共通設定情報
require_once("./class/bgMail.php");					// メール処理クラスライブラリ

// POSTデータの受け取りと共通な文字列処理
//5番（addslashes）は省く（メール送信時に ' " などに\が付いてメール送信されてしまう為、またデータベースにメール内容を登録をするわけではないので特に必要性は無い）
//管理者が入力欄で記入したデータをデータベースに入れる処理はないので５番のaddslashesの文字処理は省く
//もしデータベースに登録する必要がある場合はSQL文の入力したデータにaddslashesを付けて対応する
//if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));
if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));//5番（addslashes）は省く（メール送信時に ' " などに\が付いてメール送信されてしまう為、またデータベースにメール内容を登録をするわけではないので特に必要性は無い）

$status = ($_POST["status"])?$_POST["status"]:$_GET["status"];
$sending_check = ($_POST["sending_check"])?$_POST["sending_check"]:$_GET["sending_check"];
$err_check = ($_POST["err_check"])?$_POST["err_check"]:$_GET["err_check"];

#===============================================================================
# $_POST["status"]の内容により処理を分岐
#===============================================================================
switch($status):
case "completed":

	if($err_check == 1){

		include("DISP_completion.php");
		$_SESSION["search_cond"] = array();

	}elseif($err_check == 0){

		include("DISP_error.php");
		$_SESSION["search_cond"] = array();

	}

	break;
case "send_check":

	// メルマガの内容を入れる
	include("DISP_sending.php");

	break;
case "send_mail":

	include("LGC_getDB-data.php");
	include("LGC_regist.php");//送信履歴をDBに格納
	include("LGC_sendmail.php");

	break;
case "sen_confirm":
	//メルマガ内容確認画面
	// check log file
	if ( file_exists( "./tmp/sendmailCLI_log.log" ) ){

		$old_log_count = file("./tmp/sendmailCLI_log.log");
		$_SESSION['old_log_count'] = count($old_log_count);

	}else{
		die("Super Error !! Not f log !!");
	}

	if($SUBJECT && $COMMENT){
		$_SESSION["title"] = $SUBJECT;
		$_SESSION["content"] = $COMMENT;
	}

	include("DISP_send_confirm.php");

	break;

case "edit_insert_comment":
// メールの内容を入力する
	include("DISP_insertData.php");
	break;

case "insert_comment":
// メールの内容を入力する
	include("LGC_sendRegist_ser.php");
	include("DISP_insertData.php");

	break;
case "search_result":case "pagen":

	// 指定された検索条件を元に顧客情報一覧を取得して結果表示
	include("LGC_getDB-data.php");

	//指定した最大件数を超えていた場合
	if(!$error_msg){
		include("DISP_serch_result.php");
	}else{
		include("DISP_serch_input.php");
	}

	break;

default:

	//Sessionを初期化
	$_SESSION["send_comf"] = array();
	$_SESSION["search_cond"] = array();
	$_SESSION["title"] = "";
	$_SESSION["content"] = "";
	include("DISP_serch_input.php");

endswitch;
// デバッグ用
/*
echo $sql;
echo $sql2;
echo nl2br(print_r($_SESSION , true));
*/
?>
