
<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">


       <div class="common-list-item wd180 push-app" name="push-app">
           <div class="common-list-cell">
               {$v['name']}
           </div>
       </div>

       <div class="common-list-item wd100 push-app" name="push-man">
          <div class="common-list-cell">
              {$v['user_name']}
          </div>
      </div>


        <div class="common-list-item wd120">
            <div class="common-list-cell">
                 {$v['create_time']}
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
          <div class="common-list-item biaoti-transition">
          	      <div class="common-list-overflow max-wd">
          	      	<a href="{$href}"  target="formwin">
          		    {if $v['indexpic_url']}
          		        <img  _src="{$v['indexpic_url']}"  class="img_{$v['id']} biaoti-img"/>
          		    {/if}
          		   		<span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
          		   		{if $v['outlink']}
          		   		<a class="news-outer" title="外链"></a>
          		   		{/if}
          			</a>
          		   </div>
          </div>
    </div>
</li>
