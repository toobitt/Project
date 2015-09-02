<?php 

?>

<ul class="form_ul">
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">&nbsp;&nbsp;&nbsp;数据清理时间：</span>
            <input type="text" value="{$settings['base']['clear_config']['clear_date']}" name="base[clear_config][clear_date]" style="width:200px;">
            <font class="important" style="color:red">清理时间 单位天  清理此时间之前的数据  值为0时不进行清理</font>
        </div>
    </li>
    
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">&nbsp;&nbsp;&nbsp;清理分类：</span>
            <input type="text" value="{$settings['base']['clear_config']['clear_sort']}" name="base[clear_config][clear_sort]" style="width:200px;">
        
            <font class="important" style="color:red">清理此分类下未审核的数据(包含子集)  值为0时不进行清理</font>
        </div>
    </li>

    <li class="i">
        <div class="form_ul_div">
            <span  class="title">&nbsp;&nbsp;&nbsp;图片最大尺寸：</span>
            <input type="text" value="{$settings['base']['maxpicsize']}" name="base[maxpicsize]" style="width:200px;">

            <font class="important" style="color:red"></font>
        </div>
    </li>
    
   	<li class="i">
		<div class="form_ul_div">
			<span  class="title">自动草稿功能：</span>
			<label><input type="radio" name="base[autoSaveDraft]" value="1" {if $settings['base']['autoSaveDraft'] == 1} checked="checked"{/if} />开启</label><label><input type="radio" name="base[autoSaveDraft]" value="0"{if $settings['base']['autoSaveDraft'] == 0} checked="checked"{/if} />关闭</label>
					<font class="important" style="color:red">开启后，系统会自动保存草稿到草稿箱</font>
		
		</div>
	</li>

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
    
</ul>