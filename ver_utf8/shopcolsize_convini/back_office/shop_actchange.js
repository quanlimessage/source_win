
//------------------------------------------------------------
// キャンセル用にフォームの値を変更
//------------------------------------------------------------
function chgcancel(i){
	document.new_regist.action = i;
	document.new_regist.target = '_self';
	document.new_regist.status.value = '';
}

//------------------------------------------------------------
// プレビュー用にフォームの値を変更
//------------------------------------------------------------
function chgpreview(i){
	document.new_regist.action = i;
	document.new_regist.target = '_blank';
	document.new_regist.status.value = 'prev';
}

//------------------------------------------------------------
// 詳細プレビュー用にフォームの値を変更
//------------------------------------------------------------
function chgpreview_d(i){
	document.new_regist.action = i;
	document.new_regist.target = '_blank';
	document.new_regist.status.value = 'prev_d';
}

//------------------------------------------------------------
// サブミット用にフォームの値を変更
//------------------------------------------------------------
function chgsubmit(){
	document.new_regist.action = './';
	document.new_regist.target = '_self';
	document.new_regist.status.value = 'product_entry_completion';
}
