<?php
/*******************************************************************************
ID PASSWORDでの会員用ページの表示（Ｍ１）

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config.php");	// 設定ファイル
require_once("dbOpe.php");			// ＤＢ操作クラスライブラリ
require_once("util_lib.php");			// 汎用処理クラスライブラリ

// POSTデータの受け取りと共通な文字列処理
if($_GET){extract(utilLib::getRequestParams("get",array(8,7,1,4),true));}
if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4),true));}

#---------------------------------------------------------------
# ログインのＩＤ，ＰＷ入力結果
# エラーが発生した場合の処理
#---------------------------------------------------------------
	//getでエラーが送られていたらエラー内容を表示させる
	$login_err = "";//エラー内容初期化

	switch($err):
		case 1://該当するＩＤ、ＰＷで無い場合
			$login_err = "入力されましたIDまたはPASSWORDが間違っております。<br>確認してください。";
			break;

		case 2:
			$login_err = "IDまたはPASSWORDが未記入です。";
			break;
	endswitch;

$clogin_flg = "";//ログインのフラグを初期化

///////////////////////////////////////////////////////////////////////////////////////////
//エラーが発生していない場合はログインの表示処理関連を行う
if(!$login_err){

	#=================================================================================
	# 会員ログインの設定
	#=================================================================================
	//クッキーから情報を取得する

	if(isset($_COOKIE["cookie_login_data"])){//クッキーのデータが存在している場合

		list($c_login_id,$c_login_pw) = explode(",", $_COOKIE["cookie_login_data"]);//メンバーＩＤ、パスワードのデータを受け渡す

		//念の為、データが正常か調べる（パスワードは暗号化しているためＩＤのみで抽出、同じＩＤが重複しないのが前提条件）
		$c_login_id_sql = utilLib::strRep($c_login_id,5);//ＳＱＬに使用できるようにも辞書リを行う
		$sql_ck="SELECT ID,PW FROM M1_PRODUCT_LST WHERE (ID = '".$c_login_id_sql."') AND (DEL_FLG = '0')";

		// ＳＱＬを実行
		$fetchIdPwCK = dbOpe::fetch($sql_ck,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//もし、パスワードがが一致しない場合はクッキーを初期化させる
		if($c_login_pw == crypt($fetchIdPwCK[0]['PW'],$c_login_id)){
			$clogin_flg = "1";//ログインしている状態

		}else{//ログインが不正な場合,クッキーの情報を初期化させる

			setcookie ("cookie_login_data", "",mktime(0,0,0,12,31,(date("Y") + 3)), '/', '.'.$_SERVER['SERVER_NAME']);//一旦データを空にする（一行下の行だけだとデータ削除がうまく行かないため）
			setcookie ("cookie_login_data");//こちらの処理でブラウザーが閉じるまでを有効期限に設定をする。

			$c_login_id = "";
			$c_login_pw = "";
			$login_flg = "";//ログインしていない状態
		}
	}

}

?>