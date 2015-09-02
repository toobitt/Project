<li order_id="{$v['order_id']}" _id="{$v[$primary_key]}" class="common-list-data clear"  id="r_{$v[$primary_key]}" name="{$v['order_id']}" data-name="{$v['express_name']}" data-no="{$v['express_no']}">
   <div class="common-list-left ">
      <div class="common-list-item paixu">
         <a class="lb" name="alist[]">
           <input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" title="{$v[$primary_key]}" />
         </a>
      </div>
   </div>
   <div class="common-list-right">
        <div class="common-list-item common-list-pub-overflow news-fabu open-close">
          <div class="common-list-pub-overflow">
          </div>
        </div>
        
        <?php if($v['nameofcontact']||$v['contact_telphone']):?>
        <div class="common-list-item news-fabu common-list-pub-overflow">
            <span>{$v['nameofcontact']}</span>
        </div>
        <?php else:?>
        <div class="common-list-item news-fabu common-list-pub-overflow">
            <span>{$v['nameofconsignee']}</span>
        </div>    
        <?php endif;?>    
        <div class="common-list-item news-fenlei open-close wd70">
            <span>{$v['create_time']}</span>
        </div>
        
        <div class="common-list-item news-quanzhong open-close wd60">
            <span>
                <!--商品：{$v['goods_value']}</br>
                      运费：{$v['delivery_fee']}</br>
                      计：-->{$v['order_value']}元
            </span>
        </div>
        <div class="common-list-item news-zhuangtai open-close wd60">
            <?php if($v['pay_status']==1):?>
            <span style="color:#E066FF;">{$v['pay_status_title']}</span>
            <?php elseif($v['pay_status']==2):?>
            <span style="#0D0D0D;">{$v['pay_status_title']}</span>    
            <?php elseif($v['pay_status']==3):?>  
            <span style="color:#EE2C2C;">支付失败</span>        
            <?php endif;?>      
        </div>
        <div class="common-list-item news-ren open-close wd100">
        	<select name="tracestep" id="trace_step_<?php echo $v['id'];?>" _id="<?php echo $v['id'];?>" class="trace_step">
        	<?php foreach($_configs['trace_step'] as $trace_step_k=>$trace_step_v):?>
        	<?php if($v['delivery_tracing']==$trace_step_k):?>
        		<option value="<?php echo $trace_step_k;?>" selected="selected"><?php echo $trace_step_v;?></option>
        	<?php continue;endif; ?>
        	<option value="<?php echo $trace_step_k;?>" ><?php echo $trace_step_v;?></option>
        	<?php endforeach;?>
        	</select>
        </div>
        
       
    </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti min-wd">
        <div class="common-list-item biaoti-transition">
          <div class="common-list-overflow max-wd">
            <a href="{$href}"  target="formwin">
            {if $v['indexpic_url']}
                <img  _src="{$v['indexpic_url']}"  class="img_{$v['id']} biaoti-img"/> 
            {/if}
                <span id="title_{$v['id']}" class="m2o-common-title {$classname}">{$v['title']}</span>
            </a>
           </div>
        </div>
   </div>
</li>
