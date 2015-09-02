<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list">
        <div class="common-list-item">
            <div class="common-list-cell">
                    <span>{$v['user_name']}</span>
            </div>
        </div> 
        
         <div class="common-list-item"  style="width:800px;">
         	
            <div class="common-list-cell content">
                    <span>{code}echo substr($v['content'],0,50);{/code}</span>
                    
            </div>
            
        </div> 
           
        <div class="common-list-item">
            <div class="common-list-cell">
                    <span>{$v['create_time']}</span>
            </div>
        </div>
        
    </div>
</li>