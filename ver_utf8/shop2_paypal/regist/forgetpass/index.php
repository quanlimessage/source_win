<?php
/*******************************************************************************
カテ+ブランド+サイズ+カラー+在庫対応
	ショッピングカートプログラム

パスワード変更プログラム：メインコントローラー

	※カートシステム本体とは独立して動作（独立プログラム扱い）
	※登録ユーザーがパスワードを忘れてしまっている場合にメールアドレス
	　を変更できる（当然登録者のみ）。

	※入力内容：現在登録されているメールアドレス
	※エラー発生時：エラーメッセージを表示し、再入力画面を出力
	※メールアドレスで認証結果ＯＫでかつ、メアドに不備がなければ
	　CUSTOMER_IDをcrypt化したデータをGETにつけたURL（このファイル自身。キー：“cid”と“did”）
	　を案内メール記述して送付。送信ＯＫならその旨の表示をさせて一旦終了とする。

	※ユーザーがメールに記載されたURLをクリックし、GETデータをチェック。
	※問題がなければ新しい任意のパスワードを入力させてパスワードを更新させて
	　完了画面を表示させて終了

*******************************************************************************/
//session_cache_limiter("none");
session_start();

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// エラーメッセージの初期化
$error_message = "";

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/INI_config.php");		// 設定ファイル
require_once("../../common/INI_ShopConfig.php");	// ショップ用設定ファイル
require_once("dbOpe.php");							// ＤＢ操作クラスライブラリ
require_once("util_lib.php");						// 汎用処理クラスライブラリ
require_once("tmpl2.class.php");					// PHPテンプレートクラスライブラリ

// パスワードをcrypt → 半角英数字化する為の匿名関数
$cryptPass = create_function('$pw','return preg_replace("/[^a-zA-Z0-9]/","",crypt($pw,"AP"));');

#================================================================================
# $_POST['status']の内容により処理をコントロール
#================================================================================
switch($_POST['status']):
case "pck":
///////////////////////////////////////////////////////////
// To Step4 新パスワードチェックとパスワードデータの更新
//			（	※DISP_step3.phpの送信より）

		include("LGC_inputChk.php");
		if($error_message){
			include("DISP_step3.php"); // to pck
		}
		else{

			// 完了画面を表示し、認証セッションを初期化して終了
			include("DISP_step4.php");
			$_SESSION['authOK'] = array();
		}

	break;
case "mck":
///////////////////////////////////////////////////////////
// To Step2 メールチェック	※DISP_step1.phpの送信より

	include("LGC_inputChk.php");
	if($error_message){
		include("DISP_step1.php"); //to mck
	}
	else{

		// メール送信完了画面を表示して一旦終了とする
		include("DISP_step2.php");
	}

	break;
default:
////////////////////////////////////////////////////////////
// Step1（現在のメアド入力）または
// Step3（メールで指定したGET付のURLよりアクセス）の判断
//	※$_GET['cid'] と $_GET['did']で判断

	if($_GET['cid'] && $_GET['did']){

		include("LGC_inputChk.php");
		if($error_message){
			header("Location: ./");exit();

		}
		else{
			include("DISP_step3.php"); // to pck
		}

	}
	else{
		include("DISP_step1.php"); // to mck
	}

endswitch;
?>
