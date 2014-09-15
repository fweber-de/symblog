/*
 symblog
 JS

 (c) Florian Weber <florian.weber.dd@icloud.com>
 */

$(document).ready(function () {

    //editor
    $('.sb-editor').trumbowyg();

    $('.sb-editor').keyup(function () {
        $('#input-text').html($(this).html());
    });

    $('#form-post').submit(function () {
        $('#input-text').html($('.sb-editor').html());
        $(this).submit();
    });

    $(window).bind("beforeunload", function () {
        return confirm("Do you really want to close?");
    });

});
