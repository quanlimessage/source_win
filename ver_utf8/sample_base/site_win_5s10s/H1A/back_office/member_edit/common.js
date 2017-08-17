// JavaScript Document

/////////////////////////////////////
// 汎用確認メッセージ
/////////////////////////////////////
function ConfirmMsg(msg){
	return (confirm(msg))?true:false;
}

/////////////////////////////////////////////////////////////////////////////////
// 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
/////////////////////////////////////////////////////////////////////////////////
function inputChk(f,confirm_flg){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\r\n恐れ入りますが、下記の内容を確認してください。\r\n\r\n";

	// 未入力と不正入力のチェック

	if(!f.name.value){
		error_mes += "・名前を入力してください。\r\n";flg = true;
	}

	if(!f.kana.value){
		error_mes += "・フリガナを入力してください。\r\n";flg = true;
	}

	if((f.zip1.value && f.zip1.value.match(/[^-0-9]/)) || (f.zip2.value && f.zip2.value.match(/[^-0-9]/))){
		error_mes += "・郵便番号は半角数字のみで入力してください。\r\n";flg = true;
	}

	if(f.tel.value && f.tel.value.match(/[^-0-9]/)){
		error_mes += "・電話番号は半角数字とハイフンのみで入力してください。\r\n";flg = true;
	}
	if(f.fax.value && f.fax.value.match(/[^-0-9]/)){
		error_mes += "・FAX番号は半角数字とハイフンのみで入力してください。\r\n";flg = true;
	}

	if(!f.email.value){
		error_mes += "・E-Mailを入力してください。\r\n";flg = true;
	}
	else if(!f.email.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・E-Mailに誤りがあります。\r\n";flg = true;
	}

	// 判定
	if(flg){
		// アラート表示して再入力を警告
		window.alert(error_mes);return false;
	}
	else{

			return ConfirmMsg('入力いただいた内容で登録します。\nよろしいですか？');
	}

}
