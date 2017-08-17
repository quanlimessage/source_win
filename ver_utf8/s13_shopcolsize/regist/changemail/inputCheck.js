// JavaScript Document
/***************************************
 二重送信防止
***************************************/

// type1
completionFlg = false;
function easySubmitOnce(){

	if(completionFlg){
		alert("送信完了済みです。");
		return false;
	}
	else{
		completionFlg = true;
		return true;
	}

}

// type2
function submitOnce(f){ // arg:this

	for(i=0;i<f.elements.length;i++){
		if(f.elements[i].type == "submit")f.elements[i].disabled = true;
	}

}

/********************************************************************************
 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
********************************************************************************/

//-------------------------------------------------------------
// ※メールアドレスのチェック
//-------------------------------------------------------------
function emailChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	// 古いメールアドレスのチェック
	if(!f.oldmail.value){
		error_mes += "・古いメールアドレスが未入力です。\n\n";flg = true;
	}
	else if(!f.oldmail.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・古いメールアドレスの形式に誤りがあります。\n\n";flg = true;
	}

	// パスワードのチェック
	if(!f.pwd.value){
		error_mes += "・パスワードが未入力です。\n\n";flg = true;
	}

	// 新しいメールアドレスのチェック
	if(!f.newmail.value){
		error_mes += "・新しいメールアドレスが未入力です。\n\n";flg = true;
	}
	else if(!f.newmail.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・新しいメールアドレスの形式に誤りがあります。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{
		return true;
	}

}
