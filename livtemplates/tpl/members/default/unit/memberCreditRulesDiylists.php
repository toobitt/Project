<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">    <div class="m2o-item m2o-paixu">		 <input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">	</div>		<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['name']}">		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">			<span class="m2o-item-bt">				{if $v['icon']}					{code}						$v['icon']=hg_fetchimgurl($v['icon']);					{/code}					<img src="{$v['icon']}" />				{/if}				<span>{$v['appname']}</span>			</span>		</a>	</div>			<div class="m2o-item m2o-mark">{$v['rulename']}</div>  <!--    <div class="m2o-item m2o-sort">{$v['creditshigher']}</div>        <div class="m2o-item m2o-switch" _status="{$v['switch']}" >    	<div class="common-switch {if $v['switch']}common-switch-on{/if}">           <div class="switch-item switch-left" data-number="0"></div>           <div class="switch-slide"></div>           <div class="switch-item switch-right" data-number="100"></div>        </div>    </div>      <div class="m2o-item m2o-sort">{$v['creditslower']}</div>   -->    <!-- <div class="m2o-item m2o-time">        <span class="name">{$v['user_name']}</span>        <span class="time">{$v['update_time']}</span>    </div> -->    <div class="m2o-item m2o-ibtn">    </div></div>