// JavaScript Document

/*********************************************************************
 商品サムネイルから対象の商品詳細ページをポップアップ表示
**********************************************************************/
function showTargetProduct(number){
	var url = "target_product.php?product_no="+number;
	window.open(url,"shop00","width=410,height=600,left=0,top=0,scrollbars=0");
}

/*********************************************************
 汎用確認メッセージ
*********************************************************/
function ConfirmMsg(msg){
	var result;
	result = (confirm(msg))?true:false;
	return result;
}

/***************************************
 二重送信防止
***************************************/

// type1
completionFlg = false;
function easySubmitOnce(){

	if(completionFlg){
		alert("送信完了済みです。");
		return false;
	}
	else{
		completionFlg = true;
		return true;
	}

}

// type2
function submitOnce(f){ // arg:this

	for(i=0;i<f.elements.length;i++){
		if(f.elements[i].type == "submit")f.elements[i].disabled = true;
	}

}

/********************************************************************************
 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
********************************************************************************/

//-------------------------------------------------------------
// ショッピングサイトの認証入力画面用の入力チェック
// ※メールアドレスのチェック
//-------------------------------------------------------------
function emailChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	// メールアドレスのチェック
	if(f.email.value != "" && !f.email.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・メールアドレスの形式に誤りがあります。\n\n";flg = true;
	}

	if(!f.email.value && f.pwd.value != ""){
		error_mes += "・メールアドレスを入力してください。\n\n";flg = true;
	}

	if(f.email.value != "" && !f.pwd.value){
		error_mes += "・パスワードを入力してください。\n\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{
		return true;
	}

}

//-------------------------------------------------------------
// ショッピングサイトのStep1．個人情報入力画面用の入力チェック
//-------------------------------------------------------------
function inputChk1(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	// 各項目のチェック
	if(f.payment_method.length){//支払い方法が複数ある場合
		for(i=0,radioChk=0;i<f.payment_method.length;i++)if(f.payment_method[i].checked)radioChk = 1;
		if(!radioChk){
			error_mes += "・お支払方法を選択してください。\n\n";flg = true;
		}

	}else{//支払い方法が一つしかない場合
		if(!f.payment_method.checked){
			error_mes += "・支払方法選択を入力してください。\n\n";flg = true;
		}
	}

	if(!f.last_name.value){
		error_mes += "・お名前（姓漢字）を入力してください。\n\n";flg = true;
	}

	if(!f.first_name.value){
		error_mes += "・お名前（名漢字）を入力してください。\n\n";flg = true;
	}

	if(!f.last_kana.value){
		error_mes += "・お名前（姓かな）を入力してください。\n\n";flg = true;
	}

	if(!f.first_kana.value){
		error_mes += "・お名前（名かな）を入力してください。\n\n";flg = true;
	}

	if(!f.zip1.value){
		error_mes += "・郵便番号（左）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.zip1.value)){
		error_mes += "・郵便番号（左）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(!f.zip2.value){
		error_mes += "・郵便番号（右）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.zip2.value)){
		error_mes += "・郵便番号（右）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.state.selectedIndex == 0){
		error_mes += "・都道府県を選択してください。\n\n";flg = true;
	}

	if(!f.address1.value){
		error_mes += "・ご住所(市町村以下)を入力してください。\n\n";flg = true;
	}

	if(f.tel1.value == ""){
		error_mes += "・お電話番号（左）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.tel1.value)){
		error_mes += "・お電話番号（左）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.tel2.value == ""){
		error_mes += "・お電話番号（中央）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.tel2.value)){
		error_mes += "・お電話番号（中央）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.tel3.value == ""){
		error_mes += "・お電話番号（右）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.tel3.value)){
		error_mes += "・お電話番号（右）は半角数字のみご使用ください。\n\n";flg = true;
	}

	// メールアドレスのチェック
	if(!f.email.value){
		error_mes += "・メールアドレスを入力してください。\n\n";flg = true;
	}
	else if(!f.email.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "・メールアドレスの形式に誤りがあります。\n\n";flg = true;
	}

	//パスワードの入力欄が存在している場合、入力チェックを行う
	if(document.getElementById('password') != null){
		if(!f.password.value){
			error_mes += "・パスワードを入力してください。\n\n";flg = true;
		}

		if(f.password.value != f.password2.value){
			error_mes += "・パスワードが確認用と違っております。\n\n";flg = true;
		}
	}

	if(!f.deli_last_name.value && !f.deli_first_name.value && !f.deli_zip1.value && !f.deli_zip2.value && !f.deli_address1.value && !f.deli_tel1.value && !f.deli_tel2.value && !f.deli_tel3.value){

	// 何もしない

	}else{

	// 配送先情報チェック
	if(!f.deli_last_name.value){
		error_mes += "・配送先お名前（姓漢字）を入力してください。\n\n";flg = true;
	}

	if(!f.deli_first_name.value){
		error_mes += "・配送先お名前（名漢字）を入力してください。\n\n";flg = true;
	}

	if(!f.deli_zip1.value){
		error_mes += "・配送先郵便番号（左）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.deli_zip1.value)){
		error_mes += "・配送先郵便番号（左）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(!f.deli_zip2.value){
		error_mes += "・配送先郵便番号（右）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.deli_zip2.value)){
		error_mes += "・配送先郵便番号（右）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.deli_state.selectedIndex == 0){
		error_mes += "・配送先都道府県を選択してください。\n\n";flg = true;
	}

	if(!f.deli_address1.value){
		error_mes += "・配送先ご住所(市町村以下)を入力してください。\n\n";flg = true;
	}

	if(f.deli_tel1.value == ""){
		error_mes += "・配送先電話番号（左）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.deli_tel1.value)){
		error_mes += "・配送先電話番号（左）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.deli_tel2.value == ""){
		error_mes += "・配送先電話番号（中央）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.deli_tel2.value)){
		error_mes += "・配送先電話番号（中央）は半角数字のみご使用ください。\n\n";flg = true;
	}

	if(f.deli_tel3.value == ""){
		error_mes += "・配送先電話番号（右）を入力してください。\n\n";flg = true;
	}
	else if(isNaN(f.deli_tel3.value)){
		error_mes += "・配送先電話番号（右）は半角数字のみご使用ください。\n\n";flg = true;
	}

	}
	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}
	else{
		return true;
	}

}
