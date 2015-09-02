{template:head}
{css:2013/button}
{css:2013/m2o}
{css:2013/iframe}
{css:deploy}
{js:jqueryfn/jquery.tmpl.min}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:hg_switchable}
{js:2013/ajaxload_new}
{js:publishsys/deploy}
{code}
$cliendId = $_INPUT['client_id'];
!$cliendId && ($cliendId = 2);

$site_id = $_INPUT['site_id'];
!$site_id && ($site_id = 1);

$deploy = $deploy[0];
$contentType = $deploy['content_type'];
$temp = array(
    0 => array(
        'id' => 0,
        'content_type' => '首页'
    ),
    '-1' => array(
        'id' => -1,
        'content_type' => '列表'
    )
);
$temp += $contentType;
$contentType = $temp;

$site = $deploy['site'][$cliendId];
$pageTitle = $deploy['page_title'];
$page = $deploy['page'];
//print_r($deploy);



if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
if(!class_exists('publishsys'))
{
include_once(ROOT_DIR . 'lib/class/publishsys.class.php');
$publishsys = new publishsys();
}
{/code}
<script>
var siteId = parseInt({$site_id}) || 1;
var clientType = parseInt({$cliendId}) || 2;
var titles = {code}echo $pageTitle ? json_encode($pageTitle) : '{}'{/code};
var pages = {code}echo $page ? json_encode($page) : '{}'{/code};
</script>

<script type="text/x-jquery-tmpl" id="nav-item-tpl">
<li class="{{if is_last > 0}}no-child{{/if}}" data-id="{{= id}}" data-name="{{= name}}">
    {{if !(is_last > 0)}}
    <span class="hook"></span>
    {{/if}}
    <span class="title">{{= name}}{{if is_auth != 2 }}<a></a>{{/if}}</span>
</li>
</script>

<script type="text/x-jquery-tmpl" id="pop-title-item-tpl">
<li data-id="{{= id}}"><span class="del"></span>{{= allTitle}}</li>
</script>

<script type="text/x-jquery-tmpl" id="pop-tab-control-tpl">
<li data-id="{{= id}}" class="control-item">{{= name}}</li>
</script>

<script type="text/x-jquery-tmpl" id="pop-tab-content-box-tpl">
<div class="tab-item" data-id="{{= id}}">
    <div class="switch_list">
        <ul><li>加载内容中...</li></ul>
    </div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="pop-tab-content-tpl">
<li data-id="{{= sign}}" data-title="{{= title}}">
    <div class="prev-item">
        <img src="{{= img}}">
        <div class="handle">
            <a href="{{= yulan}}" target="_blank" go-blank>预览</a>
            <span>|</span>
            <a href="{{= yushe}}" target="_blank" go-blank>预设</a>
        </div>
    </div>
    <p class="intro">{{= title}}</p>
</li>
</script>

<script type="text/x-jquery-tmpl" id="pop-tab-content-tpl">
<li data-id="{{= id}}" data-title="{{= title}}"></li>
</script>

<script type="text/x-jquery-tmpl" id="list-each-tpl">
<div class="temp-item" data-key={{= key}}>
    <div class="temp-title">
        <ul class="clear">
            {{each titles}}
            <li data-id="{{= $value['id'] + '_' + $value['title']}}" data-title="{{= $value['title']}}" data-allTitle="{{= $value['full_title']}}"><span>{{= $value['full_title']}}</span></li>
            {{/each}}
        </ul>
        <a></a>
    </div>
    <div class="temp-content">
        <ul class="clear">
            {{each pages}}
            <li data-id="{{= ($value.dep['content_type'] == -1 || $value.dep['content_type'] == 0) ? $value.dep['content_type'] : ($value.content_type ? $value.content_type['id'] : 0)}}" data-tid="{{= $value.dep['template_sign']}}" data-tname="{{= $value.dep['template_name']}}">
                <label>{{= ($value.content_type ? $value.content_type['content_type'] : '')}}: </label>
                <span class="title">{{= $value.dep['template_name']}}</span>
				<span class="controll">
					<a href="./magic/main.php?gmid=412&ext={{= encodeURIComponent('site_id=' + $value.dep.site_id + '&page_id=' + $value.dep.page_id + '&page_data_id=' + $value.dep.page_data_id + '&content_type=' + $value.dep.content_type)}}" target="_blank" go-blank></a>
					<a href="./magic/data.php?gmid=412&ext={{= encodeURIComponent('site_id=' + $value.dep.site_id + '&page_id=' + $value.dep.page_id + '&page_data_id=' + $value.dep.page_data_id + '&content_type=' + $value.dep.content_type)}}" target="_blank" go-blank></a>
				</span>
            </li>
            {{/each}}
        </ul>
    </div>
</div>
</script>



    <div class="search_a" id="info_list_search" style="display:none;">
        <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
            <div class="right_1">
            {code}
            $time_css = array(
                'class' => 'transcoding down_list',
                'show' => 'time_item',
                'width' => 120,
                'state' => 1,/*0--正常数据选择列表，1--日期选择*/
                'para'=> array('fid'=>$_INPUT['fid']),
            );
            $_INPUT['create_time'] = $_INPUT['create_time'] ? $_INPUT['create_time'] : 1;

            if(!$_INPUT['site_id'])
            {
                $_INPUT['site_id'] = 1;
            }
            //获取所有站点
            $hg_sites = array();
            //foreach ($publish->getallsites() as $index => $value) {
            //    $hg_sites[$index] = $value;
            //}
            //调用模块接口获取站点 模板对站点有单独的权限验证
            foreach ($publishsys->getallsites() as $index => $value) {
                $hg_sites[$index] = $value;
            }
            $attr_site = array(
                'class'  => 'colonm down_list date_time',
                'show'   => 'app_show',
                'width'  => 104,
                'state'  => 0,
            );

            $attr_site_2 = array(
                'class'  => 'colonm down_list date_time',
                'show'   => 'app_show_2',
                'width'  => 104,
                'state'  => 0,
            );
            //获取所有终端
            $hg_clients = array();
            foreach ($publish->getallclients() as $index => $value) {
                $hg_clients[$index] = $value;
            }
            {/code}
           	{template:site/new_site_search, site_id, $_INPUT['site_id'], $hg_sites}
            {template:form/search_source,client_id,$cliendId,$hg_clients,$attr_site_2}
            <input type="hidden" name="a" value="show" />
            <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="_id" value="{$_INPUT['_id']}" />
            </div>
            <div class="right_2">
                <div class="button_search">
                    <input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                </div>
                {template:form/search_input,keyword,$_INPUT['keyword']}
            </div>
        </form>

        <div class="edit_show">
            <span class="edit_m" id="arrow_show"></span>
            <div id="edit_show"></div>
        </div>
    </div>

<!-- 列表start -->
<div class="wrap">
    <ul style="display:none;background:#99d100;font-size:14px;line-height:1.8;color:#fff;padding:10px;">
        <li>按住ctrl从左边选择页面进行部署，将会弹出部署窗，可以一次选择多个页面设置</li>
        <li>弹出的部署窗中：从模板列表中选中模板按住拖动至类型文本框即设置部署</li>
        <li><span style="visibility:hidden;">弹出的部署窗中：</span>双击类型文本框前的类型名称即清除部署</li>
    </ul>
    <div class="template-main clearfix pop">
    	<!-- 模板开始 -->
		<div class="template-area">
			<div class="pop-content">
				<div class="tab-area">
					<div class="tab-control">
						<ul class="clear"></ul>
						<span class="toggle-btn"><em></em></span>
					</div>
					<div class="tab"></div>
				</div>
			</div>
			<div class="mask"></div>
		</div>
    	<!-- 模板结束 -->
    	<div class="search-area">
    		<div class="top">
	    		<input name="key" placeholder="输入模板关键字" />
				<span class="search-cancel">退出</span>
	    		<span class="search-btn"></span>
    		</div>
    		<div class="search-result"></div>
    	</div>
    </div>
	<div class="m2o-flex">
		<aside class="temp-nav">
			<ul class="temp-list"></ul>
		</aside>
		<section class="m2o-flex-one temp-box">
            {if !$deploy['no_auth_site']}
			<div class="temp-item temp-global" type="global">
				<div class="temp-title">
					<ul class="clear">
						<li data-id="site{$site_id}" data-title="全局站点模板" data-allTitle="全局站点模板"><span>全局站点模板</span></li>
					</ul>
					<a></a>
				</div>
				<div class="temp-content">
					<ul class="clear">
                        {foreach $contentType as $key => $val}
                        {code}
                            $current = $site[$key];
                            $ext = 'site_id=' . $site_id . '&page_id=0&page_data_id=0&content_type=' . $key;
                            $ext = urlencode($ext);
                        {/code}
						<li data-id="{$key}" data-tid="{$current['template_sign']}" data-tname="{$current['template_name']}">
							<label>{$val['content_type']}: </label>
							<span class="title">{$current['template_name']}</span>
							<span class="controll">
								<a href="./magic/main.php?gmid=412&ext={$ext}" target="_blank" go-blank></a>
								<a href="./magic/data.php?gmid=412&ext={$ext}" target="_blank" go-blank></a>
							</span>
						</li>
						{/foreach}
					</ul>
				</div>
			</div>
            {/if}

			<div id="list-box"></div>
		</section>
	</div>
</div>
<!-- 列表end -->

<!-- 弹窗start -->

<div class="pop" id="pop">
	<div class="head">
		<div class="btns">
			<a class="pop-save-button">保存</a>
			<a class="pop-close-button2"></a>
		</div>
		<p class="title">设置个性模板</p>
	</div>
	<div class="pop-content">
		<ul class="title-list clear"></ul>
		<div class="info-list">
			<ul class="clear">
			    {foreach $contentType as $key => $val}
				<li data-id="{$key}">
					<span class="title">{$val['content_type']}</span>
					<input >
				</li>
				{/foreach}
			</ul>
		</div>
		<div class="tab-area">
			<div class="tab-control">
				<ul class="clear"></ul>
			</div>
			<div class="tab"></div>
		</div>
	</div>
</div>
<!-- 弹窗end -->


<!-- magic start -->
<div id="magic-box">
    <ul>
    {foreach $contentType as $key => $val}
        <li>
        	<span>{$val['content_type']}</span>
        	<span class="controll">
        		<a data-type="{$key}" data-index="main" target="_blank" go-blank></a>
        		<a data-type="{$key}" data-index="data" target="_blank" go-blank></a>
        	</span>
        </li>
    {/foreach}
    </ul>
</div>
<!-- magic end -->

{template:foot}