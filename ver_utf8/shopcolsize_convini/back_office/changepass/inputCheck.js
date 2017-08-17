// JavaScript Document
/*********************************************************
 汎用確認メッセージ
*********************************************************/
function ConfirmMsg(msg){
	var result;
	result = (confirm(msg))?true:false;
	return result;
}

/********************************************************************************
 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
********************************************************************************/

//-------------------------------------------------------------
// ※メールアドレスのチェック
//-------------------------------------------------------------
function inputChk1(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "";

	// 入力項目チェック
	if(!f.chk_id.value){
		error_mes += "・現在の管理IDを入力してください。\n\n";flg = true;
	}
	if(!f.chk_pass.value){
		error_mes += "・現在の管理パスワードを入力してください。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}else{
		return true;
	}
}

function inputChk2(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "";

	// 入力項目チェック
	if(!f.new_id.value){
		error_mes += "・新管理IDを入力してください。\n\n";flg = true;
	}
	if(!f.new_pw.value){
		error_mes += "・新管理パスワードを入力してください。\n\n";flg = true;
	}
	else if(f.new_pw.value != f.new_pw2.value){
		error_mes += "・新パスワードの指定が正しくありません。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}else{

		// 確認メッセージ
		return ConfirmMsg('入力いただいた内容で登録します。\nよろしいですか？');
		return true;

	}
}
