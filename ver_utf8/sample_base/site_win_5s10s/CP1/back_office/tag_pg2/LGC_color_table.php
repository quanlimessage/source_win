<?php 
////////////////////////////////////////////////////////////////////
// カラーパレットの表示処理
//
//　cp_disp
// 引数 レイヤー名、表示する位置左側から（単位px）、表示する位置上から（単位px）
////////////////////////////////////////////////////////////////////

function cp_disp($layer,$css_left,$css_top){
////////////////////////////////////////////////////////////////////
//カラーパレットのcss設定
echo "
<style type=\"text/css\">

#{$layer} {
 position:absolute;
 left:{$css_left}px;
 top:{$css_top}px;
 width:432px;
 height:360px;
 z-index:1;
 visibility:hidden;
 background-color:#CCCCCC;
}
</style>
";

////////////////////////////////////////////////////////////////////
//カラーパレットのテーブルの骨組み１
echo "
<div id=\"{$layer}\">
<table width=\"500\" border=\"0\" cellpadding=\"0\">
 <tr>
  <td width=\"65\" bgcolor=\"#FFFFFF\" valign=\"top\" align=\"center\">
  <table width=\"55\" border=\"0\" cellpadding=\"0\">
  ";

////////////////////////////////////////////////////////////////////
//カラーパレットのカラーボタン部分の表示
for($i=0;$i<12;$i++):

$mainColArr =
 array(
  "000000","333333","666666",
  "999999","CCCCCC","FFFFFF",
  "FF0000","00FF00","0000FF",
  "FFFF00","00FFFF","FF00FF"
  );

echo "<tr>\n";
echo "\t<td bgcolor=\"#".$mainColArr[$i]."\" height=\"25\">\n";
echo "<a href=\"javascript:void(0)\"
onClick=\"addStyle(edit_component,'span','color:#".$mainColArr[$i]."','{$layer}');
return false;\">";
echo "<img src=\"../tag_pg/img/colbtn.gif\" alt=\"#".$mainColArr[$i]."\"
border=\"0\"></a></td>\n";
// if($i==0)echo "\t<td rowspan=\"12\">&nbsp;</td>";
echo "</tr>\n";
endfor;


////////////////////////////////////////////////////////////////////
//カラーパレットのテーブルの骨組み２
echo "
</table>
  </td>
  <td width=\"432\" align=\"right\">
   <table width=\"432\" border=\"1\" cellpadding=\"1\">
   <tr>
   ";


////////////////////////////////////////////////////////////////////
// セーフカラー配列
   $colAry = array(0,3,6,9,C,F);
   // 初期化
   $color="";
   $col[1]="00";
   $col[2]="00";
   $col[3]="00";
   // webセーフカラー216色なので216回ループ
   for($i=1;$i<=6;$i++){

    for($j=1;$j<=6;$j++){

     for($k=1;$k<=6;$k++){

      $col[1] = str_repeat($colAry[$i-1],2);
      $col[2] = str_repeat($colAry[$j-1],2);
      $col[3] = str_repeat($colAry[$k-1],2);

      // カラーNOの代入
      $color = $col[1].$col[2].$col[3];
      echo "\t<td bgcolor=\"#".$color."\">";
      echo "<a href=\"javascript:void(0)\"
onClick=\"addStyle(edit_component,'span','color:#".$color."','{$layer}');
return false;\">";
      echo "<img src=\"../tag_pg/img/colbtn.gif\" alt=\"#".$color."\"
border=\"0\"></a></td>\n";

     }

      if($j%2 == 0)echo "</tr>\n<tr>\n";
    }

   }


////////////////////////////////////////////////////////////////////
//カラーパレットのテーブルの骨組み３
echo "
   </tr>
   </table>
</td>
 </tr>
 <tr>
  <td colspan=\"2\" height=\"30\" align=\"center\" bgcolor=\"#FFFFFF\">
  <a href=\"javascript:; onClick=MM_showHideLayers('{$layer}','','hide');\">閉じる</a>
  </td>
 </tr>
</table>
</div>
";

}

function tag_button($lf,$lf2,$level=1){

switch($level){
	case 1:
		$img_path = "../";
		break;
	case 2:
		$img_path = "../../";
		break;
}

/*
$HTML = <<< HTML
<a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="{$img_path}tag_pg/img/link.png" width="22" height="22" alt="リンク" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'strong'); return false;"><img src="{$img_path}tag_pg/img/text_bold.png" width="22" height="22" alt="太字" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'i'); return false;"><img src="{$img_path}tag_pg/img/text_italic.png" width="22" height="22" alt="斜体" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="{$img_path}tag_pg/img/text_underline.png" width="22" height="22" alt="下線" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTagStyle(Temp.name,'span','font-size:10px;'); return false;"><img src="{$img_path}tag_pg/img/text_small.png" width="22" height="22" alt="小文字" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTagStyle(Temp.name,'span','font-size:18px;'); return false;"><img src="{$img_path}tag_pg/img/text_large.png" width="22" height="22" alt="大文字" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTagStyle(Temp.name,'p','text-align: left;'); return false;"><img src="{$img_path}tag_pg/img/text_left.png" width="22" height="22" alt="左寄せ" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTagStyle(Temp.name,'p','text-align: center;'); return false;"><img src="{$img_path}tag_pg/img/text_center.png" width="22" height="22" alt="中央寄せ" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();addTagStyle(Temp.name,'p','text-align: right;'); return false;"><img src="{$img_path}tag_pg/img/text_right.png" width="22" height="22" alt="右寄せ" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('{$lf}',obj.name,'show');OnLink('{$lf}',event.x,event.y,event.pageX,event.pageY); return false;"><img src="{$img_path}tag_pg/img/rainbow1.png" alt="文字色" border="0"></a>
<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('{$lf2}',obj.name,'show');OnLink('{$lf2}',event.x,event.y,event.pageX,event.pageY); return false;"><img src="{$img_path}tag_pg/img/rainbow2.png" alt="文字背景色" border="0"></a>
HTML;
*/

$HTML = <<< HTML
<select name="fontsize" onChange="CheckObj();addFontsSize(Temp.name,this); this.options.selectedIndex=0; return false;">
			<option value="">サイズ</option>
			<option value="x-small">極小</option>
			<option value="small">小</option>
			<option value="medium">小さめ</option>
			<option value="large">中</option>
			<option value="x-large">大きめ</option>
			<option value="xx-large">大</option>
			</select>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:left;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_left.png" width="16" height="16" alt="テキストを左寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:center;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_center.png" width="16" height="16" alt="テキストを真中寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:right;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_right.png" width="16" height="16" alt="テキストを右寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="../tag_pg/img/link.png" width="16" height="16" alt="リンク" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'strong'); return false;"><img src="../tag_pg/img/text_bold.png" width="16" height="16" alt="太字" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="../tag_pg/img/text_underline.png" width="16" height="16" alt="下線" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('{$lf}',obj.name,'show');OnLink('{$lf}',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a>

HTML;


return $HTML;
}
?>