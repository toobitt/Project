// JavaScript Document

//推荐模块是否存在，0没有，1为存在，初始化为0，通过ajax返回，并设置hasselect=1
var hasselect =0;
var soapSearch;
function rec(id,obj)
{
	var htmltop = document.documentElement.scrollTop;
	jQuery('#rn_info').text('');
	jQuery('#sure_bt').attr('disabled','');
	jQuery('#sure_bt').css('background-color','#09C');
	jQuery.ajax({
		url:'admin.php',
		type:'post',
		data:{
			a:'rec_info',
			m:'user',
			r:hasselect,
			id:id
		},
		error:function(){alert('ajax request error');},
		success:function(html){
			html = new Function("return"+html)();
			insertInfo(html);
			hasselect =1;
			document.getElementById('rec_div').style.display='block';
			document.getElementById('rec_div').style.top=(htmltop+200)+"px";
		}
	});
}

function insertInfo(html)
{
	jQuery('#area').val(html.blog);
	jQuery('#avatar_id').val(html.id);
	jQuery('#avatar_img').val(html.img);
	jQuery('#rec_avatar').attr('src',html.avatar);
	jQuery('#rec_name').html(html.username);
	if(hasselect == 0)
	{
		inputSelect(html.soap);
	}
}
function inputSelect(obj)
{
	soapSearch = obj[1];
	var opt = '';
	var firstIndex =0;
	for(var i in obj[0])
	{
		if(firstIndex == 0)
		{firstIndex =i;}
		opt += "<option value='"+i+"'>"+obj[0][i]+"</option>";
	}
	jQuery('#ft_st').html(opt);
	opt ="";
	for(var i in obj[1][firstIndex])
	{
		opt += "<option value='"+i.substr(1)+"'>"+obj[1][firstIndex][i]+"</option>";
	}
	if(obj[1][firstIndex])
	{
		jQuery('#sd_st').html(opt);
	}
	else
	{
		jQuery('#sd_st').hidden();
	}
}

function change_first(index)
{
	var se = document.getElementById('sd_st');
	se.innerHTML = '';
	if(!soapSearch[index])
	{
		se.style.display='none';
	}
	else
	{
		se.style.display='inline';
		for(var v in soapSearch[index])
		{
			var opt = document.createElement('option');
			opt.value = v;
			opt.innerHTML = soapSearch[index][v];
			se.appendChild(opt);
		}
	}
}

function cancel_box()
{
	document.getElementById('rec_div').style.display='none';
}

function submit_box(obj)
{
	obj.disabled='disabled';
	jQuery('#sure_bt').css('background-color','#666666');
	jQuery('#rn_info').text('推荐中…………');
	var sd_st = $("#sd_st").find("option:selected").val();
	if(!sd_st)
	{
		var sd_st = $("#ft_st").find("option:selected").val();
	}
	jQuery.ajax({
		url:'admin.php',
		type:'post',
		data:{
			a:'soap_info',
			m:'user',
			id:jQuery('#avatar_id').val(),
			img:jQuery('#avatar_img').val(),
			name:jQuery('#rec_name').text(),
			blog:jQuery('#area').val(),
			soapId:sd_st
		},
		error:function(){alert('ajax request error');},
		success:function(html){
			jQuery('#rn_info').text(html);
			setTimeout('cancel_box();',500);
		}
	});
}