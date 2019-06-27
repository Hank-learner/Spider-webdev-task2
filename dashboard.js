document.getElementById ('smallnav').addEventListener ('click', smallnav);

function smallnav () {
  var x = document.getElementById ('nav-right');
  if (x.style.display === 'block') {
    x.style.display = 'none';
  } else {
    x.style.display = 'block';
  }
}

var displaytable = document.getElementById ('disptab');
var displaylist = document.getElementById ('displist');
var displaynewtitle = document.getElementById ('dispaddtitle');
var addsetform = document.getElementById ('dispaddset');
var testdisplay = document.getElementById ('test');
var additemform = document.getElementById ('dispaddtitle');
var setvalue;
function xmlhttprequest (formdata, display) {
  var xmlhttp = new XMLHttpRequest ();
  var formData = new FormData ();
  formData = formdata;
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      display.innerHTML = xmlhttp.responseText;
    }
  };
  xmlhttp.open ('POST', 'dashboardphp.php');
  xmlhttp.send (formData);
}

function displayaddset () {
  var formData = new FormData ();
  formData.append ('display', 'displayaddset');
  xmlhttprequest (formData, addsetform);
}

function displayusersets () {
  var formData = new FormData ();
  formData.append ('display', 'displayusersets');
  xmlhttprequest (formData, displaylist);
  testdisplay.innerHTML = '';
}
displayusersets ();
displayaddset ();
var valued;
function displayusersettable (value) {
  if (value == 'select0') {
    displaytable.innerHTML = '';
    displaynewtitle.innerHTML = '';
  } else {
    valued = value;
    setvalue = document.getElementById (value).innerHTML;

    var formData = new FormData ();
    formData.append ('setvalue', setvalue);
    formData.append ('display', 'displaytable');

    xmlhttprequest (formData, displaytable);
    if (value == 'select0') {
      displaynewtitle.innerHTML = '';
    } else {
      displaynewtitle.innerHTML = '<h2><u>' + setvalue + '</u></h2>';
      var formdata = new FormData ();
      formdata.append ('setvalue', setvalue);
      formdata.append ('display', 'displayaddtitleform');
      xmlhttprequest (formdata, displaynewtitle);
    }
  }
}

addsetform.addEventListener ('submit', addset);
function addset (event) {
  var newsetname = document.getElementById ('inputset').value;

  var formData = new FormData ();
  formData.append ('newsetname', newsetname);
  xmlhttprequest (formData, document.getElementById ('dispsetaddindication'));
  event.preventDefault ();
  displayusersets ();
  displaytable.innerHTML = '';
  displaynewtitle.innerHTML = '';
  testdisplay.innerHTML = '';
}

additemform.addEventListener ('submit', additem);
function additem (event) {
  var titletoadd = document.getElementById ('inputtitle').value;
  var expensetoadd = document.getElementById ('inputexpenses').value;
  var datetimetoadd = document.getElementById ('inputdatetime').value;

  var formData = new FormData ();
  formData.append ('setvalue', setvalue);
  formData.append ('expense', expensetoadd);
  formData.append ('title', titletoadd);
  formData.append ('datetime', datetimetoadd);
  formData.append ('display', 'updateaddition');

  xmlhttprequest (formData, testdisplay);
  event.preventDefault ();

  displayusersettable (valued);
}

additemform.addEventListener ('click', deleteset);
function deleteset (e) {
  if (e.target.classList.contains ('delset')) {
    if (confirm ('Are you sure to delete the set and all its details?')) {
      var formdata = new FormData ();
      formdata.append ('setvalue', setvalue);
      formdata.append ('display', 'deleteset');
      xmlhttprequest (formdata, displaynewtitle);
      displayusersets ();
      displaytable.innerHTML = '';
    }
  }
}

displaytable.addEventListener ('click', updatetable);
function updatetable (e) {
  var delitem = e.target.classList.contains ('delitem');
  var settleitem = e.target.classList.contains ('settleitem');

  if (delitem || settleitem) {
    var tr = e.target.parentElement.parentElement;
    var c = tr.childNodes;

    var title = c[0].innerHTML;
    var expense = c[1].innerHTML;
    var datetime = c[2].innerHTML;
    var formData = new FormData ();

    formData.append ('setvalue', setvalue);
    formData.append ('title', title);
    formData.append ('expense', expense);
    formData.append ('datetime', datetime);

    if (delitem) {
      // var table = tr.parentElement;
      formData.append ('display', 'updatedeletion');
      //table.removeChild (tr);
    } else if (settleitem) {
      formData.append ('display', 'updatesettled');
    }
    xmlhttprequest (formData, testdisplay);
    displayusersettable (valued);
  }
}
