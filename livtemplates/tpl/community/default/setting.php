{template:./head}
{js:qingao/jquery.upload}
{js:qingao/qingao_setting}
{css:manage}
<div class="gong_main">
	<div class="g_main_box">
		<div class="g_main_box_l">
			<ul>
				<li><a style="background: #fd8c02; color: #fff;">设置</a></li>
				<li><a href="manage.php?a=member&group_id={$group_id}">成员</a></li>
				<li><a href="manage.php?a=notice&group_id={$group_id}">公告</a></li>
			</ul>
		</div>
		<div class="g_main_box_r">
			<a href="group.php?group_id={$group_id}">返回讨论区首页</a>
		</div>
	</div>
	<div class="g_main_con">
		<div class="g_main_con_set">
			<div class="g_main_left_line">
				<div class="g_main_right_line">
				<form method="post" name="settingForm">
					<div class="cy_set_jiben">
						<h2>基本设置</h2>
						<label>圈子名称：<input type="text" name="group_name" value="{$group['name']}" class="cy_set_title" /></label>
						<label>圈子域名：<input type="text" name="domain" value="{$group['group_domain']}" class="cy_set_title" /></label>
						<label>圈子标签：<input type="text" name="tags" value="{foreach $group['tag'] as $k=>$v}{if $k==0}{$v['mark_name']}{else},{$v['mark_name']}{/if}{/foreach}" class="cy_set_title" /></label>
						<label><span>圈子宣言：</span> <textarea class="cy_set_miaoshu" name="description">{$group['description']}</textarea></label>
						<label>设置讨论区首页帖子数目：<select name="showNum" class="cy_set_select">
								<option value="20" {if $group['thread_list'] == 20}selected="selected"{/if}>20</option>
								<option value="30" {if $group['thread_list'] == 30}selected="selected"{/if}>30</option>
								<option value="50" {if $group['thread_list'] == 50}selected="selected"{/if}>50</option>
								<option value="100" {if $group['thread_list'] == 100}selected="selected"{/if}>100</option>
						</select> </label>
					</div>

					<div class="cy_set_img">
						<h2>圈子顶图</h2>
						<p>
							<span>当前图片：</span><img src="{if is_string($group['background'])}{$group['background']}{else}{$group['background']['host']}{$group['background']['dir']}333x222/{$group['background']['filepath']}{$group['background']['filename']}{/if}" id="background_img" />
						</p>
						<label>上传图片：<input type="button" id="upload_background" value="上传顶图" />[支持JPG文件格式，最大2M]</label>
					</div>

					<div class="cy_set_img">
						<h2>圈子头像</h2>
						<p>
							<span>当前头像：</span><img src="{if is_string($group['logo'])}{$group['logo']}{else}{$group['logo']['host']}{$group['logo']['dir']}50x50/{$group['logo']['filepath']}{$group['logo']['filename']}{/if}" id="logo_img" />
						</p>
						<label>上传图片：<input type="button" id="upload_logo" value="上传头像" />[支持JPG文件格式，最大2M]</label>
					</div>
					<div class="cy_set_quanxian">
						<h2>成员权限</h2>
						{foreach $group_permission_arr as $k=>$v}
						<p><input name="permission[]" type="checkbox" value="{$k}"{if $group['permission']&$k} checked="checked"{/if} /><span>{$v}</span></p>
						{/foreach}
					</div>

					<div class="cy_set_admin_qx">
						<h2>管理员权限</h2>
						{foreach $manage_permission_arr as $k=>$v}
						<p><input name="permission[]" type="checkbox" value="{$k}"{if $group['permission']&$k} checked="checked"{/if} /><span>{$v}</span></p>
						{/foreach}
					</div>

					<div class="cy_set_Replies">
						<h2>
							回帖设置<span>(防灌水)</span>
						</h2>
						<label>每个成员对同一帖一回帖时间间隔为&nbsp;<input type="text" name="per_add_time" class="cy_set_select" value="{$group['per_add_time']}" />秒</label>
					</div>

					<div class="cy_set_Replies">
						<h2>回收站设置</h2>
						<label>回收站中的帖子自动清除时间间隔为&nbsp;<input type="text" name="auto_delete_time" class="cy_set_select" value="{$group['auto_delete_time']}" />小时</label>
					</div>

					<div class="cy_set_submit">
						<input type="submit" name="settingBtn" value="保存" />&nbsp;<input type="reset"
							value="取消" />
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
{template:./footer}