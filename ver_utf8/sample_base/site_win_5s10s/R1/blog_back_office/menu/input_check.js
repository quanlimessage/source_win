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
	var error_mes = "エラーです！\n\n";

	if(!f.category_name.value){
		error_mes += "・新規登録するカテゴリー名を入力してください。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{

		// 確認メッセージ
		return ConfirmMsg('入力いただいた内容で登録します。\nよろしいですか？');
		return true;

	}

}

// 更新時
function inputChk2(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "エラーです！\n\n";

	if(!f.category_code.value){
		error_mes += "・更新するカテゴリー名を入力してください。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
}

// 削除時
function DelConfim(f){
		// 確認メッセージ
		return confirm('このジャンルを削除します。\nよろしいですが？');
}