<?php
/******************************************************************************************
 ÅÔÆ»ÉÜ¸©¾ðÊó¡õÁ÷ÎÁ´ÉÍý	¢¨ÇÛÎó¤Î¾õÂÖ¤Ç´ÉÍý
 2004/7/26 Yossee
******************************************************************************************/

#------------------------------------------------------------------------------
# ÅÔÆ»ÉÜ¸©¥ê¥¹¥È£± ¢¨Ã±½ã¤ÊÅÔÆ»ÉÜ¸©¾ðÊó¤Î¤ß
#------------------------------------------------------------------------------
$pref_list = array(
	0 => "ËÌ³¤Æ»",
	1 => "ÀÄ¿¹¸©",

	2 => "´ä¼ê¸©",

	3 => "µÜ¾ë¸©",

	4 => "½©ÅÄ¸©",

	5 => "»³·Á¸©",

	6 => "Ê¡Åç¸©",

	7 => "°ñ¾ë¸©",

	8 => "ÆÊÌÚ¸©",
	9 => "·²ÇÏ¸©",
	10 => "ºë¶Ì¸©",
	11 => "ÀéÍÕ¸©",
	12 => "ÅìµþÅÔ",
	13 => "¿ÀÆàÀî¸©",
	14 => "¿·³ã¸©",
	15 => "ÉÙ»³¸©",
	16 => "ÀÐÀî¸©",
	17 => "Ê¡°æ¸©",
	18 => "»³Íü¸©",
	19 => "Ä¹Ìî¸©",
	20 => "´ôÉì¸©",
	21 => "ÀÅ²¬¸©",
	22 => "°¦ÃÎ¸©",
	23 => "»°½Å¸©",
	24 => "¼¢²ì¸©",
	25 => "µþÅÔÉÜ",

	26 => "ÂçºåÉÜ",

	27 => "Ê¼¸Ë¸©",

	28 => "ÆàÎÉ¸©",
	29 => "ÏÂ²Î»³¸©",

	30 => "Ä»¼è¸©",

	31 => "Åçº¬¸©",

	32 => "²¬»³¸©",

	33 => "¹­Åç¸©",

	34 => "»³¸ý¸©",

	35 => "ÆÁÅç¸©",

	36 => "¹áÀî¸©",

	37 => "°¦É²¸©",

	38 => "¹âÃÎ¸©",
	39 => "Ê¡²¬¸©",
	40 => "º´²ì¸©",
	41 => "Ä¹ºê¸©",
	42 => "·§ËÜ¸©",
	43 => "ÂçÊ¬¸©",
	44 => "µÜºê¸©",
	45 => "¼¯»ùÅç¸©",
	46 => "²­Æì¸©"
);

#------------------------------------------------------------------------------
# ÅÔÆ»ÉÜ¸©¥ê¥¹¥È£² ¢¨optgroupÂÐ±þ¡ÊÃÏÊý¾ðÊó¤òÉÕ¿ï¡Ë
#------------------------------------------------------------------------------
$pref_local_list["ËÌ³¤Æ»¡¦ÅìËÌ"][0] = "ËÌ³¤Æ»";
$pref_local_list["ËÌ³¤Æ»¡¦ÅìËÌ"][1] = "ÀÄ¿¹¸©";
$pref_local_list["ËÌ³¤Æ»¡¦ÅìËÌ"][2] = "´ä¼ê¸©";
$pref_local_list["ËÌ³¤Æ»¡¦ÅìËÌ"][3] = "½©ÅÄ¸©";
$pref_local_list["ËÌ³¤Æ»¡¦ÅìËÌ"][4] = "µÜ¾ë¸©";
$pref_local_list["ËÌ³¤Æ»¡¦ÅìËÌ"][5] = "»³·Á¸©";
$pref_local_list["ËÌ³¤Æ»¡¦ÅìËÌ"][6] = "Ê¡Åç¸©";

$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][0] = "ÅìµþÅÔ";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][1] = "¿ÀÆàÀî¸©";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][2] = "ºë¶Ì¸©";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][3] = "ÀéÍÕ¸©";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][4] = "°ñ¾ë¸©";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][5] = "ÆÊÌÚ¸©";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][6] = "·²ÇÏ¸©";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][7] = "»³Íü¸©";
$pref_local_list["´ØÅì¡¦¹Ã¿®±Û"][8] = "Ä¹Ìî¸©";

$pref_local_list["Åì³¤¡¦ËÌÎ¦"][0] = "ÀÅ²¬¸©";
$pref_local_list["Åì³¤¡¦ËÌÎ¦"][1] = "°¦ÃÎ¸©";
$pref_local_list["Åì³¤¡¦ËÌÎ¦"][2] = "´ôÉì¸©";
$pref_local_list["Åì³¤¡¦ËÌÎ¦"][3] = "¿·³ã¸©";
$pref_local_list["Åì³¤¡¦ËÌÎ¦"][4] = "ÉÙ»³¸©";
$pref_local_list["Åì³¤¡¦ËÌÎ¦"][5] = "ÀÐÀî¸©";
$pref_local_list["Åì³¤¡¦ËÌÎ¦"][6] = "Ê¡°æ¸©";

$pref_local_list["¶áµ¦"][0] = "»°½Å¸©";
$pref_local_list["¶áµ¦"][1] = "¼¢²ì¸©";
$pref_local_list["¶áµ¦"][2] = "µþÅÔÉÜ";
$pref_local_list["¶áµ¦"][3] = "ÂçºåÉÜ";
$pref_local_list["¶áµ¦"][4] = "Ê¼¸Ë¸©";
$pref_local_list["¶áµ¦"][5] = "ÆàÎÉ¸©";
$pref_local_list["¶áµ¦"][6] = "ÏÂ²Î»³¸©";

$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][0] = "Ä»¼è¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][1] = "Åçº¬¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][2] = "²¬»³¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][3] = "¹­Åç¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][4] = "»³¸ý¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][5] = "ÆÁÅç¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][6] = "¹áÀî¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][7] = "°¦É²¸©";
$pref_local_list["Ãæ¹ñ¡¦»Í¹ñ"][8] = "¹âÃÎ¸©";

$pref_local_list["¶å½£¡¦²­Æì"][0] = "Ê¡²¬¸©";
$pref_local_list["¶å½£¡¦²­Æì"][1] = "º´²ì¸©";
$pref_local_list["¶å½£¡¦²­Æì"][2] = "Ä¹ºê¸©";
$pref_local_list["¶å½£¡¦²­Æì"][3] = "·§ËÜ¸©";
$pref_local_list["¶å½£¡¦²­Æì"][4] = "ÂçÊ¬¸©";
$pref_local_list["¶å½£¡¦²­Æì"][5] = "µÜºê¸©";
$pref_local_list["¶å½£¡¦²­Æì"][6] = "¼¯»ùÅç¸©";
$pref_local_list["¶å½£¡¦²­Æì"][7] = "²­Æì¸©";

#------------------------------------------------------------------------------
# ÅÔÆ»ÉÜ¸©¥ê¥¹¥È£³ ¢¨ÅÔÆ»ÉÜ¸©¾ðÊó¤ÈÁ÷ÎÁ¤òÂ¿¼¡¸µÇÛÎó¤Ç³ÊÇ¼¡ÊID¾ðÊó¤âÉÕ²Ã¡Ë
#	¢¨Í×ÁÇÈÖ¹æ¤ÏÅÔÆ»ÉÜ¸©¥ê¥¹¥È£±($pref_list)¤ÈÆ±¤¸
#		shipping1¡§ÄÌ¾ïÁ÷ÎÁ
#		shipping2¡§³ä°ú¸åÁ÷ÎÁ
#		daibiki1¡§³ä°ú¸åÁ÷ÎÁ
#		daibiki2¡§³ä°ú¸åÁ÷ÎÁ
#------------------------------------------------------------------------------
$shipping_list[0]['id'] = 0;

$shipping_list[0]['pref'] = "ËÌ³¤Æ»";
$shipping_list[0]['shipping1'] = 1000;
$shipping_list[0]['shipping2'] = 500;
$shipping_list[0]['daibiki1'] = 500;
$shipping_list[0]['daibiki2'] = 0;

$shipping_list[1]['id'] = 1;

$shipping_list[1]['pref'] = "ÀÄ¿¹¸©";
$shipping_list[1]['shipping1'] = 600;

$shipping_list[1]['shipping2'] = 500;
$shipping_list[1]['daibiki1'] = 500;
$shipping_list[1]['daibiki2'] = 0;

$shipping_list[2]['id'] = 2;

$shipping_list[2]['pref'] = "´ä¼ê¸©";

$shipping_list[2]['shipping1'] = 600;
$shipping_list[2]['shipping2'] = 500;
$shipping_list[2]['daibiki1'] = 500;
$shipping_list[2]['daibiki2'] = 0;

$shipping_list[3]['id'] = 3;

$shipping_list[3]['pref'] = "µÜ¾ë¸©";

$shipping_list[3]['shipping1'] = 600;

$shipping_list[3]['shipping2'] = 500;
$shipping_list[3]['daibiki1'] = 500;
$shipping_list[3]['daibiki2'] = 0;

$shipping_list[4]['id'] = 4;

$shipping_list[4]['pref'] = "½©ÅÄ¸©";

$shipping_list[4]['shipping1'] = 600;

$shipping_list[4]['shipping2'] = 500;
$shipping_list[4]['daibiki1'] = 500;
$shipping_list[4]['daibiki2'] = 0;

$shipping_list[5]['id'] = 5;

$shipping_list[5]['pref'] = "»³·Á¸©";

$shipping_list[5]['shipping1'] = 600;

$shipping_list[5]['shipping2'] = 500;
$shipping_list[5]['daibiki1'] = 500;
$shipping_list[5]['daibiki2'] = 0;

$shipping_list[6]['id'] = 6;

$shipping_list[6]['pref'] = "Ê¡Åç¸©";

$shipping_list[6]['shipping1'] = 600;

$shipping_list[6]['shipping2'] = 500;
$shipping_list[6]['daibiki1'] = 500;
$shipping_list[6]['daibiki2'] = 0;

$shipping_list[7]['id'] = 7;

$shipping_list[7]['pref'] = "°ñ¾ë¸©";

$shipping_list[7]['shipping1'] = 600;
$shipping_list[7]['shipping2'] = 500;
$shipping_list[7]['daibiki1'] = 500;
$shipping_list[7]['daibiki2'] = 0;

$shipping_list[8]['id'] = 8;

$shipping_list[8]['pref'] = "ÆÊÌÚ¸©";

$shipping_list[8]['shipping1'] = 600;

$shipping_list[8]['shipping2'] = 500;
$shipping_list[8]['daibiki1'] = 500;
$shipping_list[8]['daibiki2'] = 0;

$shipping_list[9]['id'] = 9;

$shipping_list[9]['pref'] = "·²ÇÏ¸©";

$shipping_list[9]['shipping1'] = 600;
$shipping_list[9]['shipping2'] = 500;
$shipping_list[9]['daibiki1'] = 500;
$shipping_list[9]['daibiki2'] = 0;

$shipping_list[10]['id'] = 10;

$shipping_list[10]['pref'] = "ºë¶Ì¸©";

$shipping_list[10]['shipping1'] = 600;
$shipping_list[10]['shipping2'] = 500;
$shipping_list[10]['daibiki1'] = 500;
$shipping_list[10]['daibiki2'] = 0;

$shipping_list[11]['id'] = 11;

$shipping_list[11]['pref'] = "ÀéÍÕ¸©";

$shipping_list[11]['shipping1'] = 600;

$shipping_list[11]['shipping2'] = 500;
$shipping_list[11]['daibiki1'] = 500;
$shipping_list[11]['daibiki2'] = 0;

$shipping_list[12]['id'] = 12;

$shipping_list[12]['pref'] = "ÅìµþÅÔ";

$shipping_list[12]['shipping1'] = 600;

$shipping_list[12]['shipping2'] = 500;
$shipping_list[12]['daibiki1'] = 500;
$shipping_list[12]['daibiki2'] = 0;

$shipping_list[13]['id'] = 13;

$shipping_list[13]['pref'] = "¿ÀÆàÀî¸©";

$shipping_list[13]['shipping1'] = 600;
$shipping_list[13]['shipping2'] = 500;
$shipping_list[13]['daibiki1'] = 500;
$shipping_list[13]['daibiki2'] = 0;

$shipping_list[14]['id'] = 14;

$shipping_list[14]['pref'] = "¿·³ã¸©";

$shipping_list[14]['shipping1'] = 600;
$shipping_list[14]['shipping2'] = 500;
$shipping_list[14]['daibiki1'] = 500;
$shipping_list[14]['daibiki2'] = 0;

$shipping_list[15]['id'] = 15;

$shipping_list[15]['pref'] = "ÉÙ»³¸©";

$shipping_list[15]['shipping1'] = 600;

$shipping_list[15]['shipping2'] = 500;
$shipping_list[15]['daibiki1'] = 500;
$shipping_list[15]['daibiki2'] = 0;

$shipping_list[16]['id'] = 16;

$shipping_list[16]['pref'] = "ÀÐÀî¸©";

$shipping_list[16]['shipping1'] = 600;
$shipping_list[16]['shipping2'] = 500;
$shipping_list[16]['daibiki1'] = 500;
$shipping_list[16]['daibiki2'] = 0;

$shipping_list[17]['id'] = 17;

$shipping_list[17]['pref'] = "Ê¡°æ¸©";

$shipping_list[17]['shipping1'] = 600;

$shipping_list[17]['shipping2'] = 500;
$shipping_list[17]['daibiki1'] = 500;
$shipping_list[17]['daibiki2'] = 0;

$shipping_list[18]['id'] = 18;

$shipping_list[18]['pref'] = "»³Íü¸©";

$shipping_list[18]['shipping1'] = 600;
$shipping_list[18]['shipping2'] = 500;
$shipping_list[18]['daibiki1'] = 500;
$shipping_list[18]['daibiki2'] = 0;

$shipping_list[19]['id'] = 19;

$shipping_list[19]['pref'] = "Ä¹Ìî¸©";

$shipping_list[19]['shipping1'] = 600;
$shipping_list[19]['shipping2'] = 500;
$shipping_list[19]['daibiki1'] = 500;
$shipping_list[19]['daibiki2'] = 0;

$shipping_list[20]['id'] = 20;

$shipping_list[20]['pref'] = "´ôÉì¸©";

$shipping_list[20]['shipping1'] = 600;
$shipping_list[20]['shipping2'] = 500;
$shipping_list[20]['daibiki1'] = 500;
$shipping_list[20]['daibiki2'] = 0;

$shipping_list[21]['id'] = 21;

$shipping_list[21]['pref'] = "ÀÅ²¬¸©";

$shipping_list[21]['shipping1'] = 600;
$shipping_list[21]['shipping2'] = 500;
$shipping_list[21]['daibiki1'] = 500;
$shipping_list[21]['daibiki2'] = 0;

$shipping_list[22]['id'] = 22;

$shipping_list[22]['pref'] = "°¦ÃÎ¸©";

$shipping_list[22]['shipping1'] = 600;
$shipping_list[22]['shipping2'] = 500;
$shipping_list[22]['daibiki1'] = 500;
$shipping_list[22]['daibiki2'] = 0;

$shipping_list[23]['id'] = 23;

$shipping_list[23]['pref'] = "»°½Å¸©";

$shipping_list[23]['shipping1'] = 600;
$shipping_list[23]['shipping2'] = 500;
$shipping_list[23]['daibiki1'] = 500;
$shipping_list[23]['daibiki2'] = 0;

$shipping_list[24]['id'] = 24;

$shipping_list[24]['pref'] = "¼¢²ì¸©";

$shipping_list[24]['shipping1'] = 600;
$shipping_list[24]['shipping2'] = 500;
$shipping_list[24]['daibiki1'] = 500;
$shipping_list[24]['daibiki2'] = 0;

$shipping_list[25]['id'] = 25;

$shipping_list[25]['pref'] = "µþÅÔÉÜ";

$shipping_list[25]['shipping1'] = 600;

$shipping_list[25]['shipping2'] = 500;
$shipping_list[25]['daibiki1'] = 500;
$shipping_list[25]['daibiki2'] = 0;

$shipping_list[26]['id'] = 26;

$shipping_list[26]['pref'] = "ÂçºåÉÜ";

$shipping_list[26]['shipping1'] = 600;

$shipping_list[26]['shipping2'] = 500;
$shipping_list[26]['daibiki1'] = 500;
$shipping_list[26]['daibiki2'] = 0;

$shipping_list[27]['id'] = 27;

$shipping_list[27]['pref'] = "Ê¼¸Ë¸©";

$shipping_list[27]['shipping1'] = 600;

$shipping_list[27]['shipping2'] = 500;
$shipping_list[27]['daibiki1'] = 500;
$shipping_list[27]['daibiki2'] = 0;

$shipping_list[28]['id'] = 28;

$shipping_list[28]['pref'] = "ÆàÎÉ¸©";

$shipping_list[28]['shipping1'] = 600;
$shipping_list[28]['shipping2'] = 500;
$shipping_list[28]['daibiki1'] = 500;
$shipping_list[28]['daibiki2'] = 0;

$shipping_list[29]['id'] = 29;

$shipping_list[29]['pref'] = "ÏÂ²Î»³¸©";

$shipping_list[29]['shipping1'] = 600;

$shipping_list[29]['shipping2'] = 500;
$shipping_list[29]['daibiki1'] = 500;
$shipping_list[29]['daibiki2'] = 0;

$shipping_list[30]['id'] = 30;

$shipping_list[30]['pref'] = "Ä»¼è¸©";

$shipping_list[30]['shipping1'] = 600;

$shipping_list[30]['shipping2'] = 500;
$shipping_list[30]['daibiki1'] = 500;
$shipping_list[30]['daibiki2'] = 0;

$shipping_list[31]['id'] = 31;

$shipping_list[31]['pref'] = "Åçº¬¸©";

$shipping_list[31]['shipping1'] = 600;

$shipping_list[31]['shipping2'] = 500;
$shipping_list[31]['daibiki1'] = 500;
$shipping_list[31]['daibiki2'] = 0;

$shipping_list[32]['id'] = 32;

$shipping_list[32]['pref'] = "²¬»³¸©";

$shipping_list[32]['shipping1'] = 600;

$shipping_list[32]['shipping2'] = 500;
$shipping_list[32]['daibiki1'] = 500;
$shipping_list[32]['daibiki2'] = 0;

$shipping_list[33]['id'] = 33;

$shipping_list[33]['pref'] = "¹­Åç¸©";

$shipping_list[33]['shipping1'] = 600;

$shipping_list[33]['shipping2'] = 500;
$shipping_list[33]['daibiki1'] = 500;
$shipping_list[33]['daibiki2'] = 0;

$shipping_list[34]['id'] = 34;

$shipping_list[34]['pref'] = "»³¸ý¸©";

$shipping_list[34]['shipping1'] = 600;

$shipping_list[34]['shipping2'] = 500;
$shipping_list[34]['daibiki1'] = 500;
$shipping_list[34]['daibiki2'] = 0;

$shipping_list[35]['id'] = 35;

$shipping_list[35]['pref'] = "ÆÁÅç¸©";

$shipping_list[35]['shipping1'] = 600;

$shipping_list[35]['shipping2'] = 500;
$shipping_list[35]['daibiki1'] = 500;
$shipping_list[35]['daibiki2'] = 0;

$shipping_list[36]['id'] = 36;

$shipping_list[36]['pref'] = "¹áÀî¸©";

$shipping_list[36]['shipping1'] = 600;

$shipping_list[36]['shipping2'] = 500;
$shipping_list[36]['daibiki1'] = 500;
$shipping_list[36]['daibiki2'] = 0;

$shipping_list[37]['id'] = 37;

$shipping_list[37]['pref'] = "°¦É²¸©";

$shipping_list[37]['shipping1'] = 600;

$shipping_list[37]['shipping2'] = 500;
$shipping_list[37]['daibiki1'] = 500;
$shipping_list[37]['daibiki2'] = 0;

$shipping_list[38]['id'] = 38;

$shipping_list[38]['pref'] = "¹âÃÎ¸©";

$shipping_list[38]['shipping1'] = 600;

$shipping_list[38]['shipping2'] = 500;
$shipping_list[38]['daibiki1'] = 500;
$shipping_list[38]['daibiki2'] = 0;

$shipping_list[39]['id'] = 39;

$shipping_list[39]['pref'] = "Ê¡²¬¸©";

$shipping_list[39]['shipping1'] = 600;

$shipping_list[39]['shipping2'] = 500;
$shipping_list[39]['daibiki1'] = 500;
$shipping_list[39]['daibiki2'] = 0;

$shipping_list[40]['id'] = 40;

$shipping_list[40]['pref'] = "º´²ì¸©";

$shipping_list[40]['shipping1'] = 600;

$shipping_list[40]['shipping2'] = 500;
$shipping_list[40]['daibiki1'] = 500;
$shipping_list[40]['daibiki2'] = 0;

$shipping_list[41]['id'] = 41;

$shipping_list[41]['pref'] = "Ä¹ºê¸©";

$shipping_list[41]['shipping1'] = 600;

$shipping_list[41]['shipping2'] = 500;
$shipping_list[41]['daibiki1'] = 500;
$shipping_list[41]['daibiki2'] = 0;

$shipping_list[42]['id'] = 42;

$shipping_list[42]['pref'] = "·§ËÜ¸©";

$shipping_list[42]['shipping1'] = 600;

$shipping_list[42]['shipping2'] = 500;
$shipping_list[42]['daibiki1'] = 500;
$shipping_list[42]['daibiki2'] = 0;

$shipping_list[43]['id'] = 43;

$shipping_list[43]['pref'] = "ÂçÊ¬¸©";

$shipping_list[43]['shipping1'] = 600;

$shipping_list[43]['shipping2'] = 500;
$shipping_list[43]['daibiki1'] = 500;
$shipping_list[43]['daibiki2'] = 0;

$shipping_list[44]['id'] = 44;

$shipping_list[44]['pref'] = "µÜºê¸©";

$shipping_list[44]['shipping1'] = 600;

$shipping_list[44]['shipping2'] = 500;
$shipping_list[44]['daibiki1'] = 500;
$shipping_list[44]['daibiki2'] = 0;

$shipping_list[45]['id'] = 45;

$shipping_list[45]['pref'] = "¼¯»ùÅç¸©";

$shipping_list[45]['shipping1'] = 600;

$shipping_list[45]['shipping2'] = 500;
$shipping_list[45]['daibiki1'] = 500;
$shipping_list[45]['daibiki2'] = 0;

$shipping_list[46]['id'] = 46;

$shipping_list[46]['pref'] = "²­Æì¸©";

$shipping_list[46]['shipping1'] = 1000;

$shipping_list[46]['shipping2'] = 500;
$shipping_list[46]['daibiki1'] = 500;
$shipping_list[46]['daibiki2'] = 0;
?>
