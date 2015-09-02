{template:./head}
</section><!--展示区完-->
	<!--网站主体-->
	<div class="wrap">
		<!--列表-->
		<div class="action_left">
			<div class="action_ltop"></div>
			<div class="action_lmid">
				<!--tab切换-->
				<div class="scrolldoorFrame">
					<div class="ac_hot">
						<h3><a href="#">热门行动</a></h3>
						<ul class="scrollUl fz">
						  <li {if $date_search==1}class="t01"{else}class="t02"{/if} id="n11"><a href="{$urltype}&date_search=1">本周</a></li>
						  <li {if $date_search==2}class="t01"{else}class="t02"{/if} id="n12"><a href="{$urltype}&date_search=2">下一周</a></li>
						  <li {if $date_search==3}class="t01"{else}class="t02"{/if} id="n13"><a href="{$urltype}&date_search=3">最近一个月</a></li>
					  	</ul>
					</div>
					 
					  <div class="bor03 ">
						<div id="f11">
						
							<ul class="ac_list">
							{if $actions}
							{foreach $actions as $activity =>$v}
								<li>
									<a href="activity.php?action_id={$v['id']}"><img src="{$v['action_img']}"></a>
									<div class="ac_list_div">
										<h4><a href="activity.php?action_id={$v['id']}">{code}echo hg_cutchars(strip_tags($v['action_name']), 10);{/code}</a></h4>
										<p>{code}echo hg_cutchars(strip_tags($v['place']), 10);{/code}</p>
										<p>{$v['collect_num']}人感兴趣</p>
										<p>{$v['yet_join']}人参加</p>
									</div>
								</li>
							{/foreach}
							{else}
								<h5>没有你查找的数据</h5>
							{/if}
							</ul>
							
							<div class="pages_nav">
								{$pagelink}
							</div>
						</div>
					  </div>
           			</div>	
			</div>
			<div class="action_lbtn"></div>
		</div>
		<!--右侧-->
		{template:./setion}
{template:./footer}