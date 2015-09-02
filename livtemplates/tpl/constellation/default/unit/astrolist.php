<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox"  value="{$v['id']}" title="{$v['id']}"  name="infolist[]"></a>
            </div>
        </div>
         <div class="common-list-item">
            <div class="common-list-cell">
                <img src="{$v['logo']}" width="40" height="30"  id="img_{$v['id']}"/>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                    <div title="操作" class="btn-box-cz">
                        <div class="btn-box-cz-menu">
				           	<span onclick="hg_stationForm({$v['id']});" style="margin:4px 2px 0 0;" class="button_2">编辑</span>
			            </div>
			       </div> 
           </div>
        </div>
        
        
        <div class="common-list-item vote-tjr wd120">
            <div class="common-list-cell">
			    <span class="common-user">从{$v['astrostart']}</span>
			    <span class="common-time">至{$v['astroend']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
			   <div class="common-list-cell">
			   <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
                <span class="common-list-overflow m2o-common-title" title="{$v['astrocn']}">{$v['astrocn']}</span>
               </a>
            </div>  
	    </div>
   </div>
</li>
