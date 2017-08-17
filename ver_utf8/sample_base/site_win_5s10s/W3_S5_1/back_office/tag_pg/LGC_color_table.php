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
?>