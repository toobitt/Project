<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{css:vod_style}
{css:pic_list}
{js:albums_app/albums}
<body class="biaoz"  style="position:relative;z-index:1;background:#f0f0f0;"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}></div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						{code}
							$attr_status = array(
								'class' => 'colonm down_list data_time',
								'show' => 'status_show',
								'width' => 104,
								'state' => 0,
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'date_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_type = array(
								'class' => 'colonm down_list data_time',
								'show' => 'type_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_client = array(
								'class' => 'colonm down_list data_time',
								'show' => 'client_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$typeArr = array(
								'all' => '所有类型'
							);
							foreach ($_configs['type'] as $k => $v)
							{
								$typeArr[$k] = $v['name'];
							}
							$clientType = array(
								0 => '所有客户端'
							);
							foreach ($list[0]['clients'] as $k => $v)
							{
								$clientType[$k] = $v;
							}
							if (!$_INPUT['type']) $_INPUT['type'] = 'all';
							if (!$_INPUT['client']) $_INPUT['client'] = 0;
							if (!$_INPUT['status']) $_INPUT['status'] = 1;
							if (!$_INPUT['date_search']) $_INPUT['date_search'] = 1;
						{/code}
						{template:form/search_source,status,$_INPUT['status'],$_configs['status'],$attr_status}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						{template:form/search_source,type,$_INPUT['type'],$typeArr,$attr_type}
						{template:form/search_source,client,$_INPUT['client'],$clientType,$attr_client}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
					  </div>
					  <div class="text-search">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,k,$_INPUT['k']}                        
					  </div>
	             </form>
			</div>
		</div>
		<form method="post" action="" name="listform">
                <ul class="list_img photo-list handle-list" id="pictures_list">
			    {if $list[0]['photos']}
       			    {foreach $list[0]['photos'] as $k => $v}
                      {template:unit/allpiclist}
                    {/foreach}
				{else}
				<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">该相册没有图片！</p>
		        <script>hg_error_html(pictures_list,1);</script>
  				{/if}
				<!--<li style="height:0px;padding:0;" class="clear"></li>  -->
                </ul>
                <div class="bottom clear">
	            	<div class="left" style="width:400px;">
                		<input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
                		<a style="cursor:pointer;" name="state" class="bataudit" data-method="audit">审核</a>
			    		<a style="cursor:pointer;" name="batdelete" data-method="delete">删除</a>
			    		{if $_configs['type']}
			    			{foreach $_configs['type'] as $k => $v}
			    			<a style="cursor:pointer;" name="{$k}" data-method="{$k}">{$v['name']}</a>
			    			{/foreach}
			    		{/if}
					</div>
					{$pagelink}
            	</div>
		</form>
		<div class="tips"></div>
</div>
</div>
</body>
{template:foot}