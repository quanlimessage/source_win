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

	// メールアドレスのチェック
	if(!f.chkmail.value){
		error_mes += "・メールアドレスが未入力です。\n\n";flg = true;
	}
	else if(!f.chkmail.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・メールアドレスの形式に誤りがあります。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{
		return true;
	}

}

function pwdChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	// パスワードのチェック
	if(!f.npwd.value){
		error_mes += "・新しいパスワード（上）が未入力です。\n\n";flg = true;
	}
	if(!f.npwd2.value){
		error_mes += "・新しいパスワード（下）が未入力です。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{
		return true;
	}

}
