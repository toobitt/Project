{template:head}
{css:2013/form}
{css:hg_sort_box}
{css:2013/button}
{css:survey_form}
{js:ajax_upload}
{js:2013/ajaxload_new}
{js:hg_sort_box}
{js:common/common_form}
{js:pop/base_pop}
{js:pop/pop_list}
{js:survey/add_survey_form}
{code}
if($id)
{
	$optext="更新问卷";
	$ac="update";
}
else
{
	$optext="新增问卷";
	$ac="create";
}
{/code}
{code}
	//print_r($formdata);
	//print_r($_configs['type']);
{/code}
<script>
$.globalData = {code}echo $formdata ? json_encode($formdata) : '{}';{/code};
$.globalTags = {code}echo $tags ? json_encode($tags) : '{}';{/code};
</script>
<form class="m2o-form" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="verifycode_form" _publish="{$formdata['is_publish']}" data-id="{$id}">
    <div class="cover"></div>
    <header class="m2o-header">
    	<div class="m2o-inner">
        	<div class="m2o-title m2o-flex m2o-flex-center">
           	 	<h1 class="m2o-l">{$optext}</h1>
            	<div class="m2o-m m2o-flex-one">
                 	<input placeholder="输入问卷名称" name="title" class="m2o-m-title need-word-count" title="{$formdata['title']}"  required value="{$formdata['title']}" />
            	</div>
            	<div class="m2o-btn m2o-r">
                	<input type="submit" value="保存信息" class="m2o-save" name="sub" id="sub" data-target="run.php?mid={$_INPUT['mid']}&a={$ac}" data-method="{$ac}"/>
                	<span class="m2o-close option-iframe-back"></span>
                	<input type="hidden" name="a" value="{$ac}" />
                	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
                	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					 <!-- 另存为 -->
				    <div class="notice-box">
				    	<p class="arrow"></p>
				    	<div class="msg"><p>此问卷已发布使用，保存后会改变前端的显示，您是否继续保存？</p></div>
				    	<div class="save-box">
				    		<p class="save_as">另存为</p>
				    		<p class="other_save">保存</p>
				    		<p class="cancel">取消</p>
				    	</div>
				    </div>
	    			<!-- end -->
            	</div>
        	</div>
      </div>  
    </header>
    <div class="m2o-inner">
	<div class="m2o-main m2o-flex">
        <section class="m2o-flex-one m2o-survey">
        	<div class="survey-info">
        		<ul class="question-types">
        		{foreach $_configs['type'] as $k => $v}
        			<li data-type="{$k}" data-title="{$v}">添加{$v}</li>
        		{/foreach}
        		</ul>
        		<!-- <div class="save-survey"> 
        			<span class="save">保存文稿</span>
        		</div> -->
        		<div class="info-contain">
        			<div class="info"></div>
        		</div>
        		<div class="question-box"></div>
        	</div>
        	<input type="hidden" name="type" value="" />
        	<input type="hidden" name="type_id" value="" />
        	<input type="hidden" name="1_title" value="" />
        	<input type="hidden" name="2_title" value="" />
        	<input type="hidden" name="3_title" value="" />
        	<input type="hidden" name="4_title" value="" />
        	<input type="hidden" name="1_option" value="" />
        	<input type="hidden" name="2_option" value="" />
        	<input type="hidden" name="1_initnum" value="" />
        	<input type="hidden" name="2_initnum" value="" />
        	<input type="hidden" name="1_brief" value="" />
        	<input type="hidden" name="2_brief" value="" />
        	<input type="hidden" name="1_more" value="" />
        	<input type="hidden" name="2_more" value="" />
        	<input type="hidden" name="4_tip" value="" />
        	<input type="hidden" name="1_required" value="" />
        	<input type="hidden" name="2_required" value="" />
        	<input type="hidden" name="3_required" value="" />
        	<input type="hidden" name="4_required" value="" />
        	<input type="hidden" name="4_max" value="" />
        	<input type="hidden" name="4_min" value="" />
        	<input type="hidden" name="2_max" value="" />
        	<input type="hidden" name="2_min" value="" />
        	<input type="hidden" name="3_num" value="" />
        	<input type="hidden" name="1_other" value="" />
        	<input type="hidden" name="2_other" value="" />
        	<input type="hidden" name="edit_proid" value="" />
        	<input type="hidden" name="delete_proid" value="" />
        	
        </section>
        <aside class="m2o-l m2o-aside">
        	<div class="video-box"></div>
        	<div class="m2o-item img-info" style="position:relative">
			        <div class="indexpic">
			            {code}
			            $indexpic_url = hg_fetchimgurl($formdata['indexpic'],325);
			            {/code}
			            <img src="{$indexpic_url}" />
			            <span class="indexpic-suoyin {if $formdata['indexpic']}indexpic-suoyin-current{/if}"></span>
			            <input type="hidden" name="indexpic" value="{$indexpic}" />
			        </div>
			        <input type="file" name="indexpic" style="display:none;" class="upload-file" />
			</div>
            <div class="m2o-item form-dioption-sort"  id="sort-box">
                <label style="color:#9f9f9f;">分类： </label>
                <p style="display:inline-block;" class="sort-label" _multi="survey_node">{if $formdata['sort_name']}{$formdata['sort_name']}{else}请选择分类{/if}<img class="common-head-drop" src="{$RESOURCE_URL}survey/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$formdata['node_id']}" id="sort_id" />
            </div>
        	<div class="m2o-item brief">
        		<textarea class="survey-brief" placeholder="描述" name="brief">{$formdata['brief']}</textarea>
        		<input type="text" name="more" value="{$formdata['more']}" placeholder="查看更多链接 http://"/>
        	</div>
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">附加描述</span>
        		<ul class="additional-information">
        			<li class="pic">图片</li>
        			<li class="margin video">视频</li>
        			<li class="margin voice">音频</li>
        			<li class="margin cite">引用</li>
        		</ul>
        		<div class="information-box">
        			<em></em>
        			<div class="attach-box pic-box">
        				<ul>
        					{foreach $formdata['pictures'] as $k => $v}
        					<li class="attach-info" id="{$v['id']}" _id="{$v['id']}">
        						<img src="{$v[img_info]}" />
        						<p class="attach-del"></p>
        					</li>
        					{/foreach}
        					<li class="attach-info pic-default"></li>
        					<input type="file" class="upload-file" name="Filedata" style="display:none;"/>
        				</ul>
        			</div>
        			<div class="attach-box file-box none">
        				<ul>
        					{foreach $formdata['videos'] as $k => $v}
        					<li class="attach-info" id="{$v['id']}" _id="{$v['id']}">
        						<img src="{$v[img_info]}" />
        						<span class="play play-button" data-url="{$v['m3u8']}" style="left:50%"></span>
        						<p class="attach-del"></p>
        					</li>
        					{/foreach}
        					<li class="attach-info video-default"></li>
        					<input type="file" class="uploadvod-file" name="Filedata" style="display:none;"/>
        				</ul>
        			</div>
        			<div class="attach-box audio-box none">
        				<ul>
        					{foreach $formdata['audios'] as $k => $v}
        					<li class="attach-info" id="{$v['id']}" _id="{$v['id']}">
        						<img src="{if $v[img_info]} {$v[img_info]} {else} {$RESOURCE_URL}survey/survey-default.png {/if}" />
        						<span class="play play-button" data-url="{$v['m3u8']}" style="left:50%"></span>
        						<p class="attach-del"></p>
        					</li>
        					{/foreach}
        					<li class="attach-info video-default"></li>
        					<input type="file" class="uploadvod-file" name="Filedata" style="display:none;"/>
        				</ul>
        			</div>
        			<div class="attach-box cite-box none">
        				<ul>
        					{foreach $formdata['publicontents'] as $k => $v}
        					<li class="attach-info" id="{$v['id']}" _id="{$v['id']}">
        						<img src="{if $v[img_info]} {$v[img_info]} {else} {$RESOURCE_URL}survey/survey-default.png {/if}" />
        						<p class="attach-del"></p>
        					</li>
        					{/foreach}
        					<li class="attach-info cite-default"></li>
        				</ul>
        			</div>
        		</div>
        	</div>
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">有效时间</span>
        		<div class="active-time">
        			<input type="text" name="start_time" class="date-picker" _time="true" _second="true" value="{if $formdata['start_time'] && $formdata['start_time'] !=0 } {$formdata['start_time']} {/if}"/>
        			<span>至</span>
        			<input type="text" name="end_time" class="date-picker" _time="true" _second="true" value="{if $formdata['end_time'] && $formdata['end_time']!=0 } {$formdata['end_time']} {/if}"/>
        		</div>
        	</div>
        	{template:unit/publish_for_form, 1, $formdata['column_id']}
        	<div class="m2o-item">
        		<div class="form-dioption-fabu form-dioption-item">
                    <a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：" _type="publish" >发布至</a>
                </div>
        	</div>
       		<div class="more-info">
       			<div class="more-site">
       				<span style="color:#9f9f9f;">更多设置</span>
       				<p class="toggle down"></p>
       			</div>
       			<div class="info-box">
	       			<!--<div class="display survey-info survey-open">
	       				<span>问卷公开:</span>
	       				<div class="is_open">
	       					<div class="display open">
	       						<input type="radio" name="is_open" />
	       						<p>公开</p>
	       						<p>(所有人登录即可参与)</p>
	       					</div>
	       					<div class="display no-open">
	       						<input type="radio" name="is_open" />
	       						<p>不公开</p>
	       						<p>(只有您邀请的人才能看到此问卷)</p>
	       					</div>
	       				</div>
	       			</div>-->
	       			<div class="display survey-info">
	       				<span>结果公开:</span>
	       				<div class="display is_open">
	       					<div class="display open">
	       						<input type="radio" name="is_result_public" {if $formdata['is_result_public']} checked {/if} value="1"/>
	       						<p>公开</p>
	       					</div>
	       					<div class="display no-open">
	       						<input type="radio" name="is_result_public" {if !$formdata['is_result_public']} checked {/if} value="0"/>
	       						<p>不公开</p>
	       					</div>
	       				</div>
	       			</div>
	       			<div class="display survey-info survey-time">
	       				<span>答题时间:</span>
	       				<div class="is_open">
	       					<div class="display open num-limit">
	       						<input type="checkbox" name="is_time" {if $formdata['use_hour'] || $formdata['use_minute'] || $formdata['use_second']} checked {/if} value="1"/>
	       						<p>最长答题时间</p>
	       						<input type="text" name="use_hour"  value="{$formdata['use_hour']}"/><p>时</p>
	       						<input type="text" name="use_minute" value="{$formdata['use_minute']}"/><p>分</p>
	       						<input type="text" name="use_second" value="{$formdata['use_second']}"/><p>秒</p>
	       					</div>
	       					<div class="display no-open">
	       						<span class="overtime">超时后</span>
	       						<input type="radio" name="is_auto_submit" {if $formdata['is_auto_submit']} checked {/if}  value="1"/><p>自动提交</p>
	       						<input type="radio" name="is_auto_submit" {if !$formdata['is_auto_submit']} checked {/if} value="0"/><p>不提交</p>
	       					</div>
	       				</div>
	       			</div>
		       		<div class="display survey-info ip-limit">
		       			<input type="checkbox" name="is_login" {if $formdata['is_login']} checked {/if} value="1"/><p>登录后答题</p>
		       			<input type="checkbox" name="is_verifycode" {if $formdata['is_verifycode']} checked {/if} value="1"/><p>开启验证码</p>
		       		</div>
		       		<div class="verify_type" style="display:{if $formdata['is_verifycode']}block{else}none{/if}">
	                    {code}$verify_type = $verify_type[0];{/code}
		                	{foreach $verify_type as $k=>$v}
		                	<div class="verify_code">
			                	<div class="form-dioption-item verify_title">
			                  		<input type="radio"  {if $formdata['verify_type'] == $v['id'] }checked{/if} name="verifycode_type" value="{$v['id']}">
			                  		<a class="" >{$v['name']}</a>
			                	</div>
			                	<img src="run.php?a=get_verify_code&type={$v['id']}"/>
		                	</div>
		                	{/foreach}
	                </div>
	                <div class="display survey-info">
	       				<span>IP限制:</span>
	       				<div class="display device_limit num-limit">
	       					<input type="checkbox" name="is_ip" {if $formdata['is_ip']} checked {/if} value="1"/>
	       					<span>同一IP地址限制</span><input type="text" name="ip_limit_num" value="{if $formdata['is_ip']} {$formdata['ip_limit_num']}{else}1{/if}"/><p>次</p>
	       				</div>
	       			</div>
	                <div class="block survey-info">
	       				<span>设备限制:</span>
	       				<div class="block device_limit num-limit">
	       					<input type="checkbox" name="is_device" {if $formdata['is_device']} checked {/if} value="1"/>
	       					<span>同一设备限制</span><input type="text" name="device_limit_num" value="{if $formdata['is_device']} {$formdata['device_limit_num']}{else}1{/if}"/><span>次,</span>
	       				</div>
	       				<div class="block">
	       					<span>错误时报错：</span>
	       					<input type="text" name="device_num_error" value="{if $formdata['is_device']}{$formdata['device_num_error']}{/if}"/>
	       				</div>
	       				<div class="block device_limit num-limit">
	       					<span>同一设备单次投票时间间隔不少于</span>
	       					<input type="text" name="device_limit_time" value="{if $formdata['is_device']} {$formdata['device_limit_time']}{else}0{/if}"/>
	       					<span>小时,</span>
	       				</div>
	       				<div class="block">
	       					<span>错误时报错：</span>
	       					<input type="text" name="device_time_error" value="{if $formdata['is_device']}{$formdata['device_time_error']}{/if}"/>
	       				</div>
	       			</div>
       			</div>
       		</div>
       		<input type="hidden" name="attach_pic" value="" />
       		<input type="hidden" name="attach_video" value="" />
       		<input type="hidden" name="attach_audio" value="" />
       		<input type="hidden" name="attach_cite" value="" />
        </aside>
    </div>
    </div>
</form>
<!-- 新增文本题 -->
<script type="text/x-jquery-tmpl" id="text-tpl">
<div class="add-new-question" data-type="${type}" data-id="${id}">
	<p class="question  new-question" _mark="${optext}">${ac}问题></p>
    <span class="question question-type">${title}</span>
    <p class="close-question"></p>
</div>
<div class="type-box">
<div style="margin-top: 10px;">
    <div class="type-title">
       <span>问题:</span>
       <input type="text" class="input-title" value="${question}"/>
       <div class="add-information">
        	<p class="add-pic"></p>
        	<p class="add-info"></p>
       </div>
    </div>
	<div class="type-title">
    	<div class="tip">
      		<!-- <input type="checkbox" name="" />-->
       		<span>提示:</span>
        	<input type="text" name="" value="${tip}" style="width: 475px;height: 19px;margin-left: 20px;"/>
    	</div>
	</div>
</div>
    <div class="textarea"></div>
</div>
<div class="type-box question-condition" style="display: -webkit-box;">
    <div class="condition">
        <input type="checkbox" name="is_answer" class="limit-input" {{if answer}} checked {{/if}}/>
        <span>必答题<em>*</em></span>
    </div>
    <div class="condition limit-word" {{if answer}} style="display:block" {{/if}}>
        <span>最少</span>
        <input type="text" name="is_min" class="input-option" value="{{if min}}${min}{{/if}}"/>
        <span>字</span>
    </div>
    <div class="condition" >
        <span>最多</span>
        <input type="text" name="is_max" class="input-option" value="{{if max}}${max}{{/if}}"/>
        <span>字</span>
    </div>
</div>

<div class="save-box">
     <span class="save saveonly">保存</span>
     <span class="save saveadd">保存并继续添加</span>
</div>
</script>

<!-- 新增填空题 -->
<script type="text/x-jquery-tmpl" id="pack-tpl">
<div class="add-new-question" data-type="${type}" data-id="${id}">
	<p class="question  new-question" _mark="${optext}">${ac}问题></p>
    <span class="question question-type">${title}</span>
    <p class="close-question"></p>
</div>
<div class="pack m2o-flex">
	<div class="pack-info">
{{if tags}}
{{each tags}}
		<div class="tag-info">
			<div class="pack-question">
				<p>{{= $value}}</p>
				<input type="text" class="word-count" value="{{= num[$index]}}"/>
				<a>字</a>
			</div>
			<input type="text" class="brief" />
			<p class="del-tag">-</p>
		</div>
{{/each}}
{{/if}}
</div>
	<div class="pack-label">
		<div class="used-tag">
      	 	<span>常用标签</span>
			<a title="点击管理常用标签">管理</a>
			<p title="点击添加常用标签">+</p>
    	</div>
		<div class="add-tag">
			<input type="text" name="" placeholder="输入标签名称" style="width:288px;margin-left:15px;display:none;"/>
		</div>
   	 	<ul class="tag-list">
{{if tag}}
{{each tag}}
        	<li _id="{{= $value['id']}}" title="{{= $value['tag_name']}}"><span>{{= $value['tag_name']}}</span><p>x</p></li>
{{/each}}
        	
{{/if}}
    	</ul>
		
	</div>
</div>
<div class="required">
	<input type="checkbox" name="is_answer" {{if answer}} checked {{/if}}/>
	<span>必填项<em>*</em></span>
</div>
<div class="save-box">
    <span class="save saveonly">保存</span>
    <span class="save saveadd">保存并继续添加</span>
</div>
</script>

<!-- 新增单选、多选题 -->
<script type="text/x-jquery-tmpl" id="more-tpl">
<div class="add-new-question" data-type="${type}" data-id="${id}">
	<p class="question  new-question" _mark="${optext}">${ac}问题></p>
	<span class="question question-type">${title}</span>
	<p class="close-question"></p>
</div>
<div class="type-box">
	<div class="type-title">
			<span>问题:</span>
{{if question}} 
			<input type="text" class="input-title" value="${question}"/>
{{else}}
			<input type="text" class="input-title" />
{{/if}}

			<!--<div class="add-information">
				<p class="add-pic"></p>
				<p class="add-info"></p>
			</div>-->
	</div>

	<div class="type-title">
			<span>描述:</span>
			{{if brief}} 
			<textarea class="input-brief">${brief}</textarea>
			{{else}}
			<textarea class="input-brief"></textarea>
			{{/if}}
			<br/>
			<span>更多:</span>
			<input type="text" class="input-more" value="${more}" placeholder="查看更多链接: http://"/>
			<!--<div class="add-information">
				<p class="add-pic"></p>
				<p class="add-info"></p>
			</div>-->
	</div>

	<div class="type-title">
		<span class="options">选项:</span>
		<div class="select-more">
{{if option}}
{{each option}}
			<div class="more-option">
				<input type="text" class="option" value="{{= $value}}" placeholder="选项名"/>
				<input type="text" class="initnum" value="{{if initnum}}{{= initnum[$index] }}{{/if}}"  placeholder="初始数"/>
				<div class="add-information">
					<p class="btn-add"></p>
					<p class="btn-del"></p>
					<p class="btn-up"></p>
					<p class="btn-down"></p>
				</div>
			</div>
{{/each}}
{{else}}
			<div class="more-option">
				<input type="text" class="option"  placeholder="选项名"/>
				<input type="text" class="initnum" placeholder="初始数"/>
				<div class="add-information">
					<p class="btn-add"></p>
					<p class="btn-del"></p>
					<p class="btn-up"></p>
					<p class="btn-down"></p>
				</div>
			</div>
{{/if}}
		</div>
	</div>
</div>
<div class="type-box question-condition">
	<div class="condition">         
		<input type="checkbox" name="other-option" {{if isother}} checked {{/if}}>         
		<span>其他</span>  
		<!--<div class="other-option"></div> -->      
	</div>
</div>
<div class="type-box question-condition" style="display: -webkit-box;">     
	<div class="condition">         
		<input type="checkbox" name="is_answer" class="limit-input" {{if answer}} checked {{/if}}>         
		<span>必答题<em>*</em></span>     
	</div> 
{{if type == 2}}    
	<div class="condition limit-word"  {{if answer}} style="display:block" {{/if}}>
        <!--<input type="checkbox" name="" />-->
        <span>最少</span>
        <input type="text" name="is_min" class="input-option" value="{{if min}}${min}{{/if}}"/>
        <span>项</span>
    </div>
    <div class="condition">
       <!-- <input type="checkbox" name="" />-->
        <span>最多</span>
        <input type="text" name="is_max" class="input-option" value="{{if max}}${max}{{/if}}"/>
        <span>项</span>
    </div>
  	
{{/if}}
</div>
<div class="save-box">
<span class="save saveonly">保存</span>
<span class="save saveadd">保存并继续添加</span>
</div>
</script>
<!--填空题标签  -->
<script type="text/x-jquery-tmpl" id="tag-tpl">
	<li _id="${id}" title="${tag}"><span>${tag}</span><p>x</p></li>
</script>
<!-- 选择标签 -->
<script type="text/x-jquery-tmpl" id="sel-tpl">
<div class="tag-info">
		<div class="pack-question">
			<p>${title}</p>
			<input type="text" class="word-count"/>
			<a>字</a>
		</div>
		<input type="text" class="brief" />
		<p class="del-tag">-</p>
	</div>
</script>


<!-- 预览模板 -->
<!-- 填空题 -->
<script type="text/x-jquery-tmpl" id="prepack-tpl">
<div class="cite-question" data-type="${type}" data-id="${id}" data-title="${type_name}" data-select="${select}">
	<div class="personal-info">
		<span>
			<a class="index">1</a>
			<p>.</p>
		</span>
		<ul>
	    {{each option}}
	    <li class="fill-blank" style="display: -webkit-box;float: left;" data-num="{{if num}} {{= num[$index]}} {{else}} {{= $value['char_num']}} {{/if}}">
	    <p class="fill-name" style="font-size:14px;">{{if sign}} {{= $value }} {{else}} {{= $value['name']}} {{/if}}</p>
		<p>:</p>
	    <div style="width:125px;height:27px;border-bottom:1px solid #bababa;margin-top: -8px;"></div>
		</li>
	    {{/each}}
		</ul>
		<p class="mark" style="{{if select == 1}} display:block{{/if}}">*</p>
	</div>
	<ul class="operation">
	    <li class="edit">编辑</li>
	    <li class="delete">删除</li>
	    <li class="copy">复制</li>
	    <li class="move moveup">上移</li>
	    <li class="move movedown">下移</li>
	    <li class="add-more">在此后添加一题</li>
	</ul>
</div>
</script>
<!-- 单选/多选题 -->
<script type="text/x-jquery-tmpl" id="presel-tpl">
<div class="cite-question" data-type="${type}" data-id="${id}" data-brief="${brief}" data-more="${more}" data-title="${type_name}" data-select="${select}" data-max="${max}" data-min="${min}" data-other="${other}">
	<span>
		<a class="index"></a>
		<em>.</em>
		<p class="type-question" style="width: 700px;width: auto;max-width: 700px;">${title}</p>
		<p class="mark" style="{{if select == 1}} display:block{{/if}}">*</p>
		{{if type==2}}
		<a style="vertical-align: super;">
        【<p style="color:red">{{= tip}}</p>】</a>
        {{/if}}
	</span>
	<div class="item-brief">
		<p class="brief">${brief}</p>
		{{if more}}
			<p class="item-more">【查看更多:<a class="more"> {{= more}}</a>】</p>
		{{/if}}
	</div>
	{{each option}}
	<div class="check">
		<input type="checkbox" name="" />
		<p class="sign">{{= $value['name']}}</p>
		<span class="ininum" _initnum="{{if $value['initnum']}}{{= $value['initnum']}}{{else}}0{{/if}}">【{{if $value['initnum']}}{{= $value['initnum']}}{{else}}0{{/if}}】</span>
	</div>
	{{/each}}
	{{if other==1}}
	<div style="display: -webkit-box;display: -moz-box;margin-left: 25px;"><input type="checkbox" name="" />
		<p class="sign">其他</p>
		<div style="width: 200px;border-bottom: 1px solid #bababa;"></div>
	</div>
	{{/if}}
	<ul class="operation">
		<li class="edit">编辑</li>
	    <li class="delete">删除</li>
	    <li class="copy">复制</li>
	    <li class="move moveup">上移</li>
	    <li class="move movedown">下移</li>
	    <li class="add-more">在此后添加一题</li>
	</ul>
</div>
</script>
<!-- 文本题 -->
<script type="text/x-jquery-tmpl" id="pretext-tpl">
<div class="cite-question comment" data-type="${type}" data-id="${id}" data-title="${type_name}" data-select="${select}" data-max="${max}" data-min="${min}">
	<span><a class="index"></a><em>.</em><p class="type-question">${title}</p><p class="mark" style="{{if select == 1}} display:block{{/if}}">*</p></span>
	<div style="width:576px;height:91px;border:1px solid #bababa;margin: 10px 0 5px 20px;">
		<p class="text-tip" style="margin: 5px 0 0 10px;color: #999;">${tips}</p>
	</div>
	<p class="tip">{{if min}}最少输入${min}个字,{{/if}}{{if max}}最多不超过${max}个字{{/if}}</p>
	<ul class="operation">
	  <li class="edit">编辑</li>
	  <li class="delete">删除</li>
	  <li class="copy">复制</li>
	  <li class="move moveup">上移</li>
	  <li class="move movedown">下移</li>
	  <li class="add-more">在此后添加一题</li>
	</ul>
</div>
</script>

<!-- 单选多选 增加选项 -->
<script type="text/x-jquery-tmpl" id="addoption-tpl">
<div class="more-option">
	<input type="text" class="option" />
	<input type="text" class="initnum" />
	<div class="add-information">
		<p class="btn-add"></p>
		<p class="btn-del"></p>
		<p class="btn-up"></p>
		<p class="btn-down"></p>
	</div>
</div>
</script>

<!-- 附加信息 -->
<script type="text/x-jquery-tmpl" id="attachpic-tpl">
<li class="attach-info" id="${id}" _id="${id}">
	<img src="{{if img}} ${img} {{else}} {$RESOURCE_URL}survey/survey-default.png {{/if}}"" />
	<p class="attach-del"></p>
{{if playurl}}
	<span class="play play-button" data-url="${playurl}" style="left:50%"></span>
{{/if}}
</li>
</script>
<!-- 播放器 -->
<script type="text/x-jquery-tmpl" id="vedio-tpl">
<div style="width:375px;height:310px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="375" height="310">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl=${video_url}&autoPlay=true&aspect=${aspect}">
  </object>
</div>
  <span class="vedio-back-close"></span>
</script>
</script>
