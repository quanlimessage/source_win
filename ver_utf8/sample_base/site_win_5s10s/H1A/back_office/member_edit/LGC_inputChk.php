<?php
/************************************************************************
 お問い合わせフォーム（POST渡しバージョン）
 処理ロジック：エラーチェック
	※POST送信されたデータに対して不備が無いかチェックする

************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// 全角英数字→半角英数字に変換
$zip1 = mb_convert_kana($zip1,"a");
$zip2 = mb_convert_kana($zip2,"a");
$tel = mb_convert_kana($tel,"a");
$fax = mb_convert_kana($fax,"a");
$email = mb_convert_kana($email,"a");

// フリガナは全角カタカナに統一
$kana = mb_convert_kana($kana,"C");

#----------------------------------------------------------------------------------
# エラーチェック	※strCheck(対象文字列,モード,エラーメッセージ);を使用。
#	0:	未入力チェック
#	1:	空白文字チェック
#	4:	最後の文字に不正な文字が使われているか
#	5:	不正かつ危険な文字が使われているか
#	6:	メールアドレスチェック（E-Mailのみ）
#----------------------------------------------------------------------------------
$error_mes .= utilLib::strCheck($name,0,"名前を入力してください。<br>\n");
$error_mes .= utilLib::strCheck($kana,0,"フリガナを入力してください。<br>\n");

$zip = $zip1 . $zip2;
if(!empty($zip)){
	if(ereg("[^0-9]",$zip)){
		$error_mes .= "郵便番号は半角数字のみで入力してください。<br><br>\n";
	}
}

if(!empty($tel)){
	if(ereg("[^-0-9]",$tel)){
		$error_mes .= "TELは半角数字とハイフンのみで入力してください。<br><br>\n";
	}
}

if(!empty($fax)){
	if(ereg("[^-0-9]",$fax)){
		$error_mes .= "FAXは半角数字とハイフンのみで入力してください。<br><br>\n";
	}
}

$error_mes .= utilLib::strCheck($email,0,"E-Mailを入力してください。<br>\n");
if($email){
	$mailchk = "";
	$mailchk .= utilLib::strCheck($email,1,true);
	$mailchk .= utilLib::strCheck($email,4,true);
	$mailchk .= utilLib::strCheck($email,5,true);
	$mailchk .= utilLib::strCheck($email,6,true);

	//メールアドレスに全角文字と半角カタカナの入力は拒否させる
	if((mb_strlen($email, 'UTF-8') != strlen($email)) || mb_ereg("[ｱ-ﾝ]", $email)){
		$mailchk .= "メールアドレスに不正な文字が含まれております。";
	}

	if($mailchk)$error_mes .= "E-Mailの形式に誤りがあります<br>\n";
}

//メールアドレスに重複があればエラーを発生させる
//ただし、更新の場合はこの処理をしない
if($regist_type != "update"){
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".MEMBER_LST." WHERE(EMAIL = '$email') AND (DEL_FLG = '0')";
	$fetch = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if($fetch[0]['CNT'] > 0){
	$error_mes .= "入力されましたメールアドレスは既に登録済みです。<br>\n";
	}
}

?>
