// JavaScript Document
/////////////////////////////////////////////////////////////////////////////////
// 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
/////////////////////////////////////////////////////////////////////////////////
function login_check(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\r\n恐れ入りますが、下記の内容を確認してください。\r\n\r\n";

	if(!f.login_id.value){
		error_mes += "・IDを入力してください。\r\n";flg = true;
	}
	/*
	else if(!f.login_id.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・IDの形式に誤りがあります。\r\n";flg = true;
	}
	*/

	if(!f.login_pw.value){
		error_mes += "・パスワードを入力してください。\r\n";flg = true;
	}

	if(flg){
		// アラート表示して再入力を警告
		window.alert(error_mes);return false;
	}else{
		return true;
	}
}