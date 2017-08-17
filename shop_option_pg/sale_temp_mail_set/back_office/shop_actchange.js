
//------------------------------------------------------------
// •≠•„•Û•ª•ÅE—§À•’•©°º•‡§Œ√Õ§Ú —ππ
//------------------------------------------------------------
function chgcancel(i){
	document.new_regist.action = i;
	document.new_regist.target = '_self';
	document.new_regist.status.value = '';
}

//------------------------------------------------------------
// •◊•ÅE”•Â°ºÕ—§À•’•©°º•‡§Œ√Õ§Ú —ππ
//------------------------------------------------------------
function chgpreview(i){
	document.new_regist.action = i;
	document.new_regist.target = '_blank';
	document.new_regist.status.value = 'prev';
}

//------------------------------------------------------------
// æ‹∫Ÿ•◊•ÅE”•Â°ºÕ—§À•’•©°º•‡§Œ√Õ§Ú —ππ
//------------------------------------------------------------
function chgpreview_d(i){
	document.new_regist.action = i;
	document.new_regist.target = '_blank';
	document.new_regist.status.value = 'prev_d';
}

//------------------------------------------------------------
// •µ•÷•ﬂ•√•»Õ—§À•’•©°º•‡§Œ√Õ§Ú —ππ
//------------------------------------------------------------
function chgsubmit(){
	document.new_regist.action = './';
	document.new_regist.target = '_self';
	document.new_regist.status.value = 'product_entry_completion';
}