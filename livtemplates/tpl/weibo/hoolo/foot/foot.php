<?php 
/* $Id: foot.php 380 2011-07-20 08:34:51Z lijiaying $ */
?>

<div class="fixed_b" id="fixed_bottom">
	<div class="fixedBox">
		<div class="MMessageUser">
			<ul id="dialogMinimizeContainer"></ul>
		</div>
	</div>
</div> 
<div class="footer clear">
  <div class="bottom">
    	<ul class="copy_txt">
        	<li class="cp_atxt"><a href="http://www.hoolo.tv/public/gyhlw/" target="_blank">关于葫芦</a> | <a href="http://www.hoolo.tv/public/wzdt/" target="_blank"> 网站地图</a> | <a href="http://www.hoolo.tv/public/bqsm/" target="_blank"> 版权申明</a> | <a href="http://www.hoolo.tv/public/yqlj/" target="_blank"> 友情链接</a> | <a href="http://www.hoolo.tv/public/gghz/" target="_blank">广告合作</a> | <a href="http://www.hoolo.tv/public/lxwm/" target="_blank"> 联系我们</a> | <a href="http://www.hoolo.tv/public/cpyc/" target="_blank"> 诚聘英才</a> | <a href="http://www.hoolo.tv/public/yhxy/" target="_blank"> 用户协议</a></li>
            <li class="cp_btxt">杭州文广集团 梦想传媒有限责任公司  版权所有  2010-2011 浙ICP备10043234-2 视听内容来源：杭州文广网</li>
        </ul>
 </div>
</div>
<input type="hidden" name="push_flag" id="push_flag" value="0" />
<!-- end wrap -->
{template:unit/tips}
{template:unit/report}
<div id="desktop"></div>
{code}
echo hg_add_foot_element('echo'); 
{/code}
</div>
{code}
echo $_settings['stat_code'];
{/code}
</body>
</html>