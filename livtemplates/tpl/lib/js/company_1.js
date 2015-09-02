function hg_column_manage(sid, title){	$('#auth_title').html(title + ' - 栏目管理');	if ($('#add_auth').css('display') == 'none')	{		var url = "?mid="+gMid+"&a=column&sid="+sid;		$.get(url, function(data) {			$('#auth_form').html(data);		});		$('#add_auth').css({'display':'block'});		$('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){			hg_resize_nodeFrame();		});	}	else	{		hg_closeAuth();	}}function hg_org_manage(fid, title){	$('#auth_title').html(title + ' - 组织管理');	if ($('#add_auth').css('display') == 'none')	{		var url = "?mid="+gMid+"&a=org&fid="+fid;		$.get(url, function(data) {			$('#auth_form').html(data);		});	    $('#add_auth').css({'display':'block'});	    $('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){		    hg_resize_nodeFrame();	    });	}	else	{		hg_closeAuth();	}}function hg_user_manage(cid, title){	$('#auth_title').html(title + ' - 用户管理');	if ($('#add_auth').css('display') == 'none')	{		var url = "?mid="+gMid+"&a=user&cid="+cid;		$.get(url, function(data) {			$('#auth_form').html(data);		});		$('#add_auth').css({'display':'block'});	 	$('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){			hg_resize_nodeFrame();		});	}	else	{		hg_closeAuth();	}}//关闭面板function hg_closeAuth(){	$('#log_box').html();	$('#add_auth').animate({'right':'120%'},'normal',function(){		$('#add_auth').css({'display':'none','right':'0'});		hg_resize_nodeFrame();	});}$(function() {	$('.columnList input[name="checkall"]').live('click', function() {		var val = $(this).attr('checked');		if (val) {			$('.columnList input[name="column_id"]').attr('checked', val);			$('.columnList input[name="org_id"]').attr('checked', val);			$('.columnList input[name="user_id"]').attr('checked', val);		}else {			$('.columnList input[name="column_id"]').removeAttr('checked');			$('.columnList input[name="org_id"]').removeAttr('checked');			$('.columnList input[name="user_id"]').removeAttr('checked');		}	});	//添加栏目	$('.addColumn').live('click', function() {		var url = $(this).attr('uri');		$.get(url, function(data) {			$('.columnList').hide();			$('#auth_form').append(data);		});	});		//编辑栏目	$('.updateColumn').live('click', function() {		var url = $(this).attr('uri');		$.get(url, function(data) {			$('.columnList').hide();			$('#auth_form').append(data);		})	});	//保存栏目	$('#saveColumn').live('click', function() {		var url = '?mid='+gMid;		var params = {			pid : parseInt($('.columnForm select[name="pid"]').val()),			siteId : parseInt($('.columnForm input[name="site_id"]').val()),			col_name : $.trim($('.columnForm input[name="col_name"]').val()),			col_intro : $.trim($('.columnForm textarea[name="col_intro"]').val()),			outsideLink : parseInt($('.columnForm input:checked[name="outsideLink"]').val()),			col_link : $.trim($('.columnForm input[name="col_link"]').val())		};		var id = parseInt($('.columnForm input[name="id"]').val());		if (id) {			params.a = 'updateColumn';			params.id = id;			$.post(url, params, function(data) {				if (data) {					$('#column_'+params.id+' td:eq(1)').text(params.col_name);					$('.columnList').show();					$('.columnForm').remove();				}			});		}else {			params.a = 'addColumn';			$.post(url, params, function(data) {				if (data) {					var url = "./run.php?mid="+gMid+"&a=column&sid="+params.siteId+"&infrm=1";					$.get(url, function(str) {						$('#auth_form').html(str);					});				}			});		}	});		//删除单个栏目	$('.deleteColumn').live('click', function() {		if (confirm('确定删除此记录吗？'))		{			var url = $(this).attr('uri');			$.getJSON(url, function(data) {				$('#column_'+data[0]).remove();			});		}	});	//批量删除栏目	$('.batchDelColumn').live('click', function() {		//获取栏目id		var ids = [];		$('.columnList input[name="column_id"]').each(function() {			if ($(this).attr('checked'))			{				ids.push($(this).val());			}		});		ids = ids.join(',');		if (!ids) {			alert('请选择要删除的栏目');		}else {			if (confirm('确定删除这些记录吗？'))			{				var url = '?mid='+gMid+'&a=deleteColumn&id='+ids;				$.getJSON(url, function(data) {					for (var i=0,l=data.length;i<l;i++)					{						$('#column_'+data[i]).remove();					}				});			}		}	});		//添加组织	$('.addOrg').live('click', function() {		var url = $(this).attr('uri');		$.get(url, function(data) {			$('.columnList').hide();			$('#auth_form').append(data);		});	});		//编辑组织	$('.updateOrg').live('click', function() {		var url = $(this).attr('uri');		$.get(url, function(data) {			$('.columnList').hide();			$('#auth_form').append(data);		})	});		//保存组织	$('#saveOrg').live('click', function() {		var url = '?mid='+gMid;		var params = {			fid : parseInt($('.columnForm select[name="fid"]').val()),			org_name : $.trim($('.columnForm input[name="org_name"]').val()),			org_intro : $.trim($('.columnForm textarea[name="org_intro"]').val())		};		var id = parseInt($('.columnForm input[name="id"]').val());		if (id) {			params.a = 'updateOrg';			params.id = id;		}else {			params.a = 'addOrg';		}		$.post(url, params, function(data) {			if (data) {				var cid = parseInt($('.columnForm input[name="cid"]').val());				var url = "./run.php?mid="+gMid+"&a=org&fid="+cid+"&infrm=1";				$.get(url, function(str) {					$('#auth_form').html(str);				});			}		});	});		//删除单个组织	$('.deleteOrg').live('click', function() {		if (confirm('确定删除此记录吗？'))		{			var url = $(this).attr('uri');			$.getJSON(url, function(data) {				$('#org_'+data[0]).remove();			});		}	});		//批量删除组织	$('.batchDelOrg').live('click', function() {		//获取组织id		var ids = [];		$('.columnList input[name="org_id"]').each(function() {			if ($(this).attr('checked'))			{				ids.push($(this).val());			}		});		ids = ids.join(',');		if (!ids) {			alert('请选择要删除的组织');		}else {			if (confirm('确定删除这些记录吗？'))			{				var url = '?mid='+gMid+'&a=deleteOrg&id='+ids;				$.getJSON(url, function(data) {					data = data[0];					for (var i=0,l=data.length;i<l;i++)					{						$('#org_'+data[i]).remove();					}				});			}		}	});		//添加用户	$('.addUser').live('click', function() {		var url = $(this).attr('uri');		$.get(url, function(data) {			$('.columnList').hide();			$('#auth_form').append(data);		});	});		//编辑用户	$('.updateUser').live('click', function() {		var url = $(this).attr('uri');		$.get(url, function(data) {			$('.columnList').hide();			$('#auth_form').append(data);		})	});		//保存用户	$('#saveUser').live('click', function() {		var c_id = parseInt($('.columnForm input[name="c_id"]').val());		$('#userForm').submit();		$('#innerFrame').load(function() {			var url = "?mid="+gMid+"&a=user&cid="+c_id;			$.get(url, function(str) {				$('#auth_form').html(str);			});		});	});		//删除单个用户	$('.deleteUser').live('click', function() {		if (confirm('确定删除此记录吗？'))		{			var url = $(this).attr('uri');			$.getJSON(url, function(data) {				$('#user_'+data[0]).remove();			});		}	});		//批量删除用户	$('.batchDelUser').live('click', function() {		//获取用户id		var ids = [];		$('.columnList input[name="user_id"]').each(function() {			if ($(this).attr('checked'))			{				ids.push($(this).val());			}		});		ids = ids.join(',');		if (!ids) {			alert('请选择要删除的用户');		}else {			if (confirm('确定删除这些记录吗？'))			{				var url = '?mid='+gMid+'&a=deleteUser&id='+ids;				$.getJSON(url, function(data) {					data = data[0];					for (var i=0,l=data.length;i<l;i++)					{						$('#user_'+data[i]).remove();					}				});			}		}	});	$('#isLink').live('click', function() {		$('#columnLink').show();	});	$('#notLink').live('click', function() {		$('#columnLink').hide();	});	$('.columnForm .cancelBtn').live('click', function() {		$('.columnList').show();		$('.columnForm').remove();	});});