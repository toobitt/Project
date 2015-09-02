{template:head}
{css:award_information}
{js:page/page}
{js:2013/ajaxload_new}
{js:tv_interact/information_page}
<div class="result-info-box">
	<div class="title"><p>{$formdata['name']}的中奖信息</p><a class="total-num">获奖人数:<em>{if $formdata['total']}{$formdata['total']}{else}0{/if}</em>人</a></div>
	<div class="result-info">
		<div class="info">
			<ul class="info-list" _id="{$formdata['id']}">
			</ul>
			<div class="page_size"></div>
		</div>
	</div>
</div>
<script type="text/x-jquery-tmpl" id="info-tpl">
{{each option}}
	<li class="list">
		<div class="info-content">
			<div class="info">
				<div class="img">
					<img src="{{if $value['avatar']}}{{= $value['avatar']}}{{else}}{$RESOURCE_URL}tv_interact/avatar.jpg{{/if}}" class="logo" width="60" height="60">
					<span class="logo-name">用户图标</span>
				</div>
				<div class="list-profile">
					<div class="info-intro" title="{{= $value['member_name']}}"><label>用户名：</label><span>{{= $value['member_name']}}</span></div>
					<div class="info-intro" title="{{= $value['phone_num']}}"><label>手机号：</label><span>{{= $value['phone_num']}}</span></div>
					<div class="info-intro reward" title="{{= $value['red_bag']}}"><label>红包信息：</label><span>{{= $value['red_bag']}}</span></div>
				</div>
			</div>
			<div class="info-time">
					<span>{{= $value['create_time']}}</span>
			</div>
		</div>
	</li>
{{/each}}
</script>