{template:head}
{code}
$list = $tuji_list;
//hg_pre($list);
$image_resource = RESOURCE_URL;
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['tuji_status']))
{
    $_INPUT['tuji_status'] = 0;
}


$columnData = array(
	array(
		'class' => 'tuji-fabu',
		'innerHtml' => '发布至'
	),
	array(
		'class' => 'tuji-fenlei',
		'innerHtml' => '分类'
	),
	array(
		'class' => 'tuji-quanzhong',
		'innerHtml' => '权重'
	),
	array(
		'class' => 'tuji-zhuangtai',
		'innerHtml' => '状态'
	),
	array(
		'class' => 'tuji-ren',
		'innerHtml' => '添加人/时间'
	)
);
$headData = array(
	'class' => 'tuji-list',
	'innerHtml' => array(
		'left' => array(
			'innerHtml' => array(
				array(
					'class' => 'tuji-paixu',
					'innerHtml' => '<a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order(\'tujilist\');"  title="排序模式切换/ALT+R"></a>',
				),
				array(
					'class' => 'tuji-fengmian',
					'innerHtml' => '缩略图'
				)
			)
		),
		'right' => array(
			'innerHtml' => $columnData,
		),
		'biaoti' => array(
			'innerHtml' => array(
				array(
					'class' => 'tuji-biaoti',
					'innerHtml' => '标题'
				)
			)
		)
	)
);
$bottomData = array(
	'audit' => '审核',
	'back' => '打回',
	'delete' => '删除'
); 

$emptyData = array(
	'describe' => '没有您要找的内容！',
	'id' => 'tujilist'
);

{/code}
{js:vod_opration}
{js:tuji}
{js:column_node}
{js:zoomer}
{css:tuji_list}
{css:vod_style}
{css:edit_video_list}
{code}
$status_key = 'status_display';
$attrs_for_edit = array(
	'id', 'click_num', 'click_count' ,'comm_num', 'img_src', 'img_count', 'downcount','pub_url', 'catalog'
);
{/code}
{js:jqueryfn/jquery.tmpl.min}
{template:list/common_list}

<style type="text/css">
{code}
$styles = array(
    1 => array(
        1 => array(-15, 8, 0),
        2 => array(37, 3, 3),
        3 => array(48, 15, -8)
    ),
    2 => array(
        1 => array(-15, 12, -5),
        2 => array(35, 0, 0),
        3 => array(-25, 9, 8)
    ),
    3 => array(
        1 => array(45, 8, -5),
        2 => array(-20, 1, 5),
        3 => array(10, 16, 5)
    ),
    4 => array(
        1 => array(40, 3, 8),
        2 => array(-20, 10, 0),
        3 => array(30, 19, 0)
    )
);
$preStyle = array('-moz-', '-webkit-', '-ms-', '-o-', '');
foreach($styles as $k => $v){
    foreach($v as $kk => $vv){
        echo '.rotate-transform-'.$k.' .rotate-item-'.$kk.'{';
        foreach($preStyle as $vvv){
            echo $vvv.'transform:translate('.$vv[0].'px, '.$vv[1].'px) rotate('.$vv[2].'deg);';
        }
        echo '}';
    }
}
{/code}
</style>



<div class="" {if $_INPUT['infrm']}style="display:none"{/if}>
	<div class="common-list-search" id="info_list_search">
	    <span class="serach-btn"></span>
		<form name="searchform" id="searchform" action="" method="get"
			onsubmit="return hg_del_keywords();" target="">
			<div class="select-search">
				{code}
								$attr_source = array(
									'class' => 'transcoding down_list',
									'show' => 'tuhji_status_show',
									'width' => 104,/*列表宽度*/
									'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								);
	
								$attr_date = array(
									'class' => 'colonm down_list data_time',
									'show' => 'colonm_show',
									'width' => 104,/*列表宽度*/
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$attr_weight = array(
									'class'  => 'colonm down_list data_time',
									'show'   => 'weight_show',
									'width'  => 104 /*列表宽度*/,
								);						
	
				{/code}
				{code}
					$column_default = $_INPUT['pub_column_id'] ? $_INPUT['pub_column_id'] : 0;
					if( $column_default ==0 ) {
						$column_list = 	array(
							0 => '栏目'
						);
					}else{
						$column_list = split(',', $_INPUT['pub_column_name'] );
					}
					$attr_column = array(
						'class' => 'pub_column_search down_list',
						'show' => 'pub_column_show',
						'select_column' => $_INPUT['pub_column_name'],
						'width' => 90,/*列表宽度*/
						'state' => 4 /*0--正常数据选择列表，1--日期选择, 2--input自动检索, 3--失去焦点搜索*,4--栏目搜索*/
					);
				{/code}
				{template:form/search_source,status,$_INPUT['status'],$_configs['image_upload_status'],$attr_source}
				{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
				{template:form/search_source,pub_column_id,$column_default,$column_list,$attr_column}
				{template:form/search_weight,weight_search,$_INPUT['weight_search'],$_configs['weight_search'],$attr_weight}
				<input type="hidden" name="a" value="show" /> <input type="hidden"
					name="mid" value="{$_INPUT['mid']}" /> <input type="hidden"
					name="infrm" value="{$_INPUT['infrm']}" /> <input type="hidden"
					name="_id" value="{$_INPUT['_id']}" /> <input type="hidden"
					name="_type" value="{$_INPUT['_type']}" />
				<input type="hidden" name="node_en" value="{$_INPUT['node_en']}" />
			</div>
			<div class="text-search">
				<div class="button_search">
					<input type="submit" value="" name="hg_search"
						style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
				</div>
				{template:form/search_input,k,$_INPUT['k']}
			</div>
			<div class="custom-search">
			{code}
				$attr_creater = array(
					'class' => 'custom-item',
					'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'添加人'
				);
			{/code}
			{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
		</div>
		</form>
	</div>	
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		{if $_configs['is_cloud']}<a class="add-yuan-btn add-button news mr10"  gmid="{$_INPUT['mid']}" nodevar="tuji_node">{$_configs['is_cloud']}</a>{/if}
		 <a class="blue mr10" href="run.php?mid={$_INPUT['mid']}&a=tuji_form&infrm=1" target="formwin">
	               <span class="left"></span>
	               <span class="middle"><em class="add">新增图集</em></span>
	               <span class="right"></span>
	            </a>
	            <a class="gray mr10" href="run.php?mid={$_INPUT['mid']}&a=configuare&infrm=1" target="mainwin">
	                <span class="left"></span>
	                <span class="middle"><em class="set">配置图集库</em></span>
	                <span class="right"></span>
	             </a>
	</div>
</div>
{template:list/ajax_pub}
<div class="common-list-content" style="min-height:auto;min-width:auto;">

			<!-- 新增图集模板开始 -->
		 	<div id="add_tuji"  class="single_upload" style="min-height:1300px;">
				<h2><span class="b" onclick="hg_closeTuJiTpl();"></span><span id="tuji_title">新增图集</span></h2>
				<div id="tuji_contents_form"  class="upload_form" style="height:1300px;margin-top:10px;overflow:auto;"></div>
			</div>
			<!-- 新增图集模板结束 -->

 		    <!-- 移动图集模板开始 -->
		 	<div id="move_tuji"  class="single_upload">
				<h2><span class="b" onclick="hg_showMoveTuJi();"></span><span id="move_title">移动图集</span></h2>
				<div id="tuji_sort_form"  class="upload_form" style="height:808px;margin-top:10px;overflow:auto;"></div>
			</div>
			<!-- 移动图集模板结束 -->

    			{template:list/list_column}
                <form method="post" action="" name="listform" style="display:block;position:relative;" class="common-list-form">
                	<ul class="common-list tuji-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu open-close">
                                     <a title="排序模式切换/ALT+R" onclick="hg_switch_order('tujilist');"  class="common-list-paixu"></a>
                                </div>
                                <!-- <div class="common-list-item open-close wd150">缩略图</div> -->
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item tuji-fabu common-list-pub-overflow">发布至</div>
                                <div class="common-list-item  tuji-fenlei open-close wd80">分类</div>
                                <div class="common-list-item tuji-quanzhong open-close wd60">权重</div>
                                <div class="common-list-item tuji-zhuangtai open-close wd60">状态</div>
                                <div class="common-list-item tuji-tuisong open-close wd60">推送</div>
                                <div class="common-list-item tuji-ren open-close wd100">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">标题</div>
					        </div>
                        </li>
                     </ul>
                    <ul class="tuji-list common-list public-list hg_sortable_list" id="tujilist" data-table_name="tuji" data-order_name="order_id">
					{if $list}
                   	{foreach $list as $k => $v}
                    	{template:unit/tuji_row}
                    {/foreach}
                    {else}
                        {template:list/list_empty}
                    {/if}
                    </ul>
                    <ul class="common-list public-list">
						<li class="common-list-bottom clear">
							<div class="common-list-left">
								<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a>
								<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
								<a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">签发</a>
								<a style="cursor:pointer;" onclick="return hg_bacthmove_show(this,'tuji_node');">移动</a>
                       			<a style="cursor:pointer;" onclick="return hg_bacthspecial_show(this);" name="publish">专题</a>
                       			<a style="cursor:pointer;" onclick="return hg_bacthblock_show(this);" name="block">区块</a>
                       			{if 1 == 1}
                                    <a style="cursor:pointer;"   onclick="return hg_ajax_batchpost(this,'','推送',1,'id','','ajax');" name="outpush">推送</a>
                                {/if}
                       		</div>
                       		{$pagelink}
                    	</li>
                    </ul>
                    <div class="edit_show">
						<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
						<div id="edit_show"></div>
					</div>
    			</form>
    			
    		


<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
   
</div>

<div id="add_share"></div>
{template:unit/record_edit}
<!-- 移动框 -->
{template:unit/list_move_box}

<script type="text/javascript">
$(function(){
    $('.tuji-fengmian').on('click', function(){
        return;
        var li = $(this).closest('.common-list-data');
        if(li.hasClass('open')){
            li.removeClass('open');
        }else{
            $('.common-list-data.open').removeClass('open');
            li.addClass('open');
        }
        hg_open_tuji(li.attr('_id'));
    });


    {js:common/preloadimg}
    $('.rotate-img').each(function(){
        $(this).preLoadImg({
            height : 60,
            height : 45,
            src : $(this).attr('_src'),
            loading : true,
            callback : function(){
                $(this).removeAttr('_src');
            }
        });
    });
    
    /*缓存页面的打开的标题个数*/
    $.commonListCache('tuji-list');
});
</script>
{template:foot}