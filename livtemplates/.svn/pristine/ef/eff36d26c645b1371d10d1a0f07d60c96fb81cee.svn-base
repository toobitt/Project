{template:head}
{css:ad_style}
{js:mms_default}
{js:input_file}
{js:message}
{js:vote}
{css:column_node}
{js:column_node}
{code}
$version_info = $version_info[0];
{/code}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap">
    <div class="ad_middle">
            <h2>应用版本变更信息</h2>
            <table  border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr><td class="text_indent" >应用名称：</td><td class="text_indent" >{$version_info['name']}</td><tr>
            	<tr><td class="text_indent" >当前期号：</td><td class="text_indent" >{$version_info['num']}</td><tr>
            	<tr><td class="text_indent" >当前版本：</td><td class="text_indent" >{$version_info['version']}</td><tr>
            	<tr><td class="text_indent" >创建时间：</td><td class="text_indent" >{$version_info['create_time']}</td><tr>
            	<tr><td class="text_indent" >更新时间：</td><td class="text_indent" >{$version_info['update_time']}</td><tr>
            	<tr><td class="text_indent" >描述：</td><td class="text_indent" ><textarea readonly>{$version_info['description']}</textarea></td><tr>
            	<tr><td class="text_indent" >数据库变动：</td><td class="text_indent" ><textarea readonly>{$version_info['db_update']}</textarea></td><tr>
            	<tr><td class="text_indent" >配置变动：</td><td class="text_indent" ><textarea readonly>{$version_info['config_update']}</textarea></td><tr>
            </table>
    </div>
    <div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}