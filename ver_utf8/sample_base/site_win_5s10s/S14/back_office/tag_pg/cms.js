Temp = new Object();
Temp.name='';
function SaveOBJ(obj){
 Temp.name=obj;
}

function CheckObj(){
 if(!Temp.name) {
  alert("エラー：\n編集する項目を選択してください。");
  return false;
 }else{
  return true;
 }
}

	//ブラウザの判定
	var IE = 0,NN = 0,N6 = 0;

	if(document.all){
		IE = true;
	}else if(document.layers){
		NN = true;
	}else if(document.getElementById){
		N6 = true;
	}

	//カラーパレットの表示位置の設定
	function OnLink(Msg,mX,mY,nX,nY){

			var pX = 0,pY = -180;
			var sX = 10,sY = -180;

		if(IE){
			MyMsg = document.all(Msg).style;
			MyMsg.visibility = "visible";
		}

		if(NN){
			MyMsg = document.layers[Msg];
			MyMsg.visibility = "show";
		}

		if(N6){
			MyMsg = document.getElementById(Msg).style;
			MyMsg.visibility = "visible";
		}

		if(IE){
			pX = document.body.scrollLeft;
			pY = document.body.scrollTop;
			MyMsg.left = mX + pX + sX;
			MyMsg.top = mY + pY + sY;
		}

		if(NN || N6){
			MyMsg.left = nX+ sX;
			MyMsg.top = nY + sY;
		}

	}

	function MM_findObj(n, d) { //v4.01
	  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	  if(!x && d.getElementById) x=d.getElementById(n); return x;
	}

	function MM_showHideLayers() { //v6.0
	  var i,p,v,obj,args=MM_showHideLayers.arguments;
	  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
	    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
			if(args[i+1] != null){
			edit_component = MM_findObj(args[i+1]);
			}
	    obj.visibility=v; }
	}

	function closeColor(Lobj){
		var div1 = getElementById(Lobj);
		div1.style.visibility      ="hidden";
	}

	var edit_component = null;
	var edit_target = null;
	function SetTag (v) {
		if (! document.selection) return;
		var sel = document.selection.createRange();
		if (! sel.text) return;
		sel.text = '<' + v + '>' + sel.text + '</' + v + '>';
	}

	function SetPictogram (v) {
		if (edit_target) edit_target.value = edit_target.value + v;
	}

	function addSelection(obj, startTag, endTag) {
	  var d = document;
	  //IE用
	  if(d.selection){
		var str = document.selection.createRange().text;
		if(str){
		  with(obj){
		document.selection.createRange().text = startTag + str + endTag;
		return;
		  }
		}
	  }
	  //Mozilla用
	  else if ((obj.selectionEnd - obj.selectionStart) >0) {
		var startPos = obj.selectionStart;
		var endPos   = obj.selectionEnd;

		obj.value = obj.value.substring(0, startPos)
		  + startTag + obj.value.substring(startPos, endPos) + endTag
		  + obj.value.substring(endPos, obj.value.length);
		return;
	  }
	  else {
		obj.value += startTag + endTag;
	  }
	}

	function addTag(obj, tag) {
	  var startTag = '<' + tag + '>';
	  var endTag   = '</' + tag + '>';
	  addSelection(obj, startTag, endTag);
	}

	function addStyle(obj, tag, style, layerobj) {
	  var startTag = '<' + tag + ' style="' + style + '">';
	  var endTag   = '</' + tag + '>';

	  //オブジェクトがnullでTempにオブジェクトがある場合（カラーパレットのボタンを押した時にオブジェクトが移される為、もう一度ボタンを押されない限りオブジェクトがnullになる為処理をする）
	  if((obj == null) && (Temp != null)){
	  obj=Temp.name;
	  }

	  //objがnullの場合何も処理をしない、そしてカラーパレットの表示が消える
	  if(obj != null){
		  addSelection(obj, startTag, endTag);
		  if(tag == 'span'){
		  	MM_showHideLayers(layerobj,obj.name,'hide');
		  }
	  }

	}

	function addLink(obj) {
	  var link = prompt('リンクを貼りたいURLを入力してください。: ', 'http://');
	  var startTag = '<a href="' + link + '" target="_blank">';
	  var endTag   = '</a>';
	  if(link != null) addSelection(obj, startTag, endTag);
	}

	function addStr(obj, str) {
	  //IE
	  if (document.selection) {
		obj.focus();
		sel = document.selection.createRange();
		sel.text = str;
	  }
	  //Mozilla
	  else if (obj.selectionStart || obj.selectionStart == '0') {
		var startPos = obj.selectionStart;
		var endPos   = obj.selectionEnd;
		obj.value = obj.value.substring(0, startPos)
		+ str
		+ obj.value.substring(endPos, obj.value.length);
	  //Other
	  } else {
		obj.value += str;
	  }
	}
