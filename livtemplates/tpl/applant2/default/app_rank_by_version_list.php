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

if($list && isset($list['rank']))
{
	$list = $list['rank'];
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
</script>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
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
                <form method="post" action="" name="vod_sort_listform" class="m2o-list">
                    <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu">
			 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu"></a>
			                    </div>
			                    <div class="common-list-item wd60">应用图标</div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd80">打包次数</div>
                                <div class="common-list-item wd80">ios版本</div>
                                <div class="common-list-item wd80">安卓版本</div>
                                <div class="common-list-item wd60">ios状态</div>
                                <div class="common-list-item wd60">安卓状态</div>
                                <div class="common-list-item wd120">发布时间</div>
                                <div class="common-list-item open-close wd120">创建时间/创建人</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">应用名称</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/rank_by_version_item}
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

{css:2013/list}
{js:2013/list}
<script>
var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
var qrcode_url = {code}echo json_encode($_configs['qrcode_url']);{/code};
$.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(i == id){
                   info = {
                       id : n[0]['id'],
                       client : n,
                       codepic : qrcode_url + '?id=' + n[0]['uuid'] + '&type=' + ( n[0]['is_release'] == 1 ? 'release' : 'debug' )
                   }
                   return false;
               }
            });
            return info;
        }
    });
    $('.m2o-each').geach();
	$('.m2o-list').glist();
</script>
<style>
.m2o-each{width:auto;}
.m2o-option .codepic{width:100px!important;height:100px!important;}
.m2o-option .codepic img{max-height:100%;max-width:100%;}
.m2o-option .record-edit-btn{width: 100px;margin: 0 0 10px 0!important;}
</style>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="record-edit-area">
				<div class="codepic">
					<img src="{{= codepic}}" />
				</div>
				<div>
				{{each client}}
				<a class="record-edit-btn" href="{{= $value['download_url']}}">{{= $value['client_name']}}</a>
				{{/each}}
				</div>
			</div>
        </div>
    </div>
	<div class="m2o-option-close"></div>
</div>
</script>

{template:foot}