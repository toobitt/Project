{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
<style>
.item{float:left;width:160px;height:140px;margin:0 10px 15px 0;text-align:center;}
.user-pic{display:inline-block;position:relative;width:140px;height:100px;line-height:100px;margin-top:10px;}
.user-pic img{max-width:140px;max-height:100px;vartical-align:center;}
.item .name{color:#333;}
.item .del{display:none;position:absolute;width:18px;height:18px;background:#999;color:#fff;text-align:center;line-height:18px;cursor:pointer;border-radius:50%;top:-8px;right:-8px;}
.item:hover .user-pic{background:#eee;}
.item:hover .del{display:block;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>交换列表</h2>
					<ul class="form_ul clear">
						{if $formdata}
							{foreach $formdata AS $k => $v}
								{code}
									if($v['avatar'] && is_array($v['avatar']))
									{
										$_avatar = $v['avatar']['host'] .  $v['avatar']['dir'] .  $v['avatar']['filepath'] .  $v['avatar']['filename'];
									}
									else
									{
										$_avatar = $RESOURCE_URL . 'avatar.jpg';
									}
								{/code}
							<li class="item">
								<a class="user-pic">
									<img src="{$_avatar}"/>
									<span class="del">x</span>
								</a>
								<p class="name">{$v['name']}</p>
							</li>
							{/foreach}
						{/if}
					</ul>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}