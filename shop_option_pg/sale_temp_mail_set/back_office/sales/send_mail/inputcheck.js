// JavaScript Document

/*********************************************************
 ���ϥ����å�
*********************************************************/
//------------------------------------------------------------
// ��å�������������ɽ��
//------------------------------------------------------------
function confirm_message(f){

	// �ե饰�ν����
	var flg = false;
	var error_mes = "Error Message\r\n��������ޤ��������������Ƥ򤴳�ǧ��������\r\n\r\n";

	// ̤���Ϥ��������ϤΥ����å�
	/*
	if(!f.title.value){
		error_mes += "�������ȥ�򤴵�������������\r\n";flg = true;
	}

	if(!f.sex.value){
		if(!f.sex[0].checked && !f.sex[1].checked){
			error_mes += "�����̤����򤯤�������\r\n";flg = true;
		}
	}

	if(f.tel.value && f.tel.value.match(/[^-0-9]/)){
		error_mes += "�������ֹ��Ⱦ�ѿ����ȥϥ��ե�ΤߤǤ��������Ƥ���������\r\n";flg = true;
	}

	if(!f.email.value){
		error_mes += "���᡼�륢�ɥ쥹�򤴵�������������\r\n";flg = true;
	}
	else if(!f.email.value.match(/^[^@]+@[^.]+\..+/)){
		error_mes += "���᡼�륢�ɥ쥹�η����˸�꤬����ޤ���\r\n";flg = true;
	}
	*/

	// Ƚ��
	if(flg){
		// ���顼��ɽ�����ƺ����Ϥ�ٹ�
		window.alert(error_mes);return false;
	}else{
		return confirm("�������Ƥǥ᡼����������ޤ���\n������Ǥ��礦����");
	}

}
