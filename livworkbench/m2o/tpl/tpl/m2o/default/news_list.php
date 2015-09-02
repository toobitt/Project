{code}
$news_B = array(
	array(
		'src' => 'http://news.sina.com.cn/c/2013-01-31/081026166493.shtml',
		'title' => '美日等国停飞所有波音787客机 中国未放飞',
		'date' => '02月21日 10:09'
	),
	array(
		'src' => 'http://news.sina.com.cn/c/2013-01-31/021926162758.shtml',
		'title' => '空军歼10战机快修视频曝光 锤子电钻齐上阵',
		'date' => '02月21日 10:09'
	),
	array(
		'src' => 'http://news.sina.com.cn/c/2013-01-31/081026166493.shtml',
		'title' => '10名在韩失踪中国，一小时后挂掉',
		'date' => '02月21日 10:09'
	),
	array(
		'src' => 'http://news.sina.com.cn/c/2013-01-31/081026166493.shtml',
		'title' => '实拍北京地铁2号线男女因占座爆发群殴',
		'date' => '02月21日 10:09'
	),
	array(
		'src' => 'http://news.sina.com.cn/c/2013-01-31/081026166493.shtml',
		'title' => '男子舔电线杆舌头被冻住 快递员遭男子暴打',
		'date' => '02月21日 10:09'
	),
);
{/code}

{template:unit/head}
{template:unit/head_menu}
<style>
a{color:inherit;}
.container{padding:45px 0;}
.page-news_list .news_B{padding:25px 0 20px;border-bottom:1px dotted #cfcfcf;}
.news-list-left{float:left;margin:0 0 40px 20px;width:610px;}
.news-title{padding-bottom:10px;border-bottom:2px solid #979797;text-indent:5px;position:relative;}
.news-title-deco{bottom:-2px;left:0;position:absolute;height:2px;width:75px;background:#ad0802;}
.news-list-right{float:right;width:310px;margin-right:20px;}

.prefix-vedio-icon{padding-left:20px;background:url({$RESOURCE_URL}m2o/vedio_icon.png) no-repeat;}

.news-list-hot{background:#f7f7f7;padding:7px 0;line-height:32px;}
.news-list-hot a{display:block;padding:0 15px;}
.news-list-hot span{width:15px;height:15px;display:inline-block;line-height:15px;text-align:center;margin-right:10px;color:#818181;}
.news-list-hot a:hover{background:#d7d7d7;color:#205ca5;}
.news-list-hot .topNum span{color:white;background:#969696;}
.news-list-hot .topNum1 span{background:#AD0802;}
.news-list-ad{width:310px;}
.news-list-ad img{width:310px;height:180px;}
.news-list-ad span{line-height:40px;height:40px;font-size:16px;text-indent:15px;}
.news-list-vedio-news{width:145px;}
.news-list-vedio-news img{width:145px;height:90px;}
.news-list-vedio-news p{width:125px;margin:5px auto 0;line-height:20px;}
.news-list-pic-news p{padding:10px 0 0 15px;}
.news-list-pic-news p span{display:block;}

.news-list-plinks{margin-left:115px;}
.news-list-plinks .plinks-item{float:left;width:20px;color:#808080;}
.news-list-plinks .plinks-prev{width:95px;}
.news-list-plinks .plinks-next{width:95px;text-align:right;}
.news-list-plinks .cur-page{color:#1d5da5;}
</style>

<div class="container clearfix layout page-{$data['page_name']}">
	<div class="news-list-left">
		<div class="news-title">
			<div class="news-list-nav"><a>首页</a> > <a>新闻</a> > <a>新闻列表</a></div>
		</div>
		{foreach range(0, 6) as $index}
		<div class="news_B ft16 lh24">
			<ul>
				{foreach $news_B as $k => $v}
				<li><a src="{$v['src']}" {if ($index + $k) == 4}class="prefix-vedio-icon"{/if}>{$v['title']}<em class="ml15 ft12 cr_808080">{$v['date']}</em></a></li>
				{/foreach}
			</ul>
		</div>
		{/foreach}
		
		<div class="news-list-plinks clearfix mt20">
			<div class="plinks-item plinks-prev"><a>上一页</a></div>
			<div class="plinks-item"><a>1</a></div>
			<div class="plinks-item">|</div>
			<div class="plinks-item"><a>2</a></div>
			<div class="plinks-item">|</div>
			<div class="plinks-item cur-page"><a>3</a></div>
			<div class="plinks-item">|</div>
			<div class="plinks-item"><a>4</a></div>
			<div class="plinks-item">...</div>
			<div class="plinks-item"><a>25</a></div>
			<div class="plinks-item plinks-next"><a>下一页</a></div>
		</div>
	</div>
	<div class="news-list-right">
		<div class="news-title">
			<span class="news-title-deco"></span>
			<h3>热点新闻</h3>
		</div>
		<ol class="news-list-hot mt15">
		{foreach range(1, 10) as $v}
			<li {if $v < 4}class="topNum topNum{$v}"{/if}>
				<a href="http://news.sina.com.cn/c/2013-01-30/192426161704.shtml" target="_blank"><span>{$v}</span>日本忧中国有害物质随风飘至</a>
			</li>
		{/foreach}
		</ol>
		<div class="img_f_text news-list-ad mt20">
			<img src="tpl/tpl/lib/images/m2o/photo_3.png" width="310" height="180" />
			<span>FASLF ASLF</span>
		</div>
		
		<div class="news-title mt20">
			<span class="news-title-deco"></span>
			<h3>视频新闻</h3>
		</div>
		<div class="clearfix">
			{foreach range(1, 4) as $v}
			<div class="news-list-vedio-news mt15 {if $v %  2 != 0}fl{else}fr{/if}">
				<div class="img_f_text">
					<img src="tpl/tpl/lib/images/m2o/photo_3.png" width="145" height="90" />
					<span></span>
				</div>
				<p>索尼发布Plsalsfd4游戏机</p>
			</div>
			{/foreach}
		</div>
		
		<div class="news-title mt20">
			<span class="news-title-deco"></span>
			<h3>图片新闻</h3>
		</div>
		{foreach range(1, 3) as $v}
		<div class="news-list-pic-news clearfix mt15">
			<img class="fl" src="tpl/tpl/lib/images/m2o/photo_3.png" width="110" height="60" />
			<p class="fl">索尼发布Plsalsfd4游戏机
				<span class="ft12 lh24 cr_808080">已地卡罗法讲述了</span>
			</p>
		</div>
		{/foreach}
	</div>
</div>
{template:unit/foot}