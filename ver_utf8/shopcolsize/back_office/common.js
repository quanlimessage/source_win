// 印刷用関数
function PrintPage(){
	if(document.getElementById || document.layers){
		window.print();		//印刷をします
	}
}
