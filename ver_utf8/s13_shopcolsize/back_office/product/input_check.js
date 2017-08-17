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

	if(!f.part_no.value){
		error_mes += "・商品番号を入力してください。\n\n";flg = true;
	}

	if(!f.category_code.value){
		error_mes += "・カテゴリーを選択してください。\n\n";flg = true;
	}

	if(!f.product_name.value){
		error_mes += "・商品名を入力してください。\n\n";flg = true;
	}

	/*
	if(!f.stock_quantity.value){
		error_mes += "・在庫数を入力してください。\n\n";flg = true;
	}
	*/

	if(!f.selling_price.value){
		error_mes += "・商品単価を入力してください。\n\n";flg = true;
	}

	if(!f.thumbnail_file.value && f.regist_type.value == "new"){
		error_mes += "・サムネイル画像は必須です。\n\n";flg = true;
	}

	if(!f.elements['product_img_file[1]'].value && f.regist_type.value == "new"){
		error_mes += "・詳細画像1は必須です。\n\n";flg = true;
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
