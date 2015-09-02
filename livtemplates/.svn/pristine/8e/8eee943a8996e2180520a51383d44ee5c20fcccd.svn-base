<?php
$count = count( $formdata['names'] );
$row = $count + 1;
//print_r( $formdata );
?>
{css:bootstrap/3.3.0/bootstrap.min}
{css:feedback_print}
{js:jquery.min}
{js:jquery.tmpl.min}
{js:feedback/feedback_print}

<style type="text/css">
	@media print{
    .table-box .gray-item, table .gray-each{display:none; }
    .table-box tfoot{display:none; }
	.tdCheck{display:none; }
	.next, .nav-btn{display:none; }
  }
</style>


<div class="inner-wrap container">
	<div class="nav-btn btn btn-print">打印</div>
	<div class="table-box">
		<table class="table">
			  <caption>{$formdata['title']}</caption>
			  <thead>
			    <tr>
			    	<th class="tdCheck">&nbsp;</th>
				 {foreach $formdata['names'] as $k => $v}	
			   		<th class="m2o-{$k}" title="{$v}">
			   			<label for="checkbox{$k}">{$v}</label>
			   			<input class="m2o-toggle tdCheck" id="checkbox{$k}" type="checkbox" name="{$k}" checked="" />
			   		</th>
			   	{/foreach}
			    </tr>
			  </thead>
			  <tbody>
			  	{if is_array( $formdata['data'] ) && count( $formdata['data'] ) > 0 }
					{foreach $formdata['data'] as $k => $v}	 
						<tr class="m2o-each">
								<td class="tdCheck">
									<input type="checkbox" value="{$k}" title="{$k}" class="m2o-check" checked="" ></td>
							{foreach $formdata['names'] as $kk=> $vv}	
								{code}
									$each = $v[$kk];
									if( is_array( $each ) ){
										$img = $each[0] && $each[0]['filename'] ? hg_fetchimgurl( $each[0], 72, 53 ) : '';
										$imgItem = true;
									}else{
										$imgItem = false;
									}
								{/code}
								{if $imgItem}
									<td class="m2o-{$kk}">
										{if $img}
										{/if}
										<img src="{$img}" />
									</td>
								{else}
									<td class="m2o-{$kk}" title="{$each}">{$each}</td>
								{/if}
							 {/foreach}
						</tr>
					{/foreach}
				{else}
					<tr class="common-list-empty">
						<td class="colspan" colspan="{$row}">没有你要找的内容！</td>
					</tr>
				{/if}
			  </tbody>
			  {if is_array( $formdata['data'] ) && count( $formdata['data'] ) > 0 }
			  <tfoot>
	  			<tr>
	  				<td colspan="{$row}" class="footer">
		    			<input type="checkbox" id="checkboxAll" title="全选" class="checkAll" checked="" />
		    			<label for="checkboxAll">全选</label>
	  				</td>
				</tr>
			  </tfoot>
			  {/if}
		</table>
		{if $formdata['page_info']['is_next_page'] > 0}
		<div class="next">点击加载更多</div>
		{/if}
	</div>
</div>
<script type="text/x-jquery-tmpl" id="add-print-tpl">
	{{each list}}
		<tr class="m2o-each">
			<td class="tdCheck">
				<input type="checkbox" value="{{= $k}}" title="{{= $k}}" class="m2o-check" checked="" />
			</td>
			{{each($kk, $vv) names}}
				{{if $value[ $kk ] && $value[ $kk ]['img']}}
				<td class="m2o-{{= $kk}}">
					<img src="{{= $value[ $kk ]['img']}}" />
				</td>
				{{else}}
				<td class="m2o-{{= $kk}}" title="{{= $value[ $kk ]}}">{{= $value[ $kk ]}}</td>
				{{/if}}
			{{/each}}
		</tr>
   {{/each}}
</script>
<script type="text/javascript">
	$.globalSearch = {
		names : {code} echo $formdata['names'] ? json_encode( $formdata['names'] ) : '{}' {/code},
		mid : {code} echo json_encode( $_INPUT['mid'] ) {/code},
		fid : {code} echo json_encode( $_INPUT['fid'] ) {/code},
		page : {code} echo $_INPUT['page'] ? json_encode( $_INPUT['page'] ) : 1 {/code}
	}
</script>