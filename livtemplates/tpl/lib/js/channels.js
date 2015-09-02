$(function(){
	$('#status tr').hover(
		function(){
			$(this).css('background','#F0EFF5');
		},function(){
			$(this).css('background','');	
	})
});

function hg_click_button(e){
	if($(e).hasClass('click_on'))
	{
		$(e).attr('class','click_off');
	}
	else
	{
		$(e).attr('class','click_on');
	}
}
