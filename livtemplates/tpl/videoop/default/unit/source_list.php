<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" >
   <div class="common-list-left ">
      <div class="common-list-item paixu">
         <a class="lb" name="alist[]">
           <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
         </a>
      </div>
   </div>
   <div class="common-list-right">
        <div class="common-list-item open-close" style="width:480px;">
            <span>http://{$v['host']}/{$v['dir']}{$v['filename']}</span>
        </div>
        <div class="common-list-item wd60 news-zhuangtai open-close">
            <div class="common-switch-status">
             <span _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="color:{$_configs['status_color'][$v['state']]};">{$_configs['status_config'][$v['state']]}</span>
            </div>
        </div>
        <div class="common-list-item wd100 news-ren open-close">
             <span class="news-name">{$v['create_user']}</span>
             <span class="news-time">{$v['create_time_show']}</span>
        </div>
    </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   {code}
        $href = './run.php?mid='.$_INPUT['mid'].'&a=form&id='.$v['id'].'&infrm=1';
        $classname = '';
    {/code}
   <div class="common-list-biaoti min-wd">
        <div class="common-list-item biaoti-transition">
          <div class="common-list-overflow max-wd">
            <a href="{$href}"  target="nodeFrame">
                <span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
            </a>
           </div>
        </div>
   </div>
</li>