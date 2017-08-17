// JavaScript Document

/*********************************************************
 入力チェック
*********************************************************/
//------------------------------------------------------------
// メッセージダイアログ表示
//------------------------------------------------------------
function confirm_message(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\r\n恐れ入りますが、下記の内容を確認してください。\r\n\r\n";

	// 未入力と不正入力のチェック
	/*
	if(!f.title.value){
		error_mes += "・タイトルを入力してください。\r\n";flg = true;
	}

	if(!f.sex.value){
		if(!f.sex[0].checked && !f.sex[1].checked){
			error_mes += "・性別を選択してください。\r\n";flg = true;
		}
	}

	if(f.tel.value && f.tel.value.match(/[^-0-9]/)){
		error_mes += "・電話番号は半角数字とハイフンのみで入力してください。\r\n";flg = true;
	}

	if(!f.email.value){
		error_mes += "・メールアドレスを入力してください。\r\n";flg = true;
	}
	else if(!f.email.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・メールアドレスの形式に誤りがあります。\r\n";flg = true;
	}
	*/

	// 判定
	if(flg){
		// アラート表示して再入力を警告
		window.alert(error_mes);return false;
	}else{
		return confirm("この内容で登録します。\nよろしいでしょうか？");
	}

}
