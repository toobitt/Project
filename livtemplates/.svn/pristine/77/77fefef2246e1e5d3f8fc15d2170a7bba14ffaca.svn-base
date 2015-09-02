$(function(){
	$('#select_program').click(function(){
		if($('#program_box').css('display') == 'none')
		{
			$('#program_box').slideDown();
		}
		else
		{
			$('#program_box').slideUp();
		}
	})
	
	$('#program_box span').click(function(){
		var st = $(this).attr('st');
		var et = $(this).attr('et');
		$('#selected_program').text('当前选取节目:' + $(this).text());
		$('#program_box').slideUp();
		$('#select_program').text('重新选节目');
		$('#start_time').val(st);
		$('#end_time').val(et);
	})
})
