<li class="common-list-data clear"  id="r_{$data['id']}"    name="{$data['id']}"   orderid="{$data['order_id']}" >
    <!--
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$data[$primary_key]}" title="{$data[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    -->
    <div class="common-list">
        <div class="common-list-item circle-tjr">
             <div class="common-list-cell">
           			<a title="详细" href="./run.php?mid={$_INPUT['mid']}&a=get_session_info&token_id={$data['token_id']}&infrm=1"><em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$data['id']}"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
        	</div>
        </div> 
        
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$data['id']}</span>
            </div>
        </div> 
        
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$data['from_user_name']}</span>
            </div>
        </div> 
        
        
        
         <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$data['to_user_name']}</span>
            </div>
        </div> 
           
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$data['create_time']}</span>
            </div>
        </div>
    </div>
</li>