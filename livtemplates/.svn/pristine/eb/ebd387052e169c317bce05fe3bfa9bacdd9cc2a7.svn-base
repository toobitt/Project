{template:head}
{css:2013/form}
{css:2013/button}
{css:vote_from}
{css:vote_result}
{js:vote/vote_result}
{js:2013/ajaxload_new}
{js:page/page}
{code}
//print_r($formdata);
{/code}
  <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <span class="m2o-l" style="font-size: 28px;">{$formdata['title']}</span>
            <div class="m2o-btn m2o-r m2o-flex-one">
                <span class="m2o-close option-iframe-back"></span>
                <span class="vote-comment"> </span>
            </div>
        </div>
      </div>
    </header>
 <div class="m2o-inner m2o-vote-details">
     <div class="m2o-main m2o-flex">
        <div class="m2o-l">
        	<div class="vote-left-info m2o-flex">
        		<div class="vote-pic" style="">
        			<img src="{$formdata['index_img']}" />
        		</div>
        		<div class="vote-info m2o-flex-one" >
        			<div class="form-dioption-fabu form-dioption-item">
                    	<a class="overflow">分类:<span>{$formdata['sort_name']}</span></a>
                	</div>
                	<div class="form-dioption-fabu form-dioption-item" style="height: 70px;">
                    	<a class="overflow">简介:</a><span style="font-size:12px;display: block;height: 88px;overflow: hidden;text-indent: 35px;margin-top: -19px;" title="{$formdata['describes']}">{$formdata['describes']}</span>
                	</div>
        		</div>
        	</div>
        	<div class="form-dioption-fabu form-dioption-item vote-show">
        	    {if ($formdata['end_time'] && $formdata['start_time'])}
                <a class="overflow" >有效时间: <span>{$formdata['start_time']} 至 {$formdata['end_time']}</span></a>
                {elseif ($formdata['end_time'])}
                <a class="overflow" >有效时间: <span>至 {$formdata['end_time']}</span></a>
                {else}
                <a class="overflow" >有效时间: <span> 永久有效 </span></a>
                {/if}
            </div>
            <div class="form-dioption-fabu form-dioption-item vote-show">
               	 <a class="overflow" >发布至:<span>{$formdata['column_name']}</span></a>
            </div>
            <div class="form-dioption-fabu form-dioption-item vote-show total_info">
				<a class="allvote overflow">总票数：<span class="red">{$formdata['question_total_ini']}</span>&nbsp;&nbsp;&nbsp;&nbsp; 总人数：<span class="red">{$formdata['person_total']}</span>（含初始值）</a>
				<a class="acturlvote overflow">总票数：<span class="red">{$formdata['question_total']}</span>&nbsp;&nbsp;&nbsp;&nbsp; 总人数：<span class="red">{$formdata['preson_count']}</span>（不含初始值）</a>
			</div>
        </div>
      	<div class="m2o-flex-one" style="background: #fff;">
      		<div class="info-switch">
      			<span class="switch select">投票</span>
			{if ($formdata['other_options'])}
      			<span class="switch">其他答案</span>
      		{/if}
      		    <span class="switch result_detail">详细投票</span>
      			<span class="total"><label>{$formdata['preson_count']}</label>人参与投票 <span class="is_ini">隐藏初始值</span></span>
      		</div>
			<ul class="vote-results clear" style="list-style: none;">
				{foreach $formdata['options'] AS $k => $v}
				<li class="vote-item">
					<div class="content-list m2o-flex"> 
     					<div class="content-index" >{code}echo ++$k;{/code}</div>
     					<div class="content-img" ><img src="{$v['option_img']}"></div>
     					<div class="detail-info" style="width:auto;">
     						<p style="max-width:590px">{$v['title']}</p>
     						<ul class="detail-vote">
     							<li class="fact-vote">实际票数:<label>{$v['single_total']}</label></li>
     							<li class="total-vote">总票数:<label>{$v['ini_single']}</label></li>
   								<li class="ini-vote">初始票数:<label>{$v['ini_num']}</label></li>
   							</ul>
     					</div>
         			</div>
         			<div class="allvote">
	        	 		<div class="program">
	         			{code}
	         			$left = (round($v['ini_single']/$formdata['question_total_ini'],4))*340;
	         			{/code}
	         			<span class="" href="#" style="width:{$left}px;"></span>
	         			</div>
	         			<span>{code} echo (round($v['ini_single']/$formdata['question_total_ini'],4))*100;{/code}%</span>
         			</div>
         			<div class="acturlvote">
	         			<div class="program">
	         			{code}
	         			$left = (round($v['single_total']/$formdata['question_total'],4))*340;
	         			{/code}
	         			<span class="" href="#" style="width:{$left}px;"></span>
	         			</div>
	         			<span>{code} echo (round($v['single_total']/$formdata['question_total'],4))*100;{/code}%</span>
					</div>
				</li>
				{/foreach}
			</ul>
			<!-- 其他答案 -->
			{if ($formdata['other_options'])}
			<ul class="vote-results clear" style="list-style: none;display:none;">
				{foreach $formdata['other_options'] AS $k => $v}
				<li class="vote-item">
					<div class="content-list m2o-flex"> 
     					<div class="content-index" >{code}echo ++$k;{/code}</div>
     					<div class="detail-info" style="width:auto;"> {$v} </div>
         			</div>
 				</li>
				{/foreach}
			</ul>
			{/if}
			
			<ul class="vote-results clear  result-vote" style="list-style: none;display:none;">
			    <ul class="resultsdetail-list"  _id="{$_REQUEST['id']}">
                </ul>
			    <div class="page_size"></div>
			</ul>
		</div>
     	</div>
 	</div>
 </div>
<script type="text/javascript">
 $(function($){
		$('.switch').on('click',function(event){
			var self = $(event.currentTarget);
			var index = self.index();
			self.addClass('select').siblings().removeClass('select');
			$('.vote-results:eq('+ index +')').show().siblings('.vote-results').hide();
		})
 });
</script>

<script type="text/x-jquery-tmpl" id="detail-tpl">
{{each option}}
<li class="detail-list">
    <div style="margin-left: 30px;"> 
     	{{= $value['create_time']}} {{if user_id!=0}} 用户{{= user_name}}{{else}}未登录用户{{/if}}
        {{if ip}}(IP:{{= ip}}){{else}}(IP:未知){{/if}} 投票给 " {{= title}} "
    </div>
</li>
{{/each}}
</script>
<style>
.allvote{display:block;}
.acturlvote{display:none;}
.total_info{line-height: 30px;display: inline-block;font-size: 14px;margin: 5px 20px;min-width: 65%;}
.total_info span.red{color: #e12728;}
.is_ini{opacity:0.7;display: inline-block;cursor:pointer;line-height: 30px;background: #0091db;color: white;width: 100px;text-align: center;border-radius: 5px;}
.is_ini:hover{opacity:1;}
.m2o-main{min-height:500px;}
</style>
<script>
$('.is_ini').toggle(function(){
	$('.allvote').hide();
	$('.acturlvote').show();
	$(this).text('包含初始值');
},function(){
	$('.allvote').show();
	$('.acturlvote').hide();
	$(this).text('隐藏初始值');
})
</script>