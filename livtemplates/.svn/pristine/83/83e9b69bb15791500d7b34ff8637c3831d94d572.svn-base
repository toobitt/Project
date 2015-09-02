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
{js:contribute_sort}
{css:common/common_list}
{js:common/common_list}
<script type="text/javascript">
$(function(){
	tablesort('contri_sortlist','circle','order_id');
	$("#contri_sortlist").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
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
                <form method="post" action="" name="listform">
                    <!-- 标题 -->
                    <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('contri_sortlist');"  title="排序模式切换/ALT+R"><em></em></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="circle-bj common-list-item open-close">编辑</div>
                            </div>
                            <div class="circle-title">汽车分类</div>
                        </li>
                    </ul>
	                <ul class="common-list" id="contri_sortlist">
					    {if $list}
		       			    {foreach $list as $k => $v} 
		                      {template:unit/catTypelist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						
		  				{/if}
	                </ul>
		           <ul class="common-list">
				      <li class="common-list-bottom clear">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete','删除',1,'id','','ajax');"    name="batdelete">删除</a>
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','审核',1,'id','&audit=1','ajax');"    name="bataudit" >审核</a>
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','打回',1,'id','&audit=0','ajax');"    name="bataudit" >打回</a>
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
function hg_call_sort_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}

function hg_back_audit(obj)
{
   var obj = eval("("+obj+")");
   var status_text = "";
   if(obj.state == 1)
   {
	   status_text = '已审核';
   }
   else if(obj.state == 2)
   {
	   status_text = '已打回 ';    
   }
   for(var i = 0;i<obj.id.length;i++)
   {
	   $('#statusLabelOf'+obj.id[i]).text(status_text);
   }
	if($('#edit_show'))
	{
		hg_close_opration_info();
	}	
}
</script>
{template:foot}