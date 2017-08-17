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

$email = mb_convert_kana($email,"a");//スタイルのime-modeはIEのみ有効ですので、FireFoxで全角で入力される可能性があるため処理をします。

// フリガナは全角カタカナに統一"C"で全角カタカナ"c"で全角ひらがな
$kana = mb_convert_kana($kana,"C");

//メールアドレスの空白を除去する
$email = preg_replace("/[[:space:]]/","",$email);//半角の空白を削除
$email = mb_ereg_replace("(　)","",$email);//全角の空白を削除

//////////////////////////////////////////////////////////////////////////////////
//必須で必ず日本語を入力する場所に対しての処理
//日本語以外の英数字記号を弾く
function jap_inpck($ckdata){
		//余分なスペースは削除
			$target = str_replace(" ","",$ckdata);
			$target = str_replace("　","",$target);

		//半角英数字の場合はエラー
			if(preg_match("/[!-~]/", $target)){//半角英数字記号が入っている場合
				$err_flg = 1;
			}else{//入っていない場合
				$err_flg = 0;
			}

	return $err_flg;
}
#----------------------------------------------------------------------------------
# エラーチェック	※strCheck(対象文字列,モード,偉ーメッセージ);を使用。
#	0:	未入力チェック
#	1:	空白文字チェック
#	4:	最後の文字に不正な文字が使われているか
#	5:	不正かつ危険な文字が使われているか
#	6:	メールアドレスチェック（E-Mailのみ）
#----------------------------------------------------------------------------------
$error_mes .= utilLib::strCheck($name,0,"お名前を入力してください。<br>\n");
$error_mes .= utilLib::strCheck($kana,0,"フリガナを入力してください。<br>\n");
$error_mes .= utilLib::strCheck($sex,0,"性別を選択してください。<br>\n");
$error_mes .= utilLib::strCheck($zip1,0,"郵便番号（左）を入力してください。<br>\n");
$error_mes .= utilLib::strCheck($zip2,0,"郵便番号（右）を入力してください。<br>\n");

$error_mes .= utilLib::strCheck($state,0,"都道府県を選択してください。<br>\n");
$error_mes .= utilLib::strCheck($address,0,"ご住所を入力してください。<br>\n");
$error_mes .= utilLib::strCheck($email,0,"メールアドレスを入力してください。<br>\n");
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

	if($mailchk)$error_mes .= "メールアドレスの形式に誤りがあります。<br><br>\n";
}
$error_mes .= utilLib::strCheck($comment,0,"コメントを入力してください。<br>\n");

	//スパム対策
		//半角英数字の入力禁止処理（スパムは半角英数字のみで入力しているので入力の一部を半角英数字では通さないように処理する）
		if($name){//データがあるか判定
			if(jap_inpck($name)){//半角英数字のチェック
				$error_mes .= "お名前は漢字またはひらがなのみで入力してください。<br><br>\n";
			}

		}

	//メールの内容に【[/url] [/link] 】が入っていたらスパムと判定する
	$spam_flg = "";
	foreach($_POST as $key => $value){

		if(is_array($value)){//データが配列の場合は結合処理をする(substr_countを配列で渡すとエラーが発生するためphp5.3以降)
			 $tmp_value = implode("",$value);//配列を文字列として結合させる。
			 unset($value);//配列でないように一旦削除する
			 $value = $tmp_value;//データを渡す。
			 unset($tmp_value);//不要なデータを削除しておく
		}

		//該当の文字があるかチェックを行う
			if(substr_count($value,"[/url]")){
				$spam_flg = "1";//該当した場合
			}

			if(substr_count($value,"[/link]")){
				$spam_flg = "1";//該当した場合
			}

	}

	//エラーメッセージ
	if($spam_flg){$error_mes .= "この入力欄では【[/url]】【[/link]】が使用できません。<br><br>\n";}

?>
