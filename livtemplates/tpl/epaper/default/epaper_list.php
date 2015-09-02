{template:head}
{code}
if(!$_INPUT['status_show'])
{
	$_INPUT['status_show']= -1;
}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
{/code}
{css:2013/m2o}
{css:2013/iframe}
{css:2013/button}
{css:epaper_list}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload_new}
{js:epaper/epaper_add_list}
<pre>
	{code}
		//print_r($list);
	{/code}
</pre>
<script type="text/javascript">
function hg_audit_play(id)
{   
	var url = "run.php?mid="+gMid+"&a=audit&id="+id;
	hg_ajax_post(url);
}

/*审核回调*/
function hg_audit_play_callback(obj)
{
	var obj = eval('('+obj+')');
	$('#status_'+obj.id).text(obj.status);
}

$(function($){
	$.globalselect = {code} echo  $_configs['date_select'] ?  json_encode($_configs['date_select']) : '{}'; {/code};
})

</script>

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/epaper_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
	</div>
</div>	
<div class="wrap clear">
<div class="epaper-wrap">
  <ul class="epaper-list play-list clear">
	<li class="add-papers">
    	<p ><img src="{$RESOURCE_URL}card/1-2x.png" class="add-img"/></p>
        <span>新增电子报</span>
    </li>
	   {foreach  $list  as $k => $v}
	     {template:unit/epaperlist}
	   {/foreach}
  </ul>
<!--<div class="epaper-bottom clear">
  	 <div class="epaper-operate">
  	 	<input type="checkbox" name="checkall" id="checkAll" />
  	    <a name="state" data-method="audit" class="bataudit">审核</a>
  	    <a name="back" data-method="back" class="batback">打回</a>
  	    <a name="batdelete" data-method="delete" class="batdelete">删除</a>
  	 </div>
   </div> --> 
</div>
 <div id="infotip"  class="ordertip"></div>
</div>
<div class="prevent-go"></div>
<div class="dialog" >
     <form class="submit-form" id="news-form" action="run.php?mid={$_INPUT['mid']}&a=create" method="post" data-id="{$id}" >
		<div class="add-title"><span >新增电子报</span>
			<input type="submit" value="保存" class="pop-save-button" style="position: absolute;top: 5px;right: 14px;"/>
			<input type="button"  class="pop-close-button2" style="float: right;" />		  
		</div>
		<div class="addpaper m2o-flex">
			<div class="addpic" >
				<div name="picture" class="pic">
					<div>
						<p style="background:url({$RESOURCE_URL}card/1-2x.png) no-repeat;" class="add-pic"> </p>
						<img style="width:100px;height:100px;">    
					</div>
				</div>
				<span style="margin-left:37px;">电子报图标</span>
				<input type="file" name="Filedata" class="photo-file" style="display:none;">	
			</div>
			<!-- 表单右侧 -->
		    <div class="addpapernews m2o-flex-one">
				<div class="all-value" >
				    <div class="form-item m2o-flex">
					  	<span class="item-name">名称：</span>
						<div class="m2o-flex-one">
							<input name="title"  type="text" class="input-item name"><br/>
					  	</div>
					</div>
					<div class="form-item m2o-flex">
						<span class="item-name">类型：</span>
						<div class="m2o-flex-one">
							 {code}
			                    $type_item_source = array(
			                        'class' 	=> 'down_list list',
			                        'show' 		=> 'type_show',
			                        'width'     => 226,/*列表宽度*/
			                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
			                        'is_sub'	=>	1,
			                    );
			                    
			                    if($type)
			                    {
			                    	$type_default = $type;
			                    }
			                    else
			                    {
			                    	$type_default = -1;
			                    }
		                        $_configs['date_select'][-1] = '--选择类型--';
			                {/code}
			                {template:form/search_source,type,$type_default,$_configs['date_select'],$type_item_source}
						</div>
					</div>
					<div class="form-item m2o-flex">
					  	<span class="item-name">初始期数：</span>
						<div class="m2o-flex-one">
							<input name="period" type="text" class="input-item initial_stage"><br/>
					  	</div>
					</div>
					<div class="form-item m2o-flex">
					  	<span class="item-name">初始日期：</span>
						<div class="m2o-flex-one with-arrow">
							<input name="date" type="text" class="input-item date-picker" readonly="readonly" placeholder="--请选择初始日期--">
					  	</div>
					</div>
					<div class="form-item m2o-flex">
						<span class="item-name">出版社：</span>
						<div class="m2o-flex-one">
							<input name="publishing_company" type="text" class="input-item pub_com"><br/>
						</div>
					</div>
					<div class="form-item m2o-flex">
						<span class="item-name">出版物号：</span>
						<div class="m2o-flex-one">
							<input name="number" type="text" class="input-item"><br/>
						</div>
					</div>
					<div class="form-item m2o-flex">
						<span class="item-name">代号：</span>
						<div class="m2o-flex-one">
							<input name="code"  type="text" class="input-item"><br/>
						</div>
					</div>
					<div class="form-item m2o-flex">
						<span class="item-name">主办单位：</span>
						<div class="m2o-flex-one">
							<input name="unit" type="text" class="input-item unit"><br/>
						</div>
					</div>
				</div>
			</div>
			<!-- 表单右侧end -->
		</div>
	</form>
</div> 



<!-- dialog插入模板 -->
<script type="text/x-jquery-tmpl" id="dialog-tpl">
    <li _id="${id}" id="r_${id}" name="${id}" class="epaper-each">
  	     <div class="epaper-profile m2o-flex">     
  		     <div class="epaper-img">
  			 //  <img _src="" />  
  		     </div>		 	 		  
  		  <div class="epaper-brief m2o-flex-one">
  			<div class="epaper-status"><label><span>${update_time}</span><span style="margin-left:4px;">0期</span></label><span _id="${id}"  _status="${status}" class="reaudit" ></span></div>
			<div class="epaper-endtime"><lable>0叠/0版 </lable></div>
  			<div class="epaper-addtime"><span>${user_name}</span><span style="margin-left:4px;">${update_time}</span></div>
  		  </div>
  	     </div>
  	        <a class="del"></a>
		    <div class="edit">
		        <div class="add-news"><a href="./run.php?a=relate_module_show&app_uniq=epaper&mod_uniq=period&mod_a=form&epaper_id=${id}&epaper_name=${name}&cur_stage=${init_stage}&cur_date=${init_time}&infrm=1"  target="mainwin" need-back>新增一期</a></div><br/>
				<div class="edit-news"><a>编辑新闻</a></div>
				<div class="edit-link"><a>编辑链接</a></div>                                                                           
		   </div>
         <div class="news-logo">
		    <div class="logo">
                  <img src="${picture}" style="width:73px;height:23px;">
              </div>
			       <label class="news-status"><b>${value}</b></label>
            <a class="oldpaper" href="./run.php?a=relate_module_show&app_uniq=epaper&mod_uniq=period&epaper_id=${id}&epaper_name=${name}&infrm=1" target="mainwin" need-back></a>
		 </div>
     	</li>
</script>

{template:foot}


