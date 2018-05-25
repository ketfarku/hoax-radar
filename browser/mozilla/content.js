
window.onerror = function (message, source, lineno, colno, error) {
  console.error(message);
  return true;
};

var baseUrl = "https://mkkp-hoax-radar.lazos.me/";

var xhr = new XMLHttpRequest();
xhr.open("GET", baseUrl + "blacklist/", true);
xhr.onreadystatechange = function () {
  if (xhr.readyState === 4) {
    var blacklist = JSON.parse(xhr.responseText);
    var hostname = window.location.hostname;
    for (var d in blacklist) {
      if (!blacklist.hasOwnProperty(d)) {
        continue;
      }
      var item = blacklist[d];
      var domain = item.domain;
      var search = '.' + domain;
      if (hostname.indexOf(search) > -1 || hostname === domain) {
        var pop = new XMLHttpRequest();
        (function (pop) {
          pop.open("GET", baseUrl + "overlay.php?alert=" + encodeURIComponent(item.alert) + "&image=" + encodeURIComponent(item.image) + "&auditor=" + encodeURIComponent(item.auditor));
          pop.onreadystatechange = function () {
            if (pop.readyState === 4) {
              var body = document.getElementsByTagName('body');
              if (body.length < 1) {
                var timeout = setInterval(function () {
                  var body = document.getElementsByTagName('body');
                  if (body.length > 0) {
                    body[0].innerHTML += pop.responseText;
                    clearInterval(timeout);
                  }
                }, 1000);
              } else {
                body[0].innerHTML += pop.responseText;
              }
            }
          };
          pop.send();
        })(pop);
        break;
      }
    }
  }
};
xhr.send();
