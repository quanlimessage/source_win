// JavaScript Document

/*********************************************************
 ���ϥ����å�
*********************************************************/
function inputChk(f){

	// �ե饰�ν����
	var flg = false;
	var error_mes = "Error Message\n��������ޤ��������������Ƥ򤴳�ǧ��������\n\n";

	if(!f.start_y.value && (f.start_d.value || f.start_m.value)){
		error_mes += "���ǡ�����г���ǯ����ꤷ�Ʋ�������\n";flg = true;
	}

	if(!f.start_m.value && f.start_d.value){
			error_mes += "���ǡ�����г��Ϸ����ꤷ�Ʋ�������\n";flg = true;
	}

	if(!f.end_y.value && (f.end_m.value || f.end_d.value)){
		error_mes += "���ǡ�����н�λǯ����ꤷ�Ʋ�������\n";flg = true;
	}

	if(!f.end_m.value && f.end_d.value){
			error_mes += "���ǡ�����н�λ�����ꤷ�Ʋ�������\n";flg = true;
	}

	// Ƚ���̤���Ϥ��������Ϥ�����Х��顼��ɽ�����ƺ����Ϥ�¥�������ڡ����ؿʤ�ʤ���
	if(flg){
		window.alert(error_mes);return false;
	}

}
