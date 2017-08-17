<?php
/*******************************************************************************

カート：メインコントローラー
	カートの中身一覧表示以降の操作
	※すべての処理は必ずこのファイルを通じて行われる

※処理の条件分岐

	※パスワードを忘れた方向けのページと、メールアドレスが変わった方向けの
	　ページの２種類を用意。このページは別プログラムとして独立させる。
		メール変更：changemail　／　パスワードを忘れた：forgetpass

	$_POST['status']の処理内容

		default：
			デフォルト表示（買い物カゴの中身一覧＆認証ページを表示）

		step1：
			必要情報の入力画面を表示（支払方法と個人情報の入力）
			※認証に成功→入力画面（支払方法と個人情報の入力）
			※認証に失敗→再度前のページを表示！？（ん？要確認だぁ～）
			※購入内容は常に表示

		step2：
			必要情報の入力画面を表示（配送先情報の入力）
			※前のページの個人情報と配送先の情報が同じ場合は
			　チェックを入れさせて、入力の手間を省かせる。

		confirm：
			入力内容確認画面を表示

		completion：
			入力内容を確定。DBに入力情報を格納する。

			支払方法により下記の通りに処理を分岐する。

			１．支払方法：クレジット

				決済サイトへ誘導。決済に必要な情報をhiddenでセットし、POSTで
				ユーザーが手動で送信。決済に成功した場合は指定したURL宛にGETを
				含んだ結果が送信される。

					※GETのパラメーター：
					・$_GET['sendid']（購入ID。PHACASE_LSTのORDER_IDが格納。決済サイトの誘導時のhiddenで仕込んだsendidの値）
					・$_GET['result']（決済結果。値は“OK”または“ok”が格納されてるらしい。。。）

				指定URLをregist.php(旧：credit_payment_result.php)宛に結果を返すようにしてもらい、
				このファイルを独立プログラムとしてして以下の処理を行う。

					上記のGETデータを元にテーブルPURCHASE_LST（注文情報リスト）より
					購入IDと個人IDを取得し、決済済みフラグをつける等の更新を行う。
					ユーザー／管理者共に完了のメールを送信するため、他購入／個人情報を
					取得し、メール送信 → 完了画面へ転送（表示用別ファイル）して終了。

			２．支払方法：銀行振込
				ユーザーと管理者に購入明細のメールを送信 → 完了画面を表示して終了

			３．支払方法：代引き
				ユーザーと管理者に購入明細のメールを送信 → 完了画面を表示して終了

※送料・代引き手数料について
	設定ファイル(INI_config.php)で各必要値を設定
	LCG_setAmount.phpで各値を算出する。
	【詳細はINI_config.phpにて解説】

*******************************************************************************/
session_start();

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// エラーメッセージの初期化
$error_message = "";

// 不正アクセストラップ（リファラーチェック）※結果がfalseの場合は不正アクセス
//$refChk_Fnc = create_function('','return stristr($_SERVER["HTTP_REFERER"],$_SERVER["HTTP_HOST"]);');

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/INI_config.php");		// 設定ファイル
require_once("../common/INI_ShopConfig.php");	// ショップ用設定ファイル
require_once('../common/INI_pref_list.php');	// 都道府県＆送料情報（配列）
require_once("dbOpe.php");										// ＤＢ操作クラスライブラリ
require_once("util_lib.php");									// 汎用処理クラスライブラリ
require_once('tmpl2.class.php');							// PHPテンプレートクラスライブラリ
require_once('../common/LF_cart_calc2.php');	// カート操作関数（ローカルファンクション）

#============================================================================
# 処理コントロール：処理内容を分岐（判断材料：$_POST、$_GETの処理用フラグ）
# ※共通処理として、cartフラグ（$_GET['dispcart']）がある場合は、cartの処理を
#	行ってから商品リストを表示
#============================================================================
switch($_POST['status']):

case "completion":
/////////////////////////////////////////////////////////////////
// 最終処理：DBに入力情報を格納し、支払い内容により処理を分岐
//	※購入済みフラグがついていないのを確認してから下記の処理を行う
//
//		・支払方法：クレジット　			→　決済サイトへ誘導
//		・支払方法：郵便振込と代引き	→	メールを送信して終了

	// 順番どおりに処理を進めていなかった場合はエラーを表示（確認画面まで進んで、別ウィンドウからカートに商品を追加させた場合、商品の合計金額などが計算されない為の処置）
	//どこで更新されるかわからないので全ページ、移動時にshopping_step_flgのデータを更新させる。
	if ( $_SESSION['shopping_step_flg'] != "confirm" ){
		$error_type = "";
		$error_message = "エラー！！: 注文商品の決済完了前にデータの変更があった為、ご購入が失敗いたしました。<br>大変申し訳ございませんが、再度<a href=\"./\">【現在のカートの中身】ページ</a>から入力をお願いいたします。";
		include("DISP_error_disp.php");exit();
	}

	// セッションがない（有効期限切れ等）場合は環境変数をぶっこ抜いて強制終了。
	if ( !$_SESSION['setParam']['timeout_chk'] ){
		$error_type = "Completion : Session Time Out.";
		$error_message = "エラー！！: 有効期限が切れました。<br>大変申し訳ございませんが、もう一度やり直してください。";
		include("DISP_error_disp.php");exit();
	}

	#----------------------------------------------------------------------
	# 購入済みフラグがない場合のみ処理（ある場合はトップへ転送）
	# ※ブラウザのりロードによる二重申し込み防止
	#----------------------------------------------------------------------
	if ( !isset($_SESSION['setParam']['purchase_flg']) ):

		#------------------------------------------------------------------
		# クレジットの場合は、決済サイトへの誘導画面を表示
		# 銀行振込・代引きの場合は、この時点でメール送信 → 完了画面を表示（終了）
		#------------------------------------------------------------------
		switch($_SESSION['cust']['PAYMENT_METHOD']):
		////////////////////////////////////////////////////////////
		// クレジット決済の場合
		case 1:
				// ＤＢへデータを登録（失敗時：強制終了）
				include("LGC_registDB.php");

				// クレジット決済へのリンクページ表示
				include("DISP_completion.php");

				break;
		case 2:case 3:
		////////////////////////////////////////////////////////////
		// 銀行振り込み、代引きの場合

				// ＤＢへデータを登録（失敗時：強制終了）
				include("LGC_registDB.php");

				// メール送信処理
				include("LGC_sendmail.php");

				// 完了画面表示
				include("DISP_completion.php");

				break;
		default:

			// 環境変数をぶっこ抜いて強制終了
			$error_type = "Completion : Unlawful of SESSION DATA(PAYMENT_METHOD).";
			//@include("LGC_envlog.php");
			die("Unlawful Access!! : PMD");

		endswitch;

		#---------------------------------------------------------------
		# 最後にセッション情報を破棄し、購入済みフラグをつけて終了
		#---------------------------------------------------------------
		$_SESSION['cust'] = array();				// 入力情報(お客様情報)を破棄
		deleteItems();								// カートの中身を破棄
		$_SESSION['setParam']['purchase_flg'] = 1;	// 購入済みフラグをセット
		$_SESSION['setParam']['timeout_chk'] = "";	// タイムアウトチェックデータを破棄

	else:
		////////////////////////////////////////////////////////////
		// 購入フラグを既に保持していた場合

		// セッションを破棄し、デフォルトページへ
		$_SESSION['cust'] = array();	// 入力情報(お客様情報)を破棄
		deleteItems();					// カートの中身を破棄
		header("Location: ./");			// カートトップへのリダイレクト
		exit();							// カレントスクリプトの終了

	endif;

	break;
case "confirm":
////////////////////////////////////////////////////////////
// 入力チェックを行い、入力内容確認画面を表示

	// セッションがない（有効期限切れ等）場合は環境変数をぶっこ抜いて強制終了。
	if ( !$_SESSION['setParam']['timeout_chk'] ){
		$error_type = "Confirm : Session Time Out.";
		$error_message = "エラー！！: 有効期限が切れました。<br>大変申し訳ございませんが、もう一度やり直してください。";
		include("DISP_error_disp.php");	exit();
	}

	// メイン処理：送信データのチェック
	include("LGC_inputChk.php");
	if ( $error_message ){
		$_SESSION['shopping_step_flg'] = "step1";//step1に来たデータを入れる
		include("DISP_inputStep.php");	// エラーのため、前のページを表示
	}
	else{

		$_SESSION['shopping_step_flg'] = "confirm";//confirmに来たデータを入れる

		// 商品合計額計算 ＆ 送料・代引き手数料計算(結果は全てセッションに格納)
		include("LGC_setAmount.php");

		include("DISP_confirm.php");			// 入力内容の確認画面を表示
	}

	break;

case "step1":
//////////////////////////////////////////////////////////////////////////////
// メールチェックまたは認証行い、支払方法と個人情報の入力画面を表示

	// セッションがない（有効期限切れ等）場合は環境変数をぶっこ抜いて強制終了。
	if ( !$_SESSION['setParam']['timeout_chk'] ){
		$error_type = "Step1 : Session Time Out.";
		$error_message = "エラー！！: 有効期限が切れました。<br>大変申し訳ございませんが、もう一度やり直してください。";
		include("DISP_error_disp.php");	exit();
	}

	// メイン処理：送信データのチェック
	include("LGC_inputChk.php");
	#---------------------------------------------------------------
	# 使用経験なし：個人情報の入力
	# 使用経験あり：認証に失敗→エラー画面を表示
	# 使用経験あり：認証に成功→入力画面（支払方法と個人情報の入力）
	#	※エラーチェックに引っかかった場合はエラーページを表示
	#---------------------------------------------------------------
	if ( $error_message ):
		$_SESSION['shopping_step_flg'] = "default";//カートリストページ用
		include("DISP_cartList.php");			// 再入力（買い物カゴ表示＆認証ページ）
	else:
		$_SESSION['shopping_step_flg'] = "step1";//step1に来たデータを入れる
		unset($_SESSION['setParam']['purchase_flg']);//購入済みフラグを解除する(FireFoxのブラウザーはセッションを維持する為、ブラウザーが閉じても購入ができなくなるのを防ぐ為)
		include("DISP_inputStep.php");	// 個人情報入力画面を表示
	endif;

	break;
case "edit":
//////////////////////////////////////////////////////////////////////////////
// 修正モード

	// セッションがない（有効期限切れ等）場合は環境変数をぶっこ抜いて強制終了。
	if(!$_SESSION['setParam']['timeout_chk']){
		$error_type = "Edit : Session Time Out.";
		$error_message = "エラー！！: 有効期限が切れました。<br>大変申し訳ございませんが、もう一度やり直してください。";
		//@include("LGC_envlog.php");
		include("DISP_error_disp.php");	exit();
	}

	// メイン処理：$_POST['edit_type']（hiddenデータ）により読み込むページを分岐
	switch($_POST['edit_type']){
		case "to_step":
			$_SESSION['shopping_step_flg'] = "step1";//step1に来たデータを入れる
			include("DISP_inputStep.php");	// 個人情報入力画面を表示
			break;
		case "to_cartList":
			$_SESSION['shopping_step_flg'] = "default";//カートリストページ用
			include("DISP_cartList.php");		// カートの中身一覧＆認証ページ表示
			break;
		default:die("Unlawful Access!!");
	}

	break;
default:
///////////////////////////////////////////////////////////
// デフォルト表示（買い物カゴの中身一覧＆認証ページを表示）
//	※カートの中身の追加／削除はここで行う

	include("LGC_cartList.php");	// カート操作処理ロジック

	// 買い物カゴの中身をチェック
	if ( !getItems() )	$error_message .= "買い物カゴの中身が空です。<br>\n";

	include("DISP_cartList.php");	// 中身一覧＆認証ページを表示

	// カートトップでいったんフラグ用セッションデータ破棄
	//if($_SESSION['setParam'])$_SESSION['setParam'] = array();
	// セッションタイムアウトをチェックするため、アクセス時間をセッションに格納しておく
	$_SESSION['setParam']['timeout_chk'] = date("Y:m:d H:i:s");

endswitch;

?>
