<?php
/*******************************************************************************
ShopWinLite コンテンツ：ショッピングカートプログラム内

メールアドレス変更プログラム
View：確認画面を表示

	※入力した内容を比較させる（新しいメアドと古いメアド）
	※ＯＫなら完了処理。statusは4へ　／　修正ならstatusは3へ

2005/10/13 KC
*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

#------------------------------------------------------------------------
# HTTPヘッダーを出力
# 文字コード／JSとCSSの設定／無効な有効期限／キャッシュ拒否／ロボット拒否
#------------------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);

// テンプレートクラスライブラリ読込みと出力用HTMLテンプレートをセット
if ( !file_exists("TMPL_confirm.html") )	die("Template File Is Not Found!!");
$tmpl = new Tmpl2("TMPL_confirm.html");

#=================================================================================
# テンプレートを使用してHTML出力の設定
#=================================================================================

// HEADのTITLE
$tmpl->assign("shopping_title", SHOPPING_TITLE);

// 古いメールアドレス
$tmpl->assign("oldmail", $_SESSION['getParam']['oldmail']);

// 新しいメールアドレス
$tmpl->assign("newmail", $_SESSION['getParam']['newmail']);

#------------------------------------------------------------
# 設定した内容を一括出力
#------------------------------------------------------------
$tmpl->flush();

?>