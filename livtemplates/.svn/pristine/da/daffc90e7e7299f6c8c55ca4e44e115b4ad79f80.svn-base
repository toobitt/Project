{template:head}
{css:survey_result}
{js:page/page}
{js:survey/comment}
{js:2013/ajaxload_new}
<script>
$.globalData = {code}echo $formdata ? json_encode($formdata) : '{}';{/code};
</script>
<div class="comment-box">
	<div class="comment-title"><p>{$formdata['info'][0]['title']}</p></div>
	<ul class="comment-list" _id="{$_REQUEST['problem_id']}">
	</ul>
	<div class="page_size"></div>
</div>
<script type="text/x-jquery-tmpl" id="comment-tpl">
{{each option}}
    <li>
    <div class="comment-info">
        <img src="{{if option[$index]['member_info']}} {{= option[$index]['member_info']['avatar']['host']}}{{= option[$index]['member_info']['avatar']['dir']}}{{= option[$index]['member_info']['avatar']['filepath']}}{{= option[$index]['member_info']['avatar']['filename']}}{{else}} ${RESOURCE_URL}survey/avatar.jpg {{/if}}" />
		<span class="user-name">{{if option[$index]['member_info']}}{{= option[$index]['member_info']['member_name']}} {{else}}无昵称{{/if}}</span>

        <div style="display: -webkit-box;">
		{{if type == '3'}}
            {{each options}}
            <span class="user-comment">{{= options[$index]}}:<p style="color: #000;padding: 0 10px 0 5px;">{{= answer[$index]}}</p></span>
            {{/each}}
        {{else}}
            <span class="user-comment">{{= answer}}</span>
		{{/if}}
    </div> 
</li>
{{/each}}
</script>