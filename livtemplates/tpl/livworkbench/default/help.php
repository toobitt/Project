<?php 
/* $Id$ */
?>
{template:head}
{css:ad_style}
<style>
.wrap.n.s{background:#f6f6f6;padding:0;font-family:"Microsoft Yahei"}
dl{list-style: square;}
.tab_ul{height:100%;width:12%;background:#fff;float:left;border-top-left-radius: 5px;border-right:1px solid #EFEFEF;}
.tab_ul li{position:relative;font-size:14px;line-height:35px;padding:0 0 0 35px;cursor:pointer;border-top:1px solid transparent;border-bottom:1px solid transparent}
.tab_ul li.f{margin-top: 20px;}
.tab_ul dd{font-size:12px;}
.tab_ul li a{display:block;color:#c8c8c8;font-size:14px;}
.tab_ul li a:hover{color:#3A6EA5}
.tab_div ul li.title{font-size:14px;}
.tab_ul li.h{background:#f6f6f6;border-top:1px solid #efefef;border-bottom:1px solid #efefef;}
.tab_ul li.h a{color:#428bca;}
.tab_ul li span.b{font-size:0;line-height:1%;height:33px;background:url("{$RESOURCE_URL}atrr_now.png") no-repeat;display:none;position:absolute;right:-1px;top:-21px;width:23px;height:76px;}
.tab_ul li.h span.b{display:block}
.tab_div{float:left;width:88%;height:100%}
.tab_div p,.tab_div ul{color:#949494;padding:10px;line-height:22px;text-indent:20pt;color:#333;text-shadow:1px 1px 0 #fff;font-size:12px;}
.tab_div p.t{text-indent:0;line-height:inherit}
.tab_div p.t:first-letter {font-size:250%;float:left;font-family:verdana}
.tab_div h2{font: 22px/24px "Microsoft Yahei";color:#428BCA;padding:20px 0 0 20px;line-height: 36px;}
.tab_div h3 a{font: 16px/20px "Microsoft Yahei";color:#428BCA;padding:20px 0 0 20px;text-indent: 30pt;line-height: 30px;}
#elevator{right:-45px;bottom: 50%;width: 40px;padding: 20px 10px 30px;-moz-transition: bottom 250ms ease-in-out;-webkit-transition: bottom 250ms ease-in-out;}
#elevator:hover{right:-5px;}
.btn.Indicator {position: fixed;z-index: 4;display: block;text-align: center;font-size: 15px;border-width: 0;box-shadow: 0 0 #fff;-moz-box-shadow: 0 0 #fff;-webkit-box-shadow: 0 0 #fff;}
.btn strong{position: relative;z-index: 2;line-height: 15px;}
.btn span {position: absolute;z-index: 1;top: -1px;right: -1px;bottom: -1px;left: -1px;display: block;border: 1px solid;opacity: 1;border-radius: .3em;-moz-border-radius: .3em;-webkit-border-radius: .3em;box-shadow: inset 0 1px rgba(255,255,255,.35);-moz-box-shadow: inset 0 1px rgba(255,255,255,.35);-webkit-box-shadow: inset 0 1px rgba(255,255,255,.35);-moz-transition-property: opacity;-moz-transition-duration: .5s;-moz-transition-timing-function: ease-in-out;-webkit-transition-property: opacity;-webkit-transition-duration: .5s;-webkit-transition-timing-function: ease-in-out;}
.wbtn span {border-color: #BBB;background: -webkit-gradient(linear,0% 0,0% 100%,from( #FDFAFB),to( #F0EDED),color-stop(.5, #F9F7F7),color-stop(.5, #F6F3F4));background: -moz-linear-gradient(center top, #FDFAFB, #F9F7F7 50%, #F6F3F4 50%, #F0EDED);background: -o-linear-gradient(top left, #FDFAFB, #F9F7F7 50%, #F6F3F4 50%, #F0EDED);background: -webkit-gradient(linear,0% 0,0% 100%,from( #FDFAFB),to( #F0EDED),color-stop(.5, #F9F7F7),color-stop(.5, #F6F3F4));filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdfafb', endColorstr='#f0eded');
}
.menu_edit{float: right;width: 33px;}
</style>
<script>
$(function(){
	$('#tab_ul').height($('#wrap').height());
});

</script>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_microblogging first dq"><em></em><a>帮助文档</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" style="float:right;margin-right:10px;margin-top:8px;">
		{if $_INPUT['fatherid'] > -1}
		<a href="?a=menu_form&id={$fatherid}" class="button_6" style="font-weight:bold;">编辑当前分类</a>
		{/if}
		<a href="?a=menu_form&fatherid={$fatherid}" class="button_6" style="font-weight:bold;">新增分类</a>
		<a href="#" class="button_6" style="font-weight:bold;">新增文档</a>
	</div>
</div>
<div class="wrap n s clear" id="wrap">
	{if $sorts}
	<ul class="tab_ul" id="tab_ul">
		{code}
		if (!$_INPUT['fatherid'])
		{
			$class = ' h';
		}
		else
		{
			$class = '';
		}
		{/code}
		<li class="f{$class}"><a href="help.php">全部内容</a><span class="b"></span></li>
		
		{code}
		$len = count($sorts) - 1;
		$i = 0;
		{/code}
		
		{foreach $sorts AS $sort}
		{code}
		
		if ($_INPUT['fatherid'] == $sort['id'])
		{
			$class = ' h';
		}
		else
		{
			$class = '';
		}
		$i++;
		{/code}
		<li class="{$class}"><a href="help.php?fatherid={$sort['id']}{$_ext_link}">{$sort['name']}</a><span class="b"></span></li>
		{/foreach}
	</ul>
	{/if}
	<div class="tab_div">
	<h2>目录</h2>
	{if $help}
	<ul>
		{foreach $help AS $k => $v}
				<li>· <a href="#h_{$v['id']}">{$v['subject']}</a></li>
		{/foreach}	
	</ul>
	{/if}
	
	{if $help}
	
		{foreach $help AS $k => $v}
			<h3 class="h3_title"><a href="###" name="h_{$v['id']}">{$v['subject']}</a></h3>
			{$v['content']}
		{/foreach}	
	
	{/if}


	<!--<p class="t">
	T内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述内容1描述
	· 内容1描述
	· 内内容1描述
	· 内容1描述
	</p>
	<ul>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
		<li>· 内容1描述</li>
	</ul>-->
	</div>
</div>
<a id="elevator" href="#" onclick="return false;" class="Indicator btn wbtn "><strong> 回到顶部</strong><span></span></a>
{template:foot}