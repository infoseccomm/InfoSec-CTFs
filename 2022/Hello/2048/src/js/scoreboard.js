function send_score(score) {
	var url  = "/score.php?score=" + score;
	//alert("Over111 " + url);
	var xhr  = new XMLHttpRequest();
	xhr.open('GET', url, true);
	xhr.onload = function () {
    	var users = JSON.parse(xhr.responseText);
    	if (xhr.readyState == 4 && xhr.status == "200") {
    		console.log(users);
		alert(users.message);
    		//console.log(typeof users)
        	//console.table(users);
    	} else {
        	console.error(users);
    	}
	}
	xhr.send(null);
}
