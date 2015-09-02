{code}
	$embed = '<embed src="{$vedio_url}" quality="high" width="480" height="400" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>';
{/code}
	<div class="interacDisable"></div>

	<div class="interactArea">
	 
	<div class="panels">
		<div id="panel_share" class="panel panelShare" style="display: block;">
			<div class="p1">
				
				<h4><span class="close_share" style="float:right;margin-right:5px;line-height: 30px;" ><a  title="关闭" onclick="close_share();" href="javascript:void(0);">&nbsp;</a></span>分享给站外好友</h4>
				<div class="item"><span class="label">{$name}地址: </span> <input type="text" value="{$url}" id="link1"><button onclick="javascript:copyToClipboard('link1');">复制</button></div><!--
				<?php if($type == 1){?>
				<h4 class="clear">把视频贴到Blog或BBS</h4> 
				<div class="item"><span class="label">flash地址: </span> <input type="text" value="<?php echo $vedio_url;?>" id="link2"><button onclick="javascript:copyToClipboard('link2');">复制</button></div>
				<div class="item"><span class="label">html代码: </span> <input type="text" value='<?php echo $embed;?>' id="link3"><button onclick="javascript:copyToClipboard('link3');">复制</button></div>
				<?php }?>
			--></div>
			<div class="clear1"></div>
			 
 		</div>  
		</div>
		
		<div class="transArea">
		<!-- 这里更多的显示要判断下当前显示的数量 -->
		
<!--			<div class="related" style="float:right;"><a href="javascript:void(0);" onclick="show_moreShare();">更多</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a target="_blank" href="jaascript:void(0);">举报</a></div>-->
			<div class="links" >
				<div class="handle"></div>
				<span class="label" style="padding-left:5px;">转贴到:</span>
				<span class="icos">
					<a target="_blank" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={$url}" id="s_qq" charset="400-03-8" title="转发至QQ空间"><img src="./res/img/ico_Qzone.gif"></a>
					<a target="_blank" href="http://share.renren.com/share/buttonshare.do?link={$url}&title={$title}" id="s_renren" charset="400-03-7" title="转发至人人网"><img src="./res/img/ico_renren.gif"></a>
					<a target="_blank" href="http://v.t.sina.com.cn/share/share.php?appkey=<?php echo WB_AKEY;?>&url={$url}&title={$title}&sourceUrl={$_settings['livime_images_url']}&content=utf8&pic=" id="s_sina" charset="400-03-10" title="转发至新浪微博"><img src="./res/img/ico_sina.gif"></a>
					<a target="_blank" href="http://tt.mop.com/share/shareV.jsp?title={$title}&flashUrl={$vedio_url}&pageUrl={$url}" id="s_mop" charset="400-03-19" title="分享到MOP"><img src="./res/img/ico_mop.gif"></a>
					<a target="_blank" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?to=pengyou&url={$url}&title={$title}" id="s_pengyou" charset="400-03-19" title="分享到腾讯朋友"><img src="./res/img/ico_pengyou.png"></a>
					<a target="_blank" href="http://t.163.com/article/user/checkLogin.do?link={$url}&source=葫芦网&info={$title} {$url}" id="s_163" charset="400-03-13" title="分享到网易微博"><img src="./res/img/ico_163.gif"></a>
					<a target="_blank" href="http://www.douban.com/recommend/?url={$url}&title={$title}" id="s_douban" charset="400-03-17" title="推荐到豆瓣"><img src="./res/img/ico_dou.png"></a>
					<a target="_blank" href="http://tieba.baidu.com/i/sys/share?title={$title}&type=video&content=&link={$url}" id="s_baidu" title="分享到i贴吧"><img src="./res/img/ico_baidu.png"></a>
					<a target="_blank" href="http://www.tianya.cn/new/share/compose.asp?itemtype=tech&item=665&strtitle={$title}&strFlashURL={$vedio_url}&strFlashPageURL={$url}" id="s_tianya" charset="400-03-15" title="分享到天涯"><img src="./res/img/ico_tianya.png"></a>
				</span>
			</div> 
		</div>
	</div>