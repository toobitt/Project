{template:head}
{css:ad_style}
{css:column_node}
<script type="text/javascript">

</script>
{code}

$show_field = $content_detail[0]['show_field'];
$expand = $content_detail[0]['expand'];
$child_data = $content_detail[0]['child_data'];

{/code}

<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l">
				<h2>内容详情</h2>
				
			{foreach $expand as $k=>$v}
				{foreach $child_data as $k2=>$v2}
					<a href="?mid={$_INPUT['mid']}&infrm={$_INPUT['infrm']}&fieldid={$v2['id']}&expandid={$v['id']}">{$v2['title']}</a>
				{/foreach}
				
					<ul class="form_ul">
						{foreach $show_field as $k1=>$v1}	
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">{$v1['title']}:</span>
								&nbsp;
								{code}
								if($v1['type']=='text')
								{
									echo $v[$v1['field']];
								}
								else if($v1['type']=='img')
								{
									echo "<img src='http://photo1.9you.com/pic/userphoto/64/65/2037236465/cjod1225970443.jpg' width='100px'>";
//									echo "<img src='".$v[$v1['field']].".jpg'>";
								}
								{/code} 
							</div>
						</li>
						{/foreach}
					</ul>
			{/foreach}
				<input type="hidden" name="a" value="create_update" />
				<input type="hidden" name="site_id" value="{$site_form[0]['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				
				<div class="bottom clear">
		               {$pagelink}
		        </div>
				
			</form>
		</div>
	<div class="right_version"><h2><a href="javascript:history.go(-1)">返回前一页</a></h2></div>
	</div>
{template:foot}
