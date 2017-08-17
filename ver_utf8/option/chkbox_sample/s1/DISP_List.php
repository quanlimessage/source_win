<?php

    // 各記事の情報をセットする時に使用
    // fetchしたチェックボックスの情報は平文なのでimplodeしてセットして下さい。
    // configで作った配列を使用して名前を取り出す

    $icons = '';
    if($fetch[$i+$j]['ICON']){
        $icon_tmp = explode(',',$fetch[$i+$j]['ICON']);
        foreach ($icon_tmp as $v) {
            $icons .= "<li>".$ICON_NAME_LST[$v]."</li>";
        }
    }
?>
