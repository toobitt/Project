{template:head}
{code}
	if($id)
	{
		if($formdata['type'])
		{
			$optext="复制";
			$a = 'copy_sort';
		}
		else
		{
			$optext="更新";
			$a="update";
		}
		
	}
	else
	{
		$optext="添加";
		$a="add_sort";
	}
{/code}
{js:common/common_form}
{js:common}
{css:2013/form}
{css:common/common}
{css:mobile_form}
{js:ad}
<form class="m2o-form ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}分类</h1>
            <div class="m2o-l m2o-flex-one">
                <input placeholder="填写接口分类" name="sort_name" id="titles" class="m2o-m-title" value="{$formdata['sort_name']}" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存" class="m2o-save" name="sub" id="sub" />
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
		<div class="m2o-main m2o-flex">
			 <section class="m2o-m m2o-flex-one">
			 	<div class="m2o-item">
        	        <span class="title">分类路径:</span>
					<input type="text"  name='sort_dir' size="40" value="{$formdata['sort_dir']}"/><font class="important">{if($formdata['type'])}复制到的分类路径{else}所属分组接口生成文件路径，必填{/if}</font>
	   			</div>
	   			<div class="m2o-item">
        	        <span class="title">开关:</span>
					<input type="checkbox"  name='agent_switch' value="1" {if $formdata['agent_switch']}checked="checked"{/if}/><font class="important">开启后，需要$_SERVER['HTTP_USER_AGENT']含有下面要求字段</font>
	   			</div>
	   			<div class="m2o-item">
        	        <span class="title">user_agent:</span>
					<input type="text"  name='agent' size="40" value="{$formdata['agent']}"/><font class="important">多个用逗号‘,’分隔</font>
	   			</div>
	 
			 </section>
		</div>
	</div>
	<input type="hidden" name="a" value="{$a}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="type" value="{$formdata['type']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
{template:foot}