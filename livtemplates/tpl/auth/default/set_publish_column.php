<?php
/* $Id: fast_publish.php 12269 2012-09-21 05:47:41Z zhuld $ */
?>
<?php 
$item = $formdata;
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
}
$column = new column();
$publish = array();
$publish['sites'] = $column->getallsites();
$publish['items'] = $column->getAuthoredColumns();
$publish['selected_ids'] = $item['column_id'] ? $item['column_id'] : '';
$publish['selected_items'] = $column->get_selected_column_path($publish['selected_ids']);
$publish['default_site'] = each($publish['sites']);
$publish['pub_time'] = $item['pub_time'];

$hg_print_selected = array();
foreach ($publish['selected_items'] as $index => $item) {
	$hg_print_selected[$index] = array();
	$current = &$hg_print_selected[$index];
	$current['showName'] = '';
	foreach ($item as $sub_item) {
		if($sub_item['is_auth'])
		{
			$current['is_auth'] = 1;
		}
		$current['id'] = $sub_item['id'];
		$current['name'] = $sub_item['name'];
		$current['showName'] .= $sub_item['name'] . ' > ';
		$current['siteid'] = $sub_item['site_id'];
	}
	$current['showName'] = substr($current['showName'], 0, -3);
	$selected_names[] = $current['name'];
}
$publish['selected_items'] = $hg_print_selected;
$publish['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';
$publish['siteid'] = explode(',', $formdata['site_id']);
$selected_site_names = array();
foreach ($publish['sites'] as $id => $name) {
	if ( in_array($id, $publish['siteid']) ) {
		$selected_site_names[] = $name;
	}
}
$selected_site_names = $selected_site_names ? implode(',', $selected_site_names) : '无选择的';
?>
<form name="recommendform" id="recommendform" action="run.php" method="post" class="form" onsubmit="return hg_ajax_submit('recommendform');">
    <div style="margin-bottom: 10px;">
        {template:unit/publish, 1, $formdata['column_id']}
        <div class="publish-site-mulitselect">
	        <div class="publish-site" style="left:393px;top:61px;font-size:14px;">
	        	<div class="publish-site-current overflow" style="max-width:165px;cursor:pointer;">{$selected_site_names}</div>
	        	<ul style="top:30px;">
					 {foreach $publish['sites'] as $key => $each_site}
					 <li class="publish-site-item"  _siteid="{$key}" _name="{$each_site}">
					 	<label><input type="checkbox" {if in_array($key, $publish['siteid'])}checked{/if} />{$each_site}</label>
					 </li>
					 {/foreach}
				</ul>		
	        </div>
        </div>
    </div>
    <input type="hidden" name="a" value="update_publish_column" />
    <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
    <input type="hidden" name="admin_id" value="{$_INPUT['id']}" />
    <input type="hidden" name="siteid" value="{$formdata['site_id']}" id="hiddenSiteid" />
    <span class="label">&nbsp;</span><input type="submit" name="rsub" value="设置允许发布栏目" class="button_12" />
    <script>
	jQuery(function($){
	    var timeid = setInterval(function(){
	        if($.fn.hg_publish){
	        	clearInterval(timeid);
	            var publish = $('.common-hg-publish').hg_publish({
	            	maxColumn: 3
	            }).data('publish');
	            /*重写一些方法*/
	            $.extend(publish, {
	            	hiddenSiteId: $('#hiddenSiteid'),
	            	saveResult: function() {
	            		this.constructor.prototype.saveResult.call(this);
	            		this.hiddenSiteId.val( select.find('.publish-site-current').attr('_siteid') );
	            	},
	            	syncResult: function() {
	            		var ids = select.find('.publish-site-current').attr('_siteid');
	            		if (!ids) {
	            			this.saveResult();
	            			return;
	            		}
	            		ids = ids.split(',');
	            		var selector = ids.map(function(id) { return 'li[_siteid="'+ id +'"]'; }).join(',');
	            		this.result.find(selector).remove();
	            		if ( this.result.find('li').size() == 0 ) {
							this.result.closest('.publish-result').addClass('empty');
						}
						this.syncSelected();
						this.saveResult();
	            	},
	            	addResult: function() {
	            		this.constructor.prototype.addResult.apply(this, arguments);
	            		var id = this.siteid;
	            		var input = select.find('li[_siteid="'+ id +'"] input');
	            		if ( input.prop('checked') ) {
	            			input.trigger('click');
	            		}
	            	}
	            });
	            var select = $('.publish-site-mulitselect');
	            select.on('click', '.publish-site-current', function() {
	            	select.find('ul').toggle();
	            }).on('change', function() {
	            	var data = select.find('input').filter(function() {
	            		return $(this).prop('checked');
	            	}).map(function() {
	            		var li = $(this).closest('li');
	            		return { id: li.attr('_siteid'), name: li.attr('_name') };
	            	}).get();
	            	var text = data.length ? data.map(function(v) { return v.name; }).join(',') : '无选择的';
	            	var ids = data.length ? data.map(function(v) { return v.id; }).join(',') : '';
	            	select.find('.publish-site-current').text(text).attr('title', text).attr('_siteid', ids);
	            	publish.syncResult();
	            });
	        }
	    }, 100);
	});
	</script>
</form>