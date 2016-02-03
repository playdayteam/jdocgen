$(document).ready(function () {
    $('.controller_title').bind('click', function () {
        if ($(this).parent('li').children('div').attr('class') == 'collapse') {
            $(this).parent('li').children('span').text('-');
        }
        else {
            $(this).parent('li').children('span').text('+');
        }
    });

    $('#sidebar a').click(function () {
        var scrollTop = $('#sidebar').scrollTop()
        $.cookie('scrollTop', scrollTop, {path: '/'});
    });
});

$('#sidebar').scrollTop($.cookie('scrollTop'));


$(document).ready(function () {
    $("#toggle_menu_control").click(function () {
        $("#sidebar").toggle();
    });

    $("#list-version").change(function () {
        var action = $("#list-form").attr('action');
        var params = {
            'SourceForm[version]': $(this).val()
        };
        $("#list-version").attr("disabled", "disabled");
        $.post(action, params).done(function (data) {
            window.location.href = $(location).attr('href');
        });
    });
});