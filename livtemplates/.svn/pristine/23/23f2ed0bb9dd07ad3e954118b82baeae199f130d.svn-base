<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item server-paixu">
            <div class="common-list-cell">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
            </div>
        </div>
    </div>
	
	
	<div class="common-list-right">
	    <div class="common-list-item server-xx">
            <div class="common-list-cell">
                <a class="btn-box" href="javascript:void(0);" onclick="hg_show_opration_info({$v['id']});"><em></em></a>
            </div>
        </div>
        <div class="common-list-item server-bj">
            <div class="common-list-cell">
                <a class="btn-box" href="javascript:void(0);"  onclick="hg_showAddServer({$v['id']})"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item server-sc">
            <div class="common-list-cell">
                 <a class="btn-box" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"  onclick="return hg_ajax_post(this,'删除',1);"><em class="b3"></em></a>
            </div>
        </div>
        <div class="common-list-item server-dq">
            <div class="common-list-cell">
              <span id="iscur_{$v['id']}">{if $v['iscur']}<font color="red">是</font>{else}<font color="blue">不是</font>{/if}</span>
            </div>
        </div>
        <div class="common-list-item server-bs">
            <div class="common-list-cell">
              <span id="uniqueid_{$v['id']}">{$v['uniqueid']}</span>
            </div>
        </div>
        <div class="common-list-item server-nw">
            <div class="common-list-cell">
			    <span id="ip_{$v['id']}">{$v['ip']}</span>
            </div>
        </div>
        <div class="common-list-item server-ww">
            <div class="common-list-cell">
                <span id="outside_ip_{$v['id']}">{$v['outside_ip']}</span>
            </div>
        </div>
        <div class="common-list-item server-dk">
            <div class="common-list-cell">
                <span id="port_{$v['id']}">{$v['port']}</span>
            </div>
        </div>
        <div class="common-list-item server-cjsj">
            <div class="common-list-cell">
                <span>{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item server-biaoti biaoti-transition">
			   <div class="common-list-cell">
		          <a id="name_{$v['id']}" class="common-list-overflow server-biaoti-overflow" href="run.php?mid={$relate_module_id}&server_id={$v['id']}&infrm=1">{$v['name']}</a>
            </div>  
	    </div>
   </div>
</li>