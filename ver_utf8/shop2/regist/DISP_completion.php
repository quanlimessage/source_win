<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム

View：完了画面を表示（このプログラムの終了）
Status：completion

	※クレジット決済の場合
		決済サイトへ誘導するボタンを利用し、決済上で必要なデータをhiddenに仕込む

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");	exit();
}

// テンプレートクラスライブラリ読込みと郵便振込／代引き用HTMLテンプレートをセット
if(!file_exists("TMPL_completion.html"))die("Template File Is Not Found!!");
$tmpl = new Tmpl2("TMPL_completion.html");

#-------------------------------------------------------------
# HTTPヘッダーを出力
#-------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);

switch($_SESSION['cust']['PAYMENT_METHOD']):
////////////////////////////////////////////////
// クレジット決済時
case 1:

	$tmpl->assign_def("credit");

	// TITLE
	$tmpl->assign("title",SHOPPING_TITLE);

	// HEADのTITLE
	$tmpl->assign("shopping_title", SHOPPING_TITLE);

	// ゼウス：hidden用のHTML出力設定
	$tmpl->assign("aid", AID);																	// 店舗番号
	$tmpl->assign("cod", $order_id);															// 商品ID
	$tmpl->assign("jb_type", JB_TYPE);															// 決済の種類を指定。AUTH（仮売上）物販  ／ CAPTURE（仮／実同時）
	$tmpl->assign("am", $total_price);															// 支払総額（送料／手数料すべて込みの金額）
	$tmpl->assign("em", ($_SESSION['cust']['EMAIL'])?$_SESSION['cust']['EMAIL']:"");			// メールアドレス
	$tel = $_SESSION['cust']['TEL1'] . $_SESSION['cust']['TEL2'] . $_SESSION['cust']['TEL3'];	// 電話番号
	$tmpl->assign("pn", ($tel)?$tel:"");

	// J-Payment：hidden用のHTML出力設定
	/*
	$tmpl->assign("aid", AID); 						// 店舗番号
	$tmpl->assign("cod", $order_id);				// 商品ID
	$tmpl->assign("jb_type", JB_TYPE);				// 決済の種類を指定。
	$tmpl->assign("am", $total_price);				// 支払総額（送料／手数料すべて込みの金額）
	$tmpl->assign("em", ($_SESSION['cust']['EMAIL'])?$_SESSION['cust']['EMAIL']:"");			// メールアドレス
	$tel = $_SESSION['cust']['TEL1'] . $_SESSION['cust']['TEL2'] . $_SESSION['cust']['TEL3'];	// 電話番号
	$tmpl->assign("pn", ($tel)?$tel:"");
	*/

	// デジタルチェック：hidden用のHTML出力設定
	/*
	$tmpl->assign("clientip",AID); 			// 店舗番号
	$tmpl->assign("sendid", $order_id); 	// 購入ID
	$tmpl->assign("product","ZEEKSHOPPING"); 	// 購入商品名
	$tmpl->assign("money",$total_price); 	// 購入金額
	*/

	break;

///////////////////////////////////////////////
// それ以外の支払い時
case 2:case 3:

	// TITLE
	$tmpl->assign("title",SHOPPING_TITLE);

	// HEADのTITLE
	$tmpl->assign("shopping_title", SHOPPING_TITLE);

	break;
default:
	die("予想外のエラーによりこのプログラムを強制終了しました！");
endswitch;

#------------------------------------------------------------
# 設定した内容を一括出力
#------------------------------------------------------------
$tmpl->flush();
?>
