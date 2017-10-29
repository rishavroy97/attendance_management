function ToggleDisplay(id1, id2, button1, button2) {
    var x = document.getElementById(id1);
    var y = document.getElementById(id2);
    var a = document.getElementById(button1);
    var b = document.getElementById(button2);
    
    if (x.style.display === 'block') {
    	return;
    }
    else{
        x.style.display = 'block';
        y.style.display = 'none';
        a.classList.toggle("active");
    	b.classList.toggle("active");
    }
}
function usernamechange(){
    var username = document.getElementById('username');
    var ajax_request = new XMLHttpRequest();
    ajax_request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var availability = this.responseText;
        //console.log(availability);
        document.getElementById('valid').innerHTML = availability;
    }
  };
  ajax_request.open("GET", "username.php?val="+username.value, true);
  ajax_request.send();
}