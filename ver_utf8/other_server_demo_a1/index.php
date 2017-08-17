<?php
/*************************************************************************************
 お問い合わせフォーム（POST渡しバージョン）
 Main Controller：全体の処理の制御・イニシャライズ

 	★基本的仕様

		１．新規入力（送信先action:confirm）
			↓
		２．エラーチェック
				・未入力または不備がある → エラーページを表示して終了
				・問題なし → 確認画面表示へ
			↓
		３．確認画面表示（送信先action:completion）
				・修正 → 戻るボタンで前のページへ戻る（JavaScriptのhistoryを使用）
				・ＯＫ → 完了画面表示へ
			↓
		４．メール送信の処理を行い、完了画面表示して終了

*************************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("./common/config.php");	// 設定情報
require_once("./common/util_lib.php");			// 汎用処理クラスライブラリ
//require_once("./util_lib.php");			// 汎用処理クラスライブラリ

// エラーメッセージと不正アクセスフラグ（index.php以外からのコールを防~）の初期化
$error_mes = "";
$accessChk = true;

#--------------------------------------------------------------
# 全体のコントロール	※キーはhiddenで送っている$status
#--------------------------------------------------------------
switch($_POST["action"]):

case "completion":
/////////////////////////////////////////////////////////////////////////////
//　メール送信処理と完了画面を表示

	include("LGC_inputChk.php");

	if(!$error_flg){
		include("LGC_sendmail.php");

		// 完了画面表示用パラメータを生成
		$comp_id = md5(uniqid(rand(), true));

		header("Location: ./DSP_completion.php?comp_id=".$comp_id);
		exit();

	}
	else{

		utilLib::errorDisp("<p>不正な処理が行われました。<br>お手数をおかけして誠に申し訳ございませんが、送信内容を確認の上、もう一度送信しなおしてください。</p>");
	}

	break;
case "confirm":
/////////////////////////////////////////////////////////////////////////////
// エラーがあれば再入力、OKなら確認画面表示（送信先：completion）
	include("LGC_inputChk.php");

	if($error_mes):
		//utilLib::errorDisp($error_mes);
		include("DSP_error.php");
	else:
		include("DSP_confirm.php");
	endif;

	break;
default:
/////////////////////////////////////////////////////////////////////////////
// 新規入力画面を表示（送信先：confirm）

	include("DSP_input.php");

endswitch;
?>