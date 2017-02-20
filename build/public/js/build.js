$(function() {
    $('.collapsible').collapsible();
    $(".button-collapse").sideNav();
});

function doAlert()
{
    alert("bingo");
}

function editArea(id)
{
    $.get(url + "/home/editArea/"+id, function( data ) {
	$("#main_content").html(data);
    });
}

function editRoom(id)
{
    $.get(url + "/home/editRoom/"+id, function( data ) {
	$("#main_content").html(data);
    });
}