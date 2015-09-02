<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}"  name="{$v['id']}"   order_id="{$v['order_id']}">
   <div class="common-list-left">
           <div class="common-list-item common-paixu">
              <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>	
             </div>
          </div>
    </div>
    <div class="common-list-right">
         <div class="common-list-item message-lydx wd60">
              <div class="common-list-cell">
                  <a href="#"><div class="common-list-overflow lydx-overflow">{$v['type']}</div></a>
              </div>
         </div>
         <div class="common-list-item message-zt wd60" >
              <div class="common-list-cell">
                  <span id="comment_audit_{$v['id']}">{$v['state']}</span>
              </div>
         </div>
         <div class="common-list-item wd150">
              <div class="common-list-cell">
                  <span class="common-user">{$v['user_name']}</span>
                  <span class="common-time">{$v['create_time']}</span>
              </div>
         </div>
    </div>
    <div class="common-list-biaoti" >
	    <div class="common-list-item circle-biaoti biaoti-transition">
			   <div class="common-list-cell">
			   <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><span class="common-list-overflow message-biaoti-overflow">{$v['name']}</span></a>
               </span>
            </div>  
	    </div>
   </div>
 </li>