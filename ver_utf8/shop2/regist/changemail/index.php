<?php
/*******************************************************************************
ShopWinLite コンテンツ：ショッピングカートプログラム内

メールアドレス変更プログラム：メインコントローラー

	※カートシステム本体とは独立して動作（独立プログラム扱い）
	※登録ユーザーが現在登録しているメールアドレスを変更できる（当然登録者のみ）。

	※入力内容：古いメールアドレス／パスワード／新しいパスワード
	※エラー発生時：エラーメッセージを表示し、再入力画面を出力
	※認証結果ＯＫでかつ、メアドに不備がなければ確認画面を表示
	※確認がＯＫならメールアドレスを更新する
	※原則セッションを使用するが、認証が取れるまで入力ボックスに
	　入力したものを表示するという事はしない。
	（認証ＯＫで修正の場合は入力ボックスに表示してあげる）

2005/10/13 KC
*******************************************************************************/
session_start();

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// エラーメッセージの初期化
$error_message = "";

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/INI_config.php");		// 設定ファイル
require_once("../../common/INI_ShopConfig.php");	// ショップ用設定ファイル
require_once("tmpl2.class.php");					// PHPテンプレートクラスライブラリ

#=============================================================================
# メインコントローラー	※$_POST['status']で分岐
#=============================================================================
switch($_POST['status']):
case 4:

	// ＤＢ更新＆メール送信
	include("LGC_completion.php");

	// 上記の処理でエラーがなければ完了画面を表示し
	// セッションを初期化して終了（エラー時：強制終了）
	if ( $update_result && $usrmail_result ){
		include("DISP_completion.php");
		//$_SESSION['getParam']['oldmail'] = array();
		//$_SESSION['getParam']['newmail'] = array();

		$_SESSION['getParam']['oldmail'] = "";
		$_SESSION['getParam']['newmail'] = "";
		$_SESSION['getParam']['pwd'] = "";
		$_SESSION['setParam']['emailChg_Auth_flg'] = "";//認証を初期化しておく
		exit();

	}
	else{
		//$_SESSION['getParam']['oldmail'] = array();
		//$_SESSION['getParam']['newmail'] = array();

		$_SESSION['getParam']['oldmail'] = "";
		$_SESSION['getParam']['newmail'] = "";
		$_SESSION['getParam']['pwd'] = "";
		$_SESSION['setParam']['emailChg_Auth_flg'] = "";//認証を初期化しておく
		die("予想していないエラーが発生しました！");
	}

	break;
case 3:

	// 修正（再入力画面）
	include("DISP_input.php");

	break;
case 2:

	// エラーチェック
	include("LGC_inputChk.php");

	// エラー：再入力／ＯＫ：確認画面
	if($error_message):
		include("DISP_input.php");
	else:
		include("DISP_confirm.php");
	endif;

	break;
default:

	// 入力画面
	include("DISP_input.php");

endswitch;
?>
