var i = 0;
var txt = "Operator : You're required to use <span class='red'>passkey</span> before entering this page!";
var speed = 50; /* The speed/duration of the effect in milliseconds */
var txtKini = "";

$(document).ready(function(){
	
typeWriter();
setInterval(blinkInOut, 1000);

});

function typeWriter() {
  if (i < txt.length) {
	txtKini += txt.charAt(i);
    $("#message").html(txtKini);
    i++;
    setTimeout(typeWriter, speed);
  }else if(i == txt.length){
  
    $("#message").append("<br><span id='copyright'>&copy; FGroupIndonesia &trade;.</span>");
  }
  
  
}

function blinkInOut(){
	
    $('#copyright').fadeOut(500);
    $('#copyright').fadeIn(600);
	$('.red').fadeOut(500);
	$('.red').fadeIn(700);
}
