{template:head}
{css:common/common_list}
{css:vod_style}
{code}
//print_r($formdata);
{/code}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
               	<form method="post" action="" name="listform">
                   <!-- 标题 -->
                   <ul class="common-list" id="list_head">
                       <li class="common-list-head clear">
                           <div class="common-list-left">
                           </div>
                           <div class="common-list-right">
                               <div class="common-list-item open-close template-cz">操作</div>
                           </div>
                           <div class="common-list-biaoti ">
						       <div class="common-list-item open-close template-biaoti">单元名称</div>
					       </div>
                       </li>
                   </ul>
	               <ul class="common-list" id="tujilist">
					   {if $formdata}
		       			   {foreach $formdata as $k => $v} 
		       			   <li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
							<div class="common-list-left">
						    </div>
							<div class="common-list-right">
						        <div class="common-list-item template-cz">
						            <div class="common-list-cell">
										<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=edit_cell&id={$v['id']}&infrm=1">编辑</a>		
						            </div>
						        </div>
						   </div>
						   <div class="common-list-biaoti">
							    <div class="common-list-item template-biaoti biaoti-transition">
									   <div class="common-list-cell">
								          <span class="common-list-overflow special-biaoti-overflow" onclick="hg_show_opration_info({$v['id']})"><a  id="share_title_{$v['cell_name']}">{$v['cell_name']}</a></span>
						            </div>  
							    </div>
						   </div>
						</li>
		                   {/foreach}
					   {else}
					       <p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
		  			   {/if}
	               </ul>
    			</form>
           </div>
        </div>
      </div>
</body>
{template:foot}