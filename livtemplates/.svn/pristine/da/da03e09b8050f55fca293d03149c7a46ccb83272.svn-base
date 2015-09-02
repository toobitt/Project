<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:contribute_style}
{css:vod_style}
{css:edit_video_list}
{css:common_publish}
{js:vod_opration}
{js:contribute}
{js:admin_privilege}

<style>
body{font-size:14px;}
.fieldset{float:left;min-width:300px;border-color:blue;border-radius:5px;margin-right:10px;padding:10px;}
.fieldset legend{margin-left:10px;font-size:20px;font-weight:bold;padding:10px;}
.fieldset div{float:left;min-width:100px;text-align:center;border-radius:3px;background:rgb(240, 208, 238);color:#000;height:40px;line-height:40px;margin:15px;}
.fieldset div:hover{background:green;color:#fff;}
.search-box{position:fixed;width:150px;right:50px;top:50px;border:2px solid green;border-radius:5px;background:#fff;padding:10px;}
.search-result{display:none;}
</style>

<script>
(function($){
    $.widget('ui.search', {
        options : {
            data : null
        },

        _create : function(){
            var root = this.element;
            root.addClass('search-box');
            root.append('<input class="search-title" placeholder="快速搜索"/>');
        },

        _init : function(){
            var _this = this;
            var _data = _this.options['data'];
            _this.element.find('.search-title').autocomplete({
                source : function(request, response){
                    response($.grep(_data, function(item){
                        return item.name.indexOf(request.term) != -1;
                    }));
                },
                select : function(event, ui){
                    $(this).val(ui.item.name);
                    _this._trigger('select', null, ui.item.id);
                    return false;
                }
            }).data('ui-autocomplete')._renderItem = function(ul, item){
                return $('<li class="ui-menu-item" role="presentation"/>').attr('data-id', item['id']).html('<a class="ui-corner-all" tabindex="-1">' + item['name'] + '</a>').appendTo(ul);
            }
        },

        _destroy : function(){

        }
    });
})(jQuery);
jQuery(function($){
    $('#search-box').search({
        data : alls,
        select : function(event, id){
            var item = $('.item[data-id="'+ id +'"]');
            if(item[0]){
                var offset = item.offset();
                var p$ = top.$;
                var stop = p$('#mainwin').offset().top;
                p$('body').stop().animate({
                    scrollTop : stop + offset.top + 'px'
                }, 500);
                $(this).css('top', offset.top + 50 + 'px');
            }
            item.css({
                background : 'red'
            });
            setTimeout(function(){
                item.removeAttr('style');
            }, 4000);
        }
    }).draggable({
        cursor : 'move'
    });

    $.each(groups, function(i, n){
        $('#group-' + i).html(n);
    });
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	<div class="content clear">
 		<div class="f">
 			
	 		<!-- 新增分类面板 开始-->
	 		 <div id="add_auth"  class="single_upload">
	 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">新增auth</span></h2>
	 		 	<div id="add_auth_tpl" class="add_collect_form">
	 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
	 		 	</div>
			 </div>
	 		 <!-- 新增分类面板结束-->

 		    <!--
	    	<div class="right v_list_show">
	        	<div class="search_a" id="info_list_search">
	            	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                	<div class="right_1">
	                		<span>更新{$_GET['role_name']}权限</span>
							{code}	
								$time_css = array(
									'class' => 'transcoding down_list',
									'show' => 'time_item',
									'width' => 120,	
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['role_time'] = $_INPUT['role_time'] ? $_INPUT['role_time'] : 1;
							{/code}
							{template:form/search_source,role_time,$_INPUT['role_time'],$_configs['date_search'],$time_css}
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
	
	            <div class="list_first clear"  id="list_head">
	            	<span class="left">
	            		<a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"><em></em></a>
	            	</span>
                	<span class="right">
                		<a class="fl">权限</a>
                	</span>
                	<a class="title">应用名称</a>       
	            </div>
	            <form method="post" action="" name="pos_listform">
		        	<ul class="list" id="contribute_list">
						{if $formdata}
			       			{foreach $formdata as $k => $v} 
			                	{template:unit/adminprivilegelist}
			                {/foreach}
			  			{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
		            </ul>
			        <div class="bottom clear">
			        	{$pagelink}
			        </div>	
	    		</form>
	    		<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
	    	</div>
	    	-->

            {code}
            //hg_pre($formdata);

            $alls = array();

            {/code}
	    	{foreach $formdata as $kk => $vv}
	    	    {code}$groupids[] = $kk;{/code}
	    	    <fieldset class="fieldset">
	    	    <legend id="group-{$kk}"></legend>
	    	    {if $vv}
	    	    {foreach $vv as $kkk => $vvv}
	    	        {code}$alls[] = $vvv;{/code}
	    	        <div class="item" data-id="{$vvv['id']}" onclick="hg_showModule('{$vvv['bundle']}',{$_GET['id']},'{$vvv['name']}','0');">{$vvv['name']}</div>
	    	    {/foreach}
	    	    {/if}
	    	    </fieldset>
	    	{/foreach}
	    	<script>
            var alls = {code}echo json_encode($alls ? $alls : array());{/code};
            var groups = {};
            if(top != self){
                top.$('.app-tag-fenzu li').each(function(){
                    var id = $(this).attr('_index');
                    var name = $(this).attr('_name');
                    groups[id] = name;
                });
            }
            </script>
            <div id="search-box"></div>
		</div>
	</div>
	<div id="infotip"  class="ordertip"></div>
	<span id="vod_fb" class="vod_fb" style="display: block; top: -440px; left: 98px;"></span>
    <div class="vodpub lightbox" id="vodpub" style="top: -440px;">
        <div class="lightbox_top">
            <span class="lightbox_top_left"></span>
            <span class="lightbox_top_right"></span>
            <span class="lightbox_top_middle"></span>
        </div>
        <div class="lightbox_middle">
            <span style="position:absolute;right:25px;top:25px;z-index:1000;background:url('./../livtemplates/tpl/lib/images/close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;" onclick="hg_vodpub_hide();"></span>
        	<div style="max-height:500px;padding:10px 10px 0;" class="text" id="vodpub_body"></div>
        </div>
        <div class="lightbox_bottom">
            <span class="lightbox_bottom_left"></span>
            <span class="lightbox_bottom_right"></span>
            <span class="lightbox_bottom_middle"></span>
        </div>
    </div> 
</body>
{template:foot}