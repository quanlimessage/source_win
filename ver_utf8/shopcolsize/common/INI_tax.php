<?php
/*******************************************************************************
税率に関しての処理
*******************************************************************************/

#-------------------------------------------------------------------------------
#税率の設定情報をdefine定義で使えるようにする
#-------------------------------------------------------------------------------
	function read_shop_tax_config(){
			global $PDO;
		//ショッピングの設定情報を取得する
			$sql_config="
				SELECT
					*
				FROM
					SHOP_TAX_CONFIG_MST
			";

			$fetchSConfig = $PDO -> fetch($sql_config);

		//データベースで取得したデータをdefine定義していく
			foreach($fetchSConfig[0] as $k => $v){
				define($k,$v);
			}

	}

	//処理を実行させる。
	read_shop_tax_config();

#=================================================================================
# 税率の金額の自動計算を行う
#=================================================================================
	function math_tax($price=0){

		////////////////////////////////////////////////////////////////////////
		//消費税の金額を算出(+0.000001は計算の小数点の誤差補正用　((0.1+0.7)*10) =7.999999…)切り上げは関係なし

			////////////////////////////////////////////////////////////////////////
			//消費税の小数点を切り捨ての場合
				if(TAX_FLOAT == "0"){
					$price = floor(($price * TAX / 100)+0.000001)+$price;//金額の消費税

			////////////////////////////////////////////////////////////////////////
			//消費税の小数点を切り上げの場合
				}else{
					$price = ceil(($price * TAX / 100))+$price;//金額の消費税
				}

		return $price;
	}

?>
