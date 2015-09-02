 {code}
 $page_info = $formdata['page_info'];
 {/code}
 {if is_array($formdata['page_data']) && count($formdata['page_data']) > 0}
 	 <ul style="float:left;">
		 {foreach $formdata['page_data'] as $v}
		   {if $v[$page_info['last_field']]}
		   <li>
		   		<input type="radio" name="page_data_id" value="{$v[$page_info['field']]}"/> {$v[$page_info['name_field']]}
		   </li>
		   {else}
		   <li>
		   		<input type="radio" name="page_data_id" value="{$v[$page_info['field']]}"/> {$v[$page_info['name_field']]}
		   		<span style="font-size:16px;pointer:cursor;" attrid="{$page_info['id']}" fid="{$v[$page_info['field']]}" onclick="hg_select_page(this);"> > </span>
		   </li>
		   {/if}
		 {/foreach}
	 </ul>
 {/if}
