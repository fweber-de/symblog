/*
 symblog
 JS

 (c) Florian Weber <florian.weber.dd@icloud.com>
 */

$(document).ready(function () {

    var submitted = false;

    //editor
    $('.sb-editor').trumbowyg();

    $('.sb-editor').keyup(function () {
        $('#input-text').html($(this).html());
    });

    $('#form-post').submit(function () {
        $('#input-text').html($('.sb-editor').html());
        submitted = true;
        $(this).submit();
    });

    window.addEventListener("beforeunload", function (e) {
        if (!submitted) {
            var confirmationMessage = "All unsaved effort will be lost!";

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage;                            //Webkit, Safari, Chrome
        }
    });

});
