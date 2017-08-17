<?php
/*******************************************************************************
tsugueda

メインコントローラー

	※最初にログイン画面を表示して認証を行う
	※認証データはセッションに入れて常に持ちまわす（これがないとエラーにする）
	※認証成功の場合はトップメニューを表示
		一覧：
			１．商品の更新
			---------------
			１．ユーザー検索
			２．売り上げ管理

	※それぞれのメニューは独立プログラムとして動作（別途コントローラーを持たせて制御）

*******************************************************************************/
session_start();
require_once("../common/INI_config.php");		  // 設定ファイル
require_once("../common/INI_ShopConfig.php");	// ショップ用設定ファイル
require_once("../common/LGC_confDB.php");	    // 認証用ファイル
require_once("util_lib.php");
require_once('authOpe.php');
#---------------------------------------------------------------------
# サイト公開後のお客様のdemoページアクセスチェック

#---------------------------------------------------------------------\
if($siteopne_flg)location($domain);

#---------------------------------------------------------------------
# 認証ライブラリを使ってsession認証を行う

#---------------------------------------------------------------------
	function login($login_id,$login_pass){

		if($login_id && $login_pass){
		// SQLite操作ライブラリのインスタンスを作成しておく（インスタンスは1個のみ作成可）
		//	$dbh = new sqliteOpe(ID_PW_FILEPATH,CREATE_ID_PW_SQL);

	$err_msg = "";

	//使用するID PASSWORDの設定はこちらのＳＱＬ文を変更
	$sql = "
			SELECT
				BO_ID AS user,
				BO_PW AS password
			FROM
				".CONFIG_MST."
			";

	// ＳＱＬを実行
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//	$fetch = $dbh->fetch($sql);
			if(count($fetch) == 0){
				$err_msg .= "ログインデータが存在しません。";
			}else{
				for($i = 0;$i < count($fetch); $i++){
					if($fetch[$i]['user'] == $login_id && $fetch[$i]['password'] == $login_pass){

						# IPチェック
						$iplst = getPermitIPList($login_id);
						$ip_chk = false;
						//var_dump($iplst);die();
						# IP制限をかけていない場合
						if($iplst[0] != "free" && $iplst[0] != ""){
							for($j = 0;$j < count($iplst);$j++){
								if($iplst[$j] == $_SERVER['REMOTE_ADDR']) {
									$ip_chk = true;
									break;
								}
							}
						}else{
							$ip_chk = true;
						}

						if($ip_chk){
							$_SESSION['LOGIN'] = true;
						}else{
							$err_msg = "このIPでのログインは許可されていません。";
						}
						break;
					}
				}
			}

			if($_SESSION['LOGIN'] !== true) $err_msg .= "IDまたはパスワードが間違っています。";

		}else{
			$err_msg .= "IDまたはパスワードが間違っています。";
		}
		return ($err_msg) ? $err_msg : true;
	}

	function logout(){
		$_SESSION['LOGIN'] = false;
	}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

if($_POST) extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

if($login || $_GET['login']) $result = login($login_id ,$login_pass);

if($logout || $_GET['logout'] == 1){logout();}
#---------------------------------------------------------------------
# 認証ライブラリを使ってBasic認証を行う

#---------------------------------------------------------------------
/*
// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 認証クラスファイル
require_once('authOpe.php');

$idpw = $fetch_ipas;

authOpe::basicCheck2($idpw,"for Members");
*/
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>管理画面</title>
</head>

<frameset rows="80,*" cols="*" frameborder="NO" border="0" framespacing="0">
		<frame src="head.php" name="head" frameborder="no" scrolling="NO" noresize title="head" >
		<?php if($_SESSION['LOGIN']){ ?>
		<frameset cols="220,*" frameborder="NO" border="0" framespacing="0">
				<frame src="menu.php" name="menu" frameborder="no" scrolling="auto" noresize marginwidth="0" marginheight="0" title="menu">
				<frame src="main.php" name="main" frameborder="no" title="main">
		</frameset>
		<?php }else{ ?>
				<frame src="login.php<?=($result !== true) ? "?err=".urlencode($result) : "";?>" name="main" frameborder="no" title="main">
		<?php } ?>
</frameset>
<noframes><body>
</body></noframes>
</html>
