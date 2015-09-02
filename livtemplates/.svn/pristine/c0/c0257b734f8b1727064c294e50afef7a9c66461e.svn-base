<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:contribute_style}
{css:vod_style}
{css:edit_video_list}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{js:contribute}

<script type="text/javascript">
$(function(){
	tablesort('contribute_list','apps','order_id');
	$("#contribute_list").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	<div class="content clear">
 		<div class="f">
	    	<div class="right v_list_show">
	        	<div class="search_a" id="info_list_search">
	            	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                	<div class="right_1">
							{code}	
								$time_css = array(
									'class' => 'transcoding down_list',
									'show' => 'time_item',
									'width' => 120,	
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['role_time'] = $_INPUT['role_time'] ? $_INPUT['role_time'] : 1;
							{/code}
							{template:form/search_source,role_time,$_INPUT['role_time'],$_configs['date_search'],$time_css}
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
	
	            <div class="list_first clear"  id="list_head">
	            	<span class="left">
	            		<a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"><em></em></a>
	            	</span>
                	<span class="right">
                		<!-- <a class="fl">发布</a> -->
                		<a class="fl">权限</a>
                		<a class="fb">编辑</a>
                		<a class="fb">删除</a>
                		<a class="tjr">添加人/时间</a>
                	</span>
                	<a class="title">应用名称</a>       
                		
	            </div>
	            <form method="post" action="" name="pos_listform">
		        	<ul class="list" id="contribute_list">
						{if $formdata}
			       			{foreach $formdata as $k => $v} 
			                	{template:unit/adminprivilegelist}
			                {/foreach}
			  			{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
		            </ul>
			        <div class="bottom clear">
			        	<div class="left">
			            	<input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
						    <a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
						</div>
			              {$pagelink}
			        </div>	
	    		</form>
	    		<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
	    	</div>
		</div>
	</div>
	<div id="infotip"  class="ordertip"></div>
    <span id="vod_fb" class="vod_fb" style="display: block; top: -440px; left: 98px;"></span>
    <div class="vodpub lightbox" id="vodpub" style="top: -440px;">
        <div class="lightbox_top">
            <span class="lightbox_top_left"></span>
            <span class="lightbox_top_right"></span>
            <span class="lightbox_top_middle"></span>
        </div>
        <div class="lightbox_middle">
            <span style="position:absolute;right:25px;top:25px;z-index:1000;background:url('./../livtemplates/tpl/lib/images/close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;" onclick="hg_vodpub_hide();"></span>
            <div style="max-height:500px;padding:10px 10px 0;" class="text" id="vodpub_body"><link href="./cache/tpl/css/common/common_publish.css" type="text/css" rel="stylesheet"><form onsubmit="return hg_ajax_submit('recommendform');" class="form" method="post" action="run.php" id="recommendform" name="recommendform"><div style="margin-bottom: 10px;"><div style="width: 523px; position: relative; height: 224px;" id="publish-1" class="publish-box" _initwidth="368" _initheight="285">    <span class="publish-title">发布至</span>    <div class="publish-result" style="height: 174px;">        <ul style="">                                                <li _name="新闻" _id="3"><input type="checkbox" class="publish-checkbox" checked="checked">            新闻            </li>                                </ul>        <div style="display:none;" class="publish-result-tip">没有选择！</div>    </div>    <div class="publish-site">                        <div _siteid="1" class="publish-site-current">Liv新媒体</div>                <span class="publish-site-qiehuan">切换</span>        <ul>                        <li _name="Liv新媒体" _siteid="1" class="publish-site-item publish-site-select"><input type="radio" style="vertical-align:middle;margin-right:5px;" checked="checked" name="publish-sites-1">Liv新媒体</li>                                </ul>    </div>    <div class="publish-list" _initwidth="155" style="width: 309px; height: 174px;">        <div class="publish-inner-list">            <div class="publish-each">                <ul>                                                            <li _name="新闻" _id="3">                        <input type="checkbox" class="publish-checkbox">新闻                                                <span class="publish-child">&gt;</span>                                            </li>                                        <li _name="测试" _id="99">                        <input type="checkbox" class="publish-checkbox">测试                                            </li>                                        <li _name="头条" _id="25">                        <input type="checkbox" class="publish-checkbox">头条                                            </li>                                        <li _name="图集" _id="4">                        <input type="checkbox" class="publish-checkbox">图集                                                <span class="publish-child">&gt;</span>                                            </li>                                        <li _name="宽屏" _id="2">                        <input type="checkbox" class="publish-checkbox">宽屏                                                <span class="publish-child">&gt;</span>                                            </li>                                        <li _name="直播" _id="1">                        <input type="checkbox" class="publish-checkbox">直播                                                <span class="publish-child">&gt;</span>                                            </li>                                        <li _name="热播剧推荐" _id="66">                        <input type="checkbox" class="publish-checkbox">热播剧推荐                                            </li>                                        <li _name="访谈" _id="8">                        <input type="checkbox" class="publish-checkbox">访谈                                            </li>                                                        </ul>            </div>        </div>    </div>    <input type="hidden" value="3" name="column_id" class="publish-hidden">        <input type="hidden" value="新闻" class="column-name">    <input type="hidden" value="新闻" name="column_name" class="publish-name-hidden">        </div></div><input type="hidden" value="publish" name="a"><input type="hidden" value="58" name="mid"><input type="hidden" value="1079" name="id"><input type="hidden" value="" name="hg_recomend"><span class="label">&nbsp;</span><input type="submit" class="button_4" value="重新发布" name="rsub"></form></div>
        </div>
        <div class="lightbox_bottom">
            <span class="lightbox_bottom_left"></span>
            <span class="lightbox_bottom_right"></span>
            <span class="lightbox_bottom_middle"></span>
        </div>
    </div>
</body>
{template:foot}