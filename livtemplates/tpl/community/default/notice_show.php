{template:./head}
{js:qingao/reply_thread}
<style type="text/css">
#noticeCon {color:#7d7d7d;}
#noticeCon h1 {font-size:18px; font-weight:bold; text-align:center;}
#noticeCon p.notice_property {margin:20px auto 10px auto; text-align:center; font-size:12px;}
#noticeCon p.notice_property span {margin:0 30px;}
#noticeCon hr {height:1px; color:#EEE;}
#noticeCon .con {line-height:24px; font-size:14px; text-indent:2em;}
</style>
</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain" id="noticeCon">
				<h1>{$notice['title']}</h1>
				<p class="notice_property"><span><strong>发布者：</strong>{$notice['user_name']}</span><span><strong>查看数：</strong>{$notice['views']}次</span><span><strong>发布时间：</strong>{code}echo hg_get_format_date($notice['pub_date'], 2);{/code}</span></p>
				<hr />
				<div class="con">{code}echo html_entity_decode($notice['content']);{/code}</div>
			</div><!--end for cmain-->
			<div class="gmain_bottom"></div>
		</article>
		<aside class="gaside hid">
			<div class="gaside_top"></div>
			<div class="gaside_m">
				<div class="create_group"><a href="groups.php?a=create"><img src="img/creategroup.png" /></a></div>
				{template:./join}
			</div>
			<div class="gaside_bottom"></div>
		</aside>
	</section>
{template:./footer}