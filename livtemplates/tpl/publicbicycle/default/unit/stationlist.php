<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]"></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd100">
            <div class="common-list-cell">
			    <a style="color:#8ea8c8;" href="./run.php?a=relate_module_show&app_uniq=publicbicycle&mod_uniq=notice&mod_a=show&station_id={$v['id']}&infrm=1">{$v['notice_num']}</a>
			    <a title="新增公告" href="./run.php?a=relate_module_show&app_uniq=publicbicycle&mod_uniq=notice&mod_a=form&station_id={$v['id']}&infrm=1">新增</a>
            </div>
        </div>
        <div class="common-list-item">
            <div class="common-list-cell">
                    <div title="操作" class="btn-box-cz">
                        <div class="btn-box-cz-menu">
				           	<span onclick="hg_stationForm({$v['id']});" style="margin:4px 2px 0 0;" class="button_2">编辑</span>
							<span onclick="hg_delStation({$v['id']})" style="margin:4px 2px 0 0;" class="button_2">删除</span>
			            </div>
			       </div> 
           </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
			    <span>{$v['company_name']}</span>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
			    <span style="color:#17b202;">{$v['currentnum']}</span>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
			    <span>{$v['park_num']}</span>
            </div>
        </div>
        
        <div class="common-list-item wd80">
            <div class="common-list-cell">
			   <span>{$v['region_name']}</span>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
			    <div class="need-switch" title="{$v['audit']}" state="{if $v['state']==1}1{else}0{/if}" style="cursor:pointer;" vid="{$v['id']}"></div>
            </div>
        </div>
        <div class="common-list-item  wd120">
            <div class="common-list-cell">
			    <span class="common-user">{$v['user_name']}</span>
			    <span class="common-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
			   <div class="common-list-cell">
			   <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
			   	<img class="biaoti-img" src="{$v['station_icon']}">
                <span class="common-list-overflow m2o-common-title" title="{$v['name']}">{$v['name']}</span>
               </a>
            </div>  
	    </div>
   </div>
</li>
