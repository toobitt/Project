{template:unit/head}
{csshere}
{css:jquery-ui-1.8.16.custom}
{css:twitter}
{css:reset}
{css:public}
{css:twitter}

{jshere}
{js:jquery}
{js:jquery-ui-min}
{js:jquery.form}
{js:twitter/underscore}
{js:twitter/Backbone}
{js:twitter/twitter}
<body class="user"  style=""  id="body_content">
<div class="_left">{template:unit/user_menu}</div>
<div class="_mid">
	<div class="con">
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