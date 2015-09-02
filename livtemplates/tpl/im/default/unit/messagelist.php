<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
   <div class="common-list-left ">
      <div class="common-list-item paixu">
         <a class="lb" name="alist[]">
           <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
         </a>
      </div>
   </div>
   <div class="common-list-right">    
        <div class="common-list-item open-close wd100">
             <!-- <a href="./run.php?mid={$_INPUT['mid']}&a=detail&session_id={$v['id']}&infrm=1"><span>{$num}条消息</span></a> |  -->
             <!-- <a href="run.php?mid={$_INPUT['mid']}&a=send_form&infrm=1&session_id={$v['id']}">回复</a> -->
        </div>
    </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti">
        <div class="common-list-item biaoti-transition">
            <a class="fl" href="./run.php?mid={$_INPUT['mid']}&a=detail&session_id={$v['id']}&infrm=1" style="width:40px;">
            {code}
                $avatar = $name = array();
                foreach((array)$v['users'] as $kk => $vv) {
                    $name[]=  $vv['uname'];
                    $vv['uavatar'] && $avatar[] = $vv['uavatar'];
                }
                $title = '';
                $title = $v['title'] ? $v['title'] : implode('、', $name);
                
                $n = rand(0, count($avatar));
                
                $indexpic_url = '';
                if($avatar && $avatar[$n]['host']) {
                    $indexpic_url = $avatar[$n]['host'] . $avatar[$n]['dir'] . '100x100/' . $avatar[$n]['filepath'] . $avatar[$n]['filename'];
                }
            {/code}                
            {if $indexpic_url}
                    <img src="{$indexpic_url}"  class="common-img" style="width: 50px;height: 50px;vertical-align: middle;margin-right: 10px;"/> 
            {/if}
            </a>
            <div class="common-title-box common-list-overflow max-wd">
                <a href="./run.php?mid={$_INPUT['mid']}&a=detail&session_id={$v['id']}&infrm=1"><span style="color:#8fa8c6;">{$title}</span></a>
                <div class="common-title-detal"><span style="color:#999999;">{$v['last_uname']} : </span>{$v['last_message']}</span></div>
                <div class="common-title-detal"><span style="color:#999999;">{$v['last_time_show']}</span></div>
            </div>
        </div>
   </div>
</li>
