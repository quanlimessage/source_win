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

	if(!f.name.value){
		error_mes += "・会社名を入力してください。\n\n";flg = true;
	}

	if(!f.email.value){
		error_mes += "・ショップ用メールを入力してください。\n\n";flg = true;
	}
	else if(!f.email.value.match(/^[^@]+@[^.]+\..+/)){
	//else if(!email.value.match(/^(.+)@(.+)\\.(.+)$/)){
		error_mes += "・ショップ用メールアドレスの形式に誤りがあります。\n\n";flg = true;
	}

	if(!f.company_info.value){
		error_mes += "・メール表示用会社情報を入力してください。\n\n";flg = true;
	}

	/*
	if(!f.shopping_title.value){
		error_mes += "・ショッピングページタイトルを入力してください。\n\n";flg = true;
	}

	if(!f.bo_title.value){
		error_mes += "・管理画面タイトルを入力してください。\n\n";flg = true;
	}
	*/

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
