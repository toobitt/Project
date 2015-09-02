                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
                    	<a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" >
                    	<input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
                    		<a class="sharesltmix"><span class="m2o-common-title">{$v['name']}</span></a>
                    		<a class="sharesltmix">{$v['index']}:{$v['index_port']}</a>
                    		<a class="sharesltmix">{$v['search']}:{$v['search_port']}</a>
                    		<a class="sharesltmix">{if $v['is_open']}开启{else}关闭{/if}</a>
                                <a class="sharesltmix">{if $v['error']}<font color='red'>异常</font>{else}正常{/if}</a>
                    		<a class="sharesltmix"  href="./run.php?mid={$_INPUT['mid']}&a=detail&id={$v['id']}">编辑
                    		<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除
                    		</a>
						
                    	</span>
                </li>   
                
                
