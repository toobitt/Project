{template:head}
{code}
    if($id)
    {
        $optext="更新";
        $ac="update";
    }
    else
    {
        $optext="新增";
        $ac="create";
    }
{/code}
{if is_array($formdata)}
    {foreach $formdata as $key => $value}
        {code}
            $$key = $value; 
        {/code}
    {/foreach}
{/if}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}百度收录配置</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">名称：</span><input type="text" value='{$title}' name='title' class="title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">站点：</span>
        {code}
            $attr_date = array(
                'class' => 'down_list data_time',
                'show' => 'app_show',
                'width' => 104,/*列表宽度*/     
                'state' => 0, /*0--正常数据选择列表，1--日期选择*/
            );      
            $site_id = $site_id ? $site_id : 0;
            $default = 0;
            $hg_sites = $hg_sites[0];  
            $hg_sites[$default] = '请选择站点';
        {/code}
        {template:form/search_source,site_id,$site_id,$hg_sites,$attr_date}
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">EMAIL：</span><input type="text" value='{$email}' name='email' class="title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">收录周期：</span><input type="text" value='{$update_peri}' name='update_peri' class="title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">收录条数：</span><input type="text" value='{$number_include}' name='number_include' class="title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">XML文件路径：</span><input type="text" value='{$videoop_xml_dir}' name='videoop_xml_dir' class="title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">XML文件名称：</span><input type="text" value='{$videoop_xml_filename}' name='videoop_xml_filename' class="title">
    </div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}百度收录配置" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}