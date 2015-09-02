{template:head}
{code}
$list = $time_shift_log_list;
/*$listClone = array();
for($i = 0; $i < 10; $i++){
    foreach($list as $kk => $vv){
        $listClone[] = $vv;
    }
}
$list = $listClone;*/
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

$tubiao = RESOURCE_URL . 'menu2013/app-2x.png';
{/code}
{css:2013/list}
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_cache}
{js:common/record}
{js:common/record_view}
{js:common/publish_box}
{js:2013/list}
<div id="hg_page_menu" style="display:none;">
	<a class="add-button mr10" href="run.php?mid={$_INPUT['mid']}&a=load_time_shift{$_ext_link}" target="formwin">新增时移</a>
</div>

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

    <style>
    .m2o-bt img{vertical-align:middle;}
    .se{width:300px;}
    .ut span{display:block;}
    </style>
    <div class="m2o-list">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <div class="m2o-item m2o-flex-one m2o-bt" title="时移节目名称">时移节目名称</div>
            <div class="m2o-item m2o-channel" title="频道" style="width:80px;">频道</div>
            <div class="m2o-item m2o-se" title="节目开始时间 - 结束时间">节目开始时间-结束时间</div>
            <div class="m2o-item m2o-state" title="时移状态">时移状态</div>
            <div class="m2o-item m2o-time" title="时移节目名称">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
	        {foreach $list as $k => $v}
	        {code}
	            $imgInfo = $v['img_info'];
	            $img = $imgInfo ? $imgInfo['host'] . $imgInfo['dir'] . '40x30/' . $imgInfo['filepath'] . $imgInfo['filename'] : '';
	        {/code}
	        <div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" id="r_{$v['id']}">
	            <div class="m2o-item m2o-flex-one m2o-bt">
	            	<div class="m2o-title-transition max-wd">
	            	<a class="m2o-title-overflow">
	                {code}echo $img ? '<img _src="' . $img . '"/>' : '';{/code}
	                	<span class="m2o-common-title">{$v['title']}</span>
	                </a>
	                </div>
	            </div>
	            <div class="m2o-item m2o-channel" style="width:80px;">{$v['channel_name']}</div>
	            <div class="m2o-item m2o-se">{$v['starttime']} - {$v['endtime']}</div>
	            <div class="m2o-item m2o-state">{$v['status']}</div>
	            <div class="m2o-item m2o-time">
	                <span class="name">{$v['user_name']}</span>
	                <span class="time">{$v['create_time']}</span>
	            </div>
	        </div>
	        {/foreach}
        </div>
        <div class="m2o-bottom">
            {$pagelink}
    	</div>
    </div>

<script>
$(function(){
    $('.' + $.m2oClassName.each).geach();
});
(function($){
    var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};

    $.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id'],
                       video : n['video_url'],
                       img : n['img_info'] ? $.globalImgUrl(n['img_info'], '135x65') : ''
                   }
                   return false;
               }
            });
            return info;
        },

        option : function(){
            var root = this.element,
            	op = this.options;
            root.find('.m2o-video-preview').on({
                click : function(){
                    var $this = $(this).find('img');
                        $('<video/>').attr({
                            'width' : $this.data('width'),
                            'height' : $this.data('height'),
                            'src' : $this.data('video'),
                            'controls' : true,
                            'autoplay' : true
                         }).appendTo(root[0]);
                    root.goption('adjustLook');
                    root.addClass(op['play-model']).find(op['m2o-close']).addClass(op['play-back']);
                }
            });
        }
    });

    var controll = {
			delajax : function( ids, obj ,method ){
				var url = './run.php?mid=' + gMid + '&a=' + method;
				$.get(url,{id : ids},function(){
					obj.remove();
				});
			}
	};

    $(function($){
        $('body').on({
            'goptiondelete' : function(event, _this){
                var widget = _this.element,
                	op = _this.options,
                	obj = widget.closest( '.m2o-each' ),
                	id = obj.data('id'),
                	self = widget.find( op['m2o-delete'] ),
                	method = self.data('method');
                controll.delajax( id, obj ,method );
            }
        }, '.m2o-option');
    });
})(jQuery);
</script>

<script>
/*$(function() {
	window.App = Backbone;
	var Records = window.Records;
	var RecordsView = window.RecordsView;
	var Publish_box = window.Publish_box;
	
	recordCollection = new Records;
	recordsView = new RecordsView({ el: $('.m2o-each').parent(), collection: recordCollection });
    recordCollection.add($.globalListData);
 	
 	if (Publish_box) { 
 	
     	new Publish_box({
            el: $('#special_publish'),
            info_url: 'get_special_column.php?a=get_special_column',
            plugin: 'hg_special_publish',
            initialized: function(view) {
            	App.on('openSpecial_publish', view.open, view);
            	App.on('batch:special_publish', view.openForBatch, view);
            }
        });
        new Publish_box({
            el: $('#block_publish'),
            plugin: 'hg_block_publish',
            initialized: function(view) {
            	App.on('openBlock_publish', view.open, view);
            }
        });
        new Publish_box({
            el: $('#vodpub'),
            plugin: 'hg_publish',
            pluginOptions: {
            	maxColumn: 3
            },
            initialized: function(view) {
            	App.on('openColumn_publish', view.open, view);
            	App.on('batch:column_publish', view.openForBatch, view);
            }
        });
	}
});*/
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a class="m2o-delete" data-method="delete">删除</a>
			</div>
			<!--<div class="m2o-btn-area m2o-flex">
				<a class="m2o-edit">编辑</a>
				 <a class="m2o-delete">删除</a>
			</div>
			<div class="m2o-btn-area m2o-flex">
				 <a class="m2o-publish">签发</a>
				 <a class="m2o-special">专题</a>
				 <a class="m2o-block">区块</a>
			</div>-->
			<div class="m2o-option-line"></div>
			<div class="m2o-option-edit-area m2o-flex">
				<div class="m2o-video-preview">
					 <img src="{{= img}}" style="width:135px;height:65px;" data-video="{{= video}}" data-width="400" data-height="300"/>
				</div>
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

{template:foot}