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
	var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	if(!f.email2.value){error_mes += "・メールアドレスを入力してください。\n\n";flg = true;}
	else if(!f.email2.value.match(/^[^@]+@[^.]+\..+/)){error_mes += "・メールアドレスの形式に誤りがあります。\n\n";flg = true;}

/*
	if(!f.email2.value){error_mes += "・メールアドレス２を入力してください。\n\n";flg = true;}
	else if(!f.email2.value.match(/^[^@]+@[^.]+\..+/)){error_mes += "・メールアドレス２の形式に誤りがあります。\n\n";flg = true;}
*/
	if(!f.content.value){
		error_mes += "・自動返信メール表示用内容を入力してください。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
}

function regChk(f){
		// 確認メッセージ
		return ConfirmMsg('入力いただいた内容で登録します。\nよろしいですか？');
		return true;
}
