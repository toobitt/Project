{template:head}
{code}
    $opname = "数据";
    $ac = $_INPUT['ac'];
    if($ac=='update')
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
<h2>{$optext}{$opname}</h2>
<div id="test">

</div>

<ul class="form_ul">
{code}
    $item_source = array(
        'class' => 'down_list',
        'show' => 'item_show',
        'width' => 100,/*列表宽度*/     
        'state' => 0, /*0--正常数据选择列表，1--日期选择*/
        'is_sub'=>1,
    );
    $default = $group_id ? $group_id : -1;
    $group_data[$default] = '选择分类';
    foreach($group as $k =>$v)
    {
        $group_data[$v['id']] = $v['title'];
    }
{/code}


<li class="i" id="datainput">
    <div class="form_ul_div clear">
        <span class="title" >省份</span>
        <select name="province_id">
            <?php foreach($provinces as $province):?>
            <option value="{$province['id']}">{$province['name']}</option>
            <?php endforeach;?>
        </select>
    </div>
</li>

<li class="i" id="datainput">
    <div class="form_ul_div clear">
        <span class="title" >配送方式</span>
        <select name="delivery_category_id">
            <?php foreach($deliveries as $delivery):?>
            <option value="{$delivery['id']}">{$delivery['title']}</option>
            <?php endforeach;?>
        </select>
    </div>
</li>
<li class="i" id="datainput">
    <div class="form_ul_div clear">
        <span class="title" >运费:</span>
        <input type="text" name="delivery_fee" value="{$delivery_fee}"/>
    </div>
</li>


</ul>
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}{$opname}" class="button_6_14"/>
<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}
