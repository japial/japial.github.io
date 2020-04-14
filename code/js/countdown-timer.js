// @php
//     $now = Carbon::now();
//     $diff = $endtime->timestamp - $now->timestamp;
//     $distance = $diff*1000;         
// @endphp

function createCountDown(elementId, distance) {
	var x = setInterval(function() {         
	var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	document.getElementById(elementId).innerHTML =  days + ": "+ hours + ": "+ minutes + ": " + seconds + " ";
	if (distance < 0) {
		clearInterval(x);
		document.getElementById(elementId).innerHTML = "ROUND OVER";
	}
		distance = distance - 1000;
	}, 1000);
}
