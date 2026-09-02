$(function(){
	let url_atual = window.location.href;
	let url = getFinalUrl(url_atual);

	if(url[0] == ""){
		$(".menu-principal #menu-item-home").addClass("active");
	}
	else{
		$(".menu-principal #menu-item-"+url[0]).addClass("active");
	}
});

function getFinalUrl(url_atual){
	let url = [];
	let c = 0, n = -1;
	for (var i = 0; i < url_atual.length ; i++) {
		let key = url_atual.charAt(i);
		if(key == "/"){
			c++;
			if(c > 3){
				n++;
				url.push("");
			}
		} else {
			if(n > -1) url[n] += key; 
		}
	}
	console.log(url);
	return url;
}