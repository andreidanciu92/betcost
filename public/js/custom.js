$(function () {
    $('#datetimepicker1').datetimepicker({
        locale: 'it',
        format: 'D/M/Y HH:mm'
    });
});

function updateSelect() {
    let sel = $("#team_a").val();

    $("#team_b option[value=" + sel + "]")
        .attr("disabled", "disabled")
        .siblings().removeAttr("disabled");
}

