{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
{/code}
{css:vod_style}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{css:common/common_list}
{js:common/common_list}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <form method="post" action="" name="listform">
                    <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                            	<div class="common-list-item" style="width:35px"></div>
                            </div>                          
                            <div class="common-list-right">
                            	<div class="circle-tjr common-list-item open-close">所属应用</div>
                                <div class="circle-tjr common-list-item open-close" style="width:190px;">发布时间</div>
                                <div class="circle-tjr common-list-item open-close">点击量</div>
                            </div>
                            <div class="common-list-item open-close access-biaoti">标题</div>
                        </li>
                    </ul>
	                <ul class="common-list" id="contri_sortlist">
					    {if $list && is_array($list)}
		       			    {foreach $list as $k => $v} 
		                      {template:unit/detaillist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
		  				{/if}
	                </ul>
		           <ul class="common-list">
				      <li class="common-list-bottom clear">
					   <div class="common-list-left">
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
</body>
<script type="text/javascript">
function hg_call_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}

function hg_audit_back(obj)
{
	var obj = eval("("+obj+")");
    for(var i = 0;i<obj.id.length;i++)
	{
		$('#statusLabelOf'+obj.id[i]).text(obj.msg);
	}
	if($('#edit_show'))
	{
		hg_close_opration_info();
	}		
}
</script>
{template:foot}