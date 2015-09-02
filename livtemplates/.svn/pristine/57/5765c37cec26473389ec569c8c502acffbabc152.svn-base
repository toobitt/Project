
<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">

        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['password']}</span>
            </div>
        </div>
        
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['account_type']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['company_name']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['create_time']}</span>
            </div>
        </div> 
        <div class="common-list-item caozuo">
             <div class="common-list-cell">
       			<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
       			<em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
				<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&tbname=cdn_log">
				<em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
        		{if !$v['register']}
        		<a title="注册" href="./run.php?mid={$_INPUT['mid']}&a=register_upyun_user&id={$v['id']}&infrm=1">注册</a>
        		{/if}
        	</div>
        </div> 
      
    </div>
    <div class="common-list-biaoti">
    	 <div class="common-list-item  biaoti-transition">
			   <div class="common-list-cell">
				 <span>{$v['accountname']}</span>
			   </div>
		</div>
	</div>  
</li>