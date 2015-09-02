<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
   <div class="common-list-left ">
      <div class="common-list-item paixu">
         <a class="lb" name="alist[]">
           <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
         </a>
      </div>
   </div>
   <div class="common-list-right">    
        <div class="common-list-item open-close" style="width:200px;margin-top:30px;">
        </div>
    </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti">
        <div class="common-list-item biaoti-transition">
            <a class="fl" href="./run.php?mid={$_INPUT['mid']}&a=detail&session_id={$v['id']}&infrm=1" style="width:40px;">
            {code}
                $indexpic_url = '';
                if($v['send_uavatar'] && $v['send_uavatar']['host']) {
                    $indexpic_url = $v['send_uavatar']['host'] . $v['send_uavatar']['dir'] . '100x100/' . $v['send_uavatar']['filepath'] . $v['send_uavatar']['filename'];
                }
            {/code}                
            {if $indexpic_url}
                    <img src="{$indexpic_url}"  class="common-img" style="width: 50px;height: 50px;vertical-align: middle;margin-right: 10px;"/> 
            {/if}
            </a>
            <div class="common-title-box common-list-overflow max-wd">
                <a href="./run.php?mid={$_INPUT['mid']}&a=detail&session_id={$v['id']}&infrm=1"><span class="m2o-common-title" style="color:#8fa8c6;">{$v['send_uname']}</span></a>
                <div class="common-title-detal"><span>{$v['message']}</span></div>
                <div class="common-title-detal"><span style="color:#999999;">{$v['send_time_show']}</span></div>
            </div>
        </div>
   </div>
</li>
