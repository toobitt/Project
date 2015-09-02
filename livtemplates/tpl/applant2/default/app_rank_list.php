{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
{/code}
{css:vod_style}
{template:list/common_list}
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
                <form method="post" action="" name="vod_sort_listform">
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
                                <div class="common-list-item wd180">发布版本</div>
                                <div class="common-list-item wd180">测试版本</div>
                                <div class="common-list-item wd180">下载量</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">应用名称</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    {if $list['rank']}
		       			    {foreach  $list['rank']  as $k => $v}
		                      {template:unit/app_rank_list_item}
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
$(function(){
	var qrcode_url = {code}echo json_encode($_configs['qrcode_url']);{/code};
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
	var cache = new Object();
	$.each(data.version, function(k, v){
		cache[ k ] = new Object();
		cache[ k ].versionInfo = v;
		$.each( data.app, function(kk, vv){
			if( k == kk ){
				cache[ k ].uuid = vv.uuid
			}
		});
	});
	console.log( data );
	console.log( cache );
	
	$.extend($.geach || ($.geach = {}), {
	        data : function(id){
		        console.log( id );
	            var info;
	            $.each(cache, function(i, n){
		           console.log( i, n );
	               if(i == id){
	                   info = {
							id : i,
							versionInfo : n['versionInfo'],
					//		codepic : qrcode_url + '?id=' + n['uuid'] + '&type=' + n['versionInfo']['last_version'],
							codepic : qrcode_url + '?id=' + n['uuid'],
	                   }
	                   return false;
	               }
	            });
	            return info;
	        }
	    });
	    $('.m2o-each').geach();
		$('.m2o-list').glist();
});
</script>
<style>
.m2o-each{width:auto;}
.m2o-option .version-item{width:120px;}
.m2o-option .tips{color:#fff;clear:both;font-size:14px;padding-bottom:3px;}
.m2o-option .small-tips{color:#ddd;font-size:12px;}
.m2o-option .codepic{width:100px!important;height:100px!important;}
.m2o-option .codepic img{max-height:100%;max-width:100%;min-height:100%;min-width:100%;background:url({$RESOURCE_URL}loading2.gif) no-repeat center;background-size:30px;}
.m2o-option .record-edit-btn{float:none;display:block;width:100px;margin-top:10px;font-size:12px;background:#5b5b5b;height:28px;line-height:28px;}
</style>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-flex">
			{{if !(versionInfo.debug||versionInfo.release)}}
			<p class="tips">暂无打包信息</p>
			{{else}}
			<div class="version-item m2o-flex-one">
				<p class="tips">发布版本</p>
				{{if versionInfo.release}}
				<div class="codepic">
					<img src="{{= codepic}}&type=release" />
				</div>
				{{each versionInfo.release}}
				<a class="record-edit-btn" href="{{= $value['download_url']}}">{{= $value['name']}}</a>
				{{/each}}
				{{else}}
				<p class="small-tips">暂无打包信息</p>
				{{/if}}
			</div>
			<div class="version-item m2o-flex-one">
				<p class="tips">测试版本</p>
				{{if versionInfo.debug}}
				<div class="codepic">
					<img src="{{= codepic}}&type=debug" />
				</div>
				{{each versionInfo.debug}}
				<a class="record-edit-btn" href="{{= $value['download_url']}}">{{= $value['name']}}</a>
				{{/each}}
				{{else}}
				<p class="small-tips">暂无打包信息</p>
				{{/if}}
			</div>
			{{/if}}
        </div>
    </div>
	<div class="m2o-option-close"></div>
</div>
</script>
{template:foot}