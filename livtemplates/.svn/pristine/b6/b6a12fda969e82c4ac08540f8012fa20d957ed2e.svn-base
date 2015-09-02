{template:head}
{code}
{/code}
{css:2013/list}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/list}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="right v_list_show">
	                <div class="search_a" id="info_list_search">
	                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
	                    {code}	
					$time_css = array(
						'class' => 'transcoding down_list',
						'show' => 'time_item',
						'width' => 120,	
						'state' => 1,/*0--正常数据选择列表，1--日期选择*/
						'para'=> array('fid'=>$_INPUT['fid']),
					);
					$_INPUT['create_time'] = $_INPUT['create_time'] ? $_INPUT['create_time'] : 1;
					
					if(!$_INPUT['site_id'])
					{
						$_INPUT['site_id'] = 1;
					}
					//获取所有站点
					$hg_sites = array();
					foreach ($publish->getallsites() as $index => $value) {
						$hg_sites[$index] = $value;
					}
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
	<div class="m2o-list">
	        <div class="m2o-title m2o-flex m2o-flex-center">
	            <div class="m2o-item m2o-flex-one m2o-column" title="栏目">栏目</div>
	            <div class="m2o-item m2o-home-template" title="首页模板">首页模板</div>
	            <div class="m2o-item m2o-list-template" title="列表模板">列表模板</div>
	            <div class="m2o-item m2o-content-template" title="内容模板">内容模板</div>
	        </div>
	        <div class="m2o-content">
	        <div class="m2o-each m2o-flex m2o-flex-center">
	            <div class="m2o-item m2o-flex-one m2o-column">
	            	<div class="m2o-title-transition max-wd">
	            	<a class="m2o-title-overflow">
	                   <span>新闻首页</span>
	                </a>
	                </div>
	            </div>
	            <div class="m2o-item m2o-home-template"><a>xgcols.html</a></div>
	            <div class="m2o-item m2o-list-template">沿用 看无锡</div>
	            <div class="m2o-item m2o-content-template"><a>文稿：new_content.html(6)</a></div>
	        </div>
	        <div class="m2o-each m2o-flex m2o-flex-center">
	            <div class="m2o-item m2o-flex-one m2o-column">
	            	<div class="m2o-title-transition max-wd">
	            	<a class="m2o-title-overflow">
	                   <span>新闻首页</span>
	                </a>
	                </div>
	            </div>
	            <div class="m2o-item m2o-home-template"><a>xgcols.html</a></div>
	            <div class="m2o-item m2o-list-template"><a>xgindex.html</a></div>
	            <div class="m2o-item m2o-content-template">文稿 新闻首页</div>
	        </div>
	    </div>
	</div>
</div>
</body>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
			<div class="m2o-option-list">
        	<ul>
        		<li>
        			<p class="info"><span class="title">首页：</span>asdf.html</p>
        			<div class="icons">
        				<a class="icon reelect"></a>
        				<a class="icon magic"></a>
        				<a class="icon create"></a>
        				<a class="icon preview"></a>
        				<a class="icon more"></a>
        			</div>
        		</li>
        		<li>
        			<p class="info"><span class="title">首页：</span>asdf.html</p>
        			<div class="icons">
        				<a class="icon reelect"></a>
        				<a class="icon magic"></a>
        				<a class="icon create"></a>
        				<a class="icon preview"></a>
        				<a class="icon more"></a>
        			</div>
        		</li>
        	</ul>
        </div>
    </div>
	<div class="m2o-option-close"></div>
</div>
</script>

{template:foot}