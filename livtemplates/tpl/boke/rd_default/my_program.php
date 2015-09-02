<?php
/* $Id: my_program.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
{template:head}
<div class="main_div">
	<div class="right_window">
		<h3><a href="javascript:void(0);" onclick="add_program(0,0,0)">添加</a>
		<?php echo $station['web_station_name']?$station['web_station_name']."的节目单":"频道未创建";	?></h3>
			<div class="show_info">
				<div class="program_show">
					<ul  class="program_list" id="program_list">
					{code}
					$start_time=0;
					{/code}
					{if !is_array($program_info)}
						<li id="default_list">默认分类</li>
					{else}
						{code}
						$count = count($program_info);
						$i = 1;
						{/code}
						{foreach $program_info as $key =>$value}
							{code}
								$start_time = $value['end_time'];
								$p_id = $value['id'];
							{/code}
								<li id="p_show_{$value['id']}" onmouseover="edit_del({$value['id']},1);" onmouseout="edit_del({$value['id']},0);">
									<div class="program_time">
									<img src="<?php echo RESOURCE_DIR;?>img/play_default.png"/><?php echo hg_toff_time($value['start_time'],$value['end_time']);?>
									</div>
									<div class="program_name"><a target="_blank" title="{$value['programe_name']}" id="program_name_{$value['id']}"href="<?php echo hg_build_link('station_play.php',  array('sta_id'=>$value['sta_id'],'user_id'=>$value['user_id']));?>"><?php echo hg_cutchars($value['programe_name'],10," ");?></a></div>
									<div class="program_manage" id="p_{$value['id']}">
										<a href="javascript:void(0);" onclick="program({$sta_id},{$value['id']},1);">编辑</a>
										<a href="javascript:void(0);" onclick="program({$sta_id},{$value['id']},2);">删除</a>
										{if $i!=1}
											<a href="javascript:void(0);" onclick="program({$sta_id},{$value['id']},3);">上移</a>
										{/if}
										{if $i!=$count}
											<a href="javascript:void(0);" onclick="program({$sta_id},{$value['id']},4);">下移</a>
										{/if}
									</div>
								</li>
								<li id="p_edit_{$value['id']}" style="display:none">
									<ul>
										<li><input id="p_name_{$value['id']}" type="text" value="{$value['programe_name']}"/><span id="p_name_tip_{$value['id']}" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>
										<li><textarea id="p_brief_{$value['id']}" rows="3" cols="21">{$value['brief']}</textarea></li>
										<li><input type="button" value="提交" id="eidt_bt_{$value['id']}"/><input type="button" value="取消" onclick="program_back({$sta_id},{$value['id']})"/></li>
									</ul>
								</li>
								{code}
								$i++;
								{/code}
						{/foreach}
					{/if}
					</ul>
					
				<input type="hidden" id="start_time" value="{$start_time}"/>
				<input type="hidden" id="p_id" value="{$p_id}"/>
				</div>
				
			</div>
			<img src="<?php echo RESOURCE_DIR?>img/right_window_bottom.gif" class="for_ie" />
	</div>
	{template:unit/my_right_menu}
	</div>
{template:unit/add_program}
{template:foot}