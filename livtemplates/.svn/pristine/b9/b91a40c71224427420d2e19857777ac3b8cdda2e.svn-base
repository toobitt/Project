{template:unit/head}
{css:twitter}

{js:twitter/underscore}
{js:twitter/Backbone}
{js:twitter/twitter}
<body class="user"  style=""  id="body_content">
<div class="_left">{template:unit/user_menu}</div>
<div class="_mid">
	<div class="con">
		<div class="pub">
			<textarea id="content" name="content"></textarea>
				<div class="btn_list" style="display:none;">
					<span class="btn_img">图片</span>
					<span class="btn_video">视频</span>
					<span class="btn_music">音乐</span>
					<input value="发布" name="sub" type="button" class="pub-btn"/>
				</div>
		</div>
			<!--
			<div class="recommond">
			<div class="title clearfix">
			<h3>纽约华人华侨联合国楼前抗议野田演讲</h3>
			<span class="date">今天  09:21</span>
			</div>
			<p class="news-con">朱立创对中新社记者言辞激烈地表示，“今天华侨华人聚集在联合国大楼前呐喊，就是让世界和日本看到中国民间的意愿和捍卫钓鱼岛的决心，捍卫中国的领土不仅是两岸人民的责任，也是全球华人的责任。我们身上都流着炎黄子孙的热血</p>
			<p class="news-replace"><<<em>中方:搬国际法作幌子自欺欺人</em>|<em>日本外相要求中方停止反日游行</em>>></p>
			</div>-->
		<div class="conList">
			{if !empty($statusinfo)}
			<ul>
			{template:unit/status_line}
			</ul>
			{/if}
		</div>
	</div>
</div>
<div class="_right twitter-right">{template:unit/twitter_right}</div>


<div id="transmit-dialog" title="转发微博">
     <div class="transmit-box">
          <div class="transmit-text">
            <div class="title"><span>@</span><span class="user-name"></span><span class="transmit-con"><span></div>
            <span class="W_arrow"><em class="down">◆</em></span>
          </div>
          <div class="transmit-form">
                 <p class="tip">还可以输入<b class="number-normal">140</b>字</p>
                 <textarea name="transmitCon" id="transmitCon" cols="45" rows="5" class="transmit-txtarea" placeholder="请输入转发理由"></textarea>
                 <input value="转发" name="sub" type="button" class="transmit-btn" />
          </div>
     </div>
</div>
</body>
</html>