
function OpenSel(op)
{
	if (op == 1)
	{
		document.getElementById('headerfotos').style.display="block";
		document.getElementById('header').style.display="block";
		document.getElementById('headerarquivos').style.display="none";
		document.getElementById('headermenu').style.display="none";
		document.getElementById('headervideos').style.display="none";
		document.getElementById('headertopo').style.display="none";
	}
	else if (op == 2)
	{
		document.getElementById('headerfotos').style.display="none";
		document.getElementById('header').style.display="none";
		document.getElementById('headerarquivos').style.display="block";
		document.getElementById('headermenu').style.display="block";
		document.getElementById('headervideos').style.display="none";
		document.getElementById('headertopo').style.display="none";
	}
	else
	{
		document.getElementById('headerfotos').style.display="none";
		document.getElementById('header').style.display="none";
		document.getElementById('headerarquivos').style.display="none";
		document.getElementById('headermenu').style.display="none";
		document.getElementById('headervideos').style.display="block";
		document.getElementById('headertopo').style.display="block";
	}
}

function mouseOver(td)
{
	td.style.backgroundColor='#e0e7ef';
	td.style.cursor='pointer';
}
function mouseOut(td)
{
	td.style.backgroundColor= '';
	td.style.cursor='';
} 
function mouseDown(url)
{
	window.open(url);
}
