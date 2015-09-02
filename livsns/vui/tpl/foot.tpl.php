<?php 
/* $Id: foot.tpl.php 3516 2011-04-10 16:30:05Z develop_tong $ */
?>
<div class="fixed_b clear1" id="fixed_bottom">
	<div class="fixedBox">
		<div class="MMessageUser">
			<ul id="dialogMinimizeContainer"></ul>
		</div>
	</div>
</div> 
<div class="clear1"></div>
<div class="footer">
<div class="area_foot">
	<div class="foot_cot">
    	<ul class="copy_txt">
        	<li class="cp_atxt"><a href="http://www.hoolo.tv/public/gyhlw/" target="_blank">关于葫芦</a> | <a href="http://www.hoolo.tv/public/wzdt/" target="_blank"> 网站地图</a> | <a href="http://www.hoolo.tv/public/bqsm/" target="_blank"> 版权申明</a> | <a href="http://www.hoolo.tv/public/yqlj/" target="_blank"> 友情链接</a> | <a href="http://www.hoolo.tv/public/gghz/" target="_blank">广告合作</a> | <a href="http://www.hoolo.tv/public/lxwm/" target="_blank"> 联系我们</a> | <a href="http://www.hoolo.tv/public/cpyc/" target="_blank"> 诚聘英才</a> | <a href="http://www.hoolo.tv/public/yhxy/" target="_blank"> 用户协议</a></li>
            <li class="cp_btxt">杭州文广集团 梦想传媒有限责任公司  版权所有  2010-2011 浙ICP备10043234-2 视听内容来源：杭州文广网</li>
        </ul>
    </div>
</div>
</div>
<!-- end wrap -->
<?php include hg_load_template('tips');?>
<div id="desktop"></div>
<?php
echo hg_add_foot_element('echo'); 
?><?php echo $this->settings['stat_code'];?>
</body>
</html>
