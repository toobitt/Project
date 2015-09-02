{template:head}
{css:vod_style}
{js:jquery-ui-1.8.16.custom.min}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}">
	               <span class="left"></span>
	               <span class="middle"><em class="add">添加分类</em></span>
	               <span class="right"></span>
	</a>
</div>
<div class="clear" style="display:none;">
 <div class="f">
      <div class="right v_list_show" style="width:100%;">
            <div class="search_a" id="info_list_search">
              <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                <div class="right_2" style="display:none;">
                    <div class="button_search">
                        <input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                    </div>
                    {template:form/search_input,k,$_INPUT['k']}
                </div>
                </form>
            </div>
       </div>
    </div>
</div>
{template:unit/sort, district_node, $district_node_list}
</body>
{template:foot}