/*操作显示控制*/
$(document).ready(function(){
	/*
 	$("li span.right a.cz").hover(function(){
		$(this).parent().children("span.operation_1").hide();
		$(this).parent().children("span.operation_2").show();
	},function(){
		$(this).parent().children("span.operation_1").show();
		$(this).parent().children("span.operation_2").hide();
	});
	$("span.operation_2").hover(function(){
		$(this).show();
		$(this).parent().children("span.operation_1").hide();
		$(this).parent().children("a.cz").children("em.b4").css('background-position','0 -16px');
	},function(){
		$(this).hide();
		$(this).parent().children("span.operation_1").show();
		$(this).parent().children("a.cz").children("em.b4").css('background-position','0 0');
	});*/

	$('#select_matrial').toggle(function() {
		$('#source').slideDown('slow');
	},function() {
		$('#source').slideUp('slow');
	});
	$('#source span').each(function() {
		var _this = $(this);
		_this.click(function() {
			$('#liv_source').val(_this.text());
			
		});
	});
});
/*删除回调*/
function hg_int_del_callback(id){
	var delid=id.split(",");
	for(var i=0;i<delid.length;i++)
	{
	$("#r_"+delid[i]).remove();
	} 
}
/*检测输入的时长是否合法*/
function check_input_time(obj)
{
	if(isNaN(obj.value) || obj.value< 0 || obj.value==0 )
	{
		$(obj).val('');
		alert('只能输入大于0的数字！');
	}
}

$(function() {
	
	window.App = Backbone;
	window.recordCollection = new Records;
	window.recordViews = new RecordsView({ el: $('.common-list').parent(), collection: recordCollection });
    recordCollection.add(globalData.list);
    var ab = new ActionBox({ el: $('#record-edit') });
    ab.$el.on('click', 'a', function(e) {
    	var a = this;
    	var text = $(this).text().trim();
    	
    	if ( text == '删除' ) {
    		ab.confirm(function(yes) {
    			yes && hg_ajax_post(a);
    		});
    		return false;
    	}
    });
	
});
