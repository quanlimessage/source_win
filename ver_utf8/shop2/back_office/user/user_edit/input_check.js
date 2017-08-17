// JavaScript Document

/*********************************************************
 汎用確認メッセージ
*********************************************************/
function ConfirmMsg(msg){
	var result;
	result = (confirm(msg))?true:false;
	return result;
}

/*********************************************************
 入力チェック
*********************************************************/
function inputChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\n恐れ入りますが、下記の内容を確認してください。\n\n";

	if(!f.alpwd.value){
		error_mes += "・パスワードが空欄です。\n\n";flg = true;
	}

	if(f.last_name.selectedIndex == 0){
		error_mes += "・名字(漢字)が空欄です。\n\n";flg = true;
	}

	if(f.first_name.selectedIndex == 0){
		error_mes += "・名前(漢字)が空欄です。\n\n";flg = true;
	}

	if(!f.first_kana.value){
		error_mes += "・名字(かな)が空欄です。\n\n";flg = true;
	}

	if(!f.last_kana.value){
		error_mes += "・名前(かな)が空欄です。\n\n";flg = true;
	}

	if(!f.email.value){
		error_mes += "・メールアドレスを入力してください。\r\n";flg = true;
	}
	else if(!f.email.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・メールアドレスの形式に誤りがあります。\r\n";flg = true;
	}

	if(!f.zip1.value){
		error_mes += "・郵便番号（左）が空欄です。\n\n";flg = true;
	}
	else if(isNaN(f.zip1.value)){
		error_mes += "・郵便番号（左）は半角数字のみです。\n\n";flg = true;
	}

	if(!f.zip2.value){
		error_mes += "・郵便番号（右）が空欄です。\n\n";flg = true;
	}
	else if(isNaN(f.zip2.value)){
		error_mes += "・郵便番号（右）は半角数字のみです。\n\n";flg = true;
	}

	if(!f.address1.value){
		error_mes += "・ご住所(市町村以下)が空欄です。\n\n";flg = true;
	}

	if(f.tel1.value == ""){
		error_mes += "・お電話番号（左）が空欄です。\n\n";flg = true;
	}
	else if(isNaN(f.tel1.value)){
		error_mes += "・お電話番号（左）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.tel2.value == ""){
		error_mes += "・お電話番号（中央）が空欄です。。\n\n";flg = true;
	}
	else if(isNaN(f.tel2.value)){
		error_mes += "・お電話番号（中央）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.tel3.value == ""){
		error_mes += "・お電話番号（右）が空欄です。\n\n";flg = true;
	}
	else if(isNaN(f.tel3.value)){
		error_mes += "・お電話番号（右）は半角数字のみご使用ください。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{

		// 確認メッセージ
		return ConfirmMsg('入力いただいた内容で更新します。\nよろしいですか？');
		return true;

	}

}
