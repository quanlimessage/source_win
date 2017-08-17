<?php


	//アイコン情報を配列から平分に変換、区切り文字にカンマを使用
	//念の為、エスケープもします。

	$icon_value = '';
	if(!empty($icons) && is_array($icons)){
		$icon_value = implode(",",$icons);
	}

	$icon_value = utilLib::strRep($icon_value,5);


?>
