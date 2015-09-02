
<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
     	<div class="common-list-item circle-tjr domain-item" title="{$v['domain']}">
            <div class="common-list-cell">
                    <span>{$v['domain']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr domain-item">
            <div class="common-list-cell">
                    <span>{$v['cname']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['type']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['quota']}MB</span>
            </div>
        </div> 
        <div class="common-list-item caozuo">
             <div class="common-list-cell">
             	<!--<a title="添加域名" href="./run.php?mid={$_INPUT['mid']}&a=add_domain&id={$v['bucket_name']}&infrm=1">添加域名</a>-->
       			<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['bucket_name']}&infrm=1">
       			<em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
				<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['bucket_name']}&tbname=cdn_log">
				<em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
        	</div>
        </div> 
      
    </div>
    <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
			<div class="common-list-cell">
	        	<a class="" href="./run.php?a=relate_module_show&app_uniq=cdn&mod_uniq=cdn_domain&mod_a=show&bucket_name={$v['bucket_name']}&infrm=1">
	        	<span>{$v['bucket_name']}</span></a>
            </div>  
	    </div>
   </div>
</li>