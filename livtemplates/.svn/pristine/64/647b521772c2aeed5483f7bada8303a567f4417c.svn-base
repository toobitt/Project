{css:common/common_publish}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_cache}
{js:common/publish}
{code}
if (!isset($publish)) {
	$publish = array(
		'selected_items' => array(),
		'selected_ids' => array(),
		'selected_names' => array(),
		'pub_time' => '',
		'default_site' => array(),
		'sites' => array(),
		'items' => array()
	);
}
{/code}
<style>
.common-hg-publish .publish-result ul, .common-hg-publish .publish-result-empty{height:190px;}
</style>
<div class="publish-box common-hg-publish" id="publish-box-{$hg_name}">
	<div class="publish-result {if count($publish['selected_items']) == 0}empty{/if}" >
		<p class="publish-result-title" _title="发布">发布至：</p>
		<ul>
			{foreach $publish['selected_items'] as $item}
			<li _id="{$item['id']}" _name="{$item['name']}" data-auth="{$item['is_auth']}" _siteid="{$item['siteid']}" title="{$item['show_name']}">
				<input type="checkbox" checked="checked" class="publish-checkbox" {if !$item['is_auth']}style="visibility: hidden;"{/if}/>
				<span>{$item['showName']}</span>
			</li>
			{/foreach}
		</ul>
		<div class="publish-result-empty">显示已选择的栏目</div>
		<div class="extend-item" style="margin-top:5px;">
			<label style="margin-left:-10px;">发布时间：</label><input style="width:113px;height:18px;margin:0;" name="pub_time" value="{$publish['pub_time']}" class="date-picker" _time=true/>
		</div>
	</div>
	
	<div class="publish-site">
		<div class="publish-site-current" _siteid="{$publish['default_site']['key']}">
			{$publish['default_site']['value']}
		</div>
		<span class="publish-site-qiehuan">切换</span>
		<ul>
			 {foreach $publish['sites'] as $key => $each_site}
			 <li class="publish-site-item {if $key == $publish['default_site']['key']}publish-site-select{/if}" _siteid="{$key}" _name="{$each_site}">
			 	<label><input type="radio" name="publish-sites-{$hg_name}" {if $key == $publish['default_site']['key']}checked="checked"{/if} />
			 	{$each_site}</label>
			 </li>
			 {/foreach}
		</ul>
	</div>
	 
	<div class="publish-list">
		<div class="publish-inner-list">
			{if $publish['items']}
			<div class="publish-each">
				<ul>
					{foreach $publish['items'] as $kk => $vv}
					<li _id="{$vv['id']}" title="{$vv['name']}" _name="{$vv['name']}" class="one-column {if $vv['is_last']}no-child{/if}">
						<input type="checkbox" class="publish-checkbox" {if $vv['is_auth']==2}style="visibility:hidden;"{/if}/>
						<span class="publish-name">{$vv['name']}</span>
						<span class="publish-child">&gt;</span>
					</li>
					{/foreach}
				</ul>
			</div>
			{/if}
		</div>
	</div>
	
	<input type="hidden" class="publish-hidden" name="column_id" value="{$publish['selected_ids']}" />
	<input type="hidden" class="publish-name-hidden" name="column_name" value="{$publish['selected_names']}" />

</div>