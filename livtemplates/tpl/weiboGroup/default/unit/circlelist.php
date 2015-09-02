<li order_id="{$v['order_id']}" _id="{$v['id']}" class="common-list-data clear"  id="r_{$v['id']}" name="{$v['order_id']}" >
   <div class="common-list-left ">
      <div class="common-list-item paixu">
         <a class="lb" name="alist[]">
           <input type="checkbox" name="infolist[]" value="{$v['id']}" title="{$v['id']}" />
         </a>
      </div>
   </div>
   <div class="common-list-right">
        <div class="common-list-item open-close circle-ms">
            <div class="common-list-cell">
                    <span id="contribute_sort_desc_{$v['id']}">{$v['brief']}</span>
            </div>
        </div>
        <div class="common-list-item  open-close">
            <div class="common-switch-status">
             <span _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['state']]};">{$v['status']}</span>
            </div>
        </div>
        
        <div class="common-list-item open-close wd100">
             <span class="news-name">{$v['user_name']}</span><br/>
             <span class="news-time">{$v['create_time']}</span>
        </div>
    </div>
    <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   {code}
    if(!$v['outlink']) {
        $href = './run.php?mid='.$_INPUT['mid'].'&a=form&id='.$v['id'].'&infrm=1';
        $classname = '';
    }
    else {
        $href = './run.php?mid='.$_INPUT['mid'].'&a=form_outerlink&id='.$v['id'];
        /*$classname = 'out-color';*/
        $classanme = '';
    }
    {/code}
   <div class="common-list-biaoti">
        <div class="common-list-item biaoti-transition">
          <div class="common-list-overflow max-wd">
            <a href="{$href}">
             {code}
                $log = '';
                if($v['log'])
                {
                    $log = $v['log']['host'] . $v['log']['dir'] .'80x60/'. $v['log']['filepath'] . $v['log']['filename'];
                }           
             {/code}
            {if $log}
                <img  _src="{$log}"  class="img_{$v['id']} biaoti-img"/> 
            {/if}
                <span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['name']}</span>
                {if $v['outlink']}
                <a class="news-outer" title="外链"></a>
                {/if}
            </a>
           </div>
        </div>
   </div>
</li>
