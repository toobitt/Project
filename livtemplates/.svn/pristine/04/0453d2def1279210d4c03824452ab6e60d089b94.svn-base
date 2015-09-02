{template:head}
{css:common/common_form}
{js:2013/ajaxload_new}
{js:common/common_form}
{css:2013/iframe_form}
{css:2013/button}
{css:2013/form}
{css:template_form}
{css:common/common_list}
{code}
//print_r($consumption_list);
$orderlist = $consumption_list[0]['order'];
$formdata = $consumption_list[0]['template_info'];
{/code}
<style>
.m2o-main .m2o-m{padding:0;}
.keywords-del{display:none!important;}
.keywords-add{display:none!important;}
</style>
<form  action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="vodform"  id="vodform">
<div class="common-form-head vedio-head">
     <div class="common-form-title">
          <h2>{$formdata['title']}下载记录</h2>
          <div class="form-dioption-title form-dioption-item">
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <span class="option-iframe-back">关闭</span>
		  </div>
    </div>
</div>

	
<div class="common-form-main m2o-inner">
	<div class="m2o-main m2o-flex">
	<div class="m2o-l form-left">
        <div class="form-dioption">
		
		<div class="form-dioption-inner">
		<div class="form-edit-img">
			<div class="form-cioption-indexpic form-cioption-item">
                <div class="indexpic-box">
                    <div class="indexpic" style="font-size:0;">
                        {code}
                        $indexpic_url = $formdata['index_pic'];
                        if($indexpic_url){
                            $indexpicsrc = $indexpic_url['host'].$indexpic_url['dir'].'160x160/'.$indexpic_url['filepath'].$indexpic_url['filename'];
                        }else{
                            $indexpicsrc = '';
                        }
                        {/code}
                        <img style="max-width:160px;max-height:160px;{if !$indexpicsrc}display:none;{/if}" src="{$indexpicsrc}" title="索引图" id="indexpic_url" />
                    </div>
                    <span class="indexpic-suoyin {if $indexpicsrc}indexpic-suoyin-current{/if}"></span>
                </div>
            </div>
			<div class="form-dioption-sort form-dioption-item">
				<label style="color:#9f9f9f;">分类： </label><p style="display:inline-block;" class="sort-label" _multi="template_sort">{$formdata['sort_name']}</p>
				<input type="hidden" value="{$formdata['sort_id']}" name="sort_id" id="sort_id" />
			</div>
			    
			<div class="form-dioption-keyword form-dioption-item clearfix">
				<span class="title" style="float: left;line-height: 30px;">标签：</span>
				<span class="keywords-del"></span>
				<span class="form-item" _value="添加标签" id="keywords-box">
					<span class="keywords-start color">添加标签</span>
					<span class="keywords-add">+</span>
				</span>
				<input name="keywords" value="{$formdata['keywords']}" id="keywords" style="display:none;"/>
			</div>
			
			<div class="form-dioption-item">
    	        <span class="title">色系：</span>
    	        <span>{$formdata['_color']}</span>
		   </div>
		   <div class="form-dioption-item">
    	        <span class="title">版本：</span>
				<span>{$formdata['_version']}</span>
		   </div>
		   <div class="form-dioption-item">
    	        <span class="title">风格：</span>
				<span>{$formdata['_style']}</span>
		   </div>
		   <div class="form-dioption-item">
    	        <span class="title">用途：</span>
				<span>{$formdata['_use']}</span>
		   </div>
		   
		  {code}$vodinfo = $formdata['vodinfo'];{/code}
			   	<div class="form-dioption-item">
	    	        <span class="title">时长：</span>
					<span>{$vodinfo['video_duration']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">文件大小：</span>
					<span>{$vodinfo['video_totalsize']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">视频编码：</span>
					<span>{$vodinfo['video']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">平均码流：</span>
					<span>{$vodinfo['bitrate']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">视频帧率：</span>
					<span>{$vodinfo['frame_rate']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">分辨率：</span>
					<span>{$vodinfo['video_resolution']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">宽高比：</span>
					<span>{$vodinfo['aspect']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">音频编码：</span>
					<span>{$vodinfo['audio']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">音频采样率：</span>
					<span>{$vodinfo['sampling_rate']}</span>
			   </div>
			   <div class="form-dioption-item">
	    	        <span class="title">声道：</span>
					<span>{$vodinfo['video_audio_channels']}</span>
			   </div>
   			   
		</div>
		
		
		</div>
            
		</div>
	</div>
	<div class="m2o-m m2o-flex-one">
		{if !$orderlist}
			<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
			<script>hg_error_html('#emptyTip',1);</script>
		{else}
		<!-- 头部，记录的列属性名字 -->
		<ul class="common-list news-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
                </div>
				<div class="common-list-right">
					<div class="common-list-item wd150">ip</div>
                    <div class="common-list-item wd180">单位</div>
                    <div class="common-list-item wd70">消费价格</div>
                    <div class="common-list-item wd150">购买时间</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">用户</div>
				</div>
			</li>
		</ul>
		
		<ul class="news-list common-list public-list hg_sortable_list">
		{foreach $orderlist as $k => $v}
			<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
			   <div class="common-list-right">
			
					<div class="common-list-item wd150">
					     <span>{$v['ip']}</span>
					</div>
					<div class="common-list-item wd180">
						<span>{$v['enterprise']}</span>
					</div>
					<div class="common-list-item wd70">
						<span>{$v['cost']}</span>
					</div>
					<div class="common-list-item wd150">
					     <span class="news-time">{code}echo date('Y-m-d h:i',$v['create_time']);{/code}</span>
					</div>
				</div>
			   <div class="common-list-biaoti min-wd">
				    <div class="common-list-item biaoti-transition">
				      <div class="common-list-overflow max-wd">
					   		<span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['member_name']}</span>
					   </div>
					</div>
			   </div>
			</li>

		{/foreach}
		
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				{$pagelink}
			</li>
		</ul>   
		{/if}	
		
	</div>
	</div>

</div>
</form>


{template:foot}     				