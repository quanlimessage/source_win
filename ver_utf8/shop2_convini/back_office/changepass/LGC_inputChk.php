<?php
/*******************************************************************************
管理者ID/PASSの管理

	入力データのチェック

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}

#--------------------------------------------------------------------------------
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#--------------------------------------------------------------------------------
// 	タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4,5),true));

#=============================================================================
# $_POST['status']の内容により処理を分岐
#	※GETパラメーター（“cid”と“did”）があった場合の処理を最優先する事！
#=============================================================================
// エラーメッセージ格納用変数
$error_message = "";

switch ($_POST["status"]):
	case "completion":
	/////////////////////////////////////
	// 新ID/パスワードの文字列チェック

	#------------------------
	# 入力文字列変換
	#------------------------

	// 半角英数字に統一
	$new_id		=	mb_convert_kana($new_id,"a");
	$new_pw		=	mb_convert_kana($new_pw,"a");
	$new_pw2	=	mb_convert_kana($new_pw2,"a");

	// IDチェック
	if(empty($new_id)){
		$error_message.="新IDが未入力です。<br>\n";
	}elseif(!preg_match("/^[0-9A-Za-z]{4,8}$/i",$new_id)){
		$error_message.="IDは半角英数字のみで入力してください。<br>\n";
	}elseif( (strlen($new_id)<4) || (strlen($new_id)>8) ){
		$error_message.="IDは半角英数字4文字以上8文字以内です。<br>\n";
	}

	// パスワードチェック
	if(empty($new_pw)){
		$error_message.="新パスワードが未入力です。<br>\n";
	}elseif(!preg_match("/^[0-9A-Za-z]{4,8}$/i",$new_pw)){
		$error_message.="パスワードは半角英数字で4文字以上8文字以内に収めてください。<br>\n";
	}elseif( (strlen($new_pw)<4) || (strlen($new_pw)>8) ){
		$error_message.="パスワードは半角英数字4文字以上8文字以内です。<br>\n";
	}elseif($new_pw != $new_pw2){
		$error_message.="パスワードが確認用に入力したパスワードと一致しません。<br>\n";
	}
		break;
	case "update":
	/////////////////////////////////////
	// 現ID/現パスワードを認証

				// 共通ID/パスワード取得
				require_once('authOpe.php');

				// 現ID/現パスワード取得
				/*
				$conf_sql = "
				SELECT
					BO_ID,
					BO_PW
				FROM
					".CONFIG_MST."
				WHERE
					( CONFIG_ID = '1' )
				";

				$fetchConf = dbOpe::fetch($conf_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				*/

				$conf_sql = "
				SELECT
					BO_ID,
					BO_PW
				FROM
					".CONFIG_MST."

				";

				$fetchConf = dbOpe::fetch($conf_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			/*
			if(
			(($fetchConf[0]["BO_ID"] != crypt("id",$_POST["chk_id"])) || ($fetchConf[0]["BO_PW"] != crypt("pw",$_POST["chk_pass"])))
			&&
			(($zeek_id != $_POST["chk_id"]) || ($zeek_pass != $_POST["chk_pass"]))
			)
			*/
			for($i=0;$i<count($fetchConf);$i++){
					if(($fetchConf[$i]["BO_ID"] == $_POST["chk_id"]) && ($fetchConf[$i]["BO_PW"] == $_POST["chk_pass"])){$ipChk = 1;}
			}

			if(empty($ipChk))
			{
				$error_message .= "認証に失敗しました。";
			}
		break;
endswitch;
?>
