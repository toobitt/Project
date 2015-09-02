<?php 
/* $Id: email_log_list.php 12680 2012-10-10 09:29:56Z lijiaying $ */
?>
{template:head}
{js:email}
{css:vod_style}
{js:vod_opration}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">

<div class="content clear">
	<div class="f">
		<div class="right v_list_show" style="float:none;">
			<!-- 搜索 -->
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
					</div>
					<div class="right_2">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,k,$_INPUT['k']}                        
					</div>
				</form>
			</div>
			<form action="" method="post">
				<!-- 标题 -->
				<div class="list_first clear"  id="list_head">
					<span class="left"><a class="lb" style="cursor:pointer;"><em></em></a></span>
					<span style="width:650px;" class="right"><a class="fb" style="width:70px;margin-left: 0px;">发送人邮箱</a><a class="fb" style="width:120px;margin-left: 90px;">收件人邮箱</a><a class="zt" style="margin-left: 120px;">状态</a><a class="tjr" style="margin-left: 50px;">添加人/时间</a></span><a class="title" style="margin-left: 50px;margin-top: 8px;">邮件标题</a>
				</div>
				<ul class="list ui-sortable ui-sortable-disabled" id="status_list">
					{if $list}
						{foreach $list as $k => $v}
							{template:unit/email_log_list_list}
						{/foreach}
						<li class="clear"></li>
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					{/if}
				</ul>
				<div class="clear"></div>
				<div class="left" style="width:400px;margin-left:24px;">
					<input type="checkbox" style="position: relative;top: 7px;" name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">批量删除</a>
				</div>
			</form>
		</div>
		{$pagelink}
		</div>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
		</div>
	</div>
</div>
</body>
{template:foot}