<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['name']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="nodeFrame" need-back>
			<span class="m2o-item-bt">
				{$v['name']}
			</span>
		</a>
	</div>
	<div class="m2o-item m2o-style w80 color">{if $v['is_success']}<font style="color:#59b630;">正常</font>{else}<font style="color:red;">异常</font>{/if}</div>
	
	<div class="m2o-item m2o-style w100 color">{$v['host']}</div>
	
	<div class="m2o-item m2o-switch w80" _status="{$v['id']}" >
		{if $v['is_bhost']}
    	<div class="common-switch switch-host {if !$v['is_used']}common-switch-on{/if}">
           <div class="switch-item switch-left" data-number="0"></div>
           <div class="switch-slide"></div>
           <div class="switch-item switch-right" data-number="100"></div>
        </div>
        {else}
        <span class="color">无备用主机</span>
        {/if}
    </div> 
    <div class="m2o-item m2o-style w80 color">{$v['counts']}</div>
    <div class="m2o-item m2o-switch w100" _status="{$v['id']}" >
    	<div class="common-switch switch-status {if $v['status']}common-switch-on{/if}">
           <div class="switch-item switch-left" data-number="0"></div>
           <div class="switch-slide"></div>
           <div class="switch-item switch-right" data-number="100"></div>
        </div>
    </div> 
    <div class="m2o-item m2o-time">
        <span class="name">{$v['appname']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>


















