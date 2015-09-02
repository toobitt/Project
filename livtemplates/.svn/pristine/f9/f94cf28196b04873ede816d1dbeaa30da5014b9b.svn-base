$(function(){
	$.MC = {
		depart : $('.m2o-depart'),
		popdepart : $('.pop-depart'),
		doctor : $('.m2o-doctor'),
		header_doctor : $('.m2o-header')
	};
	
	$.MC.depart.depart({
		departtpl : $('#depart-tpl').html(),
		departlitpl : $('#departli-tpl').html(),
		url : './run.php?mid=' + gMid + '&a=get_depart&ajax=1'
	});
	
	$.MC.header_doctor.on('click', '.back_hospital', function(){
		location.href = document.referrer;
	});
	
	$.doctorConfig = {
		url : './run.php?mid=' + gMid + '&a=get_schedules',
		extend : 'reservation',
		doctortpl : $('#doctor-tpl').html(),
		doctorurl : './run.php?mid=' + gMid + '&a=form&infrm=1',
		delurl : './run.php?mid=' + gMid + '&a=delete_doctor&ajax=1'
	}
});
