<?php
/******************************************************************************************
 都道府県情報＆送料管理	※配列の状態で管理
 2004/7/26 Yossee
******************************************************************************************/

#------------------------------------------------------------------------------
# 都道府県リスト１ ※単純な都道府県情報のみ
#------------------------------------------------------------------------------
$pref_list = array(
	0 => "北海道",
	1 => "青森県",
	2 => "岩手県",
	3 => "宮城県",
	4 => "秋田県",
	5 => "山形県",
	6 => "福島県",
	7 => "茨城県",
	8 => "栃木県",
	9 => "群馬県",
	10 => "埼玉県",
	11 => "千葉県",
	12 => "東京都",
	13 => "神奈川県",
	14 => "新潟県",
	15 => "富山県",
	16 => "石川県",
	17 => "福井県",
	18 => "山梨県",
	19 => "長野県",
	20 => "岐阜県",
	21 => "静岡県",
	22 => "愛知県",
	23 => "三重県",
	24 => "滋賀県",
	25 => "京都府",
	26 => "大阪府",
	27 => "兵庫県",
	28 => "奈良県",
	29 => "和歌山県",
	30 => "鳥取県",
	31 => "島根県",
	32 => "岡山県",
	33 => "広島県",
	34 => "山口県",
	35 => "徳島県",
	36 => "香川県",
	37 => "愛媛県",
	38 => "高知県",
	39 => "福岡県",
	40 => "佐賀県",
	41 => "長崎県",
	42 => "熊本県",
	43 => "大分県",
	44 => "宮崎県",
	45 => "鹿児島県",
	46 => "沖縄県"
);

#------------------------------------------------------------------------------
# 都道府県リスト２ ※optgroup対応（地方情報を付随）
#------------------------------------------------------------------------------
$pref_local_list["北海道・東北"][0] = "北海道";
$pref_local_list["北海道・東北"][1] = "青森県";
$pref_local_list["北海道・東北"][2] = "岩手県";
$pref_local_list["北海道・東北"][3] = "秋田県";
$pref_local_list["北海道・東北"][4] = "宮城県";
$pref_local_list["北海道・東北"][5] = "山形県";
$pref_local_list["北海道・東北"][6] = "福島県";

$pref_local_list["関東・甲信越"][0] = "東京都";
$pref_local_list["関東・甲信越"][1] = "神奈川県";
$pref_local_list["関東・甲信越"][2] = "埼玉県";
$pref_local_list["関東・甲信越"][3] = "千葉県";
$pref_local_list["関東・甲信越"][4] = "茨城県";
$pref_local_list["関東・甲信越"][5] = "栃木県";
$pref_local_list["関東・甲信越"][6] = "群馬県";
$pref_local_list["関東・甲信越"][7] = "山梨県";
$pref_local_list["関東・甲信越"][8] = "長野県";

$pref_local_list["東海・北陸"][0] = "静岡県";
$pref_local_list["東海・北陸"][1] = "愛知県";
$pref_local_list["東海・北陸"][2] = "岐阜県";
$pref_local_list["東海・北陸"][3] = "新潟県";
$pref_local_list["東海・北陸"][4] = "富山県";
$pref_local_list["東海・北陸"][5] = "石川県";
$pref_local_list["東海・北陸"][6] = "福井県";

$pref_local_list["近畿"][0] = "三重県";
$pref_local_list["近畿"][1] = "滋賀県";
$pref_local_list["近畿"][2] = "京都府";
$pref_local_list["近畿"][3] = "大阪府";
$pref_local_list["近畿"][4] = "兵庫県";
$pref_local_list["近畿"][5] = "奈良県";
$pref_local_list["近畿"][6] = "和歌山県";

$pref_local_list["中国・四国"][0] = "鳥取県";
$pref_local_list["中国・四国"][1] = "島根県";
$pref_local_list["中国・四国"][2] = "岡山県";
$pref_local_list["中国・四国"][3] = "広島県";
$pref_local_list["中国・四国"][4] = "山口県";
$pref_local_list["中国・四国"][5] = "徳島県";
$pref_local_list["中国・四国"][6] = "香川県";
$pref_local_list["中国・四国"][7] = "愛媛県";
$pref_local_list["中国・四国"][8] = "高知県";

$pref_local_list["九州・沖縄"][0] = "福岡県";
$pref_local_list["九州・沖縄"][1] = "佐賀県";
$pref_local_list["九州・沖縄"][2] = "長崎県";
$pref_local_list["九州・沖縄"][3] = "熊本県";
$pref_local_list["九州・沖縄"][4] = "大分県";
$pref_local_list["九州・沖縄"][5] = "宮崎県";
$pref_local_list["九州・沖縄"][6] = "鹿児島県";
$pref_local_list["九州・沖縄"][7] = "沖縄県";

#------------------------------------------------------------------------------
# 都道府県リスト３ ※都道府県情報と送料を多次元配列で格納（ID情報も付加）
#	※要素番号は都道府県リスト１($pref_list)と同じ
#		shipping1：通常送料
#		shipping2：割引後送料
#		daibiki1：割引後送料
#		daibiki2：割引後送料
#------------------------------------------------------------------------------
$shipping_list[0]['id'] = 0;
$shipping_list[0]['pref'] = "北海道";
$shipping_list[0]['shipping1'] = 1000;
$shipping_list[0]['shipping2'] = 500;
$shipping_list[0]['daibiki1'] = 500;
$shipping_list[0]['daibiki2'] = 0;

$shipping_list[1]['id'] = 1;
$shipping_list[1]['pref'] = "青森県";
$shipping_list[1]['shipping1'] = 600;
$shipping_list[1]['shipping2'] = 500;
$shipping_list[1]['daibiki1'] = 500;
$shipping_list[1]['daibiki2'] = 0;

$shipping_list[2]['id'] = 2;
$shipping_list[2]['pref'] = "岩手県";
$shipping_list[2]['shipping1'] = 600;
$shipping_list[2]['shipping2'] = 500;
$shipping_list[2]['daibiki1'] = 500;
$shipping_list[2]['daibiki2'] = 0;

$shipping_list[3]['id'] = 3;
$shipping_list[3]['pref'] = "宮城県";
$shipping_list[3]['shipping1'] = 600;
$shipping_list[3]['shipping2'] = 500;
$shipping_list[3]['daibiki1'] = 500;
$shipping_list[3]['daibiki2'] = 0;

$shipping_list[4]['id'] = 4;
$shipping_list[4]['pref'] = "秋田県";
$shipping_list[4]['shipping1'] = 600;
$shipping_list[4]['shipping2'] = 500;
$shipping_list[4]['daibiki1'] = 500;
$shipping_list[4]['daibiki2'] = 0;

$shipping_list[5]['id'] = 5;
$shipping_list[5]['pref'] = "山形県";
$shipping_list[5]['shipping1'] = 600;
$shipping_list[5]['shipping2'] = 500;
$shipping_list[5]['daibiki1'] = 500;
$shipping_list[5]['daibiki2'] = 0;

$shipping_list[6]['id'] = 6;
$shipping_list[6]['pref'] = "福島県";
$shipping_list[6]['shipping1'] = 600;
$shipping_list[6]['shipping2'] = 500;
$shipping_list[6]['daibiki1'] = 500;
$shipping_list[6]['daibiki2'] = 0;

$shipping_list[7]['id'] = 7;
$shipping_list[7]['pref'] = "茨城県";
$shipping_list[7]['shipping1'] = 600;
$shipping_list[7]['shipping2'] = 500;
$shipping_list[7]['daibiki1'] = 500;
$shipping_list[7]['daibiki2'] = 0;

$shipping_list[8]['id'] = 8;
$shipping_list[8]['pref'] = "栃木県";
$shipping_list[8]['shipping1'] = 600;
$shipping_list[8]['shipping2'] = 500;
$shipping_list[8]['daibiki1'] = 500;
$shipping_list[8]['daibiki2'] = 0;

$shipping_list[9]['id'] = 9;
$shipping_list[9]['pref'] = "群馬県";
$shipping_list[9]['shipping1'] = 600;
$shipping_list[9]['shipping2'] = 500;
$shipping_list[9]['daibiki1'] = 500;
$shipping_list[9]['daibiki2'] = 0;

$shipping_list[10]['id'] = 10;
$shipping_list[10]['pref'] = "埼玉県";
$shipping_list[10]['shipping1'] = 600;
$shipping_list[10]['shipping2'] = 500;
$shipping_list[10]['daibiki1'] = 500;
$shipping_list[10]['daibiki2'] = 0;

$shipping_list[11]['id'] = 11;
$shipping_list[11]['pref'] = "千葉県";
$shipping_list[11]['shipping1'] = 600;
$shipping_list[11]['shipping2'] = 500;
$shipping_list[11]['daibiki1'] = 500;
$shipping_list[11]['daibiki2'] = 0;

$shipping_list[12]['id'] = 12;
$shipping_list[12]['pref'] = "東京都";
$shipping_list[12]['shipping1'] = 600;
$shipping_list[12]['shipping2'] = 500;
$shipping_list[12]['daibiki1'] = 500;
$shipping_list[12]['daibiki2'] = 0;

$shipping_list[13]['id'] = 13;
$shipping_list[13]['pref'] = "神奈川県";
$shipping_list[13]['shipping1'] = 600;
$shipping_list[13]['shipping2'] = 500;
$shipping_list[13]['daibiki1'] = 500;
$shipping_list[13]['daibiki2'] = 0;

$shipping_list[14]['id'] = 14;
$shipping_list[14]['pref'] = "新潟県";
$shipping_list[14]['shipping1'] = 600;
$shipping_list[14]['shipping2'] = 500;
$shipping_list[14]['daibiki1'] = 500;
$shipping_list[14]['daibiki2'] = 0;

$shipping_list[15]['id'] = 15;
$shipping_list[15]['pref'] = "富山県";
$shipping_list[15]['shipping1'] = 600;
$shipping_list[15]['shipping2'] = 500;
$shipping_list[15]['daibiki1'] = 500;
$shipping_list[15]['daibiki2'] = 0;

$shipping_list[16]['id'] = 16;
$shipping_list[16]['pref'] = "石川県";
$shipping_list[16]['shipping1'] = 600;
$shipping_list[16]['shipping2'] = 500;
$shipping_list[16]['daibiki1'] = 500;
$shipping_list[16]['daibiki2'] = 0;

$shipping_list[17]['id'] = 17;
$shipping_list[17]['pref'] = "福井県";
$shipping_list[17]['shipping1'] = 600;
$shipping_list[17]['shipping2'] = 500;
$shipping_list[17]['daibiki1'] = 500;
$shipping_list[17]['daibiki2'] = 0;

$shipping_list[18]['id'] = 18;
$shipping_list[18]['pref'] = "山梨県";
$shipping_list[18]['shipping1'] = 600;
$shipping_list[18]['shipping2'] = 500;
$shipping_list[18]['daibiki1'] = 500;
$shipping_list[18]['daibiki2'] = 0;

$shipping_list[19]['id'] = 19;
$shipping_list[19]['pref'] = "長野県";
$shipping_list[19]['shipping1'] = 600;
$shipping_list[19]['shipping2'] = 500;
$shipping_list[19]['daibiki1'] = 500;
$shipping_list[19]['daibiki2'] = 0;

$shipping_list[20]['id'] = 20;
$shipping_list[20]['pref'] = "岐阜県";
$shipping_list[20]['shipping1'] = 600;
$shipping_list[20]['shipping2'] = 500;
$shipping_list[20]['daibiki1'] = 500;
$shipping_list[20]['daibiki2'] = 0;

$shipping_list[21]['id'] = 21;
$shipping_list[21]['pref'] = "静岡県";
$shipping_list[21]['shipping1'] = 600;
$shipping_list[21]['shipping2'] = 500;
$shipping_list[21]['daibiki1'] = 500;
$shipping_list[21]['daibiki2'] = 0;

$shipping_list[22]['id'] = 22;
$shipping_list[22]['pref'] = "愛知県";
$shipping_list[22]['shipping1'] = 600;
$shipping_list[22]['shipping2'] = 500;
$shipping_list[22]['daibiki1'] = 500;
$shipping_list[22]['daibiki2'] = 0;

$shipping_list[23]['id'] = 23;
$shipping_list[23]['pref'] = "三重県";
$shipping_list[23]['shipping1'] = 600;
$shipping_list[23]['shipping2'] = 500;
$shipping_list[23]['daibiki1'] = 500;
$shipping_list[23]['daibiki2'] = 0;

$shipping_list[24]['id'] = 24;
$shipping_list[24]['pref'] = "滋賀県";
$shipping_list[24]['shipping1'] = 600;
$shipping_list[24]['shipping2'] = 500;
$shipping_list[24]['daibiki1'] = 500;
$shipping_list[24]['daibiki2'] = 0;

$shipping_list[25]['id'] = 25;
$shipping_list[25]['pref'] = "京都府";
$shipping_list[25]['shipping1'] = 600;
$shipping_list[25]['shipping2'] = 500;
$shipping_list[25]['daibiki1'] = 500;
$shipping_list[25]['daibiki2'] = 0;

$shipping_list[26]['id'] = 26;
$shipping_list[26]['pref'] = "大阪府";
$shipping_list[26]['shipping1'] = 600;
$shipping_list[26]['shipping2'] = 500;
$shipping_list[26]['daibiki1'] = 500;
$shipping_list[26]['daibiki2'] = 0;

$shipping_list[27]['id'] = 27;
$shipping_list[27]['pref'] = "兵庫県";
$shipping_list[27]['shipping1'] = 600;
$shipping_list[27]['shipping2'] = 500;
$shipping_list[27]['daibiki1'] = 500;
$shipping_list[27]['daibiki2'] = 0;

$shipping_list[28]['id'] = 28;
$shipping_list[28]['pref'] = "奈良県";
$shipping_list[28]['shipping1'] = 600;
$shipping_list[28]['shipping2'] = 500;
$shipping_list[28]['daibiki1'] = 500;
$shipping_list[28]['daibiki2'] = 0;

$shipping_list[29]['id'] = 29;
$shipping_list[29]['pref'] = "和歌山県";
$shipping_list[29]['shipping1'] = 600;
$shipping_list[29]['shipping2'] = 500;
$shipping_list[29]['daibiki1'] = 500;
$shipping_list[29]['daibiki2'] = 0;

$shipping_list[30]['id'] = 30;
$shipping_list[30]['pref'] = "鳥取県";
$shipping_list[30]['shipping1'] = 600;
$shipping_list[30]['shipping2'] = 500;
$shipping_list[30]['daibiki1'] = 500;
$shipping_list[30]['daibiki2'] = 0;

$shipping_list[31]['id'] = 31;
$shipping_list[31]['pref'] = "島根県";
$shipping_list[31]['shipping1'] = 600;
$shipping_list[31]['shipping2'] = 500;
$shipping_list[31]['daibiki1'] = 500;
$shipping_list[31]['daibiki2'] = 0;

$shipping_list[32]['id'] = 32;
$shipping_list[32]['pref'] = "岡山県";
$shipping_list[32]['shipping1'] = 600;
$shipping_list[32]['shipping2'] = 500;
$shipping_list[32]['daibiki1'] = 500;
$shipping_list[32]['daibiki2'] = 0;

$shipping_list[33]['id'] = 33;
$shipping_list[33]['pref'] = "広島県";
$shipping_list[33]['shipping1'] = 600;
$shipping_list[33]['shipping2'] = 500;
$shipping_list[33]['daibiki1'] = 500;
$shipping_list[33]['daibiki2'] = 0;

$shipping_list[34]['id'] = 34;
$shipping_list[34]['pref'] = "山口県";
$shipping_list[34]['shipping1'] = 600;
$shipping_list[34]['shipping2'] = 500;
$shipping_list[34]['daibiki1'] = 500;
$shipping_list[34]['daibiki2'] = 0;

$shipping_list[35]['id'] = 35;
$shipping_list[35]['pref'] = "徳島県";
$shipping_list[35]['shipping1'] = 600;
$shipping_list[35]['shipping2'] = 500;
$shipping_list[35]['daibiki1'] = 500;
$shipping_list[35]['daibiki2'] = 0;

$shipping_list[36]['id'] = 36;
$shipping_list[36]['pref'] = "香川県";
$shipping_list[36]['shipping1'] = 600;
$shipping_list[36]['shipping2'] = 500;
$shipping_list[36]['daibiki1'] = 500;
$shipping_list[36]['daibiki2'] = 0;

$shipping_list[37]['id'] = 37;
$shipping_list[37]['pref'] = "愛媛県";
$shipping_list[37]['shipping1'] = 600;
$shipping_list[37]['shipping2'] = 500;
$shipping_list[37]['daibiki1'] = 500;
$shipping_list[37]['daibiki2'] = 0;

$shipping_list[38]['id'] = 38;
$shipping_list[38]['pref'] = "高知県";
$shipping_list[38]['shipping1'] = 600;
$shipping_list[38]['shipping2'] = 500;
$shipping_list[38]['daibiki1'] = 500;
$shipping_list[38]['daibiki2'] = 0;

$shipping_list[39]['id'] = 39;
$shipping_list[39]['pref'] = "福岡県";
$shipping_list[39]['shipping1'] = 600;
$shipping_list[39]['shipping2'] = 500;
$shipping_list[39]['daibiki1'] = 500;
$shipping_list[39]['daibiki2'] = 0;

$shipping_list[40]['id'] = 40;
$shipping_list[40]['pref'] = "佐賀県";
$shipping_list[40]['shipping1'] = 600;
$shipping_list[40]['shipping2'] = 500;
$shipping_list[40]['daibiki1'] = 500;
$shipping_list[40]['daibiki2'] = 0;

$shipping_list[41]['id'] = 41;
$shipping_list[41]['pref'] = "長崎県";
$shipping_list[41]['shipping1'] = 600;
$shipping_list[41]['shipping2'] = 500;
$shipping_list[41]['daibiki1'] = 500;
$shipping_list[41]['daibiki2'] = 0;

$shipping_list[42]['id'] = 42;
$shipping_list[42]['pref'] = "熊本県";
$shipping_list[42]['shipping1'] = 600;
$shipping_list[42]['shipping2'] = 500;
$shipping_list[42]['daibiki1'] = 500;
$shipping_list[42]['daibiki2'] = 0;

$shipping_list[43]['id'] = 43;
$shipping_list[43]['pref'] = "大分県";
$shipping_list[43]['shipping1'] = 600;
$shipping_list[43]['shipping2'] = 500;
$shipping_list[43]['daibiki1'] = 500;
$shipping_list[43]['daibiki2'] = 0;

$shipping_list[44]['id'] = 44;
$shipping_list[44]['pref'] = "宮崎県";
$shipping_list[44]['shipping1'] = 600;
$shipping_list[44]['shipping2'] = 500;
$shipping_list[44]['daibiki1'] = 500;
$shipping_list[44]['daibiki2'] = 0;

$shipping_list[45]['id'] = 45;
$shipping_list[45]['pref'] = "鹿児島県";
$shipping_list[45]['shipping1'] = 600;
$shipping_list[45]['shipping2'] = 500;
$shipping_list[45]['daibiki1'] = 500;
$shipping_list[45]['daibiki2'] = 0;

$shipping_list[46]['id'] = 46;
$shipping_list[46]['pref'] = "沖縄県";
$shipping_list[46]['shipping1'] = 1000;
$shipping_list[46]['shipping2'] = 500;
$shipping_list[46]['daibiki1'] = 500;
$shipping_list[46]['daibiki2'] = 0;
?>
