<li class="common-list-data clear"  id="r_{$v['id']}" _id="{$v['id']}"    name="{$v['id']}"   orderid="{$v['orderid']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
        </div>
        <div class="common-list-item special-slt">
				<img style="width:40px;height:30px;" _src="{$v['img_info']['host']}{$v['img_info']['dir']}40x30/{$v['img_info']['filepath']}{$v['img_info']['filename']}" id="img_{$v['program_id']}" onclick="hg_show_change({$v['program_id']})" href="###" />
        </div>
        
    </div>
	<div class="common-list-right" >
        <div class="common-list-item wd70">
			    <span id="name_{$v['bitrate']}">{$v['bitrate']}</span>
        </div>
         <div class="common-list-item overflow wd70">
			    <span id="name_{$v['cpname']}">{$v['vod_leixing']}</span>
        </div>
        <div class="common-list-item wd120">
                <span id="name_{$v['cre_time']}" class="common-time">{$v['create_time']}</span>
                <span id="name_{$v['cre_time']}" class="common-time">{$v['addperson']}</span>
        </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']})" href="###"></div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['title']}" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">{$v['title']}</a>
            	<input type="hidden" name="id" value="{$v['id']}" /> 
	    </div>
   </div>
   <!--
   <div style="margin-left:24px;display:none;" id="img_box_{$v['program_id']}">
        {if $v['pic']}
        {foreach $v['pic'] as $key=>$val}
            <div style="float:left;margin-left:8px;">
				<img style="width:120px;height:75px;" _src="{$val}"  onclick="change_indexpic({$v['program_id']},{$key}, this)" />
            </div>
        {/foreach}
        {/if}
        	<div class="uploadBtn" data-program_id="{$v['program_id']}" style="cursor:pointer;float:left;margin-left:8px;border:1px dashed #aaa;width:120px;height:75px;line-height:75px;text-align:center;font-size:30px;">+</div>
   </div>
   -->
</li>