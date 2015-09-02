{template:head}
{code}
	foreach($formdata as $k=>$v)
	{
		$$k = $v;
	}
	if($id)
	{
		$optext="更新";
		$a="update";
	}
	else
	{
		$optext="添加";
		$a="create";
	}
{/code}
{css:ad_style}
{js:ad}
{js:common/common_form}
{js:common}
{css:2013/form}
{css:common/common}
{css:mobile_form}
<form class="ad_form h_l" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  id="content_form">
<header class="m2o-header">
  <div class="m2o-inner">
    <div class="m2o-title m2o-flex m2o-flex-center">
        <h1 class="m2o-l">{$optext}证书</h1>
        <div class="m2o-l m2o-flex-one">
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
		 	<ul class="form_ul">
<!-- 
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">APPID: </span><input type="text" name="app_id" size="38" value="{$appid}" {if $a=='update'}disabled="disabled"{/if}/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">APPNAME: </span><input type="text" name="app_name" size="38" value="{$appname}" {if $a=='update'}disabled="disabled"{/if}/>
		</div>
	</li>
 -->
 {if $a == create}
 	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">appid: </span>
			
			{code}
					$attr_app_auth = array(
						'class' => 'transcoding down_list',
						'show'  => 'select_ap_auth',
						'width' => 180,/*列表宽度*/
						'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					);
					$apps = $appAuthInfo[0];
					$apps['-1'] = "-请选择-";
					$appid_auth = $formdata['appid'];
					$appid_auth = $appid_auth ? $appid_auth : -1;
			{/code}
				
			{template:form/search_source,app_id,$appid_auth,$apps,$attr_app_auth}
			<font class="important">已经添加的appid,不在列表内</font>
		</div>
	</li>
{else}
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">APPNAME: </span><input type="text" name="app_name" size="38" value="{$appname}" disabled="disabled"/>
		</div>
	</li>
{/if}
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">开发版: </span><input type="file"  name='develop' accept="application/x-pkcs12" value="{$develop}"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">应用版: </span><input type="file"  name='apply' accept="application/x-pkcs12" value="{$apply}"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">强制更新: </span><input type="checkbox"  {if $force_up }checked="checked"{/if} name='force_up' value="1"/>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">最新版本: </span><input  type="text" name='version'  value="{$version}"/>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">更新地址: </span><input type="text"  size="50" name='up_url'  value="{$up_url}"/>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">关联appid: </span>
			
			{code}
					$attr_app = array(
						'class' => 'transcoding down_list',
						'show'  => 'select_ap',
						'width' => 180,/*列表宽度*/
						'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					);
					
					$app = $appinfo[0];
					$app['-1'] = "-请选择-";
					$link_appid = $formdata['link_appid'];
					$link_appid = $link_appid ? $link_appid : -1;
			{/code}
				
			{template:form/search_source,link_appid,$link_appid,$app,$attr_app}
			<font class="important">当相同token存在时，替换设置appid下设备记录</font>
		</div>
	</li>
</ul>
		 </section>
	</div>
</div>

<input type="hidden" name="a" value="{$a}" />
{if $a==update}
<input type="hidden" name="app_id" value="{$appid}" />
<input type="hidden" name="app_name" value="{$appname}" />
{/if}
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
{template:foot}