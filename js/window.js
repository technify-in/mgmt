function openTheWindow(url, querywindow_width, querywindow_height)
{
var winW = 1024, winH = 20;
if (document.body && document.body.offsetWidth) {
 winW = document.body.offsetWidth;
 winH = document.body.offsetHeight;
}
if (document.compatMode=='CSS1Compat' &&
    document.documentElement &&
    document.documentElement.offsetWidth ) {
 winW = document.documentElement.offsetWidth;
 winH = document.documentElement.offsetHeight;
}
if (window.outerWidth && window.outerHeight) {
 winW = window.outerWidth;
 winH = window.outerHeight;
}

window.open(url,'','resizable=yes,scrollbars=yes,toolbar=0,status=1,menubar=0,width='+winW+',height='+winH+'');
}

