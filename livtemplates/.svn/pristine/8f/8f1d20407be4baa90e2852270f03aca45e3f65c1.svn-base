<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:vod_style}
{css:mark_style}
{css:common/common_list}
{js:common/common_list}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                 	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    	<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	                    <div class="right_2">
	                    	<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
	                        </div>
							{template:form/search_input,k,$_INPUT['k']}                        
	                    </div>
                   	</form>
                </div>
                <form method="post" action="" name="listform">
                     <ul class="common-list" id="list_head">
                        <li class="common-list-head clear public-list-head">
                            <div class="common-list-left">
                                <div class="common-list-item paixu"><a class="common-list-paixu" onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right"> 
                                <div class="common-list-item">删除</div>
                                <div class="common-list-item">数量</div>
                                <div class="common-list-item wd150">创建时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">系统名称</div>
					        </div>
                        </li>
                     </ul>
               		 <ul class="common-list public-list" id="vodlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}	
		                      {template:unit/client_manage_list}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
                	</ul>
	               <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
	                     <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
				         <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
				       </div>
	                  {$pagelink}
	                </li>
	              </ul>	
    		</form>
 	</div>
</div>
</div>
</body>
{template:foot}