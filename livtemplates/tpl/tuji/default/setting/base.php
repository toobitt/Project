<ul class="form_ul">
<!--  
	<li class="i">
		<div class="form_ul_div">
			<span  class="title long_title">flash上传图片类型：</span>
			<input type="text" value="{$settings['base']['flash_image_type']}" 	name='base[flash_image_type]' 	style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	-->

    <!--
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">&nbsp;&nbsp;&nbsp;高级搜索：</span>
            {foreach $settings['base']['search_condition'] as $k => $v}
                <label><input type="checkbox"  value="{$v['key']}" name="base[used_search_condition][]" {if in_array($v['key'], $settings['base']['used_search_condition'])}checked="checked"{/if}/>{$v['name']}</label>
            {/foreach}
        </div>
    </li>
     -->
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">图片描述优先级：</span>
						默认使用图集描述<input type="radio" value=0 name='define[DESCRIPTION_TYPE]' {if !$settings['define']['DESCRIPTION_TYPE']}checked=checked{/if}/>
			默认图片名<input type="radio" value=1 name='define[DESCRIPTION_TYPE]' {if $settings['define']['DESCRIPTION_TYPE']==1}checked=checked{/if}/>
			人工编辑<input type="radio" value=2 name='define[DESCRIPTION_TYPE]' {if $settings['define']['DESCRIPTION_TYPE']==2}checked=checked{/if}/>
		<font class="important" style="color:red">默认使用图集描述选项为后台上传图片，图片描述留空，等待用户编辑，如不编辑则在客户端展示时则默认使用图集描述；默认图片名选项为优先使用图片文件名作为图片描述，也可编辑；人工编辑选项为上传图片描述项为空等待人工编辑,如不编辑则客户端表现为空</font>
		</div>
	</li>
	
	   	<li class="i">
		<div class="form_ul_div">
			<span  class="title">图集默认状态：</span>
			<label><input type="radio" name="base[default_state]" value="-1" {if $settings['base']['default_state'] == -1} checked="checked"{/if} />待审核</label><label><input type="radio" name="base[default_state]" value="1"{if $settings['base']['default_state'] == 1} checked="checked"{/if} />已审核</label><label><input type="radio" name="base[default_state]" value="2"{if $settings['base']['default_state'] == 2} checked="checked"{/if} />已打回</label>
					<font class="important" style="color:red">优先根据权限判断图集创建设置状态,仅当权限“创建内容状态”配置为“系统默认”时此配置才有效</font>
		
		</div>
	</li>
</ul>