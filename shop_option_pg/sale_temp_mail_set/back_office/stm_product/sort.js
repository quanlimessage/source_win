// JavaScript Document
/****************************************************************************
 �¤ӽ���ѹ�
****************************************************************************/

//----------------------------------
// �ǽ�˾夲��
//----------------------------------
function f_moveUp(){

	// ��˾夲��ǡ���������ʥ��쥯�Ȥ��줿��Ρ�
		var i = document.change_sort.nvo.selectedIndex;

	if(i == -1){
		window.alert('�ѹ��������ǡ��������򤷤Ƥ���������');
	}else{
		//���򤷤��ǡ��������
		var val = document.change_sort.nvo.options[i].value;
		var txt = document.change_sort.nvo.options[i].text;

		//���ֺǽ�ʳ��ϰ��ʾ��
		for(l=i;l > 0;l--){//���򤷤��ֹ椫���˾夲�Ƥ���
			//��Ĥ������ֹ�����
				var d = eval(l-1);

			//�ǡ�����������
				var d_val = document.change_sort.nvo.options[d].value;
				var d_txt = document.change_sort.nvo.options[d].text;

			//�ǡ������˰�ư�����롣
				document.change_sort.nvo.options[l].value = d_val;
				document.change_sort.nvo.options[l].text = d_txt;

		}

			//�Ǹ�����򤷤��ǡ������ư�����롣
				document.change_sort.nvo.options[0].value = val;
				document.change_sort.nvo.options[0].text = txt;
	}
}

//----------------------------------
// �Ǹ�˲�����
//----------------------------------
function l_moveDn(){

	// ���˲�����ǡ���������ʥ��쥯�Ȥ��줿��Ρ�
	var i = document.change_sort.nvo.selectedIndex;
	if(i == -1){
		window.alert('�ѹ��������ǡ��������򤷤Ƥ���������');
	}else{
		//���򤷤��ǡ��������
		var val = document.change_sort.nvo.options[i].value;
		var txt = document.change_sort.nvo.options[i].text;

			var mes="";
			mes = mes + "i=" + i + "\n";

		//���ֺǽ�ʳ��ϰ��ʲ���
		for(l=i;l < (document.change_sort.nvo.length - 1);l++){//���򤷤��ֹ椫�鲼�˲����Ƥ���

			//��Ĳ����ֹ�����
				var d = eval(l+1);

			//�ǡ�����������
				var d_val = document.change_sort.nvo.options[d].value;
				var d_txt = document.change_sort.nvo.options[d].text;

			//�ǡ����򲼤˰�ư�����롣
				document.change_sort.nvo.options[l].value = d_val;
				document.change_sort.nvo.options[l].text = d_txt;
		}
			//�Ǹ�����򤷤��ǡ������ư�����롣
				document.change_sort.nvo.options[d].value = val;
				document.change_sort.nvo.options[d].text = txt;
	}
}

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

//-----------------------------
// ���Υ��ȥå������˰�ư
//-----------------------------
function stock_move(){

	// ���ˤ˲�����ǡ���������ʥ��쥯�Ȥ��줿��Ρ�
	var i = (document.change_sort.nvo.selectedIndex);

	if(i == -1){
		window.alert('���ȥå�����ǡ��������򤷤Ƥ���������');
	}else if(document.change_sort.nvo.length <= 1){
		window.alert('�¤Ф���ǡ�����1�İʾ�Ĥ��Ƥ���������');
	}else{
	var val = document.change_sort.nvo.options[i].value;
	var txt = document.change_sort.nvo.options[i].text;

	//���ȥå������ΰ��ֺǸ�������
		var u = eval(document.change_sort.stock_nvo.length);//���ֺǸ�μ����ֹ���������
		document.change_sort.stock_nvo.options[u] = new Option(txt, val);

	//��ư�������ǡ����򺸤Υꥹ�Ȥ���������
		// ���쥯�ȥ����κ�����ʬ���ϰϤǲ����ν���
		if(i<eval(document.change_sort.nvo.length -1)){//���򤵤줿�Τ����ֺǸ�Фʤ����ϰ��ʾ�ˤ��餷�ƺǸ����

			// ��˾夬����
				var u = eval(i+1);
				var u_val = document.change_sort.nvo.options[u].value;
				var u_txt = document.change_sort.nvo.options[u].text;

			//���ֺǽ�ʳ��ϰ��ʲ���
				for(l=i;l < (document.change_sort.nvo.length - 1);l++){//���򤷤��ֹ椫�鲼�˲����Ƥ���

					//��Ĳ����ֹ�����
						var d = eval(l+1);

					//�ǡ�����������
						var d_val = document.change_sort.nvo.options[d].value;
						var d_txt = document.change_sort.nvo.options[d].text;

					//�ǡ����򲼤˰�ư�����롣
						document.change_sort.nvo.options[l].value = d_val;
						document.change_sort.nvo.options[l].text = d_txt;
				}
		//�Ǹ�Υǡ���������
			document.change_sort.nvo.options[d] = null;

		}else{//���򤵤줿�Τ����ֺǸ�ξ������򤵤줿�Ȥ������
			document.change_sort.nvo.options[i] = null;
		}

	}

}

//-----------------------------
// ���Υ��ȥå������¤ӽ�Υꥹ�Ȥ�����
//-----------------------------
function on_move(){

	//�¤ӽ�Υꥹ�Ȥ�����������֤����򤵤�Ƥ��뤫�����å�
		if(document.change_sort.nvo.selectedIndex == -1){
			window.alert('����������֤����򤷤Ƥ���������');
		}else{
			var t = document.change_sort.nvo.selectedIndex;

			//���򤷤Ƥ���ǡ����򺸤��¤ӽ�ꥹ�Ȥ˰�ư������ʺ�������ϸ�ˤ��롢�����������ˤ����length�ο��ͤ��Ѥ�äƤ��ޤ������
				for(i = document.change_sort.stock_nvo.options.length -1; i >= 0; i--){

					//���򤵤�Ƥ��뤫�����å�����
						if(document.change_sort.stock_nvo.options[i].selected){//���򤵤�Ƥ�����
							//�¤ӽ�Υꥹ�Ȥ�����
								//��������Ƥ�����
									var opt = document.createElement("option");
									opt.value = document.change_sort.stock_nvo.options[i].value;
									var str = document.createTextNode(document.change_sort.stock_nvo.options[i].text);
									opt.appendChild(str);

								document.change_sort.nvo.insertBefore(opt, document.change_sort.nvo.options[t]);
						}
				}

			//���򤷤Ƥ��륹�ȥå��ꥹ�Ȥ���
				for(i = document.change_sort.stock_nvo.options.length-1; i >= 0; i--){
					//���򤵤�Ƥ��뤫�����å�����
						if(document.change_sort.stock_nvo.options[i].selected){//���򤵤�Ƥ�����
							//���ȥå�����������
								document.change_sort.stock_nvo.options[i] = null;
						}
				}
		}
}

// �¤��ؤ�����������
function change_sortSubmit(){

	//�����������˱�¦�Υ��ȥå����������Ƥ��ʤ��ǡ��������뤫�����å�
		if(document.change_sort.stock_nvo.options.length){
			window.alert('���ȥå��ꥹ�Ȥ���������Ƥ��ʤ��ǡ������������ޤ���');
			return false;
		}else{

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
}