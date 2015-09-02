{template:head}
{css:ad_style}
{css:column_node}
<script type="text/javascript">

</script>
{code}
$show_field = $formdata['show_field'];
$expand = $formdata['expand'];
$child_data = $formdata['child_data'];
print_r($formdata);
{/code}

<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l">
				<h2>内容详情</h2>
				
				{foreach $child_data as $k=>$v}
					<a href="?mid={$_INPUT['mid']}&a=getdetail&infrm={$_INPUT['infrm']}&fieldid={$v['id']}&expandid={$expand['id']}">{$v['title']}</a>
				{/foreach}
				
				<div id="basic_info" >
					<ul class="form_ul">
					{foreach $expand as $k=>$v}
						{foreach $show_field as $k1=>$v1}	
						{if $v1['type']=='text'}
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">{$v1['title']}:</span>
								{$v[$v1['field']]}
							</div>
						</li>
						{/if}
						{/foreach}
					{/foreach}
					</ul>
					{foreach $expand as $k=>$v}
					{foreach $show_field as $k1=>$v1}
							{if $v1['type']=='img'}
								{$v1['title']}:<img src="{$v[$v1['field']]}">
							{/if}
					{/foreach}
					{/foreach}
				</div>
				<input type="hidden" name="a" value="create_update" />
				<input type="hidden" name="site_id" value="{$site_form[0]['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}
