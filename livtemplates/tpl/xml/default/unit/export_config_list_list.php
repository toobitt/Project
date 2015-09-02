<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt">
		<a>
			<span class="m2o-item-bt">
				<span>{$v['title']}</span>
			</span>
		</a>
		<div class="detail-box">
			<span class="btn prev">预览模版</span>
			<div class="prev-box"></div>
			<ul>
				<li>
					<span class="config-title">已导出/总数:</span>
					<span class="config-info">45/90</span>
				</li>
				<li>
					<span class="config-title">关键字:</span>
					<span class="config-info">{if $v['key']}{$v['key']}{else}无{/if}</span>
				</li>
				<li>
					<span class="config-title">时间:</span>
					<span class="config-info">{if $v['start_time'] || $v['end_time']}{$v['start_time']} - {$v[end_time]}{else}无{/if}</span>
				</li>
				<li>
					<span class="config-title">权重:</span>
					<span class="config-info">{if $v['strat_weight'] || $v['end_weight']}{$v['start_weight']} - {$v[end_weight]}{else}无{/if}</span>
				</li>
				<li>
					<span class="config-title">添加人:</span>
					<span class="config-info">{if $v['add_user_name']}{$v['add_user_name']}{else}无{/if}</span>
				</li>
				<li>
					<span class="config-title">栏目:</span>
					<span class="config-info">{if $v['column_name']}{$v['column_name']}{else}无{/if}</span>
				</li>
				<li>
					<span class="config-title">分类:</span>
					<span class="config-info">{if $v['vod_sort_name']}{$v['vod_sort_name']}{else}无{/if}</span>
				</li>
				<li>
					<span class="config-title">模板名:</span>
					<span class="config-info">{if $v['xml_name']}{$v['xml_name']}{else}无{/if}</span>
				</li>
				<li>
					<span class="config-title">是否需要文件:</span>
					<span class="config-info">{if $v['need_file']}是{else}否{/if}</span>
				</li>
								<li>
					<span class="config-title">每次导出:</span>
					<span class="config-info">{if $v['file_num']}{$v['file_num']}{else}无{/if}</span>
				</li>
			</ul>
		</div>
	</div>
	{if $v['end_time']}
	<div class="m2o-item export-progress w200">
		<div class="box">
			<span class="progress" style="width:{$v['percent']}%"></span>
		</div>
		<span class="precent">{$v['percent']}%</span>
	</div>
	{/if}
    <div class="common-list-item m2o-switch" _status="{$v['is_default']}" style="position:relative;">
    		<div class="common-switch {if $v['is_default']}common-switch-on{/if}" style="bottom:0px;">
           		<div class="switch-item switch-left" data-number="0"></div>
           		<div class="switch-slide"></div>
           		<div class="switch-item switch-right" data-number="100"></div>
        	</div>
    </div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn"></div>
</div>
