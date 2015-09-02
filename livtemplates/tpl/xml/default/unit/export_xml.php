<!-- 导出xml模版信息 -->
<div class="export-file-box">
		<div class="export-file-content">
			<div class="export-file-head">
				<span>导出xml</span>
				<span class="export-file-close"></span>
			</div>
			<div class="export-file-item export-file-title">
				<span class="label">模版名</span>
				<ul class="file-list">
				{foreach $xml[0] as $k => $v}
					<li _id="{$v['id']}">
						<input type="radio" name="export_file_name" {if $k==0}checked{/if}/>
						<span>{$v['title']}</span>
					</li>
				{/foreach}	
				</ul>
			</div>
			<div class="export-file-item export-file-need">
				<input type="checkbox" name="is_need_file" checked/>
				<span>是否需要文件</span>
			</div>
			<div class="export-file-control">
				<span><input type="button" class="export-btn" value="确定"></span>
				<div class="exporting">正在导出,请稍等...</div>
			</div>
		</div>
</div>
<!-- end -->