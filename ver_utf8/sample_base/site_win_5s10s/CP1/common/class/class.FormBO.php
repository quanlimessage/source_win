<?php
/*******************************************************************************
 フォームクラス

 2012/01/12	1.00	m.nakamura
 ********************************************************************************/
#=================================================================================
# 全階層使用するための絶対パス
#=================================================================================
 
require_once('class.Form.php');

#=================================================================================
# FormBOクラス（β版）
#=================================================================================
# ● メソッド 
#=================================================================================
class FormBO extends Form {
	var $H1 = "<tr><th width=\"15%\" nowrap class=\"tdcolored\">";
	var $H2 = "</th><td class=\"other-td\" width=\"600\" align=\"left\">";
	var $F = "</td></tr>\n";
	
	function selectBDDrawer($block_data,$fetchData,$value_name,$file_name,$layer_free,$layer_free2){
		$block_item = explode(',',$block_data['BLOCK_ITEM_ORDER']);
		$block_file = explode(',',$block_data['BLOCK_FILE_ORDER']);
		$img_width = explode(',',$block_data['DEF_IMG_WIDTH']);
		$block_item_name = explode(',',$block_data['BLOCK_ITEM_NAME']);
		$block_file_name = explode(',',$block_data['BLOCK_FILE_NAME']);

		for($i = 0,$v_num = 0; $i < count($block_item); $i++){
			$name = NULL;
			$name = $value_name."[{$v_num}]";
			switch($block_item[$i]){
			  # --------------------------------------------------------------------------
			  #　○ 通常のテキストボックス
			  #     入力可能欄： 1個
			  # --------------------------------------------------------------------------
			  case "t001":
				  $this->drawText($block_item_name[$i],$name,$fetchData['TEXT'.($v_num+1)]);
				  $v_num++;
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 半角英数字のみ入力可能
			  #     入力可能欄： 1個
			  # --------------------------------------------------------------------------
			  case "t001e":
				  $this->drawTextEng($block_item_name[$i],$name,$fetchData['TEXT'.($v_num+1)]);
				  $v_num++;
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 均等サイズのテキストボックスが二つ横に並ぶ。
			  #     入力可能欄： 2個
			  # --------------------------------------------------------------------------
			  case "t002":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  
				  $this->drawTextCol2($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 均等サイズのテキストエリアが二つ横に並ぶ。
			  #     入力可能欄： 2個
			  # --------------------------------------------------------------------------
			  case "ta002":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  
				  $this->drawTextACol2($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 均等サイズのテキストエリアが二つ横に並ぶ。
			  #     入力可能欄： 2個
			  # --------------------------------------------------------------------------
			  case "ta002_2":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  
				  $this->drawTextACol2_2($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 均等サイズのテキストボックスが二つ横に並び、間に「年～」末尾に「年」。
			  #     入力可能欄： 2個
			  # --------------------------------------------------------------------------
			  case "t002n":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  
				  $this->drawTextCol2num($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ ほぼ均等サイズのテキストボックスが三つ横に並ぶ。
			  #     入力可能欄： 3個
			  # --------------------------------------------------------------------------
			  case "t003":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  $v[2] = $fetchData['TEXT'.($v_num+1)];
				  $name[2] = $value_name."[{$v_num}]";$v_num++;
				  
				  $this->drawTextCol3($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ ほぼ均等サイズのテキストエリアが三つ横に並ぶ。
			  #     入力可能欄： 3個
			  # --------------------------------------------------------------------------
			  case "ta003":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  $v[2] = $fetchData['TEXT'.($v_num+1)];
				  $name[2] = $value_name."[{$v_num}]";$v_num++;
				  
				  $this->drawTextACol3($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ ほぼ均等サイズのテキストボックスが四つ横に並ぶ。
			  #     入力可能欄： 4個
			  # --------------------------------------------------------------------------
			  case "t004":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  $v[2] = $fetchData['TEXT'.($v_num+1)];
				  $name[2] = $value_name."[{$v_num}]";$v_num++;
				  $v[3] = $fetchData['TEXT'.($v_num+1)];
				  $name[3] = $value_name."[{$v_num}]";$v_num++;

				  
				  $this->drawTextCol4($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ ほぼ均等サイズのテキストエリアが四つ横に並ぶ。
			  #     入力可能欄： 4個
			  # --------------------------------------------------------------------------
			  case "ta004":
			  	  // 初期化
			  	  $name = array();
				  $v = array();
				  
				  $v[0] = $fetchData['TEXT'.($v_num+1)];
				  $name[0] = $value_name."[{$v_num}]";$v_num++;
				  $v[1] = $fetchData['TEXT'.($v_num+1)];
				  $name[1] = $value_name."[{$v_num}]";$v_num++;
				  $v[2] = $fetchData['TEXT'.($v_num+1)];
				  $name[2] = $value_name."[{$v_num}]";$v_num++;
				  $v[3] = $fetchData['TEXT'.($v_num+1)];
				  $name[3] = $value_name."[{$v_num}]";$v_num++;

				  
				  $this->drawTextACol4($block_item_name[$i],$name,$v);
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 通常のテキストエリア。（タグ欄あり）
			  #     入力可能欄： 1個
			  # --------------------------------------------------------------------------
			  case "ta001":
				  $this->drawTextAreaTag($block_item_name[$i],$name,$fetchData['TEXT'.($v_num+1)],$layer_free,$layer_free2);
				  $v_num++;
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 通常のテキストエリア。（タグ欄なし）
			  #     入力可能欄： 1個
			  # --------------------------------------------------------------------------
			  case "ta001_2":
				  $this->drawTextArea($block_item_name[$i],$name,$fetchData['TEXT'.($v_num+1)]);
				  $v_num++;
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 小さいサイズのテキストエリア。（タグ欄なし）
			  #     入力可能欄： 1個
			  # --------------------------------------------------------------------------
			  case "ta001_2s":
				  $this->drawTextAreaS($block_item_name[$i],$name,$fetchData['TEXT'.($v_num+1)]);
				  $v_num++;
				  break;
			  # --------------------------------------------------------------------------
			  #　○ 横が長く、縦が短いテキストエリア。（タグ欄なし）
			  #     入力可能欄： 1個
			  # --------------------------------------------------------------------------
			  case "ta001_2w":
				  $this->drawTextAreaWL($block_item_name[$i],$name,$fetchData['TEXT'.($v_num+1)]);
				  $v_num++;
				  break;
			  # --------------------------------------------------------------------------
			  #　○ タグ欄
			  #     入力可能欄： 0個
			  # --------------------------------------------------------------------------
			  case "tag001":
				  $this->drawTag($block_item_name[$i],$layer_free,$layer_free2);
				  //$v_num++;
				  break;
			}
		}
		
		for($i = 0; $i < count($block_file); $i++){
			$param_xml = simplexml_load_string($fetchData['FPARAM'.($i+1)]);
			$name = $file_name."[{$i}]";

			switch($block_file[$i]){
			  case "i001":
				  $this->drawFileUp($block_file_name[$i],$name,$fetchData['FILE'.($i+1)],$param_xml->extension,$img_width[$i],"",$param_xml->lightbox,$param_xml->border,$param_xml->link_url,$param_xml->link_target);
				  break;
			}
		}
	}
	
	# 通常テキストボックス
	function drawText($title,$name,$value){
		$param = array("size"=>"60","style"=>"ime-mode: active;","class"=>"_values");
		$id = $this->addInputText($name,$value,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2,$this->F);
		$this->drawItem($id);
	}
	
	# 英数字のみ入力可能テキストボックス
	function drawTextEng($title,$name,$value){
		$param = array("size"=>"60","style"=>"ime-mode: disabled;","class"=>"_values");
		$id = $this->addInputText($name,$value,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2,$this->F);
		$this->drawItem($id);
	}

	#2列テキストボックス
	function drawTextCol2($title,$name,$value){
		$param = array("size"=>"40","style"=>"ime-mode: active;","class"=>"_values");
		// 先頭
		$id = $this->addInputText($name[0]."",$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2,"&nbsp;");
		$this->drawItem($id);
		
		// 末尾
		$id = $this->addInputText($name[1]."",$value[1],$param);
		$this->putHtmlItem($id,"",$this->F);
		$this->drawItem($id);
		
	}
	
	#2列テキストボックス同サイズ
	function drawTextACol2($title,$name,$value){
	
		$table_html_h = "<table><tr><td>";
		$table_html_s = "</td><td>";
		$table_html_f = "</td></tr></table>";
	
		// 先頭
		$param = array("cols"=>"42","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		//$tag = tag_button($layer_free,$layer_free2)."<br>";
		$id = $this->addTextArea($name[0],$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$table_html_h,$table_html_s);
		$this->drawItem($id);
		
		$param = array("cols"=>"42","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		// 末尾
		$id = $this->addTextArea($name[1],$value[1],$param);
		$this->putHtmlItem($id,"",$table_html_f.$this->F);
		$this->drawItem($id);
	}

	#2列テキストボックス左が狭い
	function drawTextACol2_2($title,$name,$value){
	
		$table_html_h = "<table><tr><td>";
		$table_html_s = "</td><td>";
		$table_html_f = "</td></tr></table>";
	
		// 先頭
		$param = array("cols"=>"30","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		//$tag = tag_button($layer_free,$layer_free2)."<br>";
		$id = $this->addTextArea($name[0],$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$table_html_h,$table_html_s);
		$this->drawItem($id);
		
		$param = array("cols"=>"55","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		// 末尾
		$id = $this->addTextArea($name[1],$value[1],$param);
		$this->putHtmlItem($id,"",$table_html_f.$this->F);
		$this->drawItem($id);
	}

	#2列数値ボックス
	function drawTextCol2num($title,$name,$value){
		$param = array("size"=>"10","style"=>"ime-mode: disabled;","class"=>"_values");
		// 先頭
		$id = $this->addInputText($name[0]."",$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2,"年&nbsp;～&nbsp;");
		$this->drawItem($id);
		
		// 末尾
		$id = $this->addInputText($name[1]."",$value[1],$param);
		$this->putHtmlItem($id,"","年&nbsp;数値で入力してください。".$this->F);
		$this->drawItem($id);
		
	}
	
	#3列テキストボックス
	function drawTextCol3($title,$name,$value){
		$param = array("size"=>"15","style"=>"ime-mode: active;","class"=>"_values");
		$param2 = array("size"=>"35","style"=>"ime-mode: active;","class"=>"_values");
		// 先頭
		$id = $this->addInputText($name[0]."",$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2,"");
		$this->drawItem($id);

		// 中腹
		$id = $this->addInputText($name[1]."",$value[1],$param2);
		$this->putHtmlItem($id,"&nbsp;","&nbsp;");
		$this->drawItem($id);
		
		// 末尾
		$id = $this->addInputText($name[2]."",$value[2],$param2);
		$this->putHtmlItem($id,"",$this->F);
		$this->drawItem($id);
		
	}
	
	#3列テキストエリア
	function drawTextACol3($title,$name,$value){
	
		$table_html_h = "<table><tr><td>";
		$table_html_s = "</td><td>";
		$table_html_f = "</td></tr></table>";
	
		// 先頭
		$param = array("cols"=>"15","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		//$tag = tag_button($layer_free,$layer_free2)."<br>";
		$id = $this->addTextArea($name[0],$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$table_html_h,$table_html_s);
		$this->drawItem($id);
		
		$param = array("cols"=>"35","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		// 中腹
		$id = $this->addTextArea($name[1],$value[1],$param);
		$this->putHtmlItem($id,"",$table_html_s);
		$this->drawItem($id);


		$param = array("cols"=>"35","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		// 末尾
		$id = $this->addTextArea($name[2],$value[2],$param);
		$this->putHtmlItem($id,"",$table_html_f.$this->F);
		$this->drawItem($id);
	}
	
	#4列テキストボックス
	function drawTextCol4($title,$name,$value){
		$param = array("size"=>"20","style"=>"ime-mode: active;","class"=>"_values");
		$param2 = array("size"=>"20","style"=>"ime-mode: active;","class"=>"_values");
		// 先頭
		$id = $this->addInputText($name[0]."",$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2,"");
		$this->drawItem($id);

		// 中腹1
		$id = $this->addInputText($name[1]."",$value[1],$param2);
		$this->putHtmlItem($id,"&nbsp;","&nbsp;");
		$this->drawItem($id);
		
		// 中腹2
		$id = $this->addInputText($name[2]."",$value[2],$param);
		$this->putHtmlItem($id,"","&nbsp;");
		$this->drawItem($id);

		// 末尾
		$id = $this->addInputText($name[3]."",$value[3],$param2);
		$this->putHtmlItem($id,"",$this->F);
		$this->drawItem($id);
		
	}
	
	#4列テキストエリア
	function drawTextACol4($title,$name,$value){
		$table_html_h = "<table><tr><td>";
		$table_html_s = "</td><td>";
		$table_html_f = "</td></tr></table>";
	
		// 先頭
		$param = array("cols"=>"21","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		//$tag = tag_button($layer_free,$layer_free2)."<br>";
		$id = $this->addTextArea($name[0],$value[0],$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$table_html_h,$table_html_s);
		$this->drawItem($id);
		
		$param = array("cols"=>"21","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		// 中腹1
		$id = $this->addTextArea($name[1],$value[1],$param);
		$this->putHtmlItem($id,"",$table_html_s);
		$this->drawItem($id);

		$param = array("cols"=>"21","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		// 中腹2
		$id = $this->addTextArea($name[2],$value[2],$param);
		$this->putHtmlItem($id,"",$table_html_s);
		$this->drawItem($id);


		$param = array("cols"=>"21","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		// 末尾
		$id = $this->addTextArea($name[3],$value[3],$param);
		$this->putHtmlItem($id,"",$table_html_f.$this->F);
		$this->drawItem($id);
		
	}
	
	# タグのみ
	function drawTag($title,$layer_free,$layer_free2){
		$tag = tag_button($layer_free,$layer_free2);
		echo $this->H1.$title.$this->H2.$tag,$this->F;
	}
	
	# 通常テキストエリア（タグ有り）
	function drawTextAreaTag($title,$name,$value,$layer_free,$layer_free2){
		$param = array("cols"=>"85","rows"=>"10","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		$tag = tag_button($layer_free,$layer_free2)."<br>";
		$id = $this->addTextArea($name,$value,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$tag,$this->F);
		$this->drawItem($id);
	}
	
	# 横長テキストエリア
	function drawTextAreaWL($title,$name,$value){
		$param = array("cols"=>"85","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		//$tag = tag_button($layer_free,$layer_free2)."<br>";
		$id = $this->addTextArea($name,$value,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$tag,$this->F);
		$this->drawItem($id);
	}
	
	# 通常テキストエリア（小）
	function drawTextAreaS($title,$name,$value){
		$param = array("cols"=>"30","rows"=>"2","style"=>"ime-mode: active;","onFocus"=>"SaveOBJ(this);","class"=>"_values");
		//$tag = tag_button($layer_free,$layer_free2)."<br>";
		$id = $this->addTextArea($name,$value,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$tag,$this->F);
		$this->drawItem($id);
	}
	
	# 通常テキストエリア（タグなし）
	function drawTextArea($title,$name,$value){
		$param = array("cols"=>"85","rows"=>"10","style"=>"ime-mode: active;","class"=>"_values");
		$id = $this->addTextArea($name,$value,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$tag,$this->F);
		$this->drawItem($id);
	}
	
	# 通常ファイル参照
	function drawFile($title,$name){
		$param = array("size"=>"60","class"=>"_files");
		$id = $this->addInputFile($name,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2,$this->F);
		$this->drawItem($id);
	}
	
	# 更新ファイル参照
	function drawFileUp($title,$name,$filename="",$ext="",$defwidth="",$defheight="",$defp1=0,$defp2=0,$defp3="",$defp4=0){
		$filename = ($filename) ? $filename : "*";
		$path = CP1_IMG_PATH.$filename;
		
		$cmn_view_html = "";
		//$cmn_view_html .= "<input type=\"checkbox\" name=\"_lightbox".$name."\" value=\"1\" ".(($defp1 == 1)? "checked" : "").">&nbspライトボックス<br>";
		$cmn_view_html .= "リンクURL：<input type=\"text\" name=\"_link_url".$name."\" value=\"{$defp3}\" size=\"60\" style=\"ime-mode:disabled\" ><br>";
		//$cmn_view_html .= "<input type=\"checkbox\" name=\"_link_target".$name."\" value=\"1\" ".(($defp4 == 1)? "checked" : "").">&nbspリンクを別ウィンドウで表示する。<br>";
		$cmn_view_html .= "<input type=\"radio\" name=\"_link_target".$name."\" value=\"1\" ".(($defp4 != 4 && $defp4 != 3  && $defp4 != 2)? "checked" : "").">&nbspリンクしない&nbsp;";
		$cmn_view_html .= "<input type=\"radio\" name=\"_link_target".$name."\" value=\"2\" ".(($defp4 == 2)? "checked" : "").">&nbspリンクを現在のウィンドウで表示する。&nbsp;";
		$cmn_view_html .= "<input type=\"radio\" name=\"_link_target".$name."\" value=\"3\" ".(($defp4 == 3)? "checked" : "").">&nbspリンクを別ウィンドウで表示する。&nbsp;";
		$cmn_view_html .= "<input type=\"radio\" name=\"_link_target".$name."\" value=\"4\" ".(($defp4 == 4)? "checked" : "").">&nbsp画像にリンクする。&nbsp;<br>";
		//$cmn_view_html .= "<input type=\"checkbox\" name=\"_border".$name."\" value=\"1\" ".(($defp2 == 1)? "checked" : "").">&nbsp画像枠<br>";
		$cmn_view_html .= "アップロード後画像サイズ：<strong>横"."<input type=\"text\" name=\"_width".$name."\" size=\"3\" style=\"ime-mode:disabled\" value=\"{$defwidth}\"> px×縦 <input type=\"text\" name=\"_height".$name."\" size=\"3\" style=\"ime-mode:disabled\" value=\"{$defheight}\"> px</strong><br>";
		$cmn_view_html .= "※両方入力で固定サイズ。<br>";
		$cmn_view_html .= "※片方入力で自動リサイズ。<br>";
		$cmn_view_html .= "※両方未入力でリサイズなし。<br>";

		if(file_exists($path)){
			$size = getimagesize($path);
			// 今回は画像だけなので場合分けはしない。
			$view_html .= "<div style=\"margin-left:5px;margin-top:5px;margin-bottom:5px;\"><a href=\"{$path}\" target=\"_blank\"><img src=\"{$path}?r=".rand()."\" width=\"100\" border=\"0\"></a></div>";
			$view_html .= "現在のサイズ：<strong>横".$size[0]." px×縦 ".$size[1]." px</strong><br>";
			$view_html .= $cmn_view_html;
			$view_html .= "	<input type=\"checkbox\" name=\"block_del_img[]\" value=\"{$filename}\" id=\"$filename\"><label for=\"$filename\">この画像を削除</label><br>";
			$view_html .= "	<input type=\"hidden\" name=\"_old_ext".$name."\" value=\"{$ext}\">";
		
		}else{
			$view_html .= "※登録されていません。<br>";
			$view_html .= $cmn_view_html;
		}
		

		$param = array("size"=>"60","class"=>"_files");
		$id = $this->addInputFile($name,$param);
		$this->putHtmlItem($id,$this->H1.$title.$this->H2.$view_html,$this->F);
		$this->drawItem($id);
	}
	
}

?>