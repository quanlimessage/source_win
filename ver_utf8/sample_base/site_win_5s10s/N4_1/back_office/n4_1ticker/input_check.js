// JavaScript Document

/*********************************************************
 入力チェック
*********************************************************/
function inputChk1(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\n恐れ入りますが、下記の内容を確認してください。\n\n";

	if(!f.comment.value){
		error_mes += "・コメントを入力してください。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{

		// 確認メッセージ
		return confirm('入力いただいた内容で登録します。\nよろしいですか？');
		return true;

	}

}
