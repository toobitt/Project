<li class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">	   <div class="common-list-left">                 <div class="common-list-item group-paixu">                      <div class="common-list-cell">                           <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>                           </div>                   </div>                              </div>        <div class="common-list-right">                 <div class="group-cz common-list-item open-close">                        <div class="common-list-cell">						      <div title="操作" class="btn-box-cz">		                         <div class="btn-box-cz-menu" id="rr_2_{$v['id']}">						             <a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1{if $v['pid']}&fid={$v['pid']}{/if}">编辑</a>									 <a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>									 {if !$v['pid']}									 <a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=form&fid={$v['id']}&m=add&infrm=1">添加子类</a>									 {/if}					             </div>			                 </div>                        </div>                 </div>                 <div class="group-tz common-list-item open-close">                       <div class="common-list-cell">                               <span>{$v['role_name']}</span>                          </div>                </div>         </div>		 <div class="common-list-biaoti">	    <div class="common-list-item group-title biaoti-transition">			<div class="common-list-cell">                <span id="title_{$v['id']}">{$v['name']}</span>            </div>  	    </div>   </div></li>