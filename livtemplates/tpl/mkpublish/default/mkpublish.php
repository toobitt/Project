{template:head}
{css:2013/form}
{css:2013/iframe}
{code}
if(!empty($formdata))
{
$formdata = $formdata;
}
$site_id = intval($formdata['site_id']);
$page_id = intval($formdata['page_id']);


$cliendId = $_INPUT['client_id'];
!$cliendId && ($cliendId = 2);

$site_id = $_INPUT['site_id'];
!$site_id && ($site_id = 1);
{/code}

{css:common/common_publish}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload_new}
{js:common/ajax_cache}
{js:publishsys/publish}
{code}
if (!isset($publish)) {
$publish = array(
'selected_items' => array(),
'selected_ids' => array(),
'selected_names' => array(),
'pub_time' => '',
'default_site' => array(),
'sites' => array(),
'items' => array()
);
}

//print_r($mkpublish);
{/code}
{code}
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
<script type="text/javascript">
    var siteId = parseInt('{$site_id}');
    $(function() {
        $('.publish-box').hg_publish();
    })
</script>
<style>
    .column-box{padding-left:20px;}
    .index-box{height: 50px;padding-top: 10px;margin-bottom: 10px;padding-left: 10px;border-bottom: 1px solid #dadada;}
    .item{cursor:pointer;width:130px;height:40px;float:left;font-size:14px;border-radius:2px;text-align:center;line-height:40px;background:#87d241;color:#fff;margin-left:10px;}
    .column-box-item{float:left;margin-right:10px;width:260px;height:40px;line-height:40px;background:#d9eafe;border-radius:4px;text-indent:20px;color:#2061b3;font-size:16px;}
    .select{width:610px;}
    .publish-site, .publish-list{top:0;}
    .publish-box{background:none;min-height:380px;cursor:default;}
    .publish-result{width:260px;}
    .publish-list{left:280px;height:auto;width:610px!important;}
    .publish-result ul{border-top:0;}
    .publish-result li{border-bottom:1px dotted #dadada;cursor:pointer;}
    .publish-list{top:20px;}
    .publish-each{border:1px solid #d3d3d3;float:none;width:163px;min-height:348px;cursor:pointer;}
    .mkpublish-btn{margin-top:10px;}
    .mkpublish-btn input,.condition-pop .btn{cursor:pointer;width:110px;height:34px;margin:0 3px;text-align:center;color:#fff;line-height:30px;background:#5d9aea;border:0;border-radius:2px;font-size:14px;}
    #publish-box{width:100%!important;}
    .publish-result ul{margin-top:10px;min-height:300px!important;height:auto!important;max-height:300px;}
    .publish-box{padding:0;width:100%!important;}
    .publish-result{padding:0 10px;position:relative;min-height:200px;}
    .publish-result li{width:auto;}
    .publish-list{left:20px;position:relative;}
    .mk-publish-status{width:220px;float:left;margin-left:20px;padding:10px 20px;font-size:12px;}
    .mk-publish-status>div{margin-bottom:10px;}
    .publish-done{color:#a3a3a3;}
    .publish-waiting{color:#33a2fb;}
    .publish-going{color:#62ae03;}
    .publish-error{color:#fd4140;}

    .publish-time{max-width:80px;}
    .publish-title{max-width:60px;}
    .publish-time,.publish-title{display:inline-block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .condition-pop{z-index:100;background:#fff;position:absolute;opacity:0;bottom:-400px;left:280px;border:1px solid #ccc;padding:15px;-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s;}
    .condition-pop.show{bottom:0;opacity:1;}
    .condition-pop .condition-item{margin-bottom:6px;}
    .condition-pop .condition-item>span{display:inline-block;width:90px;font-size:12px;}
    .condition-pop input{height:20px;}
    .condition-pop .btn{margin:10px 0 0 20px;width:60px!important;font-size:12px;height:30px;line-height:30px;}
</style>
            <div class="search_a" id="info_list_search" style="display:none;">
                <form name="searchform" id="searchform" action="" method="get">
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
                        $_INPUT['site_id'] = '1';
                        }
                        //获取所有站点
                        $hg_sites = array();
                        //foreach ($publish->getallsites() as $index => $value) {
                        //$hg_sites[$index] = $value;
                        //}
                        //调用模块接口获取站点 模板对站点有单独的权限验证
                        //foreach ($publishsys->getallsites() as $index => $value) {
                        foreach ($publishsys->getallsites_mkpublish() as $index => $value) {
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
                    </div>
                </form>
            </div>
<div class="wrap clear">	
    <!-- new -->
    <div class="index-box">
        <div class="item" m-type="1">生成首页</div>
        <div class="item" m-type="2">生成辅助页</div>
        <div class="item" m-type="5">重新发布所有页面</div>
    </div>
    <div class="column-box">
        <div class="column-box-title">
            <div class="column-box-item">生成栏目</div>
            <div class="column-box-item select">选择栏目</div>
            <div class="column-box-item">生成状态</div>
        </div>
        <div class="column-box-con">
            <!-- 栏目开始 -->
            <div class="publish-box common-hg-publish" id="publish-box">
                <div class="publish-site-current" _siteid="{$_INPUT['site_id']}" style="display:none;">
                </div>
                <form action="" method="post"  class="ad_form h_l" name="deploy_form" id="mkpublish_form">
                    <div class="publish-result" >
                        <ul>
                        </ul>
                        <div style="font-size:12px;">
                            <input type="checkbox" value="1" name="is_contain_child" />包含子级
                            <input type="text" value="20" name="max_page" size=1 />生成页数
                        </div>
                        <div class="mkpublish-btn">
                            <input type="button" m-type="3"  value="生成栏目页" class="mk_btn" />
                            <input type="button" m-type="6" value="生成内容页"  class="mk_btn"  />
                        </div>
                    </div>
                    <!-- 生成内容条件浮窗start -->
                    {code}
                    $content_types = $publish->get_content_type();
                    {/code}
                    <div class="condition-pop">
                        <div class="condition-item"><span>生成条数:</span><input type="text" name="page_number" /></div>
                        <div class="condition-item"><span>内容类型:</span>
                            {if is_array($content_types)}
                            {foreach $content_types as $k=>$v}
                            <input type="checkbox" name="content_typearr[]" value="{$v['id']}">
                            {$v['content_type']}
                            {/foreach}
                            {/if} 
                        </div>
                        <div class="condition-item"><span></span><font color='blue' size='1'>*不选表示全部</font></div>
                        <!--
                        <div class="condition-item"><span>最小权重:</span><input type="text" name="min_weight" /><span>最大权重:</span><input type="text" name="max_weight"  /></div>
                        -->
                        <div class="condition-item"><span>最小发布时间:</span><input class="date-picker" type="text" name="min_publish_time"  /><span>最大发布时间:</span><input class="date-picker" type="text" name="max_publish_time" /></div>
                        <div class="condition-item">
                            <span class="btn" data-type="true">确定</span>
                            <span class="btn" data-type="false">取消</span>
                        </div>
                    </div>
                    <!-- 生成内容条件浮窗end -->
                    <input type="hidden" name="content_type" value="0"/>
                    <input type="hidden" name="a" value="create" />
                    <input type="hidden" name="site_id" value="{$site_id}" />
                    <input type="hidden" name="client_type" value="{$cliendId}" />
                    <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
                    <input type="hidden" name="m_type" />
                    <input type="hidden" name="fid" value="" />
                </form> 
                <div class="publish-list">
                    <div class="publish-inner-list m2o-flex">
                        <div class="publish-each">
                            <ul>
                                {foreach $mkpublish as $k => $v}
                                <li _id="{$v['fid']}" title="{$v['name']}" _name="{$v['name']}" class="one-column {if $v['is_last']}no-child{/if}">

                                    <input type="checkbox" class="publish-checkbox" {if $v['can_select'] ||  $v['is_auth']==2 }style="visibility: hidden;"{/if} />
                                           <span class="publish-name">{$v['name']}</span>
                                    <span class="publish-child">&gt;</span>
                                </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                </div>

                <!--  <div class="mk-publish-status" style="display:none;">
                        <div class="publish-done">新闻首页-内容 已生成</div>
                        <div class="publish-waiting">图集 等待生成</div>
                        <div class="publish-going">新闻 正在生成</div>
                        <div class="publish-error">栏目 生成失败</div>
                </div>-->
                <div class="mk-publish-status">
                </div>

            </div>
            <!-- 栏目结束 -->


        </div>
    </div>

    <!-- new -->
</div>
<script>
    $(function() {

        var MC = $('.condition-pop');

        $('.index-box').on({
            'click': function() {
                var $this = $(this);
            	$.globalAjax( $this, function(){
                	return $.get('run.php?siteid=' + siteId + '&mid=' + gMid + '&a=create&m_type=' + $this.attr('m-type'), function() {
                		$this.myTip({
                            string : '发布成功！',
                            delay : 500,
                            dtop : -10,
                            dleft : 120,
                            animate_css : { top : '15px' }
                        });
                    });
                } );
            }
        }, '.item');
        $('.mk_btn').on('click', function(event) {
            var my_type = $(this).attr('m-type');
            if (my_type == 6) {
                MC.addClass('show').data('index', my_type);
                MC.find('input').attr('disabled', false);
            } else {
                $('#mkpublish_form').trigger('submit', [my_type]);
            }
        });

        $('#mkpublish_form').submit(function(event, type) {
            var $this = $(this);
            var cloumns = $('.publish-result').find('.publish-checkbox').map(function() {
                if ($(this).attr('checked') == 'checked') {
                    return $(this).closest('li').attr('_id');
                }
            }).get().join(',');
            $('input[name="fid"]').val(cloumns);
            $('input[name="m_type"]').val(type);
            if (type != 6) {
                MC.find('input').attr('disabled', true);
            }
            var load = $.globalLoad( window );
            $this.ajaxSubmit({
                success: function() {
                    load();
                	$this.myTip({
                        string : '发布成功！',
                        delay : 500,
                        dtop : 160,
                        dleft : 60
                    });
                }
            });
            return false;
        });
        setInterval(function() {
            var url = './run.php?mid=' + gMid + '&a=get_mking_plan';
            $.get(url, function(data) {
                var arr = [];
                $.each(data, function(key, value) {
                    var info = {};
                    info.title = value['title'];
                    info.publish_user = value['publish_user'];
                    info.publish_time = value['publish_time'];
                    arr.push(info);
                });
                var box = $('.mk-publish-status');
                box.html($('#publish-status').tmpl(arr));
            }, 'json');
        }, '6000');

        MC.on('click', '.btn', function(event) {
            var type = $(event.currentTarget).data('type'),
                    index = MC.data('index');
            if (type) {

                jConfirm('本次生成涉及到大量内容页面，会影响发布队列，请确认是否继续！', '生成提醒', function(result) {
                    if (result) {
                        $('#mkpublish_form').trigger('submit', [index]);
                    }
                });

            } else {
                MC.removeClass('show');
            }
        });


    })
</script>
<script type="text/x-jquery-tmpl" id="publish-status">
    <div class="publish-going">
    <span class="publish-title">${title}</span>
    <span>${publish_user}</span>
    <span class="publish-time">${publish_time}</span>
    </div>
</script>
