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
           			<!--<a title="详细" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
           			<em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>-->
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=alter_drop_field&Field={$v['Field']}&tbname={code}echo $_INPUT{'tbname'}{/code}"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
        	</div>
        </div> 
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['Field']}</span>
            </div>

        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
					<span>{$v['Type']}</span>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['Type']}</span>
            </div>
        </div>  
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['Comment']}</span>
            </div>
        </div>       
    </div>
    
</li>