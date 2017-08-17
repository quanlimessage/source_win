<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：新規登録画面表示


*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="./css/jquery.lightbox-0.5.css" media="screen" />
<style type="text/css">  
<!-- 
/* ドラッグする<li>タグ用 */
.ui-state-default, .ui-widget-content .ui-state-default { color: #1c94c4; outline: none; list-style-type: none; margin: 0 10px 0 0; padding: 5px; }
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #1c94c4; text-decoration: none; outline: none; }

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
	margin: 0 0 0 0;
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
}

#sortable .block_img {
	cursor: move;
}

/* ブロックのドラッグエリア */
#draggable li img{
	cursor: move;
}


/* アイコン */
.edit_icon{
	border:0px;
	cursor: pointer;
}
.block_edit_button{
	position: relative;
}

/* ツールチップ */
#text {margin:50px auto; width:500px}
.hotspot {color:#900; padding-bottom:1px; border-bottom:1px dotted #900; cursor:pointer}

#tt {position:absolute; display:block; background:url(../../js/images/tt_left.gif) top left no-repeat}
#tttop {display:block; height:5px; margin-left:5px; background:url(../../js/images/tt_top.gif) top right no-repeat; overflow:hidden}
#ttcont {display:block; padding:2px 12px 3px 7px; margin-left:5px; background:#666; color:#FFF}
#ttbot {display:block; height:5px; margin-left:5px; background:url(../../js/images/tt_bottom.gif) top right no-repeat; overflow:hidden}
-->  
</style>
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script type="text/javascript" src="./jquery/jquery.tooltip.js"></script>
<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./jquery/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="./jquery/jquery.lightbox-0.5.js"></script>
<script type="text/javascript" src="./jquery/jquery.scrollfollow.js"></script>
<script type="text/javascript">

var varNum = 0;


$( document ).ready( function ()
			{
				$( '#example' ).scrollFollow();
			}
		);

$(function() {
	// ============================
	// ブロックの変数
	// ============================
	
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
			// 変数の設定
			ui.item.find('.block_id_input').attr('name','_block_id['+varNum+']');
			ui.item.dblclick(
				function(){
					removeBlock(ui.item);
				}

			);
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
		handle:'img',
		opacity:'0.6',
		stop : function(eve,ui){
			//$('#info1').html( $(this).attr('title'));
		}
	});


	
});

// 初期化
function init(){
	varNum = 0;
	$('#sortable').empty();
}

// 削除
function removeBlock(target){
	//if(confirm("編集された形跡があります。\n削除したブロックは復帰できません。\n本当によろしいですか？"))
	//if(confirm("削除したブロックは復帰できません。\n本当によろしいですか？"))
		$(target).remove();
		tooltip.hide();
}


function insertTmp(){
	init();
	var blocks = $('#template').val().split(',');
	for(var i = 0; i < blocks.length; i++){
		if(blocks[i] != ''){
			$('#sortable').append('<li class="ui-state-default" title="block_' + blocks[i] + '" align="left" onmouseover="tooltip.show(\'<img src=\\\'block_img/' + blocks[i] + '.jpg\\\' width=\\\'600\\\'>\');" onmouseout="tooltip.hide();" ondblclick="removeBlock($(this))"><img src="block_img/' + blocks[i] + '.jpg" width="350" border="0" class="block_img" ><input type="hidden" name="_block_id['+varNum+']" value="' + blocks[i] + '" class="block_id_input"></li>');
			varNum++;
		}
	}
	
}




</script>
</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<!--
		<td>
		<form action="sort.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		</form>
		</td>
		-->
	</tr>
</table>

<p class="page_title"><?php echo CP1_TITLE;?>：新規登録</p>
<p class="explanation">
▼新規データの登録画面です。<br>
▼ブロックを削除する際はメインレイアウトエリアのブロックをダブルクリックしてください。<br>
▼入力し終えたら<strong>「上記の内容で登録する」</strong>をクリックしてデータを登録してください。
</p>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" onSubmit="return confirm_message(this);" style="margin:0px;">
<table width="600" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored" >■基本情報</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">カテゴリー：</th>
		<td class="other-td">
		<select name="ca">
		<?php for($i=0;$i<count($fetchCA);$i++){?>
		<option value="<?php echo $fetchCA[$i]['CATEGORY_CODE'];?>"<?php echo ($ca == $fetchCA[$i]['CATEGORY_CODE'])?" selected":""; ?>><?php echo $fetchCA[$i]['CATEGORY_NAME'];?></option>
		<?php }?>
		</select>
		</td>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">タイトル：</th>
		<td class="other-td">
		<input name="title" type="text" value="<?php echo $title;?>" size="60" maxlength="127" style="ime-mode:active">
		</td>
	</tr>

	<tr>
		<th nowrap class="tdcolored">一覧用本文：</th>
		<td class="other-td">
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
			<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('<?php echo $layer_free;?>',obj.name,'show');OnLink('<?php echo $layer_free;?>',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a>
			<br>
		
		<textarea name="content" cols="50" rows="10" style="ime-mode:active" onFocus="SaveOBJ(this)"><?php echo $content;?></textarea>
		</td>
	</tr>
	<?php for($i=1;$i<=CP1_IMG_CNT;$i++):?>
	<tr>
		<th nowrap class="tdcolored"><?php echo ($i==1)?"画像":"詳細用画像";?>：</th>
		<td height="35" class="other-td">
		アップロード後画像サイズ：<strong>横<?php echo ($i==1)?CP1_IMGSIZE_MX1:CP1_IMGSIZE_MX2;?>px×縦<?php //echo ($i==1)?CP1_IMGSIZE_MY1:CP1_IMGSIZE_MY2;echo "px";?> 自動算出</strong>
		<br>
		<input type="file" name="up_img[<?php echo $i;?>]" value="" class="chkimg">
		</td>
	</tr>
	<?php endfor;?>
</table>
<table width="600" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■テンプレート制作</th>
	</tr>
	
	<tr>
		<td width="400" height="400" align="left" valign="top">
		<div id="tpl_stage">
		<ul id="sortable" class="droptrue">


		</ul>
		</div>
		</td>
		<td  valign="top">
		<div id="example">
		<ul id="draggable">
		<?php foreach($BLOCK_DATA as $k => $v){ ?>
		<li class="ui-state-default" title="block_<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>" align="left">
			<img src="block_img/<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>.jpg" width="120" border="0" class="block_img" onmouseover="tooltip.show('<img src=\'block_img/<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>.jpg\' width=\'600\'>');" onmouseout="tooltip.hide();">
			<input type="hidden" name="" value="<?php echo $BLOCK_DATA[$k]['BLOCK_ID']?>" class="block_id_input">
			
		</li>
		<?php } ?>
		</ul>
		</div>
		</td>
	</tr>
	
</table>
<table width="600" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored" >■その他設定</th>
	</tr>
	<tr>
		<th nowrap class="tdcolored">トップに登録：</th>
		<td class="other-td">
		<input type="checkbox" name="ins_chk" value="1" id="ins_chk">※この内容を一番上に登録する場合はチェックを入れてください
		</td>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">表示／非表示：</th>
		<td class="other-td">
		<input name="display_flg" id="dispon" type="radio" value="1" checked><label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="display_flg" id="dispoff" type="radio" value="0"><label for="dispoff">非表示</label>
		</td>
	</tr>
</table>





<!--<input type="button" value="プレビュー" style="width:150px;margin-top:1em;">-->
<input type="submit" value="上記の内容で登録する" style="width:150px;margin-top:1em;">
<input type="hidden" name="act" value="rayout_completion">
<input type="hidden" name="regist_type" value="new">
<input type="hidden" name="blocks" id="blocks" value="">

</form>
<br>
<form action="./" method="post">
	<input type="submit" value="リスト画面へ戻る" style="width:150px;">
	<input type="hidden" name="gr" value="<?php echo $gr;?>">
</form>

<?php 

//ボタン付近に表示する
cp_disp($layer_free,"0","0");

?>
<script type="text/javascript">
//insertTmp();
</script>

</body>
</html>