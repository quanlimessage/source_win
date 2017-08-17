// ケニー

var IE = 0,NN = 0,N6 = 0;

if(document.all){
	IE = true;
}else if(document.layers){
	NN = true;
}else if(document.getElementById){
	N6 = true;
}

function OnLink(Msg,mX,mY,nX,nY){

	var pX = 0,pY = 0;
	var sX = 10,sY = -45;

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

function OffLink(Msg){

	if(IE){
		document.all(Msg).style.visibility = "hidden";
	}

	if(NN){
		document.layers[Msg].visibility = "hide";
	}

	if(N6){
		document.getElementById(Msg).style.visibility = "hidden";
	}

}