<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :detail_opcate.php
 * package  :package_name
 * Created  :2013-7-24,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/

 $v['index_pic'] = json_decode($v['index_pic'],1);
 if(!empty($v['index_pic']))
 	$pic = $v['index_pic']['host'].$v['index_pic']['dir'].$v['index_pic']['filepath'].$v['index_pic']['filename'];
?>
<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item circle-tjr">
             <div class="common-list-cell" style="width:48px;">
           			<a title="详细" href="./run.php?mid={$_INPUT['mid']}&a=form_data&id={$v['id']}&cate_id={$v['cate_id']}&op=update&infrm=1&relation_module_id={$relate_module_id}">
           			<em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete_data&id={$v['id']}&cate_id={$_INPUT['cate_id']}"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
        	</div>
        </div> 
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['data']['brief']}</span>
            </div>
        </div>
         
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['data']['author']}</span>
            </div>
        </div>
        
    </div>
    <div class="common-list-biaoti" style="cursor:pointer;">
	    <div class="common-list-item open-close">
		   <div class="common-list-cell">
				{if $pic}
					<img src="{$pic}" style="width:40px;height:30px;margin-right:10px;" />
				{else}
				{/if}
				<span class="common-list-overflow max-wd" id="title_{$v['id']}">{$v['data']['title']}</span>
           </div>
	    </div>
    </div>
    
</li>
