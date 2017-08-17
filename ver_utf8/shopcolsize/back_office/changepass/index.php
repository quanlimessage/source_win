<?php
/*******************************************************************************
管理者ID/PASS変換プログラム

	メインコントローラー

*******************************************************************************/
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// エラーメッセージの初期化
$error_message = "";

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/INI_config.php");		// 設定情報
require_once("../../common/INI_ShopConfig.php");	// ショップ用設定情報

#================================================================================
# $_POST['status']の内容により処理をコントロール
#================================================================================
switch($_POST['status']):
case "completion":
///////////////////////////////////////////////////////////
// DB更新+変更通知メール送信+完了画面出力

		// 入力された現ID/現パスワードの確認
		include("LGC_inputChk.php");
		// エラーがあったら再度更新画面出力
		if($error_message){
			include("DISP_update.php");
			break;
		}

		// DB更新処理
		include("LGC_registDB.php");

		// 変更通知メール送信
		include("LGC_sendmail.php");

		// 完了画面出力
		include("DISP_completion.php");

	break;
case "update":
///////////////////////////////////////////////////////////
// 更新確認画面の出力

		// 入力された現ID/現パスワードの確認
		include("LGC_inputChk.php");
		// 適合しなかったらカレントスクリプトの強制終了
		if($error_message){
			header("HTTP/1.0 404 Not Found"); exit("不正アクセス！認証に失敗しました。");
		}

		// 適合したら更新画面の出力
		include("DISP_update.php");

	break;
default:
		////////////////////////////////////////////////////////////////
		// DBの現ID/PASS情報取得
		//	もし情報があれば確認画面、なければ初回のID/PASS登録画面出力

			// SQL組立て
			$conf_sql = "
			SELECT
				BO_ID,
				BO_PW
			FROM
				".CONFIG_MST."
			WHERE
				( CONFIG_ID = '1' )
			";

			$fetchConf = $PDO -> fetch($conf_sql);

		//////////////////////////////////////////////////////////
		// 現ID+現パスワードが登録されているかいないかで出力分岐
		if(!empty($fetchConf[0]["BO_ID"]) && !empty($fetchConf[0]["BO_PW"])){
			////////////////////////////////////////////////////////////
			// 確認入力画面
			include("DISP_top.php"); // 現ID/PASS確認画面
		}else{
			///////////////////////////////////////////////////////////
			// 更新確認画面の出力 ※初回登録用
			include("DISP_update.php");
		}

endswitch;
?>
