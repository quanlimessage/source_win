// JavaScript Document
/****************************************************************************
 �¤ӽ���ѹ�
****************************************************************************/

//----------------------------------
// ��˾夲��
//----------------------------------
function moveUp(){

	// ��˾夲��ǡ���������ʥ��쥯�Ȥ��줿��Ρ�
	var i = document.change_sort.nvo.selectedIndex;
	if(i == -1){ window.alert('�ѹ��������ǡ��������򤷤Ƥ���������');
	}
	else{
	var val = document.change_sort.nvo.options[i].value;
	var txt = document.change_sort.nvo.options[i].text;

	// selectedIndex��0����Ƭ�ˤǤʤ����˲����ν���
	if(i>0){

		// ���˲�����ǡ��������
		var d = eval(i-1);
		var d_val = document.change_sort.nvo.options[d].value;
		var d_txt = document.change_sort.nvo.options[d].text;

		// �ǡ����������ؤ���
		document.change_sort.nvo.options[d].value = val;
		document.change_sort.nvo.options[d].text = txt;
		document.change_sort.nvo.options[d].selected = true;
		document.change_sort.nvo.options[i].value = d_val;
		document.change_sort.nvo.options[i].text = d_txt;
	}
	}

}

//-----------------------------
// ���˲�����
//-----------------------------
function moveDn(){

	// ���ˤ˲�����ǡ���������ʥ��쥯�Ȥ��줿��Ρ�
	var i = (document.change_sort.nvo.selectedIndex);
	if(i == -1){ window.alert('�ѹ��������ǡ��������򤷤Ƥ���������');
	}
	else{
	var val = document.change_sort.nvo.options[i].value;
	var txt = document.change_sort.nvo.options[i].text;

	// ���쥯�ȥ����κ�����ʬ���ϰϤǲ����ν���
	if(i<eval(document.change_sort.nvo.length -1)){

		// ��˾夬����
		var u = eval(i+1);
		var u_val = document.change_sort.nvo.options[u].value;
		var u_txt = document.change_sort.nvo.options[u].text;

		// �ǡ����������ؤ���
		document.change_sort.nvo.options[i].value = u_val;
		document.change_sort.nvo.options[i].text = u_txt;
		document.change_sort.nvo.options[u].value = val;
		document.change_sort.nvo.options[u].text = txt;
		document.change_sort.nvo.options[u].selected = true;
	}
	}

}

// ���Τ�������¤��ؤ���������������
function change_sortSubmit(){

	with(document.change_sort){

		// ���쥯�ȥ����η��ʬ��hidden�ǡ�����new_view_order�ˤ˥��ֶ��ڤ�ˤ��Ƴ�Ǽ
		for(i=0;i<nvo.length;i++){
			new_view_order.value += nvo.options[i].value;
			if(i < eval(nvo.length))new_view_order.value += "\t";
		}

		// ��Ǽ������ä�����������
		submit();

	}
}