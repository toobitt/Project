{template:head}
{css:ad_style}
{code}
$models = array('0'=>'咨询模型','1'=>'网站广告','2'=>'对联广告','3'=>'留言模型','4'=>'视频模型');
$dbinfo = $formdata['dbinfo'];
$mdb = $formdata['db_condition']['mdbinfo'];
$ldb = $formdata['db_condition']['ldbinfo'];
$serdb = serialize($dbinfo);
$id
{/code}

<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_web_site first"><em></em><a>来源配置</a></li>
			<li class=" dq"><em></em><a>内容设置</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}内容设置</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">内容主表: </span>
			{code}
				$attr_dbinfo = array(
					'class' => 'transcoding down_list',
					'show'  => 'dbinfo_show',
					'width' => 180,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'onclick' => 'change_mdb();'
				);
				if(0 == $mdb['mdb'])
				{
					$db = 0;
				}
				else
				{
					$db = $mdb['mdb'] ? $mdb['mdb'] : -1;
				}
				
			{/code}	
			{template:form/search_source,mdb,$db,$dbinfo,$attr_dbinfo}
			<div style="float:left;width:30px;">主键</div>
			<div id='field1' style="float:left;width:150px;">
				{if !$mdb}
					<select><option>-请选择-</option></select>
				{else}
					<select name='mdbkey'>
					{foreach $formdata['mdbcolumn'] as $k => $v}
						<option value="{$v}" {if $v == $mdb['mdbkey']}selected{/if}>{$v}</option>
					{/foreach}
					</select>
				{/if}
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">关联表: </span>
			{code}
				$attr_ldb = array(
					'class' => 'transcoding down_list',
					'show'  => 'ldbinfo_show',
					'width' => 180,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'onclick' => 'change_ldb();'
				);
				$db = $ldb['ldb'] ? $ldb['ldb'] : -1;
				
			{/code}	
			{template:form/search_source,ldb,$db,$dbinfo,$attr_ldb}		
			<div id='field3' style="float:left;width:150px;margin-left:30px;">
				{if !$ldb}
					<select><option>-请选择-</option></select>
				{else}
					<select name='ldblink'>
					{foreach $formdata['ldbcolumn'] as $k => $v}
						<option value="{$v}" {if $v == $ldb['ldblink']}selected{/if}>{$v}</option>
					{/foreach}
					</select>
				{/if}
			</div>
			<div id='field2' style="float:left;width:150px;">
				{if !$mdb}
					<select><option>-请选择-</option></select>
				{else}
					<select name='mdblink'>
					{foreach $formdata['mdbcolumn'] as $k => $v}
						<option value="{$v}" {if $v == $mdb['mdblink']}selected{/if}>{$v}</option>
					{/foreach}
					</select>
				{/if}
			</div>
			<div  style="float:left;width:30px;">主键</div>
			
			<div id='field4' style="float:left;width:150px;">
				{if !$ldb}
					<select><option>-请选择-</option></select>
				{else}
			
					<select name='ldbkey'>
					{$ldb['ldbkey']}
					{foreach $formdata['ldbcolumn'] as $k => $v}
						<option value="{$v}" {if $v == $ldb['ldbkey']}selected{/if}>{$v}</option>
					{/foreach}
					</select>
				{/if}
			</div>
		</div>
	</li>
</ul>
<script type="text/javascript">

function change_mdb()
{
	url = 'source_config.php?a=edited&id='+$('#id').val()+'&mdb='+$('#mdb').val();
	hg_request_to(url,'', 'get', 'showmdb', 1);
}
var showmdb = function (data)
{	
	$('#field1').html(get_html('mdbkey',data));
	$('#field2').html(get_html('mdblink',data));
}

function get_html(name,data)
{
	var html = '<select name='+name+' ><option>-请选择-</option>';
	for (var i=0; i<data.length; i++)
	{
		html = html + '<option>' +data[i] + '</option>';
	}
		html = html + '</select>';
	return html;
}

function change_ldb()
{
	url = 'source_config.php?a=edited&id='+$('#id').val()+'&mdb='+$('#ldb').val();
	hg_request_to(url,'', 'get', 'showldb', 1);
}
var showldb = function (data)
{
	$('#field3').html(get_html('ldblink',data));
	$('#field4').html(get_html('ldbkey',data));
}

</script>
<input type="hidden" name="a" value="edit_update" />
<input type="hidden" name="referto" value="javascript:history.go(-1);" class="button_6_14"/>
<input type="hidden" name="id" id="id" value={$_INPUT['id']} />
<input type="hidden" name="serdb" value='{$serdb}' />
<input type="hidden" name="html" value="ture"/>
<br>
<input type="submit" name="sub" value="确定" class="button_6_14"/>
<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version"><h2><a href="./source_config.php">返回前一页</a></h2></div>
</div>
{template:foot}