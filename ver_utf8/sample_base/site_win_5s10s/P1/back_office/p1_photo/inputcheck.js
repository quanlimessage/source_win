// JavaScript Document

/*********************************************************
 入力チェック
*********************************************************/
//------------------------------------------------------------
// メッセージダイアログ表示
//------------------------------------------------------------
function confirm_message(f){

	var message = "";
	/*

	if((!f.up_img1.value)&&(!f.up_img2.value)&&(!f.up_img3.value)&&(!f.up_img4.value)&&(!f.up_img5.value)&&(!f.up_img6.value)){
		if((!f.del_img[0].checked)&&(!f.del_img[1].checked)&&(!f.del_img[2].checked)&&(!f.del_img[3].checked)&&(!f.del_img[4].checked)&&(!f.del_img[5].checked)){
			message = "画像が選択されていません。\n\n参照ボタンから変更する画像を選択してください。";
			window.alert(message);return false;
		}
		else{
			message = "画像が選択されていません。\n削除のみを行いますがよろしいでしょうか？";
			return confirm(message);
		}
	}
	else{
		message = "画像を変更します。\nよろしいでしょうか？";
		return confirm(message);
	}
	*/

		message = "画像を変更します。\nよろしいでしょうか？";
		return confirm(message);

}
