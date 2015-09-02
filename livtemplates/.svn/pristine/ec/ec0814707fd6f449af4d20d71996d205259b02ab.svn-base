<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item">
        	<a class="show-pop">
	           <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['content']}</span>
            </a>
        </div>
       	<div class="vod-fengmian common-list-item wd80">
        	<a>
	           <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;"></span>
            </a>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item wd220">
                <span></span>
        </div>
        <!--<div class="common-list-item wd80 overflow">
        </div>-->
        <div class="common-list-item wd80 overflow">
        </div>
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                {$v['relation_content']}
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">   
            {$v['app_name']} 
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                {if($v['type'] == 'app')}
                APP
                {elseif($v['type'] == 'group')}
                群组
                {/if}
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
           		<a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
           		{if $v['member_name']}
           			{$v['member_name']}
           		{else}
           			匿名用户
           		{/if}
           		</a>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a>
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;"></span>
                </a>
        </div>
    </div>
</li>