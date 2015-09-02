 {code}
 $page_info = $formdata['page_info'];
// print_r($formdata['page_data']);
 {/code}
 {template:head}
 <div>
 {if $formdata['back_fid']}
 <a href="./run.php?mid={$_INPUT['mid']}&a=get_page_data&infrm=1&page_id={$page_info['id']}&fid={$v[$page_info['field']]}">
 <<返回
 </a>
 <br>
 {/if}
 {foreach $formdata['page_data'] as $v}
   	
   	<a href="./run.php?mid={$_INPUT['mid']}&a=mkpublish_form&infrm=1&page_id={$page_info['id']}&page_data_id={$v['id']}&page_data_fid={$v['fid']}&deploy_name={$v['name']}" target="set_iframe">
   	{$v[$page_info['name_field']]}
   	</a>
   {if !$v[$page_info['last_field']] && $page_info['has_child']}
   <a href="./run.php?mid={$_INPUT['mid']}&a=get_page_data&infrm=1&page_id={$page_info['id']}&fid={$v['id']}" target="page_data_iframe">
    >
   {/if} 
    </a>
    <br>
 {/foreach}
 </div> 
 
