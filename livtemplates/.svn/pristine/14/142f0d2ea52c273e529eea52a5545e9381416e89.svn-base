jQuery(function(){
	(function($){
		 $.voteFun = function() {
			var items = $('.option-list').find('.option_title'),
				btn = items.find( '.option_del' ),
				len = items.length,
				istrue = false;
			( len > 2 ) ? istrue = true : istrue = false;		//初始化投票选项大于2出现删除按钮
			btn[ istrue ? 'show' : 'hide' ]();
		};
		$.voteFun();
	})($);
})

/*打开投票滑动窗口*/
function hg_showQuestion(html)
{
	if($('#add_question').css('display')=='none')
	{
		hg_slideQuestion(html)
	}
	else
	{	
		var tid = $('#add_question').find('h2').data('id');
		if(this.id == tid){
			hg_closeQuestionTpl();
		}else{
			hg_slideQuestion(html)
		}
	}
}

function hg_slideQuestion(html){
	$('#add_question').css({'display':'block'});
	$('#question_option_con').html(html);
	$('#add_question').animate({'right':'0'},'normal',function()
	{
		hg_resize_nodeFrame();
	});
	var dHeight = $('.content').height();
	var hHeight = $('#add_question').find('h2').height();
	var tHeight = $('#add_question').find('.total_info').height();
	var bHeight = (dHeight - hHeight - tHeight + 5) + 'px';
	$('.vote-result').css({'height' : bHeight, 'max-height': bHeight});
}
/*关闭滑动窗口*/
function hg_closeQuestionTpl()
{
	$('#add_question').animate({'right':'-508px'},'normal',function(){$('#add_question').css({'display':'none'});hg_resize_nodeFrame();});
}

/*添加投票
function hg_voteQuestionCreate()
{
	return hg_ajax_submit('voteQuestion','','','returnBackId');
	
}
function returnBackId(obj)
{
	if(obj)
	{
		hg_closeQuestionTpl();
	}
}*/
/*添加选项*/
function hg_optionTitleAdd(obj,type)
{
	var f_prev_id = $(obj).parent().prev().attr('id');
	$('#' + f_prev_id).each(function(){
	//	$(this).find('span[name^="optionFileStyle"]').attr('class','optionFileStyle');
	});
	if (f_prev_id)
	{
		var ii = f_prev_id.substr(11,1);

		if (f_prev_id.length > 12)
		{
			ii = f_prev_id.substr(11,2);
		}
	}
	
	
	var i = $('#questionBox #' + f_prev_id + ' .option_title').length;
	if (i == 0)
	{
		i = $('#option_box_1').children().length;
	}
	var div = '<div class="option_title add">';
		div += '<span class="upload_style"></span>';
		if (i > 8)
		{
			div += '<span class="num_a num_b">' + (i + 1) + '.</span>';
		}
		else
		{
			div += '<span class="num_a">' + (i + 1) + '.</span>';
		}
		div += '<input onblur="hg_optionChecked(this);" type="text" name="option_title_' + (ii - 1) + '[]" value="" style="width:290px"/>';
		div += '<span class="vote_question_files_b"></span>';
		if (type == 'question')
		{
			div += '<span class="option_del_box"><span name="option_del[]" class="option_del" title="删除" onclick="hg_optionTitleDel(this,0,1);"></span></span>';
		}
		else
		{
			div += '<span class="option_del_box"><span name="option_del[]" class="option_del" title="删除" onclick="hg_optionTitleDel(this)"></span></span>';
		}
		div += '<span class="add-data-btn">添加内容</span><input class="hidden-id"  type="hidden" name="publishcontent_id[]" /><span name="optionFileStyle[]" class="optionFileStyle_c"><input type="file" name="option_files_'+(ii-1)+'_'+i+'" class="option_style" hidefocus></span>';
		if (type == 'question')
		{
			div += '<span onclick="hg_optionDescribe(this);" class="option_describe" style="left:65px;" title="选项描述"></span>';
			div += '<span style="position: relative;left: 148px;display: inline-block;margin-right:2px;">初始投票数<input name="ini_num[]" value="" style="width: 30px;margin-left: 5px;" /></span>';
			div += '<span name="option_describe_box[]"></span>';
		//	div += '<span class="describe_overflow" style="left:72px;"></span>';
		}
		div += '</div>';
	$('#' + f_prev_id).append(div);
	$.voteFun();
	if (i==19)
	{
		$(obj).hide();
	}
	
	hg_resize_nodeFrame();
}
/*删除选项  至少保留2个选项*/
var gObject = '';
var gFFId = '';
function hg_optionTitleDel(obj,id,type)
{
	var f_f_id = $(obj).parent().parent().parent().attr('id');
	gFFId = f_f_id;
	if (f_f_id)
	{
		var jj = f_f_id.substr(11,1);
	
		if (f_f_id.length > 12)
		{
			jj = f_f_id.substr(11,2);
		}
	}
	if (id)
	{
		gObject = obj;
		if(confirm('确定删除该选项吗？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delQuestionOption&id=' + id;
			hg_ajax_post(url,'','','delQuestionOption_back');
		}
	}
	else
	{
		$(obj).parent().parent().remove();
	}
	
	var i = 1;
	$($('#' + f_f_id).find('.num_a')).each(function(){
		$(this).html(i + '.');
		if (i < 10)
		{
			$(this).removeAttr('class');
			$(this).attr('class', 'num_a');
		}
		i ++;
	});
	
	var j = 0;
	$('#'+f_f_id+' input[name^="option_files_"]').each(function(){
		$(this).attr('name','option_files_' + (jj - 1) + '_' + j);
		j ++;
	});

	if (type == 1)
	{
		if ($('#' + f_f_id + ' .option_title').length == 2)
		{
			$('#' + f_f_id + ' .option_del').hide();
		}
		if ($('#' + f_f_id + ' .option_title').length < 20)
		{
			$('#add_button_' + jj).show();
		}
	}
	else
	{
		if ($('#questionBox #' + f_f_id + ' .option_title').length == 2)
		{
			$('#' + f_f_id + ' .option_del').hide();
			$('#' + f_f_id).each(function(){
			//	$(this).find('span[name^="optionFileStyle"]').attr('class','optionFileStyle_c');
			});
		}
		if ($('#questionBox #' + f_f_id + ' .option_title').length < 20)
		{
			$('#add_button_' + jj).show();
		}
	}
	
	
	/*修改投票选项描述下标*/
	var option_describe = 0;
	$('#' + f_f_id + ' input[name^="option_title_0"]').each(function(){
		$(this).parent().find('textarea[name^="option_describes"]').attr('name','option_describes['+option_describe+']');
		option_describe ++;
	});
	hg_resize_nodeFrame();
}

function delQuestionOption_back(obj)
{
	if (obj)
	{
		$(gObject).parent().parent().remove();
		$.voteFun();
		var i = 1;
		$($('#' + gFFId).find('.num_a')).each(function(){
			$(this).html(i + '.');
			if (i < 10)
			{
				$(this).removeAttr('class');
				$(this).attr('class', 'num_a');
			}
			i ++;
		});
		if (gFFId)
		{
			var jj = gFFId.substr(11,1);
	
			if (gFFId.length > 12)
			{
				jj = gFFId.substr(11,2);
			}
		}
		
		var j = 0;
		$('#' + gFFId +' input[name^="option_files_"]').each(function(){
			$(this).attr('name','option_files_' + (jj - 1) + '_' + j);
			j ++;
		});
		if ($('#questionBox #' + gFFId + ' .option_title').length == 2)
		{
			$('#' + gFFId + ' .option_del').hide();
			$('#' + gFFId).each(function(){
			//	$(this).find('span[name^="optionFileStyle"]').attr('class','optionFileStyle_c');
			});
		}
		if ($('#questionBox #' + gFFId + ' .option_title').length < 20)
		{
			$('#add_button_' + jj).show();
		}
		/*修改投票选项描述下标*/
		var option_describe = 0;
		$('#' + gFFId + ' input[name^="option_title_0"]').each(function(){
			$(this).parent().find('textarea[name^="option_describes"]').attr('name','option_describes['+option_describe+']');
			option_describe ++;
		});
	}
}

/*单选、多选 选择*/
function hg_option_select(obj)
{
	if($(obj).attr('checked') == 'checked')
	{
		$(obj).parent().find('input[name^="option_type"]').removeAttr('checked');
		$(obj).attr('checked','checked');
	}
}

/*问卷状态*/
var gVoteId = '';
function hg_vote_state(id)
{
	gVoteId = id;
	var url = './run.php?mid=' + gMid + '&a=voteState&id=' + id;
	hg_ajax_post(url,'','','voteState_back');
}
function voteState_back(obj)
{
	if (obj==1)
	{
		$('#audit_' + gVoteId).html('已审核');
		$('#vote_state_' + gVoteId).html('打回');
	}
	else
	{
		$('#audit_' + gVoteId).html('待审核');
		$('#vote_state_' + gVoteId).html('审核');
	}
	$('#v_opearte_' + gVoteId).hide();
}
/*是否有其他选项*/
var gVoteQuestionId = '';
function hg_voteQuestionIsOther(id)
{
	gVoteQuestionId = id;
	var url = './run.php?mid=' + gMid + '&a=isOtherOption&id=' + id;
	hg_ajax_post(url,'','','isOtherOption_back');
}
function isOtherOption_back(obj)
{
	if (obj==1)
	{
		$('#is_other_' + gVoteQuestionId).addClass('a');
		$('#is_other_' + gVoteQuestionId).removeClass('b');
		$('#is_other_' + gVoteQuestionId).attr('title','有');
	}
	else
	{
		$('#is_other_' + gVoteQuestionId).addClass('b');
		$('#is_other_' + gVoteQuestionId).removeClass('a');
		$('#is_other_' + gVoteQuestionId).attr('title','无');
	}
}

/*查看投票选项*/
function hg_getQestionOption(id)
{
	this.id = id;
	var url = './run.php?mid=' + gMid + '&a=getQestionOption&id=' + id;
	hg_ajax_post(url);
	
}
function getQestionOption_back(html)
{	
	hg_showQuestion(html);

}
/*logo隐藏事件*/
function hg_logo_value()
{
	$('#logo_img').hide();
}

/*删除问卷分类时验证是否有问卷信息*/
var gGroupId = '';
var gGroupName = '';
function hg_delVoteChecked(id,name)
{
	gGroupId = id;
	gGroupName = name;
	var url = './run.php?mid=' + gMid + '&a=delVoteChecked&id=' + id;
	hg_ajax_post(url,'','','delVoteChecked_back');
}
function delVoteChecked_back(obj)
{
	if (obj == 0)
	{
		if (confirm('确定删除' + gGroupName+ '？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + gGroupId;
			hg_ajax_post(url);
		}
	
	}
	else
	{
		if (confirm('[' + obj + '] 在用此分类，确定删除' + gGroupName + '吗？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + gGroupId;
			hg_ajax_post(url);
		}
	}
}

/*删除问卷时验证是否有投票*/
var gVoteDelId = '';
var gVoteDelTitle = '';
function hg_delQuestionChecked(id,title)
{
	gVoteDelId = id;
	gVoteDelTitle = title;
	var url = './run.php?mid=' + gMid + '&a=delQuestionChecked&id=' + id;
	hg_ajax_post(url,'','','delQuestionChecked_back');
}
function delQuestionChecked_back(obj)
{
	if (obj == 0)
	{
		if (confirm('确定删除' + gVoteDelTitle+ '？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + gVoteDelId;
			hg_ajax_post(url);
		}
	
	}
	else
	{
		if (confirm('['+ obj +'] 投票在用此问卷，确定删除'+ gVoteDelTitle +'吗？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + gVoteDelId;
			hg_ajax_post(url);
		}
	}
}
function hg_delVoteNodes(id)
{
	var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
	hg_ajax_post(url);
}
/*删除问卷投票*/
function hg_delQuestionOption(id)
{
	if (confirm('确定删除吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;;
		hg_ajax_post(url);
	}
}

/*投票用户信息设置*/
function hg_questionUser()
{
	var num = $('#question_user').find(':checkbox[name^="is_"]:checked').length;
	if (num == 5)
	{
		$('#all_select').attr('checked','checked');
	}
	else
	{
		$('#all_select').removeAttr('checked');
	}
}
/*全选*/
function hg_questionUserAll(obj)
{
	if ($(obj).attr('checked') == 'checked')
	{
		$('#question_user ').find('input[name^="is_"]').attr('checked','checked');
	}
	else
	{
		$('#question_user ').find('input[name^="is_"]').removeAttr('checked');
	}
}

/*添加投票DOM*/
function hg_addQuestionDom()
{
	var num = $('#questionBox').find('ul').length + 1;
	$('#question_html').find('span[name^="title_num"]').html('问题&nbsp;' + num + '：');
	$('#question_html').find('div[name^="option_box"]').attr('id','option_box_' + num);
	$('#question_html').find('input[name^="option_title_"]').attr('name','option_title_' + (num - 1) + '[]');
	$('#question_html').find('input[name^="is_other"]').attr('name','is_other['+(num-1)+']');
	$('#question_html').find('input[name^="question_files_"]').attr('name','question_files_' + (num - 1));

	var ii = 0;
	$($('#question_html').find('input[name^="option_files_"]')).each(function(){
		$(this).attr('name','option_files_' + (num - 1) + '_' + ii);
		ii ++;
	});

	$('#question_html').find('span[name^="option_del"]').hide();
	$('#question_html').find('a[id^="add_button_"]').attr('id','add_button_' + num);
	
	$('#questionBox').last().find('div[name^="option_box"]').parent().slideUp();

	var div = $('#question_html').html();
	$('#questionBox').append(div);
	hg_resize_nodeFrame();
	$('#question_html').find('span[name^="title_num"]').html('');
	$('#question_html').find('div[name^="option_box"]').removeAttr('id');
}
/*删除投票DOM、数据*/
var gDelQueDomObj = "";
function hg_delQuestionDom(obj,id)
{
	gDelQueDomObj = obj;
	if (id)
	{
		if (confirm('确定删除吗？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delVoteQuestion&id=' + id;
			hg_ajax_post(url,'','','delVoteQuestion_back');
		}
	}
	else
	{
		$(obj).parent().parent().parent().remove();		//暂时这样处理
		delVoteQuestionDom();
	}
	hg_resize_nodeFrame();
}
function delVoteQuestion_back(obj)
{
	if (obj)
	{
		$(gDelQueDomObj).parent().parent().parent().remove();
		delVoteQuestionDom();
	/*	var i = 1;
		$('span[name^="title_num"]').each(function(){
			$(this).html('问题&nbsp;' + i + '：');
			i ++;
		});
		var j = 1;
		$('div[name^="option_box"]').each(function(){
			$(this).attr('id','option_box_' + j);
			j ++;
		});
		//修改允许其他选项下标
		var is_other = 0;
		$('#questionBox input[name^="is_other"]').each(function(){
			$(this).attr('name','is_other['+is_other+']');
			is_other ++;
		});
		//修改投票图片下标
		var q = 0;
		$('#questionBox span[name^="questionFileStyle"]').each(function(){
			$($(this).find('input[name^="question_files_"]')).attr('name','question_files_' + q);
			q ++;
		});
		var ii = 0;
		$('div[name^="option_box"]').each(function(){
			$($(this).find('input[name^="option_title_"]')).attr('name','option_title_' + ii + '[]');
			ii ++;
		});
		var ij = 0;
		$('#questionBox div[name^="option_box"]').each(function(){
			var jj = 0;
			$(this).find('span[name^="optionFileStyle"] input[name^="option_files_"]').each(function(){
				$(this).attr('name','option_files_' + ij + '_' + jj);
				jj ++;
			});
			ij ++;
		});
		//修改添加按钮下标
		var aa = 1;
		$('#questionBox a[id^="add_button_"]').each(function(){
			$(this).attr('id','add_button_' + aa);
			aa ++;
		});
		*/
	}
}
function delVoteQuestionDom()
{
	//修改投票序号
	var i = 1;
	$('span[name^="title_num"]').each(function(){
		$(this).html('问题&nbsp;' + i + '：');
		i ++;
	});
	//修改投票ID序号
	var j = 1;
	$('div[name^="option_box"]').each(function(){
		$(this).attr('id','option_box_' + j);
		j ++;
	});
	//修改允许其他选项下标
	var is_other = 0;
	$('#questionBox input[name^="is_other"]').each(function(){
		$(this).attr('name','is_other['+is_other+']');
		is_other ++;
	});
	//修改投票图片下标
	var q = 0;
	$('#questionBox span[name^="questionFileStyle"]').each(function(){
		$($(this).find('input[name^="question_files_"]')).attr('name','question_files_' + q);
		q ++;
	});
	
	//修改投票下标
	var ii = 0;
	$('div[name^="option_box"]').each(function(){
		$($(this).find('input[name^="option_title_"]')).attr('name','option_title_' + ii + '[]');
		ii ++;
	});
	//修改投票选项图片下标
	var ij = 0;
	$('#questionBox div[name^="option_box"]').each(function(){
		var jj = 0;
		$(this).find('span[name^="optionFileStyle"] input[name^="option_files_"]').each(function(){
			$(this).attr('name','option_files_' + ij + '_' + jj);
			jj ++;
		});
		ij ++;
	});
	//修改添加按钮下标
	var aa = 1;
	$('#questionBox a[id^="add_button_"]').each(function(){
		$(this).attr('id','add_button_' + aa);
		aa ++;
	});
}
/*点击添加问题按钮时将已有问题DOM收缩*/
function hg_question_contract(obj)
{
	$(obj).parent().next().slideToggle();
}
/*vote列表操作按钮

function hg_vote_operate(obj,id)
{
	if ($(obj).parent().next().css('display') == 'none')
	{
		$('#vodlist li[id^="r_"]').find('.v_opearte').hide();
		$(obj).parent().next().show();
	}
	else
	{
		$('#vodlist li[id^="r_"]').find('.v_opearte').hide();
		$(obj).parent().next().hide();
	}
	
}*/
/*vote移动*/
function hg_vote_remove(obj,group_id,id)
{
	var url = './run.php?mid=' + gMid + '&a=vote_remove&group_id=' + group_id + '&id=' + id;
	hg_ajax_post(url);
}
function voteMoveForm_back(obj)
{
	var obj = eval('('+obj+')');
	if (obj.id)
	{
		var frame = document.getElementById("mainwin");
		if ($(frame).attr('id'))
		{
			frame = frame.contentWindow;
			var nodeframe = frame.document.getElementById("nodeFrame");
			if(nodeframe)
			{
				frame = nodeframe.contentWindow;
			}
			
			if (frame.$('#vodlist').attr('id'))
			{
				frame.$('#vodlist').find('#v_name_' + obj.id).html(obj.name);
				frame.$('#vodlist').find('#v_opearte_' + obj.id).hide();
			}
		}
	}
	$('#livwindialogClose').click();
}
/*最大选项数提示*/
function hg_maxOptionShow(obj,type)
{
	if ($(obj).val() == 0)
	{
		if (type == 1)
		{
			$(obj).parent().find('.maxOptionAlert').html('0 - 表示投票选项无限制').fadeIn(2000);
		}
		else
		{
			$(obj).parent().find('.maxOptionAlert').html('0 - 表示投票选项无限制').fadeOut(2000);
		}
	}
	else
	{
		$(obj).parent().find('.maxOptionAlert').html('').fadeOut(2000);
	}
}

/*选项验证重复*/
function hg_optionChecked(obj)
{
	var i=1;
	$($(obj).parent().parent().find('input[name^="option_title_"]')).each(function(){
		if ($(this).val() == $(obj).val())
		{
			if(i > 1 && $(obj).val())
			{
				alert('投票选项有重复');
			}
			else
			{
				i++;
			}
		}
	});
}
/*获取投票其他选项*/
var gQuestionIdImg = "";
function hg_getOtherOption(question_id)
{
	gQuestionIdImg = question_id;
	$('#getOtherOption_img_' + question_id).show();
	var url = './run.php?mid=' + gMid + '&a=getOtherOption&vote_question_id=' + question_id ;
	hg_ajax_post(url);
}
function getOtherOption_back(html)
{
	if (html)
	{
		$('#livwindialogbody').html(html);
		$('#getOtherOption_img_' + gQuestionIdImg).hide();
		$('#livwindialog').show();
		hg_voteOtherInfoShow();
	}
}

/*获取其他选项操作*/
function hg_otherOperate(obj,id)
{
	if ($('#other_operate_box_' + id).css('display') == 'none')
	{
		$('span[id^="other_operate_box_"]').hide();
		$('#other_operate_box_' + id).show();
	}
	else
	{
		$('span[id^="other_operate_box_"]').hide();
		$('#other_operate_box_' + id).hide();
	}
}
/*其他选项删除*/
var gOtherOptionId
function hg_optionOtherDel(obj,id)
{
	gOtherOptionId = id;
	if (confirm("确定删除吗？"))
	{
		var url = './run.php?mid=' + gMid + '&a=delQuestionOption&id=' + id;
		hg_ajax_post(url,'','','delQuestionOption_Otherback');
	}
	
}
function delQuestionOption_Otherback(obj)
{
	if (obj)
	{
		$('#other_option_id_' + gOtherOptionId).remove();
		var i = 1;
		$('span[id^="other_num_"]').each(function(){
			$(this).html(i + '.');
			i ++;
		});
	}
}
/*关闭其他选项窗口*/
function hg_otherClose()
{
	//$('#livwindialog').hide();
	$('#livwindialog').animate({'top':'-480px'});
}

/*滑动效果*/
function hg_voteOtherInfoShow()
{
	var off = $(top.document.getElementById('mainwin').contentWindow.document.getElementsByTagName('body')).scrollTop();

	off = off + 120;

	$('#livwindialog').animate({'top':off});
}
/*获取更多*/
var gQuestionId = "";
function hg_getOtherMore(obj,vote_question_id)
{
	gQuestionId = vote_question_id;
	$('#getOtherMore_img_' + vote_question_id).show();
	var offset = $('#other_append_box_' + vote_question_id).find('li[id^="other_option_id_"]').length;
	var url = './run.php?mid=' + gMid + '&a=getOtherMore&vote_question_id=' + vote_question_id + "&offset=" + offset;
	hg_ajax_post(url);

}
function getOtherMore_back(html)
{
	if (html)
	{
		$('#other_append_box_' + gQuestionId).append(html);
		
		var i = 1;
		$('#other_append_box_' + gQuestionId + ' span[id^=other_num_]').each(function(){
			$(this).html(i + '.');
			i ++;
		});
	}
	$('#getOtherMore_img_' + gQuestionId).hide();
	hg_resize_nodeFrame();
	
}
/*投票选项描述*/
function hg_optionDescribe(obj)
{
	var i = $(obj).prev().find('input[name^="option_files_0_"]').attr('name');
	if (i)
	{
		var ii = i.substr(15,1);
		if (i.length > 16)
		{
			ii = i.substr(15,2);
		}
	}
	
	if (!$.trim($(obj).parent().find('span[name^="option_describe_box"]').html()))
	{
		var textarea = '<textarea name="option_describes[' + ii + ']" style="margin-top:10px;" onblur="hg_optionDescribeHide(this);"></textarea>';
		$(obj).parent().find('span[name^="option_describe_box"]').append(textarea);
	}
	else
	{
		$(obj).parent().find('span[name^="option_describe_box"] textarea[name^="option_describes"]').show();
	//	$(obj).parent().find('.describe_overflow').hide();
	}
	hg_resize_nodeFrame();
}
/*投票选项描述隐藏*/
function hg_optionDescribeHide(obj)
{
	$(obj).hide();
	if ($(obj).val())
	{
		var describes = $(obj).val().substr(0,3);
	}
	
	if ($(obj).val())
	{
		$(obj).parent().next().html(describes + '...').show();
		$(obj).parent().parent().find('.option_describe').addClass('option_describe_b');
		$(obj).parent().parent().find('.option_describe').removeClass('option_describe');
	}
	else
	{
		$(obj).parent().parent().find('.option_describe_b').addClass('option_describe');
		$(obj).parent().parent().find('.option_describe_b').removeClass('option_describe_b');
	}
}
/*其他选项审核*/
var gOptionOtherStateObj = "";
var gOptionOtherStateId = "";
function hg_optionOtherState(obj,id)
{
	gOptionOtherStateObj = obj;
	gOptionOtherStateId = id;
	var url = './run.php?mid=' + gMid + '&a=optionOtherState&id=' + id;
	hg_ajax_post(url,'','','optionOtherState_back');
}
function optionOtherState_back(obj)
{
	if (obj == 1)
	{
		$(gOptionOtherStateObj).html('打回');
		$('#other_state_' + gOptionOtherStateId).html('已审核');
	}
	else
	{
		$(gOptionOtherStateObj).html('审核');
		$('#other_state_' + gOptionOtherStateId).html('待审核');
	}
	$('#other_operate_box_' + gOptionOtherStateId).hide();
}

/*改变图片上传样式*/
function hg_questionFileStyle(obj,type)
{
	if (type == 1)
	{
		$(obj).parent().prev().prev().prev().removeClass('question_files_b');
		$(obj).parent().prev().prev().prev().addClass('question_files');
	}
	else
	{
		$(obj).parent().prev().removeClass('question_files_b');
		$(obj).parent().prev().addClass('question_files');
	}
}
/*改变图片上传样式*/
function hg_optionFileStyle(obj)
{
	$(obj).parent().prev().prev().removeClass('vote_question_files_b');
	$(obj).parent().prev().prev().addClass('vote_question_files');
}

/*投票状态*/
var gQuestionStatusId = '';
function hg_questionStatus(id)
{
	gQuestionStatusId = id;
	var url = './run.php?mid=' + gMid + '&a=status&id=' + id;
	hg_ajax_post(url,'','','questionStatus_back');
}
function questionStatus_back(obj)
{
	if (obj==1)
	{
		$('#status_' + gQuestionStatusId).html('已审核');
	}
	else
	{
		$('#status_' + gQuestionStatusId).html('待审核');
	}
}

/*编辑用户添加选项*/
function hg_updateOtherTitle()
{
	
	return hg_ajax_submit('otherForm','','','updateOtherTitle_back');
	
}
function updateOtherTitle_back(obj)
{
	
}
function hg_changeOtherTitle(obj,id)
{
	if ($(obj).val() == $('#hiddenTitle_' + id).val())
	{
		$('#hiddenFlag_' + id).val('');
		if ($('#Flag_' + id).val() == 1)
		{
			$(obj).css({'border':'1px solid #83ABCF'});
		}
		else
		{
			$(obj).css({'border':'1px solid #D6D6D6'});
		}
	}
	else
	{
		$('#hiddenFlag_' + id).val(1);
		$(obj).css({'border':'1px solid #83ABCF'});
	}
}


function show_opration_button(btn, showElem){
	for(var i = 0; i < btn.length; i++){
		btn[i].onmouseover = function(){
			$(this).find(showElem).show();
		};
		btn[i].onmouseout = function(){
			$(this).find(showElem).hide();
		};

	}
}

$(function(){
	show_opration_button($(".cz"), ".show_opration_button");
});

/*编辑*/
function hg_voteForm(id)
{
	var url = './run.php?mid=' + gMid + '&a=form&id=' + id + '&infrm=1';
	window.location.href = url;
}

/*发布*/
var gStateId = '';
function hg_statePublish(id)
{
	gStateId = id;
	var url = './run.php?mid=' + gMid + '&a=recommend&id=' + id;
	hg_ajax_post(url);
}
/*发布回调函数*/
function hg_show_pubhtml(html)
{
	$('#vodpub_body').html(html);
	hg_vodpub_show(gStateId);
}
function hg_vodpub_hide(id)
{
	$('#vod_fb').hide();
	$('#vodpub').animate({'top':'-440px'});
}
function hg_vodpub_show(id)
{
	var tops=t=0;
	t = $('#r_'+ id).position().top;
	if(t >= 230)
	{
		tops = t-140 ;
	}
	$('#vodpub').animate({'top':tops},
		function(){
			$('#vod_fb').css({'display':'block','top':t+11,'left':'98px'});
		}
	);
}




