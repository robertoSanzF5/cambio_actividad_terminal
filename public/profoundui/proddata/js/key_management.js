
function init() {

  if (LOADMSG != "") {
  
    // For IE8 running in compatability view or IE7 mode,
    // the main background image disappears when alert is run 
    // directly onload. 
    
    setTimeout(function() {
      alert(LOADMSG);
      if (REDIRECT) {
        location.href = "/profoundui/welcome";
      }
      else {
        document.keyForm.key.focus();
      }
    }, 1);
    
  }

}

function requestKey() {

  var url = "http://www.profoundui.com/activation?";
  
  url += "prod=profoundui" + 
         "&sysname=" + encodeURIComponent(SYSNAME) +
         "&serial=" + encodeURIComponent(SERIAL) +
         "&pgroup=" + encodeURIComponent(PGROUP) +
         "&osrelease=" + encodeURIComponent(OSRELEASE) + 
         "&model=" + encodeURIComponent(MODEL);
  if (KEY != "") {
    url += "&key=" + encodeURIComponent(KEY);
  }
  
  window.open(url, "_blank");
  
}