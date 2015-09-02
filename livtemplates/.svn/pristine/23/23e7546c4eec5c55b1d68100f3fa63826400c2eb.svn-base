{template:head} {css:2013/form} {css:2013/button} {css:sign_from}
{css:sign_result} {code} //print_r($formdata); {/code}
<header class="m2o-header">
<div class="m2o-inner">
	<div class="m2o-title m2o-flex m2o-flex-center">
		<span class="m2o-l" style="font-size: 28px;">签到信息展示</span>
		<div class="m2o-btn m2o-r m2o-flex-one">
			<span class="m2o-close option-iframe-back"></span> <span
				class="vote-comment"> </span>
		</div>
	</div>
</div>
</header>
<div class="m2o-inner m2o-vote-details">
	<div class="m2o-main m2o-flex">
		<div class="m2o-l">
			<div class="vote-left-info m2o-flex">
				<div class="vote-pic" style="">
					{if $formdata['member_info']['avatar']}
					{code}$avatar=hg_fetchimgurl($formdata['member_info']['avatar']);{/code}
					<img src="{$avatar}" />{/if} 
				</div>
				<div class="vote-info m2o-flex-one">
					<div class="form-dioption-fabu form-dioption-item">
						<a class="overflow">会员名:<span style="">{$formdata['member_info']['member_name']}</span>
						</a> {if $formdata['member_info']['groupicon']}
						{code}$groupicon=hg_fetchimgurl($formdata['member_info']['groupicon']);{/code}
						 <img src="{$groupicon}" />{/if}
					</div>
					<div class="form-dioption-fabu form-dioption-item"
						style="height: 70px;">
						<a class="overflow">个性签名:</a><span
							style="font-size: 12px; display: block; height: 88px; overflow: hidden; text-indent: 35px; margin-top: -19px;"
							title="{$formdata['describes']}">{$formdata['describes']}</span>
					</div>
				</div>
			</div>
			<div class="form-dioption-fabu form-dioption-item vote-show">
				<a class="overflow">会员组:<span>{$formdata['member_info']['groupname']}</span>
				</a>
			</div>
			<div class="form-dioption-fabu form-dioption-item vote-show">
				{if ($formdata['member_info']['groupexpiry'])} <a class="overflow">有效时间:
					<span>至 {$formdata['member_info']['groupexpiry']}</span> </a>
				{else} <a class="overflow">有效时间: <span> 永久有效 </span> </a> {/if}
			</div>
		</div>
		<div class="m2o-flex-one" style="background: #fff;">
			<div class="info-switch">
				{if ($formdata['is_todaysign'])} 
				<span class="switch select">今日已签到</span>
				{else} 
				<span class="switch select">今日未签到</span> 
				{/if}
				<span class="switch">签到时间:</span> {$formdata['time']}
				<!-- <span class="switch">详细投票</span> -->
				<span class="total right">总共签到<label>{$formdata['days']}</label>天</span>
				<span class="total right">本月签到<label>{$formdata['days']}</label>天</span>
				<span class="total right">连续签到<label>{$formdata['days']}</label>天</span>
			</div>
			<ul class="vote-results clear" style="list-style: none;">
				<li class="vote-item">
								<div class="content-list m2o-flex">
						<div class="detail-info" style="width: auto;">
				<span class="total">总奖励(总积分):</span>
				<span>{$formdata['reward']}</span>
						</div>
				
					
					 {if $get_credit_type&&is_array($get_credit_type)}
         {foreach $get_credit_type as $k => $v}
				<div class="detail-info" style="width: auto;">
				<span class="total">总奖励({$v['title']}):</span>
				<span>{$formdata['reward_'.$k]}</span>
						</div>
				
				<div class="detail-info" style="width: auto;">
				{if ($formdata['is_todaysign'])} 
				<span class="total">今日奖励({$v['title']}):</span>
				{else} 
				<span class="total">往日奖励({$v['title']}):</span> 
				{/if}
				<span>{$formdata[$k]}</span>
						</div>
				
					{/foreach}
					{/if}
					</div>
					</li>
					<li class="vote-item">
					<div class="content-list m2o-flex">
						<div class="detail-info" style="width: auto;">
				{if ($formdata['is_todaysign'])} 
				<span class="switch">今日心情:</span>
				{else} 
				<span class="switch">往日心情:</span> 
				{/if}
						</div>
					{if $formdata['qdxq']['img']}
					{code}$qdxq_img=hg_fetchimgurl($formdata['qdxq']['img']);{/code}
							<img src="{$qdxq_img}">{/if}
					</div>
					</li>
					<li class="vote-item todaysay">
						<div class="content-list m2o-flex">
						<div class="detail-info" style="width: auto;">
				{if ($formdata['is_todaysign'])} 
				<span class="switch">今日最想说:</span>
				{else} 
				<span class="switch">往日最想说:</span> 
				{/if}
				<span id='todaysay' _uid="{$formdata['id']}">{$formdata['todaysay']}</span>
				<em class="del-extend" title="屏蔽今日最想说"></em>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
   $(".del-extend").click(function(){
	   var memberid = $('#todaysay').attr('_uid');
	   var ban_url="./run.php?mid=" + gMid + "&a=ban";
	   $.getJSON(ban_url, {id: memberid}, function(json){
		   $('#todaysay').text(json[0].todaysay);
		   alert("该会员今日最想说内容屏蔽成功!");
		 });
});

});
</script>
