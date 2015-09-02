{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);

/*状态控件*/
$status_source = array(
	'class' => 'transcoding down_list',
	'show' => 'status_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);

if(!isset($_INPUT['status']))
{
    $_INPUT['status'] = 0;
}
{/code}
{css:vod_style}
{template:list/common_list}
<script type="text/javascript">
function hg_audit_tpl(id)
{
	var url = "run.php?mid="+gMid+"&a=audit&id="+id;
	hg_ajax_post(url);
}

/*审核回调*/
function hg_audit_tpl_callback(obj)
{
	var obj = eval('('+obj+')');
	$('#status_'+obj.id).text(obj.status_text);
}

$(function(){
    $('.add-black-item').click(function(){
        var target = $(this);
        var url = 'run.php?mid='+ gMid +'&a=black_ip';
        var identifier = target.attr('_identifier');
        var member_id = target.attr('_member_id');
        var ip = target.attr('_ip');
        var black_ip = target.attr('_black_ip');
        var type = target.attr('_type');
        var deadline = target.attr('_deadline');

        $.getJSON(url, {identifier:identifier,member_id:member_id,ip:ip,black_ip:black_ip,deadline:deadline,type:type}, function( json ){
            var obj = json[0];
            console.log(obj);
            if(obj.deadline == -1 && obj.type == 2)
            {
                $('#add-black_'+obj.id).text("解封黑名单");
                $('.black-status_'+obj.id).text("永久黑名单");
                $('.black-status_'+obj.id).css('color','red');
                target.attr('_black_ip',0);
                target.attr('_deadline',0);
                target.attr('_type',0);
            }
            else if(obj.deadline == -1)
            {
                $('#add-black_'+obj.id).text("加入永久黑名单");
                $('.black-status_'+obj.id).text("App黑名单");
                $('.black-status_'+obj.id).css('color','#ff8c00');
                target.attr('_black_ip',1);
                target.attr('_deadline',-1);
                target.attr('_type',2);
            }
            else if(obj.deadline == 0)
            {
                $('#add-black_'+obj.id).text("加入App黑名单");
                $('.black-status_'+obj.id).text("解封状态");
                $('.black-status_'+obj.id).css('color','green');
                target.attr('_black_ip',1);
                target.attr('_deadline',-1);
                target.attr('_type',1);
            }
        });
    })
})
</script>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
<!--	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">-->
<!--		<span class="left"></span>-->
<!--		<span class="middle"></span>-->
<!--		<span class="right"></span>-->
<!--	</a>-->
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
							{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
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
                <form method="post" action="" name="vod_sort_listform">
                    <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu">
			 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu"></a>
			                    </div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd120">状态</div>
                                <div class="common-list-item wd120">加入黑名单</div>
                                <div class="common-list-item open-close wd120">App-ID/App-Name</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item wd90">用户ID</div>
                                <div class="common-list-item wd100" style="width: 100px;">用户名</div>
                                <div class="common-list-item wd100">IP</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/ip_blacklist_list_item}
		                    {/foreach}
		                {else}
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				{/if}
	                </ul>
	                
		            <ul class="common-list public-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
					   </div>
		               {$pagelink}
		            </li>
		          	</ul>	
    			</form>
    			<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
					<div id="edit_show"></div>
				</div>
            </div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
</div>
{template:foot}