<?php

////////////////////////////////////////////////////////////////////
// ���顼�ѥ�åȤ�ɽ������
//
//��cp_disp
// ���� �쥤�䡼̾��ɽ��������ֺ�¦�����ñ��px�ˡ�ɽ��������־夫���ñ��px��
////////////////////////////////////////////////////////////////////

function cp_disp($layer,$css_left,$css_top){
////////////////////////////////////////////////////////////////////
//���顼�ѥ�åȤ�css����
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
//���顼�ѥ�åȤΥơ��֥�ι��Ȥߣ�
echo "
<div id=\"{$layer}\">
<table width=\"500\" border=\"0\" cellpadding=\"0\">
 <tr>
  <td width=\"65\" bgcolor=\"#FFFFFF\" valign=\"top\" align=\"center\">
  <table width=\"55\" border=\"0\" cellpadding=\"0\">
  ";

////////////////////////////////////////////////////////////////////
//���顼�ѥ�åȤΥ��顼�ܥ�����ʬ��ɽ��
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
//���顼�ѥ�åȤΥơ��֥�ι��Ȥߣ�
echo "
</table>
  </td>
  <td width=\"432\" align=\"right\">
   <table width=\"432\" border=\"1\" cellpadding=\"1\">
   <tr>
   ";

////////////////////////////////////////////////////////////////////
// �����ե��顼����
   $colAry = array(0,3,6,9,C,F);
   // �����
   $color="";
   $col[1]="00";
   $col[2]="00";
   $col[3]="00";
   // web�����ե��顼216���ʤΤ�216��롼��
   for($i=1;$i<=6;$i++){

    for($j=1;$j<=6;$j++){

     for($k=1;$k<=6;$k++){

      $col[1] = str_repeat($colAry[$i-1],2);
      $col[2] = str_repeat($colAry[$j-1],2);
      $col[3] = str_repeat($colAry[$k-1],2);

      // ���顼NO������
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
//���顼�ѥ�åȤΥơ��֥�ι��Ȥߣ�
echo "
   </tr>
   </table>
</td>
 </tr>
 <tr>
  <td colspan=\"2\" height=\"30\" align=\"center\" bgcolor=\"#FFFFFF\">
  <a href=\"javascript:; onClick=MM_showHideLayers('{$layer}','','hide');\">�Ĥ���</a>
  </td>
 </tr>
</table>
</div>
";

}
?>