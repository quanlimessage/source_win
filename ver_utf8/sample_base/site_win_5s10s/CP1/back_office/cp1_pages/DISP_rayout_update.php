<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：更新画面表示


*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ./err.php");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

$frm = new FormBO();


#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：UTF-8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<script type="text/javascript" src="rayout_inputcheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="./css/jquery.lightbox-0.5.css" media="screen" />
<style type="text/css">  
<!-- 
/* ドラッグする<li>タグ用 */
.ui-state-default, .ui-widget-content .ui-state-default { color: #1c94c4; outline: none; list-style-type: none; margin: 0 10px 0 0; padding: 5px; }
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #1c94c4; text-decoration: none; outline: none; }
.ui-state-default {
	position: relative;
}

/* ドラッグ時に出る破線の設定 */
.ui-state-highlight2, .ui-widget-content .ui-state-highlight2 {border: 2px solid #888; color: #FF0000; }
.ui-state-highlight2 a, .ui-widget-content .ui-state-highlight2 a { color: #363636; }
.ui-state-highlight2 { border-style : dashed; }

#example {
	position: relative;
	height:600px;
	overflow-y:scroll;
}

/* ソートエリア */
#sortable {
	list-style-type: none;
	margin: 0 10px 0 0;
	padding: 5px;
	border : 1px solid #666666;
	min-width: 350px;
	min-height: 700px;
}
#sortable li {
	margin: 0 5px 5px 5px;
	align:left;
	width: auto;
	padding: 5px;
	cursor: move;
}

/* ブロックのドラッグエリア */
#draggable li{
	cursor: move;
}


/* アイコン */

/* ツールチップ */

#text {margin:50px auto; width:500px}
.hotspot {color:#900; padding-bottom:1px; border-bottom:1px dotted #900; cursor:pointer}

#tt {position:absolute; display:block; background:url(../../js/images/tt_left.gif) top left no-repeat}
#tttop {display:block; height:5px; margin-left:5px; background:url(../../js/images/tt_top.gif) top right no-repeat; overflow:hidden}
#ttcont {display:block; padding:2px 12px 3px 7px; margin-left:5px; background:#666; color:#FFF}
#ttbot {display:block; height:5px; margin-left:5px; background:url(../../js/images/tt_bottom.gif) top right no-repeat; overflow:hidden}

-->  
</style>
<script src="./inputcheck.js" type="text/javascript"></script>
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script type="text/javascript" src="./jquery/jquery.tooltip.js"></script>
<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./jquery/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="./jquery/jquery.scrollfollow.js"></script>
<script type="text/javascript">
$( document ).ready( function ()
	{
		$( '#example' ).scrollFollow();
	}
);

$(function() {
	// ============================
	// ブロックの変数
	// ============================
	var varNum = <?php echo count($fetchRgBlock)+1?>;
	
	// ============================
	// ソート機能
	// ============================
	var _option = {
		connectWith: '.droptrue',
		placeholder: 'ui-state-highlight2',
		tolerance:'intersect',
		forcePlaceholderSize: 'true',
		handle:'.block_img',
		opacity:'0.6',
		/*
		change:function(eve,ui){
			$('#info1').html('Change!!!');
		},
		start:function(eve,ui){
			$('#info1').html('Start!!!');
		},
		over:function(eve,ul){
			$('.droptrue').css('border-color','#666666')
			ul.placeholder.parent().css('border-color','#FF0000');
		},
		*/
		stop:function(eve,ui){
			// 画像サイズの変更
			$('#sortable > li  img.block_img').css('width','350px');
			ui.item.find('.block_id_input').attr('name','_block_id['+varNum+']');
			ui.item.find('.block_res_id_input').attr('name','_block_res_id['+varNum+']');
			if(ui.item.find('.block_res_id_input').attr('value') == ''){
				ui.item.dblclick(function(){removeBlock(ui.item);});
			}
			varNum++;
		}
		
	};
	
	$("#sortable").sortable(_option);
	
	
	// ============================
	// ドラッグからソート機能へ
	// ============================
	$('#draggable > li').draggable({
		connectToSortable: '#sortable',
		helper: 'clone',
		opacity:'0.6',
		stop : function(eve,ui){
			//$('#info1').html( $(this).attr('title'));
		}
	});


	
});
	



	// 削除
	function removeBlock(target){
		$(target).remove();
		tooltip.hide();
	}
	function removeBlock2(target){
		//if(confirm("編集された形跡があります。\n削除したブロックは復帰できません。\n本当によろしいですか？"))
		if(confirm("削除したブロックはデータを含め復帰できません。\n本当によろしいですか？")){
			$("#deleteblock").append('<input type="hidden" name="delete_res_id[]" value="'+target.find('.block_res_id_input').attr('value')+'">');
			$("#deleteblock").append('<input type="hidden" name="delete_blcok_id[]" value="'+target.find('.block_id_input').attr('value')+'">');
			//alert(target.find('.block_res_id_input').attr('value'));
			$(target).remove();
			tooltip.hide();
		}
	}


</script>

</head>
<body>
<div class="header"></div>
<table width="400" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="sort.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		<input type="hidden" name="ca" value="<?php echo $ca;?>">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo CP1_TITLE;?>：更新画面</p>
<p class="explanation">
▼現在のデータ内容が初期表示されています。<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。
</p>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;" onSubmit="return ConfirmMsg('入力いただいた内容で登録します。\nよろしいですか？')">
	<table width="600" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■テンプレート編集</th>
	</tr>
	
	<tr>
		<td width="400" height="400" align="left" valign="top">
		<div id="tpl_stage">
		<?php //var_dump($fetchRgBlock) ?>
		<ul id="sortable" class="droptrue">
		
		<?php for($i = 0; $i < count($fetchRgBlock);$i++){ ?>
		
		<li class="ui-state-default" title="block_<?php echo $fetchRgBlock[$i]['BLOCK_ID']?>" align="left">
			<img src="block_img/<?php echo $fetchRgBlock[$i]['BLOCK_ID']?>.jpg" width="350" border="0" class="block_img" ondblclick="removeBlock2($(this).parent())">
			<input type="hidden" name="<?php echo "_block_id[".($i+1)."]"?>" value="<?php echo $fetchRgBlock[$i]['BLOCK_ID']?>" class="block_id_input">
			<input type="hidden" name="<?php echo "_block_res_id[".($i+1)."]"?>" value="<?php echo $fetchRgBlock[$i]['RES_ID']?>" class="block_res_id_input">
		</li>
		
		<?php } ?>


		</ul>
		</div>
		</td>
		<td  valign="top">
		<div id="example">
		<ul id="draggable">
		<?php foreach($BLOCK_DATA as $k => $v){ ?>
		<li class="ui-state-default" title="block_<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>" align="left" onmouseover="tooltip.show('<img src=\'block_img/<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>.jpg\' width=\'600\'>');" onmouseout="tooltip.hide();">
			<img src="block_img/<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>.jpg" width="120" border="0" class="block_img">
			<input type="hidden" name="" value="<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>" class="block_id_input">
			<input type="hidden" name="" value="" class="block_res_id_input">
		</li>
		<?php } ?>
		</ul>
		</div>
		</td>
	</tr>
	
</table>


<input type="submit" value="更新する" style="width:150px;margin-top:1em;">
<input type="hidden" name="act" value="rayout_upd_completion">
<input type="hidden" name="res_id" value="<?php echo $fetch[0]["RES_ID"];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">
<div id="deleteblock"></div>
</form>
<br>
<form action="./" method="post">
	<input type="submit" value="一覧画面へ戻る" style="width:150px;">
	<input type="hidden" name="ca" value="<?php echo $fetch[0]["CATEGORY_CODE"];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
</form>

</body>
</html>