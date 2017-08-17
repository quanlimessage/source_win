<?php
/*******************************************************************************
 フォームクラス

 2010/09/29	1.02	addSelect()で、オプションの初期位置を値と同じだった場合に変更しました。
 2010/09/29	1.01	putHtmlItem()で、IDを返すようにしました。
					m.nakamura
 ********************************************************************************/

#=================================================================================
# Formクラス（β版）
#=================================================================================
# ● メソッド 
# 	Form()
#   beginFormArea()
#   endFormArea()
#	add()
#	addGroup
#   add*****()
#	drawItem()
#	drawItemAll()
#	getItem()
#	getItemList()
#	getGroup()
#	getGroupList()
#	putHtmlItem()
#	cnvParam()
#=================================================================================
class Form {
	#=============================================================================
	# メンバ
	#=============================================================================
	// 基本情報
	var $form_name	 	= "";		// フォームname要素
	var $form_action	= "";		// フォームaction要素
	var $form_method	= "";		// フォームmethod要素
	var $form_param 	= array();	// フォームパラメータ
	
	// 項目情報
	var $itemList		= array();
	var $itemId			= 0;
	
	// 項目グループ情報
	var $gruopList		= array();
	var $gruopId		= 0;
	

	#=============================================================================
	# Form()
	# コンストラクタ 
	#=============================================================================
	# * 引数1 ： フォームname要素
	# * 引数2 ： フォームaction要素
	# * 引数3 ： フォームmethod要素
	# * 引数4 ： フォームパラメータ
	#=============================================================================
	function Form($name="",$action="",$method="",$param=array()){
		$this->form_name 	= $name;
		$this->form_action	= $action;
		$this->form_method	= $method;
		$this->form_param	= $param;
	}
	
	
	#=============================================================================
	# beginFormArea()
	# フォームエリアを始める。 
	#=============================================================================
	function beginFormArea(){
		$name = ($this->form_name) ? "name=\"".$this->form_name."\"" : "";
		$method = ($this->form_action) ? "action=\"".$this->form_action."\"" : "";
		$action = ($this->form_method) ? "method=\"".$this->form_method."\"" : "";
		$params = $this->cnvParam($this->form_param);

		$HTML = "<form {$name} {$method} {$action} {$params} >";
		echo $HTML;
	}
	
	
	#=============================================================================
	# beginFormArea()
	# フォームエリアを終わる。 
	#=============================================================================
	function endFormArea(){
		echo "</form>";
	}


	#=============================================================================
	# add()
	# このフォーム内に項目を追加する
	#=============================================================================
	# * 引数1 ： 追加する項目のタイプ
	# * 引数2 ： 追加する項目のHTMLデータ
	#=============================================================================
	function add($type,$html){
		$this->itemId++;
		$this->itemList[$this->itemId] = array("TYPE" => $type,"HTML" => $html);
		return $this->itemId;
	}
	
	#=============================================================================
	# addGroup()
	# このフォーム内に項目を追加する
	#=============================================================================
	# * 引数1 ： 追加するグループ先のID（文字列でも可）
	# * 引数2 ： 追加する項目のID
	#=============================================================================
	function addGroup($gid,$id){
		if(is_array($id)){
			for($i = 0;$i < count($id);$i++){
				$this->groupList[$gid][] = $id[$i];
			}
		}else if(is_numeric($id)){
			$this->groupList[$gid][] = $id;
		}
		return $gid;
	}
	
	#=============================================================================
	# add*****()
	# 追加関数
	#=============================================================================
	# * 引数 （追加関数により違うため、変数名にて補足）
	#      ： $type			... input系のtype要素
	#      ： $name			... name要素
	#      ： $value		... value要素
	#      ： $param		... 配列のキーを要素名とし値を要素値にします。（配列）
	#      ： $checked_flg	... checkedにする場合は 1 
	#      ： $selected_flg	... selectedにする場合は 1 
	#      ： $values		... Select文の、各オプションのvalue要素（配列）
	#      ： $views		... Select文の、各オプションの表示される値（配列）
	#      ： $optparam		... Select文の、各オプションの$param（配列）
	#      ： $selected_opt	... Select文の、初期位置のオプション番号。（整数）
	#=============================================================================
	// ---------------------------------------------------------------------------
	// ■ inputタイプ
	// ---------------------------------------------------------------------------
	function addInput($type,$name="",$value="",$param=array()){
		$type = ($type) ? "type=\"".$type."\"" : "";
		$name = ($name) ? "name=\"".$name."\"" : "";
		$value = ($value) ? "value=\"".$value."\"" : "";
		$params = $this->cnvParam($param);

		$HTML = "<input {$type} {$name} {$value} {$params} >";
		return $this->add("inout/".$type,$HTML);
	}
	// input text
	function addInputText($name="",$value="",$param=array()){
		return $this->addInput("text",$name,$value,$param);
	}
	// input password
	function addInputPass($name="",$value="",$param=array()){
		return $this->addInput("password",$name,$value,$param);
	}
	// input file
	function addInputFile($name="",$param=array()){
		return $this->addInput("file",$name,"",$param);
	}
	// input checkbox
	function addInputCheckBox($name="",$value="",$checked_flg=0,$param=array()){
		if($checked_flg) $param['checked'] = "checked";
		return $this->addInput("checkbox",$name,$value,$param);
	}
	// input radio
	function addInputRadio($name="",$value="",$checked_flg=0,$param=array()){
		if($checked_flg) $param['checked'] = "checked";
		return $this->addInput("radio",$name,$value,$param);
	}
	// input submit
	function addInputSubmit($name="",$value="",$param=array()){
		return $this->addInput("submit",$name,$value,$param);
	}
	// input reset
	function addInputReset($name="",$value="",$param=array()){
		return $this->addInput("reset",$name,$value,$param);
	}
	// input hidden
	function addInputHidden($name="",$value="",$param=array()){
		return $this->addInput("hidden",$name,$value,$param);
	}
	// input button
	function addInputButton($name="",$value="",$param=array()){
		return $this->addInput("button",$name,$value,$param);
	}
	// return input image
	function addInputImage($name="",$value="",$src="",$param=array()){
		if($src) $param["src"] = $src;
		$this->addInput("image",$name,$value,$param);
	}
	
	// ---------------------------------------------------------------------------
	// ■ textaeraタイプ
	// ---------------------------------------------------------------------------
	function addTextArea($name="",$value="",$param=array()){
		$name = ($name) ? "name=\"".$name."\"" : "";
		$value = ($value) ? "".$value."" : "";
		$params = $this->cnvParam($param);

		$HTML = "<textarea {$name} {$params} >{$value}</textarea>";
		return $this->add("textarea",$HTML);
	}
	
	// ---------------------------------------------------------------------------
	// ■ selectタイプ
	// ---------------------------------------------------------------------------
	// 複雑のため配列の型を表記
	// ○$views		一次元配列
	// ○$values	一次元配列
	//
	// ○$optparam	一次元連想配列 or 二次元配列
	//   一次元連想配列の場合、全てのオプションに同じパラメーター設定が行われる。
	//   二次元配列の場合、順番にパラメーターが設定される。
	//
	// ○$optgroup	二次元配列
	//   順番に設定される。
	//   配列のフォーマットは以下、
	//   $array = array(
	//     "idx" = >	数値		.... グループ化される始まりのインデックス
	//     "label" = >	文字列		.... ラベル要素
	//     "param" = >	連想配列	.... 配列のキーを要素名とし値を要素値にします。（配列）
	//   )
	// ---------------------------------------------------------------------------
	function addSelect($name="",$param="",$views=array(),$values=array(),$optparam=array(),$optgroup=array(),$selected_opt=0){
		$name = ($name) ? "name=\"".$name."\"" : "";
		$params = $this->cnvParam($param);

		$HTML = "<select $name $params>";
		
		// valueが配列の場合
		if(is_array($values)){
			$count = (count($values) > count($views)) ? count($values) : count($views);
		}else
		// valueが数値の場合
		if(is_numeric($values)){
			$count = $values;
		}else{
			$count = 0;
		}

		for($i = 0,$optcount = 0; $i < $count; $i++){
			if($optgroup[$optcount]['idx'] == ($i+1)){
				$grlabel = ($optgroup[$optcount]['label']) ? "label=\"".$optgroup[$optcount]['label']."\"" : "";
				$grparams = $this->cnvParam($optgroup[$optcount]['param']);
				$HTML .= "<optgroup {$grparams} {$grlabel} >";
				$optcount++;
			}
			$v = (!is_numeric($values))?$values[$i]:$i+1;
			$HTML .= $this->selectOption($views[$i],
							  $v,
							  ($selected_opt == $v)?1:0,
							  (is_array($optparam[$i]))?$optparam[$i]:$optparam);
			
			if($optgroup[$optcount]['idx'] == ($i+2) ){
				$HTML .= "</optgroup>";
			}
		}
		if($optcount > 0){
			$HTML .= "</optgroup>";
		}
		$HTML .= "</select>";
		return $this->add("select",$HTML);
	}
	// select用option要素
	function selectOption($view="",$value="",$selected_flg=0,$param=array()){
		$view = ($view) ? "".$view."" : "";
		$value = ($value) ? "value=\"".$value."\"" : "";
		if($selected_flg) $param['selected'] = "selected";
		$params = $this->cnvParam($param);
		
		$HTML = "<option {$value} {$params} >{$view}</option>";
		return $HTML;
	}
	
	// option単体で扱う場合
	function addOption($view="",$value="",$selected_flg=0,$param=array()){
		$HTML = $this->selectOption($view,$value,$selected_flg,$param);
		return $this->add("option",$HTML);
	}
	
	
	#=============================================================================
	# drawItem()
	# IDで選択した項目を描画する。
	#=============================================================================
	# * 引数1 ： 追加された項目のID
	#=============================================================================
	function drawItem($id){
		echo $this->itemList[$id]["HTML"];
	}
	

	#=============================================================================
	# drawItemAll()
	# 全ての項目を描画する。
	#=============================================================================
	function drawItemAll(){
		for($i = 1; $i <= count($this->itemList);$i++){
			echo $this->itemList[$i]["HTML"];
		}
	}
	
	
	#=============================================================================
	# getItem($id)
	# IDで選択した項目のデータを返す。
	#=============================================================================
	function getItem($id){
		return $this->itemList[$id];
	}
	
	
	#=============================================================================
	# getItemList()
	# 項目のデータリストを返す。
	#=============================================================================
	function getItemList(){
		return $this->itemList;
	}
	
	
	#=============================================================================
	# getGroup($gid)
	# IDで選択したグループのデータを返す。
	#=============================================================================
	function getGroup($gid){
		return $this->groupList[$id];
	}
	
	
	#=============================================================================
	# getGroupList()
	# 項目のデータリストを返す。
	#=============================================================================
	function getGroupList(){
		return $this->groupList;
	}
	
	
	#=============================================================================
	# putItemHtml()
	# IDで選択した項目のHTML要素に追加する。
	#=============================================================================
	# * 引数1 ： HTMLデータを追加する項目のID
	# * 引数2 ： 追加する文頭
	# * 引数3 ： 追加する文末
	#=============================================================================
	function putHtmlItem($id,$str_head="",$str_foot=""){
		$this->itemList[$id]["HTML"] = $str_head.$this->itemList[$id]["HTML"].$str_foot;
		return $id;
	}
	
	
	#=============================================================================
	# cnvParam()
	# パラメータ自動生成
	#=============================================================================
	function cnvParam($param){
		$HTML = "";
		if(is_array($param)){
			foreach($param as $k => $v){
				if(!is_numeric($k))
					$HTML.=" {$k}=\"$v\"";
			}
		}
		return $HTML;
	}
}

?>