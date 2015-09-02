 <li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"   cname="{$v['cid']}"    corderid="{$v['order_id']}">
	    <div class="common-list-left" >
	        <div class="common-list-item" style="width:35px;">
	            <div class="common-list-cell">
	                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"/></a>
	            </div>
	        </div>
	    </div>
	    <div class="common-list-right">	
			<div class="common-list-item circle-bj">
	            <div class="common-list-cell" style="width:48px;">
	                    <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
	                    <a title="删除" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
	            </div>
	        </div>					    				    
	        <div class="common-list-item">
	        	<div class="common-list-cell">
	        		<span >{$v['group_name']}</span>  
	        	</div>	
	        </div>							        				        
	        <div class="common-list-item circle-zt">
	            <div class="common-list-cell">
	                   <div class="common-switch-status"><span id="statusLabelOf{$v['id']}" _id="{$v['id']}" _state="{$v['state']}" style="color:{$list_setting['status_color'][$v['status']]};">{$v['status']}</span></div>
	            </div>
	        </div>
	        <div class="common-list-item">
	            <div class="common-list-cell">
	            	<span onclick="put_queue({$v['id']});" id="queue_{$v['id']}">获取路况</span>
	            </div>
	        </div>					        
	        <div class="common-list-item wd100" style="width:100px;">
	            <div class="common-list-cell">
	            	<span class="common-user">{$v['user_name']}</span>
	            	<span class="common-time">{$v['create_time']}</span>
	            </div>
	        </div>
	    </div>			
	    <div class="common-list-biaoti" style="cursor:pointer;">
	    	<div class="common-list-item">
		        <div class="common-list-cell">
		           <span>
		           	{if $v['avatar']}<img src="{$v['avatar']}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['name']}"><span id="title_{$v['id']}">{$v['name']}</span></a>
		           </span>
		        </div> 
	        </div>         
	    </div>						    		    
 </li>