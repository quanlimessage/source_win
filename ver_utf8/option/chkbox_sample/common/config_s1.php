<?php


/*php5.5以前のバージョンは対応していないので各S系などのconfigファイルの行頭に読み込む、このファイルを使用する時はlib/array_column.phpも同時に設置して下さい。*/

require_once("lib/array_column.php");	//取り出し用の配列関数の読込




/*チェックボックスの配列configファイルの下部で作っておく、この配列はback_officeと表画面で使用するのでここで管理します*/

//チェックボックスの配列
$ICON_MST = array(
	array('id' => '1', 'name' => '赤'),
	array('id' => '2', 'name' => '青'),
	array('id' => '3', 'name' => '黄色'),
	array('id' => '4', 'name' => '緑'),
	array('id' => '5', 'name' => '黒'),
);

//nameをid添字で取り出せる配列を作っておく
$ICON_NAME_LST = array_column($ICON_MST, 'name', 'id');
?>
