<?php 
/* $Id: interactive_member_list.php 14820 2012-11-29 08:47:27Z lijiaying $ */
?>
{template:head}
{js:member}
{css:vod_style}
{css:edit_video_list}
{css:common/common_list}
{css:mem_list}
{js:vod_opration}
{js:common/common_list}
{js:tree/animate}
{css:interactive}
{js:live_interactive/interactive}

{code}

/*hg_pre($list);*/
{/code}

<!-- plat -->
<div id="plat_info" class="plat_info">
	<div id="plat_loading" class="plat_loading"></div>
</div>
<!-- plat -->
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
{template:list/ajax_pub}
<div class="content clear">
	<div class="f">
		<div class="right v_list_show" style="float:none;">
			<!-- 搜索 -->
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
				
					<div class="text-search">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,k,$_INPUT['k']}                        
					</div>
				</form>
			</div>
			<form action="" method="post">
				<!-- 标题 -->
              <ul class="common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="mem-paixu common-list-item"><a class="common-list-paixu" style="cursor:pointer;"></a></div>
                                <div class="mem-fengmian common-list-item">台标</div>
                            </div>
                            <div class="common-list-right">
                                <!-- <div class="mem-cz common-list-item open-close" which="mem-cz">操作</div> -->
                                <div class="mem-sj common-list-item open-close" which="mem-sj">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti wd120">
                            	<div class="common-list-item">频道名/台号
                            	</div>
                            </div>
                        </li>
                </ul>
                <ul class="common-list public-list" id="status_list">
					{if $list}
						{foreach $list AS $k => $v}
							{template:unit/interactive_channel_list_list}
						{/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					{/if}
				</ul>
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