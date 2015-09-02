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
		url : './run.php?mid=' + gMid + '&a=get_depart&ajax=1',
		edit_url : './run.php?mid=' + gMid + '&a=depart_detail&ajax=1',
	});
	
	var del_hospital = './run.php?mid=' + gMid + '&a=delete_hospital&ajax=1';
	$.MC.header_doctor.on('click', '.del_hospital', function(){
		var self = $(this),
			id = self.attr('_id');
		self[0].disabled = true;
		$.globalAjax(self, function(){
			return $.getJSON( del_hospital, {id : id}, function( json ){
				self[0].disabled = false;
				if( json && json['callback'] ){
					eval( json['callback'] );
					return;
				}
				if( json && json['error_msg'] ){
					$.MC.doctor.doctor('myTip', self, json['error_msg'], -100);
				}else if( $.isArray( json ) && json[0] == 'success' ){
					$.MC.doctor.doctor('myTip', self, '删除成功', -100);
					setTimeout(function(){
						top.$.closeFormWin();
					}, 1500);
				}
			} );
		});
	}).on('click', '.back_hospital', function(){
		top.$.closeFormWin();
		top.$('#mainwin')[0].contentWindow.location.reload();
	});
	
	$.popdepartConfig = {
		popdeparttpl : $('#popdepart-tpl').html(),
		departenvirtpl : $('#departenvir-tpl').html(),
		del_url : './run.php?mid=' + gMid + '&a=delete&ajax=1',
		save_method : 'create',
		update_method : 'update',
		upload_url : './run.php?mid=' + gMid + '&a=upload_pic&ajax=1'
	};
	
	$.doctorConfig = {
		url : './run.php?mid=' + gMid + '&a=get_doctor',
		doctortpl : $('#doctor-tpl').html(),
		doctorurl : './run.php?mid=' + gMid + '&a=form&infrm=1',
		delurl : './run.php?mid=' + gMid + '&a=delete_doctor&ajax=1'
	}
});
