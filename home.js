var displaywelcome = document.getElementById ('welcome');

function welcomemessage () {
  var xmlhttp = new XMLHttpRequest ();
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      displaywelcome.innerHTML = 'welcome  ' + xmlhttp.responseText;
    }
  };
  xmlhttp.open ('GET', 'homephp.php');
  xmlhttp.send ();
}
welcomemessage ();
