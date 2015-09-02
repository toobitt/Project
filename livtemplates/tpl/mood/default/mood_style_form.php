{template:head}
{css:2013/form}
{css:2013/button}
{css:mood}
{js:mms_default}
{js:common/common_form}
{js:mood/mood}
{js:ajax_upload}
{js:hg_preview}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
$status = $_configs['status'][$status];
{/code}

<style>

.save-button{position: absolute;right: 45px;}
input[type="file"]{display:none;}
</style>
<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" data-id="{$id}" id="vote-form" enctype="multipart/form-data">
	
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}样式</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title {if $name}input-hide{/if}" _value="{if $name}{$name}{else}添加样式名称{/if}" name="name" id="name" placeholder="输入样式名称" value="{$name}"/>
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" name="sub" value="保存" class="save-button"  >
				<input type="hidden" name="a" value="{$action}" id="action" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
        <div class="m2o-l" style="height:auto">
         	<div class="m2o-item" style="position:relative">
        		<div class="indexpic">
        			<img src="{$index_picture}"/>
                    <span class="indexpic-suoyin {if $index_picture}indexpic-suoyin-current{/if}"></span>
                 </div>
                 <input type="file" name="Filedata" id="photo-file" />
        	</div>
       	    {if $name}
       	    <div class="m2o-item mood-style-info" style="position:relative">
                 <!-- <div>分类：{$name}</div>-->
                 <div>状态：{$status}</div>
                 <div>创建人：{$create_user_name}</div>
                 <div>创建时间：{$create_time}</div>
             </div>
             {/if}
       </div>
         <section class="m2o-m m2o-flex-one contents-list">         	
         	<div class="content content-list-add">
         	<ul>
         	{if $option}
         	{foreach $option as $k => $v}
         		<li class="content-list m2o-flex"> 
         			<span class="content-index">{code}echo $k+1; {/code}</span>
         			<div class="content-image">
         				{if $v['picture']}
         					<img src="{$v['picture']}" style="width:50px;height:50px;" />
         				{/if}
         					<p class="thumbnail">缩略图</p>
         			</div>
         			<input class="content-title content-input" type="text" name="mood_name[{$k}]" value="{$v['name']}">
         			<input type="hidden" name="option_id[{$k}]" value="{$v['id']}" />
         			<input type="file" name="Filedata_{$k}" id="photo-file" />
         			<!-- <input type="text" name="order_id[]" value="{$v['order_id']}" /> -->
         			<span class="del content-del">一</span>
         		</li>	
         	{/foreach}
         	{else}
			{code}
			for ($i = 0; $i < 2; $i++) {
			{/code}
				<li class="content-list m2o-flex"> 
         			<span class="content-index">{code}echo $i+1;{/code}</span>
         			<div class="content-image">
         					<p class="thumbnail">缩略图</p>
         			</div>
         			<input class="content-title content-input" type="text" name="mood_name[{$i}]" value="">
         			<input type="hidden" name="option_id[{$i}]" value="" />
         			<input type="file" name="Filedata_{$i}" id="photo-file" />
         			<span class="del content-del">一</span>
         		</li>					
			{code}
			}
			{/code}
			{/if}
         	</ul>
         	</div>
         	<span class="content-add add-button">新增选项</span>
         </section>
           </div>
         </div>
      </form>

{template:foot}


<script type="text/x-jquery-tmpl" id="options-tpl">
		<li class="content-list m2o-flex"> 
         	<span class="content-index">${num}</span>
         	<div class="content-image"><p class="thumbnail">缩略图</p></div>
         	<input class="content-title content-input" type="text" name="mood_name[${reduce}]" value="">
         	<input type="hidden" name="option_id[${reduce}]" value="" />
         	<input type="file" name="Filedata_${reduce}" id="photo-file" />
         	<span class="del content-del">一</span>
        </li>	
</script>