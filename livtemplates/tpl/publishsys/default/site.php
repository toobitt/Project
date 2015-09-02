<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}

{css:vod_style}
{css:mark_style}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=form" class="button_6" style="font-weight:bold;">添加站点</a>
</div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
                    </div>
                    <div class="right_2">
                    </div>
                    </form>
                </div>


				<div class="list_first clear"  id="list_head">
                    	<span class="left">
                    	<a class="shareslt">网站名称</a>
                    	<a class="publishsys_site_key" >网站关键字</a>
							<a class="publishsys_site_des" >网站描述</a>
							<a class="shareslt">域名</a>
	                        
							<a class="shareslt">操作</a>
                    	</span>
                </div>
                <form method="post" action="" name="listform">
               		 <ul class="list" id="vodlist">
					  	{if $site[0]}
							{foreach $site[0] as $k => $v}	
		                      {template:unit/site_list}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">请选择站点……！</p>
						<script>hg_error_html(sharelist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
                	</ul>
	            <div class="bottom clear">
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'build_api_file',  '生成文件', 1, 'id', '', 'ajax','');"    name="bataudit" >批量生成</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
				   </div>
	               {$pagelink}
	            </div>	
    		</form>
 	</div>
</div>
</div>
</body>
{template:foot}