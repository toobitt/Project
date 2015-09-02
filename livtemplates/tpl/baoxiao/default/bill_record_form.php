{template:head}
{code}
    if($id)
    {
        $optext="更新";
        $ac="update";
    }
    else
    {
        $optext="增加";
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
{css:column_node}
{js:column_node}
<script type="text/javascript">
//gBatchAction['delete'] = './run.php?mid=' + gMid + '&a=delete&infrm=1&ajax=1';
function hg_bill_record_access(id)
{
	if(id)
	{
		var url = './run.php?mid=' + gMid + '&a=reaccess&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
	else
	{
		hg_disable_action("无ID");
	}
}
function hg_call_bill_record_access(data)
{
	console.log($data);
	/*
	data = data.replace(/'/g, "");
	var ids = data.split(",");
	for(i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).slideUp(1000).remove();
	}
	if($("#checkall").attr('checked'))
	{
		$("#checkall").removeAttr('checked');
	}
	*/
	hg_close_opration_info();
}
function hg_disable_action(str)
{
	jAlert(str);
}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}一条</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">分类：</span>
        {code}
			$server_source = array(
				'class' => 'down_list',
				'show' => 'server_show',
				'width' => 100,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				'is_sub'=>1,
			);
			$default = $sort_id ? $sort_id : -1;
			$server_item[$default] = '--选择--';
			foreach($sort_info as $k =>$v)
			{
				$server_item[$v['id']] = $v['name'];
			}
		{/code}
		{template:form/search_source,sort_id,$default,$server_item,$server_source}
    </div>
    <div class="form_ul_div clear">
        <span class="title">备注：</span><textarea class="" name="remark"  placeholder="想说点什么">{$remark}</textarea>
    </div>
    <div class="form_ul_div clear">
        <span class="title">费用：</span><input type="text" value='{code}echo $cost?$cost:0;{/code}' name='cost' class="site_title">￥----{$cost_capital}
    </div>
	<div class="form_ul_div clear">
        <span class="title">消费时间：</span><input type="text" name="cost_time" id="cost_time" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm'});" size="14" autocomplete="off" style="width:180px;" placeholder="什么时候花的？" value="{code} echo $cost_time?date("Y-m-d H:i",$cost_time):'' {/code}">
    </div>
    <div class="form_ul_div clear">
        <span class="title">是否发票：</span>
		<div style="display:inline-block;width:255px">
			<label><input type="radio" name="is_ticket" value="1" {if $is_ticket}checked="checked"{/if} class="n-h"><span>是</span></label>
			<label><input type="radio" name="is_ticket" value="0" {if !$is_ticket}checked="checked"{/if} class="n-h"><span>否</span></label>
		</div>
    </div>
	<div class="form_ul_div clear">
		<span class="title">凭据：</span>
		<span class="file_input s" style="float:left;">选择文件</span>
		<span style="margin-left: 50px;">
			{if $img_url}<img width="80" height="80" src="{$img_url}" />{/if}
		</span>
		<input name="indexpic" type="file" value="" style="width:85px;position: relative;left: -142px;opacity: 0;cursor: pointer;" />
	</div>
    <div class="form_ul_div">
		<span class="title">审核：</span>
		<div style="display:inline-block;width:255px">
			<label><input type="radio" name="state" value="1" {if $state}checked="checked"{/if} class="n-h"><span>是</span></label>
			<label><input type="radio" name="state" value="0" {if !$state}checked="checked"{/if} class="n-h"><span>否</span></label>
		</div>
		<!--<font class="important">审核</font>-->
	</div>    
</li></ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="bill_id" value="{$_INPUT['bill_id']}"/>
<input type="hidden" name="html" value="1" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<script>
jQuery(function($){});
	</script>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}