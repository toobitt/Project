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

/*属性类型*/
$attr_type_source = array(
	'class'  => 'attr_type down_list',
	'show'   => 'attr_type_show',
	'width'  => 104,
	'state'  => 0,
	'is_sub' => 0,
);

if(!$_INPUT['attr_type_id'])
{
	$_INPUT['attr_type_id'] = 0;
}

$attr_type_arr = array();
foreach($_configs['attribute_type'] AS $_k => $_v)
{
	 if(intval($_k) == 0)
	 {
	 	$attr_type_arr[$_k] = $_v;
	 }
	 else
	 {
	 	$attr_type_arr[$_k] = $_v['name'];
	 }
}

/*****选择所属UI******/
$attr_ui_source = array(
	'class'  => 'attr_ui down_list',
	'show'   => 'attr_ui_show',
	'width'  => 104,
	'state'  => 0,
	'is_sub' => 0,
);

if(!$_INPUT['ui_id'])
{
	$_INPUT['ui_id'] = 0;
}

$ui_data_arr[0] = '全部UI';
if($ui_data)
{
	foreach($ui_data AS $_k => $_v)
	{
		$ui_data_arr[$_v['id']] = $_v['name'];
	}
}

/*****选择所属分组******/
$attr_group_source = array(
	'class'  => 'attr_group down_list',
	'show'   => 'attr_group_show',
	'width'  => 104,
	'state'  => 0,
	'is_sub' => 0,
);

if(!$_INPUT['group_id'])
{
	$_INPUT['group_id'] = 0;
}

$ui_group_data_arr[0] = '全部分组';
if($ui_group_data)
{
	foreach($ui_group_data AS $_k => $_v)
	{
		$ui_group_data_arr[$_v['id']] = $_v['name'];
	}
}

/****选择角色****/
$attr_role_source = array(
	'class'  => 'attr_role down_list',
	'show'   => 'attr_role_show',
	'width'  => 104,
	'state'  => 0,
	'is_sub' => 0,
);

if(!$_INPUT['role_type_id'])
{
	$_INPUT['role_type_id'] = 0;
}

$ui_role_arr = array();
foreach($_configs['role_type'] AS $_k => $_v)
{
	if($_k == -1)
	{
		continue;
	}
	$ui_role_arr[$_k] = $_v;
}

{/code}
{css:vod_style}
{template:list/common_list}
{css:plugin/switchery.min}
{js:jqueryfn/switchery.min}
<style>
a{color:#333;}
ul{margin:0;}
</style>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
    	<span class="left"></span>
    	<span class="middle"><em class="add">新增前台属性</em></span>
    	<span class="right"></span>
    </a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
							{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
							{template:form/search_source,ui_id,$_INPUT['ui_id'],$ui_data_arr,$attr_ui_source}
							{template:form/search_source,group_id,$_INPUT['group_id'],$ui_group_data_arr,$attr_group_source}
							{template:form/search_source,role_type_id,$_INPUT['role_type_id'],$ui_role_arr,$attr_role_source}
							{template:form/search_source,attr_type_id,$_INPUT['attr_type_id'],$attr_type_arr,$attr_type_source}
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
                                <div class="common-list-item wd120">所属前台分组</div>
                                <div class="common-list-item wd120">所属UI</div>
                                <div class="common-list-item wd120">属性样式类型</div>
                                <div class="common-list-item wd80">角色</div>
                                <div class="common-list-item wd80">有默认值</div>
                                <div class="common-list-item wd80">适用组件</div>
                                <div class="common-list-item open-close wd120">创建人/创建时间</div>
                            	<div class="common-list-item wd150">操作</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">名称</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		       			    <li class="common-list-data clear" _id="{$v['id']}" order_id="{$v['order_id']}" _uiid="{$v['ui_id']}" _relateids="{$v['relate_ids']}">
							    <div class="common-list-left">
							        <div class="common-list-item paixu">
							            <div class="common-list-cell">
							                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
							            </div>
							        </div>
							    </div>
							    <div class="common-list-right">
							        <div class="common-list-item wd120 overflow">{$v['group_name']}</div>
							        <div class="common-list-item wd120 overflow">{$v['ui_name']}</div>
							        <div class="common-list-item wd120 overflow">{$v['attr_type_name']}</div>
							        <div class="common-list-item wd80 overflow">{$v['role_type_name']}</div>
							        <div class="common-list-item wd80 overflow">
							        	{if $v['is_has_default_value']}
							        	<span class="glyphicon glyphicon-ok" title="有默认值"></span>
							        	{else}
							        	<span class="glyphicon glyphicon-remove" title="无默认值"></span>
							        	{/if}
							        </div>
							        <div class="common-list-item wd80 overflow">
							        	<div class="hidden">
							        		<input type="checkbox" class="js-switch" {if $v['is_comp']}checked{/if} />
							        	</div>
							        </div>
							        <div class="common-list-item wd120">
							            <div class="common-list-cell">
							            	 <span class="common-user">{$v['user_name']}</span>
							                 <span class="common-time">{$v['create_time']}</span>
							            </div>
							       </div>
							        <div class="common-list-item wd150">
							    		<a title="绑定属性" class="btn btn-primary btn-sm show-ui-list-pop">
											<span class="glyphicon glyphicon-cog"></span>
										</a>
										<a title="编辑" class="btn btn-info btn-sm" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
											<sapn class="glyphicon glyphicon-edit"></sapn>
										</a>
										<a title="删除" class="btn btn-danger btn-sm" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">
											<sapn class="glyphicon glyphicon-trash"></sapn>
										</a>
							        	<a title="查看已绑定属性" class="btn btn-warning btn-sm show-related-attr-pop {if !$v['relate_ids']}hide{/if}">
											<span class="glyphicon glyphicon-eye-open"></span>
										</a>
							    	</div>
							    </div>
							    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
							        <div class="common-list-item biaoti-transition">
										<a><span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['name']}</span></a>
							        </div>
							    </div>
							</li>
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

<div class="modal fade bs-example-modal-lg" id="related-attrs-pop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-pop" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">已绑定属性</h4>
            </div>
            <div class="modal-body">
                <div class="list-group">
  					
  				</div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default close-pop">ok</button>
			</div>
        </div>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="related-attr-item-tpl">
<div class="list-group-item disabled">
	<div class="row">
		<div class="col-md-5">属性名</div>
		<div class="col-md-3">uniqueid</div>
		<div class="col-md-2">属性类型</div>
		<div class="col-md-2">所属分组</div>
	</div>
</div>
{{each data}}
<div class="list-group-item" _id="{{= $value['id']}}" _attrtype="{{= $value['attr_type_mark']}}" data-json="{{= JSON.stringify($value)}}">
	<div class="row">
		<div class="col-md-5">{{= $value['name']}}</div>
		<div class="col-md-3">{{= $value['uniqueid']}}</div>
		<div class="col-md-2">{{= $value['attr_type_name']}}</div>
		<div class="col-md-2">{{= $value['group_name']}}</div>
	</div>
</div>
{{/each}}
</script>
{template:unit/ui_and_uiattrs}
<script>
var listData = {code}echo json_encode($list);{/code};
window.mySelectType = 'multiple';	//选择关联的属性时，是否可以多选 
$(function(){
	var currentLi = null;
		currentTriggerId = '',
		currentRelateIds = [];
	$('.show-ui-list-pop').click(function(){
		currentLi = $(this).closest('.common-list-data');
		currentTriggerId = currentLi.attr('_id');
		currentRelateIds = currentLi.attr('_relateids') ? currentLi.attr('_relateids').split(',') : [];
	});
	var uiList = new myUIList({
		el : '#ui-list-pop',
		callback : function( target, id ){
			uiAttrs.reset( id );
			uiAttrs.ajaxUiAttrs(target);
		}
	});
	var uiAttrs = new myUIAttrs({
		el : '#ui-attr-pop',
		afterAjaxData : function( dom ){
			if( currentRelateIds.length ){
				var allItems = dom.find('.list-group-item');
				$( allItems ).each(function(){
					var id = $(this).attr('_id');
					if( $.inArray(id, currentRelateIds ) > -1 ){
						$(this).addClass('list-group-item-info selected');
						$(this).find('.checkbox').prop('checked',true);
					}
				});
			}
		},
		callback : function( target, current ){
			var _this = uiAttrs;
			$.globalAjax(target, function(){
				return $.getJSON(_this.bindUrl, {
					ui_attr_id : currentTriggerId,
					relate_ids : current.ids
				}, function(json){
					currentLi.find('.show-related-attr-pop').removeClass('hide');
					currentLi.attr('_relateids', current.ids);
					$('.modal').removeClass('in').hide();
					var json = json[0];
					target.myTip({
						string : json.msg,
					});
				});
			});
		}
	});
	//启用组件 
	$('.js-switch').each(function(){
		var target = $(this);
		new Switchery( target[0], { size: 'small', color : '#41B7F1' });
		target.parent().removeClass('hidden')
	});
	$('body').on('change', '.js-switch', function(){
		console.log( $(this).prop('checked')-0 );
	});
});

(function(){
	$('.show-related-attr-pop').click(function(){
		var target = $(this),
			li = target.closest('.common-list-data');
		var url = './run.php?mid='+ gMid +'&a=get_bind_info',
			param = {
				ui_id : li.attr('_uiid'),
				id : li.attr('_relateids')
			};
		$.globalAjax(target, function(){
			return $.getJSON(url, param, function( json ){
				$('#related-attr-item-tpl').tmpl({
					data : json
				}).appendTo($('#related-attrs-pop .list-group').empty());
				$('#related-attrs-pop').addClass('in').show();
			});
		});
	});
})();
</script>
{template:foot}