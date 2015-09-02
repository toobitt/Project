{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
{js:common/common_list}
{css:common/common_list}
{code}
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
//获取所有站点
$hg_sites = $publish->getallsites();
if(!$_INPUT['site_id'])
{
	$_INPUT['site_id'] = 1;
}
{/code}
<style type="text/css">
.iframe{width:150px;height:800px;}
.iframe2{width:100%;height:800px;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
 <div class="f">
      <div class="right v_list_show">
            <div class="search_a" id="info_list_search">
              <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                <div class="select-search">
                	{code}
							$attr_site = array(
								'class'  => 'colonm down_list date_time',
								'show'   => 'app_show',
								'width'  => 104,
								'state'  => 0,
							);    
                	{/code}
                	{template:form/search_source,site_id,$_INPUT['site_id'],$hg_sites,$attr_site}
					<input type="hidden" name="a" value="show" />
					<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
                </div>
                <div class="right_2">
                	<div class="button_search">
						<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                    </div>
                    {template:form/search_input,keyword,$_INPUT['keyword']} 
                </div>
              </form>
       		</div>
    	</div>   
        <div style="width:150px;height:800px;border-right:#000 solid 1px;">
        	<ul>
        	{foreach $cell_list as $v}
            	<li>
                 	<a style="font-size:14px;display:inline-block;" href="./run.php?mid={$_INPUT['mid']}&a=get_content_type&infrm=1&site_id={$v['site_id']}&page_id={$v['page_id']}&page_data_id={$v['page_data_id']}" target="content_type_iframe">
                 <span class="m2o-common-title">{$v['name']}</span>
                 	</a>
                 	{if !$v['is_last']}
                 	<a style="font-size:14px;" href="./run.php?mid={$_INPUT['mid']}&a=show&infrm=1&site_id={$v['site_id']}&page_id={$v['page_id']}&fid={$v['page_data_id']}"> > </a>
                 	{/if}
            	</li>
        	{/foreach}  
        	</ul>           
        </div>
        <div style="width:150px;height:800px;margin-left:150px;margin-top:-800px;border-right:#000 solid 1px;">
             <iframe class="iframe" src="./run.php?mid={$_INPUT['mid']}&a=get_content_type&infrm=1&site_id={$_INPUT['site_id']}&page_id={$_INPUT['page_id']}&page_data_id={$_INPUT['page_data_id']}"  name="content_type_iframe" id="content_type_iframe"></iframe>
        </div>
        <div style="height:800px;margin-left:300px;margin-top:-800px;overflow:auto;">
             <iframe class="iframe2" src=""  name="cell_list_iframe" id="cell_list_iframe"></iframe>
        </div>     	    	      
</div>
<script>
function hg_cell_search_back(obj)
{
	$("#cell_list").html(obj);
}
</script>
</body>
{template:foot}