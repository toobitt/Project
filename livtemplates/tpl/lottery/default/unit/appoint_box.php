{css:appoint_award}
<script type="text/javascript">
$(function($){
	var parent = $('.appoint_award'),
		item = $('.appoint-box');
	
	parent.on('click' , function(){
		item.addClass('show');
	});
	
	item.on('click' , '.close' ,  function(){
		item.removeClass('show');
	});

//	var availableTags = ["你好","你好啊","你好不","你好丑","你好漂亮","你好坏","哈哈","拉拉","首都哈后","二位","畅销书","自行车秩序","宣传政策性","去武当山啊"];
//	item.find( 'input[name="award_id"]' ).autocomplete({
//        source: availableTags
//  });
    
	item.on('click' , '.save' , function( event ){
		var self = $(event.currentTarget),
			lottery_id = item.find('input[name="lottery_id"]').val(),
			member_id = item.find('input[name="member_id"]').val(),
			prize_id = item.find('input[name="prize_id"]').val(),
			url = './run.php?mid=' + gMid + '&a=create';
		var data = {};
			data.lottery_id = lottery_id;
			data.member_id = $.trim(member_id);
			data.prize_id = prize_id;
			if( !data.member_id ){
				self.myTip({string : '用户ID不能为空！' , dleft : 110});
				return false;
			}
			if( !data.prize_id ){
				self.myTip({string : '奖品不能为空！' , dleft : 110});
				return false;
			}
			$.globalAjax( self, function(){
				return $.getJSON( url, data , function(json){
					if( json[0].error_msg ){
						self.myTip({string : json[0].error_msg , dleft : 110});
						return false;
					}else{
						item.removeClass('show');
						location.reload();
					}
	 			});
			});
	});
});
</script>
<div class="appoint-box">
	<div class="appoint-title">指定中奖<span class="close"></span></div>
	<div class="content">
		<div class="content-item">
			<span class="content-title">中奖用户ID：</span>
			<input type="text" name="member_id" placeholder="请输入用户ID"/>
		</div>
		<div class="content-item">
			<span class="content-title">中奖奖品：</span>
			<!--{code}
				$award_type_source = array(
					'class' 	=> 'down_list i',
					'show' 		=> 'award_type_show',
					'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
					'is_sub'	=>	1,
					'width'    => '150'
				);
				$award_type_default = 'other';
				$award_type_sort[other] = '-- 请先选择奖项 --';
				foreach($movie as $k =>$v)
				{
					 $award_type_sort[$v['id']] = $v['title'];
				}
			{/code}
				{template:form/search_source,award_type,$award_type_default,$award_type_sort,$award_type_source}-->
			{code}
				$award_source = array(
					 'class' 	=> 'down_list i',
					 'show' 	=> 'award_show',
					 'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
					 'is_sub'	=>	1,
					 'width'    => '150'
				);
				$award_default = 'other';
				$award_sort[other] = '-- 请先选择奖品 --';
				foreach($prize_info as $k =>$v)
				{
					$award_sort[$k] = $v;
				}
			 {/code}
			 	{template:form/search_source,prize_id,$award_default,$award_sort,$award_source}
		</div>
		<input type="hidden" name="lottery_id" value="{$_INPUT['lottery_id']}" />
		<span class="save">确定</span>
	</div>
</div>