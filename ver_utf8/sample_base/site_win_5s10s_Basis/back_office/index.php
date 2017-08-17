<?php
/*******************************************************************************
SiteWin10 20 30用バックオフィス
トップページ画面

*******************************************************************************/
session_start();
// 設定＆ライブラリファイル読み込み
require_once("../common/config.php");
require_once('util_lib.php');
require_once('authOpe.php');

#---------------------------------------------------------------------
# 認証ライブラリを使ってsession認証を行う

#---------------------------------------------------------------------
	function login($login_id,$login_pass){
		global $PDO;
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
				".APP_ID_PASS."
			";

	// ＳＱＬを実行
	$fetch = $PDO -> fetch($sql);

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

			if($_SESSION['LOGIN'] !== true && !$err_msg) $err_msg .= "IDまたはパスワードが間違っています。";

		}else{
			$err_msg .= "IDまたはパスワードが間違っています。";
		}
		return ($err_msg) ? $err_msg : true;
	}

	function logout(){
		$_SESSION['LOGIN'] = false;
	}

// 不正アクセスチェックのフラグ
$accessChk = 1;

if($_POST) extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

if($login || $_GET['login']) $result = login($login_id ,$login_pass);

if($logout || $_GET['logout'] == 1){logout();}

//////////////////////////////////////////////////////////////////////
// DBより全管理情報を取得し、認証ライブラリを使ってBasic認証を行う
/*
$sql = "SELECT BO_ID AS user , BO_PW AS password FROM APP_ID_PASS";
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
$result = authOpe::basicCheck2($fetch,"for Webmaster");
*/
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
				<frame src="login.php<?php echo ($result !== true) ? "?err=".urlencode($result) : "";?>" name="main" frameborder="no" title="main">
		<?php } ?>
</frameset>
<noframes><body>
</body></noframes>
</html>
