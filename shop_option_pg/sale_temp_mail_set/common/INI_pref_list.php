<?php
/******************************************************************************************
 ��ƻ�ܸ��������������	������ξ��֤Ǵ���
 2004/7/26 Yossee
******************************************************************************************/

#------------------------------------------------------------------------------
# ��ƻ�ܸ��ꥹ�ȣ� ��ñ�����ƻ�ܸ�����Τ�
#------------------------------------------------------------------------------
$pref_list = array(
	0 => "�̳�ƻ",
	1 => "�Ŀ���",

	2 => "��긩",

	3 => "�ܾ븩",

	4 => "���ĸ�",

	5 => "������",

	6 => "ʡ�縩",

	7 => "��븩",

	8 => "���ڸ�",
	9 => "���ϸ�",
	10 => "��̸�",
	11 => "���ո�",
	12 => "�����",
	13 => "�����",
	14 => "���㸩",
	15 => "�ٻ���",
	16 => "���",
	17 => "ʡ�温",
	18 => "������",
	19 => "Ĺ�",
	20 => "���츩",
	21 => "�Ų���",
	22 => "���θ�",
	23 => "���Ÿ�",
	24 => "���츩",
	25 => "������",

	26 => "�����",

	27 => "ʼ�˸�",

	28 => "���ɸ�",
	29 => "�²λ���",

	30 => "Ļ�踩",

	31 => "�纬��",

	32 => "������",

	33 => "���縩",

	34 => "������",

	35 => "���縩",

	36 => "���",

	37 => "��ɲ��",

	38 => "���θ�",
	39 => "ʡ����",
	40 => "���츩",
	41 => "Ĺ�긩",
	42 => "���ܸ�",
	43 => "��ʬ��",
	44 => "�ܺ긩",
	45 => "�����縩",
	46 => "���츩"
);

#------------------------------------------------------------------------------
# ��ƻ�ܸ��ꥹ�ȣ� ��optgroup�б�������������տ��
#------------------------------------------------------------------------------
$pref_local_list["�̳�ƻ������"][0] = "�̳�ƻ";
$pref_local_list["�̳�ƻ������"][1] = "�Ŀ���";
$pref_local_list["�̳�ƻ������"][2] = "��긩";
$pref_local_list["�̳�ƻ������"][3] = "���ĸ�";
$pref_local_list["�̳�ƻ������"][4] = "�ܾ븩";
$pref_local_list["�̳�ƻ������"][5] = "������";
$pref_local_list["�̳�ƻ������"][6] = "ʡ�縩";

$pref_local_list["���졦�ÿ���"][0] = "�����";
$pref_local_list["���졦�ÿ���"][1] = "�����";
$pref_local_list["���졦�ÿ���"][2] = "��̸�";
$pref_local_list["���졦�ÿ���"][3] = "���ո�";
$pref_local_list["���졦�ÿ���"][4] = "��븩";
$pref_local_list["���졦�ÿ���"][5] = "���ڸ�";
$pref_local_list["���졦�ÿ���"][6] = "���ϸ�";
$pref_local_list["���졦�ÿ���"][7] = "������";
$pref_local_list["���졦�ÿ���"][8] = "Ĺ�";

$pref_local_list["�쳤����Φ"][0] = "�Ų���";
$pref_local_list["�쳤����Φ"][1] = "���θ�";
$pref_local_list["�쳤����Φ"][2] = "���츩";
$pref_local_list["�쳤����Φ"][3] = "���㸩";
$pref_local_list["�쳤����Φ"][4] = "�ٻ���";
$pref_local_list["�쳤����Φ"][5] = "���";
$pref_local_list["�쳤����Φ"][6] = "ʡ�温";

$pref_local_list["�ᵦ"][0] = "���Ÿ�";
$pref_local_list["�ᵦ"][1] = "���츩";
$pref_local_list["�ᵦ"][2] = "������";
$pref_local_list["�ᵦ"][3] = "�����";
$pref_local_list["�ᵦ"][4] = "ʼ�˸�";
$pref_local_list["�ᵦ"][5] = "���ɸ�";
$pref_local_list["�ᵦ"][6] = "�²λ���";

$pref_local_list["��񡦻͹�"][0] = "Ļ�踩";
$pref_local_list["��񡦻͹�"][1] = "�纬��";
$pref_local_list["��񡦻͹�"][2] = "������";
$pref_local_list["��񡦻͹�"][3] = "���縩";
$pref_local_list["��񡦻͹�"][4] = "������";
$pref_local_list["��񡦻͹�"][5] = "���縩";
$pref_local_list["��񡦻͹�"][6] = "���";
$pref_local_list["��񡦻͹�"][7] = "��ɲ��";
$pref_local_list["��񡦻͹�"][8] = "���θ�";

$pref_local_list["�彣������"][0] = "ʡ����";
$pref_local_list["�彣������"][1] = "���츩";
$pref_local_list["�彣������"][2] = "Ĺ�긩";
$pref_local_list["�彣������"][3] = "���ܸ�";
$pref_local_list["�彣������"][4] = "��ʬ��";
$pref_local_list["�彣������"][5] = "�ܺ긩";
$pref_local_list["�彣������"][6] = "�����縩";
$pref_local_list["�彣������"][7] = "���츩";

#------------------------------------------------------------------------------
# ��ƻ�ܸ��ꥹ�ȣ� ����ƻ�ܸ������������¿��������ǳ�Ǽ��ID������ղá�
#	�������ֹ����ƻ�ܸ��ꥹ�ȣ�($pref_list)��Ʊ��
#		shipping1���̾�����
#		shipping2�����������
#		daibiki1�����������
#		daibiki2�����������
#------------------------------------------------------------------------------
$shipping_list[0]['id'] = 0;

$shipping_list[0]['pref'] = "�̳�ƻ";
$shipping_list[0]['shipping1'] = 1000;
$shipping_list[0]['shipping2'] = 500;
$shipping_list[0]['daibiki1'] = 500;
$shipping_list[0]['daibiki2'] = 0;

$shipping_list[1]['id'] = 1;

$shipping_list[1]['pref'] = "�Ŀ���";
$shipping_list[1]['shipping1'] = 600;

$shipping_list[1]['shipping2'] = 500;
$shipping_list[1]['daibiki1'] = 500;
$shipping_list[1]['daibiki2'] = 0;

$shipping_list[2]['id'] = 2;

$shipping_list[2]['pref'] = "��긩";

$shipping_list[2]['shipping1'] = 600;
$shipping_list[2]['shipping2'] = 500;
$shipping_list[2]['daibiki1'] = 500;
$shipping_list[2]['daibiki2'] = 0;

$shipping_list[3]['id'] = 3;

$shipping_list[3]['pref'] = "�ܾ븩";

$shipping_list[3]['shipping1'] = 600;

$shipping_list[3]['shipping2'] = 500;
$shipping_list[3]['daibiki1'] = 500;
$shipping_list[3]['daibiki2'] = 0;

$shipping_list[4]['id'] = 4;

$shipping_list[4]['pref'] = "���ĸ�";

$shipping_list[4]['shipping1'] = 600;

$shipping_list[4]['shipping2'] = 500;
$shipping_list[4]['daibiki1'] = 500;
$shipping_list[4]['daibiki2'] = 0;

$shipping_list[5]['id'] = 5;

$shipping_list[5]['pref'] = "������";

$shipping_list[5]['shipping1'] = 600;

$shipping_list[5]['shipping2'] = 500;
$shipping_list[5]['daibiki1'] = 500;
$shipping_list[5]['daibiki2'] = 0;

$shipping_list[6]['id'] = 6;

$shipping_list[6]['pref'] = "ʡ�縩";

$shipping_list[6]['shipping1'] = 600;

$shipping_list[6]['shipping2'] = 500;
$shipping_list[6]['daibiki1'] = 500;
$shipping_list[6]['daibiki2'] = 0;

$shipping_list[7]['id'] = 7;

$shipping_list[7]['pref'] = "��븩";

$shipping_list[7]['shipping1'] = 600;
$shipping_list[7]['shipping2'] = 500;
$shipping_list[7]['daibiki1'] = 500;
$shipping_list[7]['daibiki2'] = 0;

$shipping_list[8]['id'] = 8;

$shipping_list[8]['pref'] = "���ڸ�";

$shipping_list[8]['shipping1'] = 600;

$shipping_list[8]['shipping2'] = 500;
$shipping_list[8]['daibiki1'] = 500;
$shipping_list[8]['daibiki2'] = 0;

$shipping_list[9]['id'] = 9;

$shipping_list[9]['pref'] = "���ϸ�";

$shipping_list[9]['shipping1'] = 600;
$shipping_list[9]['shipping2'] = 500;
$shipping_list[9]['daibiki1'] = 500;
$shipping_list[9]['daibiki2'] = 0;

$shipping_list[10]['id'] = 10;

$shipping_list[10]['pref'] = "��̸�";

$shipping_list[10]['shipping1'] = 600;
$shipping_list[10]['shipping2'] = 500;
$shipping_list[10]['daibiki1'] = 500;
$shipping_list[10]['daibiki2'] = 0;

$shipping_list[11]['id'] = 11;

$shipping_list[11]['pref'] = "���ո�";

$shipping_list[11]['shipping1'] = 600;

$shipping_list[11]['shipping2'] = 500;
$shipping_list[11]['daibiki1'] = 500;
$shipping_list[11]['daibiki2'] = 0;

$shipping_list[12]['id'] = 12;

$shipping_list[12]['pref'] = "�����";

$shipping_list[12]['shipping1'] = 600;

$shipping_list[12]['shipping2'] = 500;
$shipping_list[12]['daibiki1'] = 500;
$shipping_list[12]['daibiki2'] = 0;

$shipping_list[13]['id'] = 13;

$shipping_list[13]['pref'] = "�����";

$shipping_list[13]['shipping1'] = 600;
$shipping_list[13]['shipping2'] = 500;
$shipping_list[13]['daibiki1'] = 500;
$shipping_list[13]['daibiki2'] = 0;

$shipping_list[14]['id'] = 14;

$shipping_list[14]['pref'] = "���㸩";

$shipping_list[14]['shipping1'] = 600;
$shipping_list[14]['shipping2'] = 500;
$shipping_list[14]['daibiki1'] = 500;
$shipping_list[14]['daibiki2'] = 0;

$shipping_list[15]['id'] = 15;

$shipping_list[15]['pref'] = "�ٻ���";

$shipping_list[15]['shipping1'] = 600;

$shipping_list[15]['shipping2'] = 500;
$shipping_list[15]['daibiki1'] = 500;
$shipping_list[15]['daibiki2'] = 0;

$shipping_list[16]['id'] = 16;

$shipping_list[16]['pref'] = "���";

$shipping_list[16]['shipping1'] = 600;
$shipping_list[16]['shipping2'] = 500;
$shipping_list[16]['daibiki1'] = 500;
$shipping_list[16]['daibiki2'] = 0;

$shipping_list[17]['id'] = 17;

$shipping_list[17]['pref'] = "ʡ�温";

$shipping_list[17]['shipping1'] = 600;

$shipping_list[17]['shipping2'] = 500;
$shipping_list[17]['daibiki1'] = 500;
$shipping_list[17]['daibiki2'] = 0;

$shipping_list[18]['id'] = 18;

$shipping_list[18]['pref'] = "������";

$shipping_list[18]['shipping1'] = 600;
$shipping_list[18]['shipping2'] = 500;
$shipping_list[18]['daibiki1'] = 500;
$shipping_list[18]['daibiki2'] = 0;

$shipping_list[19]['id'] = 19;

$shipping_list[19]['pref'] = "Ĺ�";

$shipping_list[19]['shipping1'] = 600;
$shipping_list[19]['shipping2'] = 500;
$shipping_list[19]['daibiki1'] = 500;
$shipping_list[19]['daibiki2'] = 0;

$shipping_list[20]['id'] = 20;

$shipping_list[20]['pref'] = "���츩";

$shipping_list[20]['shipping1'] = 600;
$shipping_list[20]['shipping2'] = 500;
$shipping_list[20]['daibiki1'] = 500;
$shipping_list[20]['daibiki2'] = 0;

$shipping_list[21]['id'] = 21;

$shipping_list[21]['pref'] = "�Ų���";

$shipping_list[21]['shipping1'] = 600;
$shipping_list[21]['shipping2'] = 500;
$shipping_list[21]['daibiki1'] = 500;
$shipping_list[21]['daibiki2'] = 0;

$shipping_list[22]['id'] = 22;

$shipping_list[22]['pref'] = "���θ�";

$shipping_list[22]['shipping1'] = 600;
$shipping_list[22]['shipping2'] = 500;
$shipping_list[22]['daibiki1'] = 500;
$shipping_list[22]['daibiki2'] = 0;

$shipping_list[23]['id'] = 23;

$shipping_list[23]['pref'] = "���Ÿ�";

$shipping_list[23]['shipping1'] = 600;
$shipping_list[23]['shipping2'] = 500;
$shipping_list[23]['daibiki1'] = 500;
$shipping_list[23]['daibiki2'] = 0;

$shipping_list[24]['id'] = 24;

$shipping_list[24]['pref'] = "���츩";

$shipping_list[24]['shipping1'] = 600;
$shipping_list[24]['shipping2'] = 500;
$shipping_list[24]['daibiki1'] = 500;
$shipping_list[24]['daibiki2'] = 0;

$shipping_list[25]['id'] = 25;

$shipping_list[25]['pref'] = "������";

$shipping_list[25]['shipping1'] = 600;

$shipping_list[25]['shipping2'] = 500;
$shipping_list[25]['daibiki1'] = 500;
$shipping_list[25]['daibiki2'] = 0;

$shipping_list[26]['id'] = 26;

$shipping_list[26]['pref'] = "�����";

$shipping_list[26]['shipping1'] = 600;

$shipping_list[26]['shipping2'] = 500;
$shipping_list[26]['daibiki1'] = 500;
$shipping_list[26]['daibiki2'] = 0;

$shipping_list[27]['id'] = 27;

$shipping_list[27]['pref'] = "ʼ�˸�";

$shipping_list[27]['shipping1'] = 600;

$shipping_list[27]['shipping2'] = 500;
$shipping_list[27]['daibiki1'] = 500;
$shipping_list[27]['daibiki2'] = 0;

$shipping_list[28]['id'] = 28;

$shipping_list[28]['pref'] = "���ɸ�";

$shipping_list[28]['shipping1'] = 600;
$shipping_list[28]['shipping2'] = 500;
$shipping_list[28]['daibiki1'] = 500;
$shipping_list[28]['daibiki2'] = 0;

$shipping_list[29]['id'] = 29;

$shipping_list[29]['pref'] = "�²λ���";

$shipping_list[29]['shipping1'] = 600;

$shipping_list[29]['shipping2'] = 500;
$shipping_list[29]['daibiki1'] = 500;
$shipping_list[29]['daibiki2'] = 0;

$shipping_list[30]['id'] = 30;

$shipping_list[30]['pref'] = "Ļ�踩";

$shipping_list[30]['shipping1'] = 600;

$shipping_list[30]['shipping2'] = 500;
$shipping_list[30]['daibiki1'] = 500;
$shipping_list[30]['daibiki2'] = 0;

$shipping_list[31]['id'] = 31;

$shipping_list[31]['pref'] = "�纬��";

$shipping_list[31]['shipping1'] = 600;

$shipping_list[31]['shipping2'] = 500;
$shipping_list[31]['daibiki1'] = 500;
$shipping_list[31]['daibiki2'] = 0;

$shipping_list[32]['id'] = 32;

$shipping_list[32]['pref'] = "������";

$shipping_list[32]['shipping1'] = 600;

$shipping_list[32]['shipping2'] = 500;
$shipping_list[32]['daibiki1'] = 500;
$shipping_list[32]['daibiki2'] = 0;

$shipping_list[33]['id'] = 33;

$shipping_list[33]['pref'] = "���縩";

$shipping_list[33]['shipping1'] = 600;

$shipping_list[33]['shipping2'] = 500;
$shipping_list[33]['daibiki1'] = 500;
$shipping_list[33]['daibiki2'] = 0;

$shipping_list[34]['id'] = 34;

$shipping_list[34]['pref'] = "������";

$shipping_list[34]['shipping1'] = 600;

$shipping_list[34]['shipping2'] = 500;
$shipping_list[34]['daibiki1'] = 500;
$shipping_list[34]['daibiki2'] = 0;

$shipping_list[35]['id'] = 35;

$shipping_list[35]['pref'] = "���縩";

$shipping_list[35]['shipping1'] = 600;

$shipping_list[35]['shipping2'] = 500;
$shipping_list[35]['daibiki1'] = 500;
$shipping_list[35]['daibiki2'] = 0;

$shipping_list[36]['id'] = 36;

$shipping_list[36]['pref'] = "���";

$shipping_list[36]['shipping1'] = 600;

$shipping_list[36]['shipping2'] = 500;
$shipping_list[36]['daibiki1'] = 500;
$shipping_list[36]['daibiki2'] = 0;

$shipping_list[37]['id'] = 37;

$shipping_list[37]['pref'] = "��ɲ��";

$shipping_list[37]['shipping1'] = 600;

$shipping_list[37]['shipping2'] = 500;
$shipping_list[37]['daibiki1'] = 500;
$shipping_list[37]['daibiki2'] = 0;

$shipping_list[38]['id'] = 38;

$shipping_list[38]['pref'] = "���θ�";

$shipping_list[38]['shipping1'] = 600;

$shipping_list[38]['shipping2'] = 500;
$shipping_list[38]['daibiki1'] = 500;
$shipping_list[38]['daibiki2'] = 0;

$shipping_list[39]['id'] = 39;

$shipping_list[39]['pref'] = "ʡ����";

$shipping_list[39]['shipping1'] = 600;

$shipping_list[39]['shipping2'] = 500;
$shipping_list[39]['daibiki1'] = 500;
$shipping_list[39]['daibiki2'] = 0;

$shipping_list[40]['id'] = 40;

$shipping_list[40]['pref'] = "���츩";

$shipping_list[40]['shipping1'] = 600;

$shipping_list[40]['shipping2'] = 500;
$shipping_list[40]['daibiki1'] = 500;
$shipping_list[40]['daibiki2'] = 0;

$shipping_list[41]['id'] = 41;

$shipping_list[41]['pref'] = "Ĺ�긩";

$shipping_list[41]['shipping1'] = 600;

$shipping_list[41]['shipping2'] = 500;
$shipping_list[41]['daibiki1'] = 500;
$shipping_list[41]['daibiki2'] = 0;

$shipping_list[42]['id'] = 42;

$shipping_list[42]['pref'] = "���ܸ�";

$shipping_list[42]['shipping1'] = 600;

$shipping_list[42]['shipping2'] = 500;
$shipping_list[42]['daibiki1'] = 500;
$shipping_list[42]['daibiki2'] = 0;

$shipping_list[43]['id'] = 43;

$shipping_list[43]['pref'] = "��ʬ��";

$shipping_list[43]['shipping1'] = 600;

$shipping_list[43]['shipping2'] = 500;
$shipping_list[43]['daibiki1'] = 500;
$shipping_list[43]['daibiki2'] = 0;

$shipping_list[44]['id'] = 44;

$shipping_list[44]['pref'] = "�ܺ긩";

$shipping_list[44]['shipping1'] = 600;

$shipping_list[44]['shipping2'] = 500;
$shipping_list[44]['daibiki1'] = 500;
$shipping_list[44]['daibiki2'] = 0;

$shipping_list[45]['id'] = 45;

$shipping_list[45]['pref'] = "�����縩";

$shipping_list[45]['shipping1'] = 600;

$shipping_list[45]['shipping2'] = 500;
$shipping_list[45]['daibiki1'] = 500;
$shipping_list[45]['daibiki2'] = 0;

$shipping_list[46]['id'] = 46;

$shipping_list[46]['pref'] = "���츩";

$shipping_list[46]['shipping1'] = 1000;

$shipping_list[46]['shipping2'] = 500;
$shipping_list[46]['daibiki1'] = 500;
$shipping_list[46]['daibiki2'] = 0;
?>
