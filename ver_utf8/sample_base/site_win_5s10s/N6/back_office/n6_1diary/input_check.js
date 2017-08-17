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
	var error_mes = "Error Message\n恐れ入りますが、下記の内容を確認してください。\n\n";

	//if(!f.subject.value){
		//error_mes += "・サブジェクトを入力してください。\n\n";flg = true;
	//}

	if(!f.comment.value){
		error_mes += "・内容を入力してください。\n\n";flg = true;
	}

	if(!f.img_file.value){
		error_mes += "・画像を選択してください。\n\n";flg = true;
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

function confirm_message(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\n恐れ入りますが、下記の内容を確認してください。\n\n";

	//if(!f.subject.value){
	//	error_mes += "・サブジェクトを入力してください。\n\n";flg = true;
	//}

	if(!f.comment.value){
		error_mes += "・内容を入力してください。\n\n";flg = true;
	}

	// 判定
	if(flg){
		// アラート表示して再入力を警告
		window.alert(error_mes);return false;
	}else{
		return confirm("この内容で登録します。\nよろしいでしょうか？");
	}

/*	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{

		// 確認メッセージ
		return ConfirmMsg('入力いただいた内容で登録します。\nよろしいですか？');
		return true;

	}
*/
}

var IE = 0,NN = 0,N6 = 0;

if(document.all){
	IE = true;
}else if(document.layers){
	NN = true;
}else if(document.getElementById){
	N6 = true;
}

function OnLink(Msg,mX,mY,nX,nY){

	var pX = 0,pY = 0;
	var sX = 10,sY = -40;

	if(IE){
		MyMsg = document.all(Msg).style;
		MyMsg.visibility = "visible";
	}

	if(NN){
		MyMsg = document.layers[Msg];
		MyMsg.visibility = "show";
	}

	if(N6){
		MyMsg = document.getElementById(Msg).style;
		MyMsg.visibility = "visible";
	}

	if(IE){
		pX = document.body.scrollLeft;
		pY = document.body.scrollTop;
		MyMsg.left = mX + pX + sX;
		MyMsg.top = mY + pY + sY;
	}

	if(NN || N6){
		MyMsg.left = nX+ sX;
		MyMsg.top = nY + sY;
	}

}

function OffLink(Msg){

	if(IE){
		document.all(Msg).style.visibility = "hidden";
	}

	if(NN){
		document.layers[Msg].visibility = "hide";
	}

	if(N6){
		document.getElementById(Msg).style.visibility = "hidden";
	}

}

function del_chk(){
		return confirm('このデータを完全に削除します。\nデータの復帰は出来ません。\nよろしいですか？');
}