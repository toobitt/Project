<li class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">
	   <div class="common-list-left">
                 <div class="common-list-item group-paixu">
                      <div class="common-list-cell">
                           <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>    
                       </div>  
                 </div>                       
       </div>
        <div class="common-list-right">
       			 <div class="group-cz common-list-item open-close">
                       <div class="common-list-cell">
                       {if $v['del']}
                           <p style="color:#F00;">已废弃</p>
                       {else}
                           {if $v['client']}
                               {foreach $v['client'] as $client}
                               {code}
                               $status = $_configs['unpack'][$client['state']];
                               {/code}
                               <p>{$client['name']}：{$status}&nbsp;&nbsp;&nbsp;&nbsp;版本：{code}echo getVersionName($client['version_name']);{/code}</p>
                               <!--
                               {if $client['download_url']}&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$client['download_url']}" target="_blank">下载</a><img src='https://chart.googleapis.com/chart?cht=qr&chld=H&chs=100x100&chl={code}echo urlencode($client['download_url']);{/code}' width="100" height="100" />{/if}
                               -->
                               {/foreach}
                           {else}
                           		<p>暂未打包</p>
                           {/if}
                       {/if}
                       </div>
                </div>
        		<div class="group-tz common-list-item open-close">
                        <div class="common-list-cell">
						      <div title="操作" class="btn-box-cz">
		                         <div class="btn-box-cz-menu" id="rr_2_{$v['id']}">
									<a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
									{if $v['del']}<a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=recover&id={$v['id']}" onclick="return hg_ajax_post(this, '还原', 1);">还原</a>{/if}
					             </div>
			                 </div>
                        </div> 
                </div>
                
                <div class="group-tjr common-list-item open-close">
                       <div class="common-list-cell">
                               <span>{code}echo date('Y-m-d H:i:s', $v['create_time']);{/code}<br />{$v['user_name']}</span>   
                       </div>
                </div>
         </div>
		 <div class="common-list-biaoti">
	    <div class="common-list-item group-title biaoti-transition">
			<div class="common-list-cell">
				<span>
			   	{if $v['icon']}
				<img style="vertical-align:middle; border-radius:10%;" width="40" height="40" src="{code}echo hg_bulid_img($v['icon'], 40, 40);{/code}" alt="{$v['name']}" />
				{/if}
			    </span>
                <span id="title_{$v['id']}" class="m2o-common-title">{$v['name']}</span>
            </div>  
	    </div>
   </div>
</li>