<li class="common-list-data clear"  id="r_{$v['program_id']}" _id="{$v['program_id']}"    name="{$v['program_id']}"   orderid="{$v['orderid']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['program_id']}" title="{$v['program_id']}"  /></a>
        </div>
        <div class="common-list-item special-slt">
				<img style="width:40px;height:30px;" _src="{$v['indexpic']}" id="img_{$v['program_id']}" onclick="hg_show_change({$v['program_id']})" href="###" />
        </div>
        
    </div>
	<div class="common-list-right" >
		<div class="common-list-item common-list-pub-overflow">
		  <div class="common-list-pub-overflow">
           	{code}
            $step = '';
            {/code}
            {if $v['pub']}
                {foreach $v['pub'] as $kk => $vv}
					{if $v['pub_url'][$kk]}
					    {if is_numeric($v['pub_url'][$kk])}
					    	<a href="./redirect.php?id={$v['pub_url'][$kk]}" target="_blank"><span class="common-list-pub">{$step}{$vv}</span></a>
					    	{else}
					    	<a href="{$v['pub_url'][$kk]}" target="_blank"><span class="common-list-pub">{$step}{$vv}</span></a>
					    	{/if}												    	
					    {else}
					    	<span class="common-list-pre-pub">{$step}{$vv}</span>
					    {/if}
            {code}
            $step = ' ';
            {/code}
            {/foreach}
            {/if}
           </div>
        </div>
        <div class="common-list-item wd70">
			    <span id="name_{$v['bitrate']}">{$v['bitrate']}</span>
        </div>
         <div class="common-list-item overflow wd70">
			    <span id="name_{$v['cpname']}">{$v['cpname']}</span>
        </div>
        <div class="common-list-item wd120">
                <span id="name_{$v['cre_time']}" class="common-time">{$v['cre_time']}</span>
        </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['program_id']})" href="###"></div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['title']}" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['program_id']}&infrm=1">
	        	<span class="m2o-common-title">{$v['title']}</span></a>
            	<input type="hidden" name="id" value="{$v['program_id']}" /> 
	    </div>
   </div>
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
</li>