<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
$list = $mobile_sort_list[0];
{/code}

{css:vod_style}
{css:mark_style}
{css:common/common_list}
{js:common/common_list}
{js:mobile/ajax_upload}
{js:mobile/importing}
<style>
.handler-icon{display:inline-block;background:#fdd01b;color:white;padding:0 8px;line-height:24px;border-radius:4px;}
.makefile{background:#1bbc9b;}
.tips{position:fixed;border:4px solid #6ba4eb;width:400px;height:80px;top:200px;left:50%;line-height:80px;text-align:center;font-size:18px;margin-left:-200px;opacity:0;z-index:-10;background:white;transition:all ease-in .4s;}
</style>
<script type="text/javascript">
function downloadFile(obj)
{
	window.location.href = $(obj).attr('_href');
}
function build_api_file_callback(data)
{
	var data = JSON.parse(data);
	
	if(data == 'success')
	{
		var words = '生成成功';
	}
	else
	{
		var words = '生成失败';
	}
	var tip = $('.tips');
	tip.text(words).css({'opacity':1,'z-index':100001});
	setTimeout(function(){
		tip.css({'opacity':0,'z-index':-10});
	},1600);
}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin" class="button_6" style="font-weight:bold;">添加分组</a>
</div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    
                    <div class="right_2">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}       
						
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
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
                                <div class="common-list-item">编辑</div>
                                <div class="common-list-item">删除</div>
                                <div class="common-list-item wd120">分类路径</div>
                                <div class="common-list-item wd80">生成文件</div>
                                <div class="common-list-item wd80">复制分类</div>
                                <div class="common-list-item wd60">导出</div>
                                <div class="common-list-item wd60">导入</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">分组名称</div>
					        </div>
                        </li>
                     </ul>
                     <input type="file" class="importing-file" style="display:none;"/>
                     <div class="tips"></div>
               		 <ul class="common-list public-list" id="vodlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}	
		                      {template:unit/sort_list}
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