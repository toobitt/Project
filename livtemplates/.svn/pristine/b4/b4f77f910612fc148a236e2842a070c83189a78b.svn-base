{template:head}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{css:2013/list}
{css:common/common}
{js:2013/list}
{template:list/common_list}
{js:box_model/list_sort}

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增应用</em></span>
		<span class="right"></span>
	</a>
</div>
<style>
.common-list-item{width:95px;}.w160{width:160px;}.w140{width:140px;}.w100{width:100px;}.color{color: #888;}
</style>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="select-search">
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
                    </div>
                    <div class="text-search">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}                        
                    </div>
                    </form>
                </div>
				
				<div id="infotip" class="ordertip" ></div>
				<div id="add_question"  class="single_upload" style="min-height:100px;">
					<div id="question_option_con"></div>
				</div>
                <form method="post" action="" name="listform">
                 <div class="m2o-list">
                <!-- 标题 -->
                 <div class="m2o-title m2o-flex m2o-flex-center">
		 	   		<div id="infotip" class="ordertip">排序模式已关闭</div>
				       <div class="m2o-item m2o-paixu common-paixu" title="排序">
				        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
				       </div>
                    <div class="m2o-item m2o-flex-one m2o-bt" title="应用名">应用名</div>
		            <div class="m2o-item m2o-state w100" title="开启状态">开启状态</div>
		            <div class="m2o-item m2o-num w100" title="应用标识">应用标识</div>
		            <div class="m2o-item m2o-sort w100" title="有效问卷">调用方法</div>
		            <div class="m2o-item m2o-time w160" title="添加人/时间">添加人/时间</div>
		        </div>
				<div class="m2o-each-list">
		        	{if is_array($list) && count($list)>0}
						{foreach $list as $k => $v}	
				            {template:unit/appsetlist}
				        {/foreach}
					{else}
						<p class="common-list-empty">没有你要找的内容！</p>
					{/if}
		        </div>
	            <div class="m2o-bottom m2o-flex m2o-flex-center">
				  	 <div class="m2o-item m2o-paixu">
		        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
		    		</div>
		    		<div class="m2o-item m2o-flex-one">
		    		   <a class="batch-handle">删除</a>
		    		</div>
		    		<div id="page_size">{$pagelink}</div>
				</div>
				</div>
    		</form>
			<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
			</div>
            </div>
        </div>
</div>

</body>
{template:unit/record_edit}
{template:foot}
 <script>
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id ){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id'],
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

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" need-back>编辑</a>
				<a class="option-delete">删除</a>
			</div>
			<div class="m2o-option-line"></div>
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
<script>
$(function(){
	var onOff = function(id, obj, state){
		var url = './run.php?mid=' + gMid + '&a=state&ajax=1';
		$.getJSON( url, {id : id, state : state} ,function( data ){
			if( data['callback'] ){
				eval( data['callback'] );
				var state = (obj.attr('_status') == 1);
				obj.find('.common-switch')[ (state ? 'add' : 'remove') + 'Class']('common-switch-on');
				obj.find('.ui-slider-handle').css({
					'left' : (state ? '100%' : '0%')
				});
			}else{
				data = data[0];
				var status = data['state'];
				obj.attr('_status', status);		
			}
		});
	}
	
	$('.common-switch').each(function(){
		var $this = $(this),
			obj = $this.parent();
		var id = $this.closest('.m2o-each').attr('id');
		$this.hasClass('common-switch-on') ? val = 100 : val = 0;
		$this.hg_switch({
			'value' : val,
			'callback' : function( event, value ){
				var is_on = 0;
				( value > 50 ) ? state = 1 : state = 0;
				onOff(id, obj, state);
			}
		});
	});
});
</script>