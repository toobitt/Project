{template:head}
{code}
$list = $cdn_space_list[0];
{/code}
{css:vod_style}
{css:ad_style}
{css:common/common_list}
<style>
.domain-item{width:260px;margin-right:10px;}
.domain-item span{max-width:260px;display:inline-block;white-space: nowrap;overflow: hidden;text-overflow:ellipsis;vertical-align: middle;}
</style>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
   <a class="blue mr10" href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增空间</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <form method="post" action="" name="listform">
                	<ul class="common-list public-list-head">
						<li class="common-list-head clear">
							<div class="common-list-left">
                            	<div class="common-list-item" style="width:35px"></div>
                            </div> 
                             <div class="common-list-right">
                             	<div class="circle-tjr common-list-item open-close domain-item">空间域名</div>
                            	<div class="circle-tjr common-list-item open-close">CNAME</div>
                            	<div class="circle-tjr common-list-item open-close">空间类型</div>
                            	<div class="circle-tjr common-list-item open-close">存储配额</div>    
                            	<div class="circle-tjr common-list-item open-close">操作</div>                        	
                            </div>  
                            <div class="common-list-biaoti">
								<div class="common-list-item">
									空间名
								</div>
							</div>                     
                        </li>
                    </ul>
	                <ul class="common-list" id="contri_sortlist">
		       			{if $list && is_array($list)}
		       			    {foreach $list as $k => $v} 
		                      {template:unit/cdnspacelist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
		  				{/if}
	                </ul>
	                
		          <ul class="common-list">
					<li class="common-list-bottom clear">
						<div class="common-list-left">
							<!-- 
							<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
							<a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">推送</a>
							 -->
						</div>
						{$pagelink}
					</li>
		         </ul>	
				</form>
           </div>
        </div>
</div>


   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</div>
{template:unit/record_edit}
{template:foot}
