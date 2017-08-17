// JavaScript Document

/////////////////////////////////////
// 汎用確認メッセージ
/////////////////////////////////////
function ConfirmMsg(msg){
	return (confirm(msg))?true:false;
}

/////////////////////////////////////]
//二重送信防止
sent_flg = 0;
function sendChk(){

	if(!sent_flg){
		sent_flg = 1;
		return true;
	}
	else{
		return false;
	}

}

/////////////////////////////////////////////////////////////////////////////////
// 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
/////////////////////////////////////////////////////////////////////////////////
function inputChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\r\n恐れ入りますが、下記の内容を確認してください。\r\n\r\n";

	// 未入力と不正入力のチェック

	if(!f.title.value){
		error_mes += "・タイトルを入力してください。\r\n";flg = true;
	}

	if(f.e_mail.value){
		if(!f.e_mail.value.match(/^[^@]+@[^.]+\..+/)){
				error_mes += "・メールアドレスの形式に誤りがあります。\r\n";flg = true;
		}
	}

	if(!f.content.value){
		error_mes += "・コメントを入力してください。\r\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{
		return true;
	}

}

// キーボードが押された時にhiddenに値を入れる
function keyDownFun(){

	document.commentForm.randdata.value="78dbh4a2nd7";

}
