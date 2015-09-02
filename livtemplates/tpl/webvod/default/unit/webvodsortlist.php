{if !$v['is_last']}
<li class="common-list-data clear"  id="r_{$v['cpid']}"    name="{$v['cpid']}"   orderid="{$v['cpid']}">
   <div class="common-list-right">
	    <div class="common-list-item">
            <div class="common-list-cell" >
                <a title="发布" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['cpid']}&infrm=1">编辑</a>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition"  style="width:250px;">
			<div class="common-list-cell">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['cp_name']}" href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['cpid']}&infrm=1">{$v['cp_name']}</a>
            	<input type="hidden" name="cpid" value="{$v['cpid']}" />
            </div>  
	    </div>
   </div>
</li>
{else}
<li class="common-list-data clear"  id="r_{$v['category_id']}"    name="{$v['category_id']}"   orderid="{$v['category_id']}">
   <div class="common-list-right">
   		<div class="common-list-item">
            <div class="common-list-cell">
			    <span id="name_{$v['cp_name']}">{$v['cp_name']}</span>
            </div>
        </div>
	    <div class="common-list-item">
            <div class="common-list-cell" >
                <a title="发布" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['cpid']}&infrm=1">编辑</a>
            </div>
        </div>
   </div>
   
   <div class="common-list-biaoti">
	    <div class="common-list-item special-biaoti biaoti-transition"  style="width:250px;">
			<div class="common-list-cell">
	        	<a class="common-list-overflow special-biaoti-overflow" id="name_{$v['title']}" href="./run.php?mid={$relate_module_id}&a=show&category_id={$v['category_id']}&infrm=1">{$v['title']}</a>
            	<input type="hidden" name="category_id" value="{$v['category_id']}" />
            </div>  
	    </div>
   </div>
</li>
{/if}