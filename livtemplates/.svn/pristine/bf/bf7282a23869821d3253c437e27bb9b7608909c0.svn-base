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
{css:column_node}
{js:column_node}
<script type="text/javascript">
//gBatchAction['delete'] = './run.php?mid=' + gMid + '&a=delete&infrm=1&ajax=1';
function hg_bill_record_access(id)
{
	if(id)
	{
		var url = './run.php?mid=' + gMid + '&a=reaccess&bill_id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
	else
	{
		hg_disable_action("无ID");
	}
}
function hg_call_bill_record_access(json)
{
	//console.log(json);
	var jsonobj=eval('(' + json + ')');
	$("#cost").val(jsonobj.total);
	$("#cost_value").val(jsonobj.total);
	hg_close_opration_info();
}

function hg_check_content()
{
	if($('#auditor_id').val() > 0 && parseInt($('input[name="state"]:checked').val()))
	{
	//	console.log($('input[name="state"]:checked').val());
	//	console.log($('#auditor_id').val());
		if(confirm('审核通过并且选择审核人之后当前报销单会被锁定，谨慎！！！'))
		{
			return true;	
		}
		else
		{
			return false;
		}
	}
	else
	{
		return true;
	}	
}

function hg_disable_action(str)
{
	jAlert(str);
}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post" id="content_form" onsubmit="return hg_check_content()">
<h2>{$optext}信息</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">所属项目：</span>
        {code}
			$server_source = array(
				'class' => 'down_list',
				'show' => 'server_show',
				'width' => 100,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				'is_sub'=>1,
			);
			$default = $project_id ? $project_id : -1;
			$server_item[$default] = '--选择--';
			foreach($project_info as $k =>$v)
			{
				$server_item[$v['id']] = $v['name'];
			}
		{/code}
		{template:form/search_source,project_id,$default,$server_item,$server_source}
    </div>
    <div class="form_ul_div clear">
        <span class="title">备注：</span><textarea class="" name="cause"  placeholder="想说点什么">{$cause}</textarea>
    </div>
    <div class="form_ul_div clear">
        <span class="title">预支费用：</span><input type="text" value='{code}echo $advice?$advice:0;{/code}' name='advice' class="site_title">￥
    </div>
    <div class="form_ul_div clear">
        <span class="title">已花费用：</span><input id="cost_value" disabled="disabled" type="text" value='{code} echo $cost?$cost:0; {/code}' name='cost_value' class="site_title"><input id="cost" type="hidden" value='{code} echo $cost?$cost:0; {/code}' name='cost' class="site_title">￥ <a style="" href="javascript:void(0);" onclick="hg_bill_record_access({$id});">重新统计</a>
    </div>
    <div class="form_ul_div clear">
        <span class="title">出差时间：</span><input type="text" name="business_time" id="business_time" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm'});" size="14" autocomplete="off" style="width:180px;" placeholder="出差时间" value="{code} echo $business_time?date("Y-m-d H:i",$business_time):'' {/code}">
    </div>
    <div class="form_ul_div clear">
        <span class="title">结束时间：</span><input type="text" name="back_time" id="back_time" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm'});" size="14" autocomplete="off" style="width:180px;" placeholder="结束时间" value="{code} echo $back_time?date("Y-m-d H:i",$back_time):'' {/code}">
    </div>
    <div class="form_ul_div clear">
        <span class="title">报销时间：</span><input type="text" name="baoxiao_time" id="baoxiao_time" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm'});" size="14" autocomplete="off" style="width:180px;" placeholder="报销时间" value="{code} echo $baoxiao_time?date("Y-m-d H:i",$baoxiao_time):'' {/code}">
    </div><div class="form_ul_div clear">
		<span class="title">审核：</span>
		<div style="display:inline-block;width:255px">
			<label><input type="radio" name="state" value="1" {if $state}checked="checked"{/if} class="n-h"><span>是</span></label>
			<label><input type="radio" name="state" value="0" {if !$state}checked="checked"{/if} class="n-h"><span>否</span></label>
		</div>
		<!--<font class="important">审核</font>-->
	</div>
	<div class="form_ul_div clear">
		<span class="title">审批人：</span>
		{code}
			$server_source = array(
				'class' => 'down_list',
				'show' => 'auditor_show',
				'width' => 300,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				'is_sub'=>1,
			);
			$default = $auditor_id ? $auditor_id : -1;
			$auditor_item[$default] = '--选择--';
			foreach($auditor_info as $k =>$v)
			{
				if($v['info'])
				{	
					
					foreach($v['info'] as $kk => $vv)
					{
						$auditor_item[$v['id']] .= $space . $vv['user_name'];
						$space = $vv['audit_level'] ? '->' : '+' ;
					}		
				}				
			}
		{/code}
		{template:form/search_source,auditor_id,$default,$auditor_item,$server_source}
		<!--<font class="important">审核</font>-->
	</div>   
</li></ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
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