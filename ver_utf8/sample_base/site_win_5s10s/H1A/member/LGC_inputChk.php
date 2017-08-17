<?php
/************************************************************************
 お問い合わせフォーム（POST渡しバージョン）
 処理ロジック：エラーチェック
	※POST送信されたデータに対して不備が無いかチェックする

************************************************************************/

// 不正アクセスチェック
if(!$accessChk){
	header("HTTP/1.0 404 Not Found");exit();
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

$error_mes .= utilLib::strCheck($email,0,"E-Mailを入力してください。<br>\n");
if($email){
	$mailchk = "";
	$mailchk .= utilLib::strCheck($email,1,true);
	$mailchk .= utilLib::strCheck($email,4,true);
	$mailchk .= utilLib::strCheck($email,5,true);
	$mailchk .= utilLib::strCheck($email,6,true);

	//メールアドレスに全角文字と半角カタカナの入力は拒否させる
	mb_regex_encoding("UTF-8");//mb_ereg用にエンコードを指定
	if((mb_strlen($email, 'UTF-8') != strlen($email)) || mb_ereg("[ｱ-ﾝ]", $email)){
		$mailchk .= "メールアドレスに不正な文字が含まれております。";
	}

	if($mailchk)$error_mes .= "E-Mailの形式に誤りがあります<br>\n";
}

//メールアドレスに重複があればエラーを発生させる
$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".MEMBER_LST." WHERE(EMAIL = '$email') AND (DEL_FLG = '0')";
$fetch = $PDO -> fetch($cnt_sql);

if($fetch[0]['CNT'] > 0){
$error_mes .= "入力されましたメールアドレスは既に登録済みです。<br>\n";
}

?>
