<?php 
?>
{template:head}
{css:2013/button}
<style>
tr:hover{background:#F0EFF5}
.middle{cursor:pointer;}
.sort-box{width:400px;height:460px;border:7px solid #659efd;position:absolute;left:490px;top:-520px;background: white;transition:all .5s;z-index: 99999999;}
.sort-box .title{height:45px;background:#659efd;}
.sort-box .title span{color:white;font-size:20px;}
.sort-box ul{font-size:15px;max-height: 380px;overflow-y: scroll;}
.pop-close-button2{float:right;margin-right:0px;margin-top:6px;}
.pop-save-button{float:right;margin-right:10px;margin-top:6px;}
.current{padding:18px;}
.sort-item{padding:10px;border-bottom:1px solid #ccc;}
.mark{cursor:pointer}
th{text-align:left;}
</style>
<!--<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first dq"><em></em><a>模板商店</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>-->
<div class="wrap n">
<div class="search_a">
<form name="searchform" action="" method="get">

<input type="hidden" name="a" value="show" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />&nbsp;
</form>
</div>
 <form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list form_table">
	{if $list_fields}
	<tr class="h" align="left" valign="middle">
		<th></th>
		<th class="list-id" title="{$list_fields['brief']}"{$list_fields['width']}>{$list_fields['id']['title']}</th>
		<th class="list-id" title="{$list_fields['brief']}"{$list_fields['width']}>{$list_fields['name']['title']}</th>
		<th>管理</th>
	</tr>
	{/if}
	<tbody id="{$hg_name}">
	{if $list}
		{foreach $list AS $k => $v}
			{if $list_fields}
			<tr class="field-list" id="{$v[$primary_key]}"  class="h" align="left" valign="middle">
				{if $batch_op}
				<td class="left"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" _id="{$v[$primary_key]}"/></td>
				{/if}
				{foreach $list_fields AS $kk => $vv}
					{code}
						$exper = $vv['exper'];			
						eval("\$val = \"$exper\";");
					{/code}
				<td{$vv['width']} class="middle">{$val}</td>
				{/foreach}
				<td align="center" class="right">
				{if $v['op_name']}
					<a _href="{$v['link']}&amp;{$v['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}" style="cursor:pointer;">{$v['op_name']}</a>
				</td>
				{else}
				{/if}
			</tr>
			{/if}
		{/foreach}
	{else}
	{code}
	$colspan = count($list_fields) + 1;
	{/code}
	<tr><td colspan="{$colspan}" style="text-align:center;">暂无此类信息</td></tr>
	{/if}
	</tbody>
</table>
<div class="form_bottom clear">
	<div class="live_delete">
		<input type="checkbox" name="checkall" id="checkall" value="infolist" title="全选" class="n-h">
		<input type="hidden" name="a" id="a" value="delete" />
		<div class="batch_op"><a>批量更新</a></div>
	</div>
	<div class="live_page">{$pagelink}</div>
</div>
</form>
</div>

<!-- 弹窗 -->
  <div class="sort-box">
	<div class="title">
	  <span>模板分类:</span>
	  <input type="button"  class="pop-close-button2" />
	  <input type="submit" value="保存" class="pop-save-button"  />
	</div>
	<ul>
		{foreach $tem_sort as $k=>$v}
	     <li class="sort-item" _id="{$v['id']}">
	     <input type="radio" name="type"/>
	        {$v['name']}
	     </li>
	    {/foreach}
	</ul>
	</div>
{template:foot}



<script>
$(function($){

	   $('.middle').on('click',function(event){
		   var self = $(event.currentTarget),
		   	   obj = self.closest('tr').find('input[type="checkbox"]'),
		   	   isSelected = obj.prop('checked');
		   isSelected ? obj.prop('checked' , false) : obj.prop('checked', true) ;
	   });
	
       $('.right a').on('click' , function(event){
           var self = $(event.currentTarget);
           var txt = self.text();
           self.closest('tr').find('input[type="checkbox"]').prop('checked' , true);
           id = self.closest('tr').attr('id');
           if(txt == '安装'){ 
               var top = $(window).scrollTop(); 
               $('.sort-box').css('top',top);
               $('.pop-save-button').on('click',function(){    
    	    	   var  sort_id = $('.sort-box ul li').filter('.current').attr('_id');
    	           var url =  '?a=temp_do'+'&type=3'+'&sign='+id+'&sort_id='+sort_id ;
    	           $('.sort-box').css('top' , '-720px');
    	           $.get(url,function(){   
    	           }) 
               });
           }else{
        	   alert("更新成功");
        	   var  url = self.attr('_href');
        	   $.get(url,function(){
            	 })
           }
       });
       $('.pop-close-button2').on('click',function(){
    	   $('.sort-box').css('top','-720px');  
       });
       $('.sort-item input').on('click',function(event){
	       var self = $(event.currentTarget); 
	       var  obj = self.closest('li');
           obj.addClass('current');
           obj.siblings().removeClass('current');
       });
       $('.batch_op').on('click',function(){
    	   var item = $('tr').find('input[type="checkbox"]').filter(function(){
        	   return $(this).prop('checked');
           });
           var ids = item.map(function(){
                 return $(this).attr('_id');
             }).get().join(',');  
           if(!ids){
        	   jAlert("请选择要更新的内容","提示");
           }else{ 
               var top = $(window).scrollTop(); 
               $('.sort-box').css('top',top); 
               $('.pop-save-button').on('click',function(){    
    	    	   var  sort_id = $('.sort-box ul li').filter('.current').attr('_id');
    	           var url =  '?a=temp_do'+'&type=3'+'&sign='+ids+'&sort_id='+sort_id ;
    	           $('.sort-box').css('top' , '-720px');
    	           $.get(url,function(){   
    	           }) 
               });
               
           }  
       });   
});

</script>