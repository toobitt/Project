<li class="common-list-data clear"  id="r_{$v['id']}" _id="{$v['id']}"  name="{$v['id']}"   order_id="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item notice-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    
    <div class="common-list-right">
    	
	    
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
       </div>
      <div class="common-list-item wd80">
            <div class="common-list-cell">
                 <a style="color:#8ea8c8;" href="./run.php?mid={$_INPUT['mid']}&a=show&station_id={$v['station_id']}">{$v['station_name']}</a>
            </div>
       	</div>
       <div class="common-list-item wd60">
            <div class="common-list-cell">
                 <a style="color:{$list_setting['status_color'][$v['audit']]};" title="{$v['audit']}" id="audit_{$v['id']}" onclick="hg_stateAudit({$v['id']},{if $v['state'] == '1'}'back'{else}'audit'{/if});" href="javascript:">{$v['audit']}</a>
            </div>
       </div>
      
       <div class="common-list-item wd120">
            <div class="common-list-cell">
                 <span class="common-user">{$v['user_name']}</span>
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
   </div>
	<div class="common-list-biaoti ">
	    <div class="common-list-item biaoti-transition">
			<a  href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" class="common-list-overflow max-wd">
			<span class="m2o-common-title">{$v['title']}</span></a>
	    </div>
   </div>
 
</li> 