
//------------------------------------------------------------
// ����󥻥�EѤ˥ե�������ͤ��ѹ�
//------------------------------------------------------------
function chgcancel(i){
	document.new_regist.action = i;
	document.new_regist.target = '_self';
	document.new_regist.act.value = '';
}

//------------------------------------------------------------
// �ץ�Eӥ塼�Ѥ˥ե�������ͤ��ѹ�
//------------------------------------------------------------
function chgpreview(i){
	document.new_regist.action = i;
	document.new_regist.target = '_blank';
	document.new_regist.act.value = 'prev';
}

//------------------------------------------------------------
// �ܺ٥ץ�Eӥ塼�Ѥ˥ե�������ͤ��ѹ�
//------------------------------------------------------------
function chgpreview_d(i){
	document.new_regist.action = i;
	document.new_regist.target = '_blank';
	document.new_regist.act.value = 'prev_d';
}

//------------------------------------------------------------
// ���֥ߥå��Ѥ˥ե�������ͤ��ѹ�
//------------------------------------------------------------
function chgsubmit(){
	document.new_regist.action = './';
	document.new_regist.target = '_self';
	document.new_regist.act.value = 'completion';
}