<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item admin-paixu">
                <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
        </div>
    </div>
	<div class="common-list-right">
        <div class="common-list-item wd60">
                <a class="btn-box" title="重发通知" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
        </div>
        <div class="common-list-item wd60">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
        </div>
        <div class="common-list-item wd80">
              <span id="contribute_sort_{$v['id']}">{$v['receiver_type']}</span>
        </div>
        <div class="common-list-item wd80">
			    <span id="contribute_cardid_{$v['id']}">{$v['platform']}</span>
        </div>
         <div class="common-list-item wd80">
              <span >{$v['errmsg']}</span>
        </div>
        <div class="common-list-item wd80">
        		<span id="contribute_client_{$v['id']}"></span>
        </div>
        <div class="common-list-item wd130">
               <span>{$v['user_name']}</span>
			   <span class="create-time">{$v['create_time']}</span>
        </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item admin-biaoti biaoti-transition">
			   <span class="common-list-overflow admin-biaoti-overflow">
			   <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" title="重新发送">
		          <span id="contribute_title_{$v['id']}" class="m2o-common-title">{$v['content']}</span>
		       </a>
               </span>
	    </div>
   </div>
</li> 
