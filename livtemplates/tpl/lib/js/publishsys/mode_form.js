function hg_addArgumentDom(str)
	{
		
		var div = "<div class='form_ul_div clear'><span class='title'>参数名称: </span><input type='text' name='"+str+"name[]' style='width:80px;' class='title'>&nbsp;&nbsp;&nbsp;标识: <input type='text' name='"+str+"sign[]' style='width:80px;' class='title'>&nbsp;&nbsp;默认值: <input type='text' name='"+str+"default_value[]' style='width:50px;'/>&nbsp;&nbsp;下拉框值: <input type='text' name='"+str+"other_value[]' style='width:150px;'/><span>&nbsp;&nbsp;&nbsp;参数类型: </span><select name='"+str+"type[]'><option value='text'>文本框</option><option value ='select'>下拉框</option></select><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		if(str=='')
		{
			$('#extend').append(div);
		}
		else
		{
			$('#out_extend').append(div);
		}
		hg_resize_nodeFrame();
	}
		
	function hg_optionTitleDel(obj)
	{
		if(confirm('确定删除该参数配置吗？'))
		{
			$(obj).parent().parent().remove();
		}
		hg_resize_nodeFrame();
	}
	$(document).ready(function(){
		var t1 = $("form select[name=sort_id]").find('option:selected').val();
		var c1 = $("input[name=referto]").val() + '&sortid=' + t1;
		$("input[name=referto]").val(c1);

		$("form select[name=sort_id]").change(function(){
			var t2 = $("form select[name=sort_id]").find('option:selected').val();
			var c2 = $("input[name=referto]").val() + '&sortid=' + t2;
			$("input[name=referto]").val(c2);
		});	
	});
	
	
	function get_mode_variable()
	{
		var url= './run.php?mid='+gMid+'&a=get_mode_variable&mode_id='+$('#mode_id').val();
		hg_ajax_post(url);
	}

$(function ($) {
	get_mode_variable_back = function(json)
	{	
		data = $.parseJSON(json);
		var ret = {};
		$.each(data, function () {
			ret[this.id] = this;
		});
		data = ret;
		cbox.html( renderBox(data) );
		
		hg_resize_nodeFrame();
	}
	
	$('.pic-view').on('click',function(){
		$('#Filepic').click();
	});
	$('#Filepic').change(function(event){
		var files = this.files;
		for(var i=0;i<files.length;i++){
			var file=files[i];
			var imageType=/image.*/;
			if(!file.type.match(imageType)){
				alert("请上传图片文件");
				continue;
			}
			var reader=new FileReader();
			reader.onload=function(e){
				var imgData=e.target.result,
					target = $('.pic-view').find('img');
				if(target[0]){
					target.attr('src',imgData);
				}else{
					$('<img />').attr('src',imgData).appendTo('.pic-view');
				}
				$('.pic-view').css({'background':'none'});
			}
			reader.readAsDataURL(file);
		}
	});
	
	
	
});