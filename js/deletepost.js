document.getElementById("deleteButton").addEventListener("click", function () {
    var width = 1000;
    var height = 950;
    var left = (window.innerWidth - width) / 2;
    var top = (window.innerHeight - height) / 2;

    var options = 'width=1000,height=1000,left=' + left + ',top=' + top + ',resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no';
    var popupURL = 'deletepost.php';

    var popupWindow = window.open(popupURL, 'PopupWindow', options);
});
