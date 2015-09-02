{template:./head}
{js:qingao/group_groupcreate}
{js:qingao/group_indexmap_b}
{js:qingao/base}
{js:qingao/action}
<script type="text/javascript">
//<![CDATA[
var MAP_CENTER_POINT = '<?php  echo (($activity_info['lat'] == "0.00000000000000") ? '32.039665' : $activity_info['lat']);?>X<?php  echo (($activity_info['lng'] == "0.00000000000000") ? '118.808604' : $activity_info['lng']);?>';
window.onload = initialize;
//]]>
</script>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.2&services=true"></script>
	<h1 class="actiont action_h1"><span class='ks_start'><img src="{$RESOURCE_URL}qingao/{$activity_info['url']}" width='65px' height='65px'></span><a href="#" id="topic_name">{$activity_info['action_name']}</a></h1><!-- actiont2表示进行中, actiont3表示已结束--->
		<div class="gshow_top"></div>
		<div class="gshow_c clearfix">
			<div class="atopic">
			{code}
					$lurl1 = $lurl2 = '';
					$lurl1 .= isset($activity_info['action_img']['host']) ? $activity_info['action_img']['host'] :"";
					$lurl1 .= isset($activity_info['action_img']['dir']) ? $activity_info['action_img']['dir'] :"";
					$lurl2 .= isset($activity_info['action_img']['filepath']) ? $activity_info['action_img']['filepath'] :"";
					$lurl2 .= isset($activity_info['action_img']['filename']) ? $activity_info['action_img']['filename'] :"";
			{/code}
				<img src="{$lurl1}626x290/{$lurl2}" width="626" height="290" />
				<div class="asay">
					<a href="javascript:;" class="awesay">大家说</a>
				</div>
				<div class="plugin_weibo" style="display:none"><!--我们说加类名 plugin_weibo_our-->
					<div class="plugin_weibo_arrow"></div>
					<div class="plugin_weibo_title">
						<h3>行动微博动态</h3>
						<a href="javascript:void(0);" class="plugin_weibo_close">x</a>
					</div>
					<div class="plugin_weibo_list">
						{template:unit/activity_weibo}
					</div>
					<div class="plugin_weibo_item_more"><span>展开更多</span></div>
					<div class="plugin_weibo_reply">
						<div class="plugin_weibo_char"><!--<span>还可输入</span><span id="charNum" data-num="100">100</span>字--></div>
						<form name="say_form" id="say_form" method="post" action="activity.php" onSubmit="return false;">
							<textarea name="content" id="content" class="plugin_weibo_reply_cnt" id="msgtxt">#{$activity_info['action_name']}#</textarea>
							<div class="plugin_weibo_reply_btn"><input type="submit" value="发布" /></div>
							<div class="sys_mood"><a href="javascript:void(0);" ></a></div>
							<input type="hidden" value="update_weibo" name="a"/>
						</form>					
					</div>
				</div><!-- plugin_weibo end-->
			</div>
			<aside class="atopic_aside" style="position:relative;">
				<hgroup>
				{if $activity_info['slogan']}
					{$activity_info['slogan']}
				{else}
					快，给行动设置一个醒目的口号!
				{/if}
					
				</hgroup>
				<div style="position:absolute; bottom:5px;">	
					<div class="atopic_tags">
					{code}
					if($activity_info['sign'])
					{	
						$spce='';
						foreach ($activity_info['sign'] as $sign)
						{
							echo $spce.'<a href="#">'.$sign['mark_name'].'</a>';
							$spce =',';
						}
					}
					else
					{
						echo "<a href=#>没有标签</a>";
					}
					{/code}
					</div>
					<div class="atopic_btn clearfix">
						{if $times}
							<a href="javascript:;" class="atopic_btn_join gray_btn">活动结束</a>
						{else}
							{if $activity_info['is_join'] == 4}
							<a href="javascript:;" class="atopic_btn_join" id="join_action" aid="{$activity_info['id']}">参与行动</a>
							{elseif $activity_info['is_join'] == 0 || $activity_info['is_join'] == 2}
							<a href="javascript:;" class="atopic_btn_join gray_btn">已参与</a>
							{elseif $activity_info['is_join'] == 1}
							<a href="javascript:;" class="atopic_btn_join  gray_btn">审核中</a>
							{/if}
						{/if}
							<a href="#" class="atopic_btn_love{if $activity_info['have']} gray_btn{/if}" id ="join_love" aid="{$activity_info['id']}" bid="{$activity_info['have']}">{if $activity_info['have'] > 0}取消关注{else}关注{/if}</a> 
							{if $ower['id'] == $activity_info['user_id']}
							<a href="activitys.php?action_id={$_INPUT['action_id']}&a=update" class="atopic_btn_love">管理</a> 
							{/if}	
					</div>
					<div class="atopic_count">
					<span>{$activity_info['apply_num']}人报名</span><span class="yet_join">{$activity_info['yet_join']}人参加</span><span id="praisecount">{$praisecount}赞</span><span>{$activity_info['collect_num']}人关注</span>
						<!-- <span>21分享</span> -->
					</div>
				</div>
				<a class="atopic_digg_btn {if !$praise}atopic_btn_digg{else}atopic_btn_already_digg{/if}" aid="{$activity_info['id']}"></a>
			</aside>
		</div>
		<div class="gshow_bottom"></div>
	</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain clearfix">
				<div class="action_detail">
					<div class="act_det_tle clearfix">
						<ul><li class="current">基本信息</li><li>更多详情</li></ul> 
					</div>
					<div class="act_det_con clearfix">
						<div class="act_det_panel">
							<div class="act_det_pane">
								<div class="act_det_item"><span>时间：</span><div>{$activity_info['start_time']}<br />{$activity_info['end_time']}<br /></div></div>							
								{if $activity_info['need_pay']}<div class="act_det_item"><span>费用：</span><div>￥{$activity_info['need_pay']}</div></div>{/if}
								{if $activity_info['place']}<div class="act_det_item"><span>地点：</span><div>{$activity_info['place']}</div></div>{/if}
								<div class="act_det_item"><span>发起人：</span><div>{$activity_info['user_name']}</div></div>
								{if $activity_info['bus']}<div class="act_det_item"><span>交通路线：</span>{$activity_info['bus']}</div>{/if}
							</div>
							<div class="act_det_pane">
								<div class="act_det_more">{code}echo hg_cutchars(strip_tags($activity_info['introduce']), 240);{/code}</div>
							</div>
						</div>
						<div class="act_det_video">
						<!-- <img src={code}echo QINGWANG_SWF;{/code}> -->
						<embed src="http://player.youku.com/player.php/sid/XMzI5MjE4NTcy/v.swf" allowFullScreen="true" quality="high" width="311" height="218" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>
						</div>
					</div>
				</div><!--action_detail end-->
			{if $activity_info['is_open']}
				{if count($material_info)}
				<div class="action_pics">
					<div class="action_title">
						<h2>行动图像</h2>
 						<h3><!--<a class="title">上传</a><a href="#" class="tmore">浏览全部</a>--></h3> 
					</div>
					<div class="action_pic clearfix">
							{code}
							$url1 = $url2 =  '';
							$url1 .= isset($material_info['0']['img_info']['host']) ? $material_info['0']['img_info']['host'] : "";
							$url1 .= isset($material_info['0']['img_info']['dir']) ? $material_info['0']['img_info']['dir'] : "";
							$url2 .= isset($material_info['0']['img_info']['filepath']) ? $material_info['0']['img_info']['filepath'] : "";
							$url2 .= isset($material_info['0']['img_info']['filename']) ? $material_info['0']['img_info']['filename'] : "";
							array_shift($material_info);
							{/code}
							<div class="action_big_pic"><img src="{if $url1}{$url1}270x285/{$url2}{else}img/apic1.jpg{/if}" height="270" width="285" /></div>						
							<ul class="action_small_pic">
							{foreach $material_info as $material}
							{code}
							$url1 = $url2 =  '';
							$url1 .= isset($material['img_info']['host']) ? $material['img_info']['host'] : "";
							$url1 .= isset($material['img_info']['dir']) ? $material['img_info']['dir'] : "";
							$url2 .= isset($material['img_info']['filepath']) ? $material['img_info']['filepath'] : "";
							$url2 .= isset($material['img_info']['filename']) ? $material['img_info']['filename'] : "";
							{/code}
								<li><img src="{$url1}80x80/{$url2}" data-src="{$url1}270x285/{$url2}" alt="标题" width="80" height="80" /></li>
							{/foreach}	
							</ul>						
					</div>
				</div>
				{/if}
				
				<div class="action_bbs">
					<div class="action_title">
						<h2>行动讨论区</h2>
						<h3><a class="title" href="thread.php?a=create&action_id={$_INPUT['action_id']}&group_id={$group_id}">发言</a>{if count($thread_info)}<a href="group.php?a=all&action_id={$_INPUT['action_id']}&group_id={$group_id}" class="tmore">浏览全部</a>{/if}</h3>
					</div>
					
					{if count($thread_info)}
					<ul class="gtalk_list">
						{foreach $thread_info as $thread}
						<li class="clearfix">
						{code}
						$avert = $url1 = $url2 =  '';
						$url1 .= isset($user_info[$thread['user_id']]['avatar']['host']) ? $user_info[$thread['user_id']]['avatar']['host'] : "";
						$url1 .= isset($user_info[$thread['user_id']]['avatar']['dir']) ? $user_info[$thread['user_id']]['avatar']['dir'] : "";
						$url2 .= isset($user_info[$thread['user_id']]['avatar']['filepath']) ? $user_info[$thread['user_id']]['avatar']['filepath'] : "";
						$url2 .= isset($user_info[$thread['user_id']]['avatar']['filename']) ? $user_info[$thread['user_id']]['avatar']['filename'] : "";
						$avert = ($url1 && $url2) ? $url1."50x50/".$url2 : DEFAULT_AVATAR1;
						{/code}
							<div class="gtalk_poster"><img src="{$avert}" /></div>
							<div class="gtalk_det">
								<a href="thread.php?thread_id={$thread['thread_id']}"><h5>{code}echo hg_cutchars(strip_tags($thread['title']), 20);{/code}</h5></a>
								<time>{code}echo $thread['last_post_time'];{/code}</time>
								<div class="gtalk_con">{code}echo hg_cutchars(strip_tags($thread['content']), 40);{/code}</div>
								<div class="clearfix"><div class="gtalk_info">来自：<a  class="gtalk_user">{$thread['user_name']}</a>/ <span class="gtalk_digg_num"><strong id="digg_{$thread['thread_id']}">{if $thread['praise']['counts']}{$thread['praise']['counts']}{else}0{/if}</strong>赞</span> <span class="gtalk_reply_num"><strong id="reply_{$thread['thread_id']}">{$thread['post_count']}</strong>回应</span> <!-- <span class="gtalk_share_num"><strong>{$thread['attach_count']}</strong>分享</span> --></div>
								  <div class="gtalk_assist"> 
								   <a href="javascript:;" cid="{$thread['thread_id']}" m="{if empty($thread['praise'])}add{else}drop{/if}"  class="{if empty($thread['praise'])}gtalk_digg{else}gtalk_digg gtalk_digg_v{/if}">赞</a> 
								  	<!-- JiaThis Button BEGIN -->
									<a href="#" class="gtalk_share jiathis jiathis_txt jiathis_separator jtico jtico_jiathis">分享</a>
									<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js" charset="utf-8"></script>
									<!-- JiaThis Button END -->
								</div> 
							</div>
							<div class="gtalk_reply">
								<form><input type="text" class="gtalk_reply_content" name="replyContent" value="回应..." />
									<input type="hidden" name="thread_id" value="{$thread['thread_id']}" />
									<input type="hidden" name="post_id" value="{$thread['first_post_id']}" />
									<input type="hidden" name="reply_user_id" value="{$thread['user_id']}" />
									<input type="hidden" name="reply_user_name" value="{$thread['user_name']}" />
									<input type="hidden" name="reply_des" value="{code}echo hg_cutchars(strip_tags($thread['content']), 40);{/code}" />
									<input type="button" onclick="show(this.form)" value="回应" class="gtalk_reply_btn" />
								</form>
							</div>
						</li>
						{/foreach}					
					</ul>
					{else}
						没有数据!
					{/if}
				</div>
				
			{/if}		
			</div><!--gtalks end-->
			<div class="gmain_bottom"></div>
		</article>
		<aside class="gaside">
			<div class="gaside_top"></div>
			<div class="gaside_m">
			{if $activity_info['lat'] !='0.00000000000000' && $activity_info['lng'] !='0.00000000000000' }		
				<div class="action_map info_map" id="mapcanvas">
					<div id="map_canvas" name="map_canvas" class="formbox" style="height:200px;"></div>
				</div>
			{/if}
				{if $activity_info['yet_join']}
				<div class="g_user">
					<h3><span class="title">参与成员</span><a href="activity.php?action_id={$_INPUT['action_id']}&a=getMemberByAid" class="tmore">{$activity_info['yet_join']}</a></h3>
					<ul class="clearfix">
						{foreach $activity_info['apply_info'] as $user}
						{code}
						$avert = $url1 = $url2 =  '';
						$url1 .= isset($user_info[$user['user_id']]['avatar']['host']) ? $user_info[$user['user_id']]['avatar']['host'] : "";
						$url1 .= isset($user_info[$user['user_id']]['avatar']['dir']) ? $user_info[$user['user_id']]['avatar']['dir'] : "";
						$url2 .= isset($user_info[$user['user_id']]['avatar']['filepath']) ? $user_info[$user['user_id']]['avatar']['filepath'] : "";
						$url2 .= isset($user_info[$user['user_id']]['avatar']['filename']) ? $user_info[$user['user_id']]['avatar']['filename'] : "";
						$avert = ($url1 && $url2) ? $url1."50x50/".$url2 : DEFAULT_AVATAR1;
						{/code}
						<li><a href="member.php?uid={$user['user_id']}"><img src="{$avert}" width="50" height="50" /></a><p><a href="member.php?uid={$user['user_id']}">{$user_info[$user['user_id']]['nick_name']}</a></p></li>
						{/foreach}
					</ul>
				</div>
				{/if}
				{if count($actions)}
				<div class="g_event">
					<h3><span class="title">你可能感兴趣的活动</span><!--  <a href="#" class="tmore">浏览更多...</a>--></h3>
					<ul class="clearfix">
					{foreach $actions as $activity_other}
					{code}
							$purl1 = $purl2 =  '';
							$purl1 .= isset($activity_other['action_img']['host']) ? $activity_other['action_img']['host'] : "";
							$purl1 .= isset($activity_other['action_img']['dir']) ? $activity_other['action_img']['dir'] : "";
							$purl2 .= isset($activity_other['action_img']['filepath']) ? $activity_other['action_img']['filepath'] : "";
							$purl2 .= isset($activity_other['action_img']['filename']) ? $activity_other['action_img']['filename'] : "";
					{/code}
						<li><a href="activity.php?action_id={$activity_other['id']}"><img src="{$purl1}80x80/{$purl2}" width="79" height="79" /></a><div class="gevt_desc"><a href="activity.php?action_id={$activity_other['id']}" class="gevt_title">{$activity_other['action_name']}</a><div class="gevt_count"><span>{$activity_other['collect_num']}人收藏</span>/<span>{$activity_other['yet_join']}人报名</span></div></div></li>
					{/foreach}
					</ul>
				</div>
				{/if}			
			</div>
			<div class="gaside_bottom"></div>
		</aside>
	</section>
	<script>
	$(document).ready(function(){
		$(".action_detail").tabs({'tabNav': '.act_det_tle ul',	'tabPanel': '.act_det_pane','active': 'current',});

		window.onload = function(){
            var zhong = $('.cmain');
            var height = $('.gaside_m').outerHeight(true);
            if(zhong.height() < height){
                zhong.height(height);
            }
        };
	});
	</script>
{template:./footer}