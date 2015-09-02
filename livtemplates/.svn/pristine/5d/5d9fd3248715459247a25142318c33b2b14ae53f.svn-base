<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" order_id="{$v['order_id']}"  name="{$v['id']}" >
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
                <img _src="{if $v['avatar_url']}{$v['avatar_url']}{else}{$RESOURCE_URL}avatar.jpg{/if}" width="40" height="30" />
        </div>
    </div>
    
    <div style='float:left'  href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
                <a>
	                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['user_name']}</span>
                </a>
        </div>
    </div>
    
    <div style='float:right'>
    	<div class="common-list-item group-role">
    		{code}
    		
    		if($v['dingdone_role_id']){
    			if($v['dingdone_role_id']==1){
    				echo "普通用户";
	    		}else if($v['dingdone_role_id']==2){
	    			echo "开发者";
	    		}		
    		}
    		if(isset($v['is_business'])){
    			if($v['is_business']==1){
    				echo "，商业用户";
	    		}else if($v['is_business']==0){
	    			echo "";
	    		}
    		}
    		if(isset($v['is_intest'])){
    			if($v['is_intest']==1){
    				echo "，内测用户";
	    		}else if($v['is_intest']==0){
	    			echo "";
	    		}	
    		}
    		
    		{/code}
    	</div>
    	<div class="common-list-item group-apply">
    		{code}
    			if($v['push_status_text']){
    				if($v['push_status_text'] == '未提交申请'){
    					echo '-';
    				}else{
    					echo "<span>".$v['push_status_text']."</span>";
    				}
    				
    			}
    		{/code}
    	</div>
    	<div class="common-list-item group-manger">
    		<a class="btn-box acolor" href="./run.php?mid={$_INPUT['mid']}&a=base_form&id={$v['id']}&infrm=1">基础信息</a>
    		<a class="btn-box acolor" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">应用信息</a>
    	</div>
    	<!--<div class='common-list-item group-ctime'>
    		<span class="common-time">
    		{code}
    			echo date('Y-m-d H:i',$v['update_time']);
    		{/code}
    		</span>
    	</div>-->
    	<div class='common-list-item group-ctime'>
    		<span class="common-time">{$v['create_time']}</span>
    	</div>
    	<!-- <div class="common-list-item wd100">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=base_form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
    	<div class="common-list-item wd90">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=base_form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
        </div>
        
        <div class="common-list-item wd100">
                <span>{$v['push_status_text']}222</span>
        </div>
    	<div class="common-list-item wd60">
                <span>{$v['business_status_text']}111</span>
        </div>
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div> -->
    </div>
    
</li>