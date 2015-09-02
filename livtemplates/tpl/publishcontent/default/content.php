{template:head}
{code}
$status_show = $_configs['status_show'];
$list = $content[0]['content_data'];
foreach($list as $kk => $vv){
	$list[$kk]['_id'] = $list[$kk]['id'];
	$list[$kk]['id'] = $list[$kk]['rid'];
}
$attrs_for_edit=array(bundle_id,module_id,content_fromid,outlink);
{/code}
{css:2013/iframe}
{css:2013/list}
{css:common/common}
{css:common/common_publish}
{css:content}


{js:underscore}
{js:Backbone}
{js:common/record}
{js:common/record_view}
{js:common/share_box}
{js:common/weight_box}
{js:common/publish_box}


{js:2013/list}
{js:2013/ajaxload_new}
{js:page/page}
{js:publishcontent/nav}
{js:publishcontent/share_box}
{js:2013/list_sort}
{code}
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
    $content_types = $publish->get_content_type();
    //print_r($content_types);
}
//获取所有站点
$hg_sites = $publish->getallsites();
foreach($hg_sites as $k=>$v)
{
    if(!$_INPUT['site_id'])
    {
        $_INPUT['site_id'] = $k;
    }
    $hg_sites_[$k] = $v;
}
$site_id = $_INPUT['site_id'];

if(!isset($_INPUT['client_type']))
{
    $_INPUT['client_type'] = $_configs['default_client_id'];
}
if(!isset($_INPUT['con_app']))
{
    $_INPUT['con_app'] = 'all';
}
if(!isset($_INPUT['con_appchild']))
{
    $_INPUT['con_appchild'] = 'all';
}
if(!isset($_INPUT['order_field']))
{
    $_INPUT['order_field'] = '-1';
}
if(!isset($_INPUT['create_date_search']))
{
    $_INPUT['create_date_search'] = '-1';
}
if(!isset($_INPUT['publish_date_search']))
{
    $_INPUT['publish_date_search'] = '-1';
}
//print_r($_INPUT);
//print_r($list);
{/code}

<script>
var siteId = parseInt({$site_id}) || 1;
</script>
<div id="search-box">{template:unit/content_search}</div>
<div class="wrap m2o-flex">
	<aside class="temp-nav">
		<ul class="temp-list">
			<li class="column-list top-item" _ajax='true' data-fid="0">
				<span class="hook"></span>
				<span class="title">栏目<a></a></span>
				<ul></ul>
			</li>
		</ul>
	</aside>
	<section class="m2o-flex-one list-wrap">
	{if !$list}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">未选择栏目或者没有内容</p>
	<script>hg_error_html('p',1);</script>
	{else}
	<form class="common-list-form" name="listform">
		<div class="m2o-list">
			<div class="m2o-title m2o-flex m2o-flex-center">
				<div class="m2o-item m2o-paixu">
					<a title="排序模式切换/ALT+R" onclick="hg_switch_order();" style="cursor:pointer;" class="common-list-paixu"></a>					
				</div>
				<div class="m2o-item m2o-flex-one m2o-bt">标题</div>
				<div class="m2o-item type wd60">类型</div>
				<div class="m2o-item pub wd60">发布至</div>
				<div class="m2o-item m2o-weight wd60">权重</div>
				<div class="m2o-item m2o-time">添加人/时间</div>
				<div class="m2o-item m2o-time">审核人/发布时间</div>
			</div>
			<div class="m2o-each-list" data-table_name="content-table">
			<!-- 列表 -->
			</div>
			<div class="m2o-bottom  m2o-flex m2o-flex-center">
				<div class="m2o-paixu">
                   <input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" class="checkAll"/>
                </div>
		       	<div class="batch-handle" data-method="delete">删除</div>
		       	<div class="m2o-flex-one">
		       		<div class="pagelink">
		       		</div>
		       	</div>
			</div>
		</div>
	</form>
{/if}
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>
</section>
</div>

{template:unit/weight_box}

<!-- 生成框 start -->
<div id="generate-box"></div>
<script type="text/x-jquery-tmpl" id="generate-tpl">
	<form class="">
		<i>x</i>
		<section class="column-box clear">
			<div class="title">
				<span>生成栏目页</span>
				<a class="btn genBtn" _type="3">生成</a>
			</div>
			<div class="half">
				<span class="info-name">包含子级:</span>
				<div class="choose">
					<a class="arrow">否</a>
					<ul>
						<li class="choose-item" _data="yes">是</li>
						<li class="choose-item" _data="no">否</li>
					</ul>
					<input type="hidden" name="is_contain_child" value="0">
				</div>
			</div>
			<div class="half">
				<span class="info-name">生成页数:</span>
				<input value="20" name="max_page" size="1" />
			</div>
		</section>
		<section class="content-box">
			<div class="title">
				<span>生成内容页</span>
				<a class="btn genBtn" _type="6">生成</a>
			</div>
			<div class="condition-item">
				<span class="info-name">生成条数:</span>
				<input type="text" name="page_number">
			</div>
			<div class="condition-item m2o-flex">
				<span class="info-name" style="display:block;">内容类型:</span>
				<div class="type-list m2o-flex-one">
				{foreach $content_types as $k=>$v}
				<input type="checkbox" name="content_typearr[]" value="{$v['id']}">{$v['content_type']}
				{/foreach}
				<p>*不选表示全部</p>
				</div>
			</div>
			<div class="condition-item">
				<span class="info-name">最小发布时间:</span>
				<input class="date-picker" name="min_publish_time" >
			</div>
			<div class="condition-item">
				<span class="info-name">最大发布时间:</span>
				<input class="date-picker" name="max_publish_time" >
			</div>
		</section>
		<input type="hidden" name="a" value="relate_module_show"/>
		<input type="hidden" name="app_uniq" value="mkpublish"/>
		<input type="hidden" name="mod_uniq" value="mkpublish"/>
		<input type="hidden" name="mod_a" value="create"/>
		<input type="hidden" name="m_type" value="3"/>
		<input type="hidden" name="site_id" value="" />
		<input type="hidden" name="fid" value="" />
		<input type="hidden" name="client_type" value="" />
	</form>
</script>
	
<!-- 生成框 end -->

<script>
	var global_status_show = $.global_status_show = {code}echo $status_show ? json_encode( $status_show ) : '{}';{/code};
</script>

<script type="text/x-jquery-tmpl" id="nav-item-tpl">
{{ if is_last }}
<li class="{{if is_last > 0}}no-child{{else}}{{/if}}" data-id="{{= id}}" data-name="{{= name}}" data-fid="{{= id}}">
	{{if !(is_last > 0)}}
    <span class="hook"></span>
	{{/if}}
    <span class="title"><span class="column-name" title="{{= name}}">{{= name}}</span>{{if +$item.is_open_mk}}<a title="快速生成" class="fast-publish"></a>{{/if}}<a class="fast-review" title="浏览栏目" href="{{= url}}" target="_blank"></a></span>
</li>
{{/if}}
</script>
<script type="text/x-jquery-tmpl" id="m2o-each-tpl">
<div id="r_{{= rid}}" class="m2o-each m2o-flex m2o-flex-center" data-id="{{= rid}}" orderid="{{= order_id}}">
	<div class="m2o-item m2o-paixu">
		<a name="alist[]" title="{{= rid}}"><input type="checkbox" name="infolist[]" value="{{= rid}}" class="m2o-check"></a>
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt">
		<div class="m2o-title-transition m2o-title-overflow">
			{{if indexpic}}<img src="{{= indexpic}}" class="biaoti-img" id="img_{{= rid}}">{{/if}}			
			<a target="formwin" class="m2o-title-overflow max-width200" title="{{= title}}" href="modify.php?app_uniqueid={{= bundle_id}}&mod_uniqueid={{= module_id}}&id={{= content_fromid}}&outlink={{= outlink}}">
				<span class="m2o-common-title">{{= title}}</span>
			</a>
			<a class="shareslt" href="{{= content_url}}" target="_blank">浏览</a> 
		</div>
	</div>
	<div class="m2o-item type wd60">{{= app_name}}</div>
	<div class="m2o-item pub wd60">{{= column_name}}</div>
	<div class="m2o-item m2o-weight wd60" _weight="{{= weight}}">
		<div class="m2o-weight-box">
			<div class="weight-inner common-quanzhong" _weight="{{= weight}}">
				<span class="weight-label">{{= weight}}</span>
			</div>
		</div>
	</div>
	<div class="m2o-item m2o-time">
		<span class="name">{{= create_user}}</span>
		<span class="time">{{= create_time}}</span>
	</div>
	<div class="m2o-item m2o-time">
		<span class="name">{{= publish_user}}</span>
		<span class="time edit-btn">{{= publish_time}}</span>
		<a class="sure">更新</a>
		<input class="edit-time" _time="true" value="{{= publish_time}}" />
	</div>
	<div class="m2o-ibtn"></div>
</div>
</script>


<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a class="option-delete">删除</a>
				{if $_configs['is_need_audit']}
				<a class="option-audit" _status="{{= status}}">{{= status_show}}</a>
				{/if}
				<a class="m2o-share" data-method="share" href="./run.php?mid={$_INPUT['mid']}&a=share_form&id=${id}">分享</a>
				{if $_configs['App_cdn']}
				<a class="m2o-cdn" data-method="cdn" href="./run.php?mid={$_INPUT['mid']}&a=cdn_publish&id={{= id}}&ajax=1">cdn推送</a>
				{/if}
			</div>
			<div class="m2o-option-line"></div>
			<div class="record-edit-info">
			{{if click_num != 0}}<span class="record-edit-item">访问:{{= click_num}}</span>{{/if}}
			{{if comm_num}}<span class="record-edit-item">评论:{{= comm_num}}</span>{{/if}}
			{{if share_num != 0}}<span class="record-edit-item">分享:{{= share_num}}</span>{{/if}}
			</div>
        </div>
    </div>
	<div class="m2o-option-confirm">
			<p>确定要删除该内容吗？</p>
			<div class="m2o-option-line"></div>
			<div class="m2o-option-confim-btns">
				<a class="confim-sure">确定</a>
				<a class="confim-cancel cancel">取消</a>
			</div>
	</div>
	<div class="m2o-option-close"></div>
</div>
</script>

<!-- 分享dom -->
<div id="add_share" ></div> 

<!-- 关于记录的操作和信息 -->
{template:foot}