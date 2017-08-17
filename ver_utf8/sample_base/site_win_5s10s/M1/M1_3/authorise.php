<?php
require_once("../common/config.php");
require_once("dbOpe.php");
require_once("util_lib.php");

if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4),true));}
$err_mes = "";//エラーメッセージの格納場所を初期化

if($login_id && $login_pw){//ＩＤとパスワードが入力されていない場合は認証処理をせずにエラーを表示

	// MySQLにおいて危険文字をエスケープしておく
	$login_id = utilLib::strRep($login_id,5);
	$login_pw = utilLib::strRep($login_pw,5);

	//該当するＩＤ，ＰＷがあるか調べる
	$sql = "
	SELECT
		ID,
		PW
	FROM
		M1_PRODUCT_LST
	WHERE
		(ID = '".$login_id."') AND
		(PW = '".$login_pw."') AND
		(DEL_FLG = '0')
	";

	$fetchPW = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if(count($fetchPW)==0){//該当するＩＤ，ＰＷが無い場合

		//エラーページへ
		setcookie ("cookie_login_data");//念の為クッキーを初期化する
		header("Location: ./?err=1");//getデータを付けて移動させる

	}else{//該当するＩＤ，ＰＷがある場合

		//クッキーの設定
		$sname = $_SERVER['SERVER_NAME'];//ドメインを受け渡す
		$cookvalue = $fetchPW[0]['ID'].",".crypt($fetchPW[0]['PW'],$fetchPW[0]['ID']);//メンバーＩＤ，パスワードを設定（cryptを使用して暗号化、キャッシュのクッキーからパスワードを簡単にわからないようにする）

		setcookie("cookie_login_data", $cookvalue,'0', '', '.'.$sname,0);//クッキーを作成（TOP階層にこのファイルがある場合はこのまま）、（有効期限はブラウザーが閉じるまで）
		//setcookie("cookie_login_data", $cookvalue,mktime(0,0,0,12,31,(date("Y") + 3)), '', '.'.$sname,0);//クッキーを作成（TOP階層にこのファイルがある場合はこのまま）、（有効期限を指定している場合）

		//新着情報のページに移動させる
		header("Location: ./");
	}

}else{//メンバーＩＤまたはパスワードが未入力の場合

	//エラーページへ
	setcookie ("cookie_login_data");//念の為クッキーを初期化する
	header("Location: ./?err=2");//getデータを付けて移動させる
}

?>