// JavaScript Document

/*********************************************************
 入力チェック
*********************************************************/

/*********************************************************
 汎用確認メッセージ
*********************************************************/
function ConfirmMsg(msg){
	var result;
	result = (confirm(msg))?true:false;
	return result;
}

//------------------------------------------------------------
// メッセージダイアログ表示
//------------------------------------------------------------
function inputChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "エラーです！\n\n";

	if(!f.category_code.value){
		error_mes += "・カテゴリーを選択してください。\n\n";flg = true;
	}

	if(!f.title.value){
		error_mes += "・タイトルを入力してください。\n\n";flg = true;
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
