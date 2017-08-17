// JavaScript Document

/////////////////////////////////////
// 汎用確認メッセージ
/////////////////////////////////////
function ConfirmMsg(msg){
	return (confirm(msg))?true:false;
}

/////////////////////////////////////////////////////////////////////////////////
// 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
/////////////////////////////////////////////////////////////////////////////////
function inputChk(f,confirm_flg){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\r\n恐れ入りますが、下記の内容を確認してください。\r\n\r\n";

	// 未入力と不正入力のチェック
	if(!f.SUBJECT.value){
		error_mes += "・件名を入力してください。\r\n";flg = true;
	}

	if(!f.COMMENT.value){
		error_mes += "・内容を入力してください。\r\n";flg = true;
	}

	// 判定
	if(flg){
		// アラート表示して再入力を警告
		window.alert(error_mes);return false;
	}
	else{

		// 確認メッセージ
		if(f.action.value=="send_data"){
			return ConfirmMsg('入力いただいた内容で送信します。\nよろしいですか？');
		}
		return true;
	}

}
