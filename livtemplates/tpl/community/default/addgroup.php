{template:./head}
{js:qingao/jquery.upload}
{js:qingao/create_group}
{css:creategroup}
</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain add_groups">
				<div class="group_title"><h1 class="gt1">创建圈子</h1></div>
				<div class="add_group">
					<div class="group_tip">独乐不不如众乐乐！召集一群同样喜好的人，聊着同样的话题，做着同样的事....世界上最大的幸福莫过于此。</div>
					<form action="groups.php" method="post" id="create_group">
						<div class="line">
							<label>圈子名称:</label><input type="text" name="group_name" class="txt" value="为你的圈子取个响当当的名字，15个字以内" />
						</div>
						<div class="line">
							<label>圈子域名:</label><input type="text" name="group_domain" class="txt" value="6-20位，字母或数字" />
						</div>
						<div class="line">
							<label>圈子宣言:</label><textarea name="group_desc" class="txt">专属你圈子的广告语，控制在60字</textarea>
						</div>
						<div class="line">
							<label>圈子头像:</label>
							<div class="f-l">
								<p><img width="100" height="100" id="logo_img" src="{$logo}" /></p>
								<p><input type="button" class="upload_img" attr="logo" /></p>
							</div>
							<div class="clear"></div>
						</div>
						<div class="line">
							<label>圈子顶图:</label>
							<div class="f-l">
								<p><img width="333" height="222" id="background_img" src="{$background}" /></p>
								<p><input type="button" class="upload_img" attr="background" /></p>
							</div>
							<div class="clear"></div>
						</div>
						<div class="line">
							<label>圈子标签:</label>
							<div id="group_tags">
								<input type="text" name="group_tag" class="txt" id="group_tag" value="不超过5个，请用逗号隔开。输入标签可以为你带来更多关注者" />
								<ul>
									<li>青奥</li>
									<li>公益</li>
									<li>旅行</li>
									<li>教育</li>
									<li>文化</li>
									<li>创业</li>
									<li>创意</li>
									<li>科技</li>
									<li>音乐</li>
									<li>艺术</li>
									<li>居家</li>
									<li>摄影</li>
									<li>设计</li>
									<li>电影</li>
									<li>美食</li>
									<li>汽车</li>
									<li>游戏</li>
									<li>动漫</li>
									<li>时尚</li>
									<li>原创</li>
									<li>搭配</li>
									<li>生活</li>
								</ul>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="line">
							<label>参与权限:</label>
							<div id="permission_select">
								<div class="p_select"><input type="radio" name="permission" value="6" checked="checked" />&nbsp;公共圈子<em>所有人都可以访问</em></div>
								<div class="p_select"><input type="radio" name="permission" value="0" />&nbsp;私密圈子<em>只有圈子成员才可以访问</em></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						<input type="hidden" name="a" value="add" />
						<input type="hidden" name="logo" id="logo" value="{$logo}" />
						<input type="hidden" name="background" id="background" value="{$background}" />
						<div class="c_submit">
							<h3>确认发布，激活你的圈子</h3>
							<input type="submit" name="add_group_btn" id="subBtn" value="创建圈子" />
						</div>
					</form>
				</div>		
			</div><!--end for cmain-->
			<div class="gmain_bottom"></div>
		</article>
		<aside class="gaside hid">
			<div class="gaside_top"></div>
			<div class="gaside_m">
				<div class="c_left_t">
	            	<h2>如何让你的圈子更圈人？</h2>
	                <ul>
	                	<li>
	                		<h3>圈子的头像有个性</h3>
	                        <p>我们将会在多以圈子头像的开工推荐你的圈子，如果圈子能够了突出主题目、，且足够有吸引力，则会被更多的的人注意到</p>
	               	   </li>
	                   <li>
	                		<h3>主题明确</h3>
	                        <p>我们将会在多以圈子头像的开工推荐你的圈子，如果圈子能够了突出主题目、，且足够有吸引力，则会被更多的的人注意到</p>
	               	   </li>
	                    <li>
	                		<h3>保持较高的活跃度</h3>
	                        <p>我们将会在多以圈子头像的开工推荐你的圈子，如果圈子能够了突出主题目、，且足够有吸引力，则会被更多的的人注意到</p>
	               	   </li>
	                </ul>
	            </div>
	            <div class="c_help">
	            	<h2>如需更多的帮助，请前往</h2>
	            <p><a href="#">>帮助中心</a></p>
	            </div>
			</div>
			<div class="gaside_bottom"></div>
		</aside>
	</section>
{template:./footer}