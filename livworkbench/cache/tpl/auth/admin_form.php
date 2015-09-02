<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php echo $_scroll_style;?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->mTemplatesTitle;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
-->
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/ad_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>auth/column_node.css" />
<script type="text/javascript">
var gPixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
var RESOURCE_URL = '<?php echo $RESOURCE_URL;?>';
var SCRIPT_URL = '<?php echo $SCRIPT_URL;?>';
var client_id = 1;
var gMid = '<?php echo $_INPUT['mid'];?>';
var gMenuid = '<?php echo $_INPUT['menuid'];?>';
var gRelate_module_id = '<?php echo $relate_module_id;?>';
var gToken = "<?php echo $_user['token'];?>";
var show_conf_menu='<?php echo $show_conf_menu;?>';
</script>
<?php echo $this->mHeaderCode;?><script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery-ui-min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery.form.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jquery.tmpl.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_datepicker.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jqueryfn/jquery.switchable-2.0.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_switchable.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>global.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>alertbox.min.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>alertbox.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>md5.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>ajax.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>swfupload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>swfupload.queue.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>fileprogress.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>handlers.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>livUpload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>upload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>vod.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>swfobject.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>lazyload.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/pic_edit.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>alert/jquery.alerts.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>ad.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>column_node.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>auth/select_role.js"></script>
<script>
jQuery(function($){
    $.pixelRatio = gPixelRatio;
    if($.pixelRatio > 1){
        $('img.need-ratio').each(function(){
            $(this).attr('src', $(this).attr('_src2x'));
        });
    }
});
</script>
<?php if($_INPUT['infrm']){ ?>
<style type="text/css">
	/*body{background:#fff;}
	.wrap{border:0;box-shadow:none;padding:10px 0 10px 10px;}*/
	.wrap .search_a{padding:0}
</style>
<?php } ?>
</head>
<body<?php echo $this->mBodyCode;?><?php echo $_scroll_style;?>>
<?php 
//hg_pre($_nav);
 ?>
<?php if($_nav){ ?>
<div class="nav-box">
     <div class="choice-area" id="hg_info_list_search">
      </div>
      <div class="controll-area fr mt5" id="hg_parent_page_menu">
       </div>
</div>
<?php } ?><?php 
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
	$role_id = $formdata['admin_role_id'];
 ?><div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2><?php echo $optext;?>用户</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">用户名：</span><input type="text" value='<?php echo $formdata["user_name"];?>' name='user_name' class="title">
	</div>
</li><li class="i">
	<div class="form_ul_div clear">
		<span class="title">密码：</span><input type="text" name='password' class="title"><font class="important">不填默认不修改密码</font>
	</div>
</li><li class="i">
	<div class="form_ul_div">	
		<span class="title">描述：</span>
<textarea name="brief" rows="<?php echo $hg_attr['height'];?>" cols="<?php echo $hg_attr['width'];?>"  class="t_c_b" onfocus="textarea_value_onfocus(this,'这里输入描述');" onblur="textarea_value_onblur(this,'这里输入描述');"><?php echo $formdata['brief'];?></textarea>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">选择角色：</span>
	<div class="select_role"></div>
	</div>
</li>
<li class="i">
    <div class="form_ul_div clear">
    <span class="title">上级组织：</span>
    <?php 
        $hg_attr['node_en'] = 'admin_org';
     ?>
    <style type="text/css">.part .css_column_id li{margin:0px 5px 2px 0px;}
</style>
<?php 
	$__call_column_count  = $hg_attr['_callcounter'] ? $hg_attr['_callcounter'] : intval($__call_column_count);
	$__hg_Pre = 'hgCounter_'.$__call_column_count.'_';
	$formdata['father_org_id'] = $formdata['father_org_id'] ? (is_array($formdata['father_org_id']) ? implode(',', $formdata['father_org_id']) : $formdata['father_org_id']) : array();
	if (!$hg_attr['multiple'])
	{
		$inputtype = 'radio';
		$hg_multiple_suffix = '';
	}
	else
	{
		$inputtype = 'checkbox';
		$hg_multiple_suffix = '[]';
	}
	$_exclude = !$hg_attr['exclude'] ? "_exclude=".$_INPUT['id']: '';
	if(!$node_data)
	{
		if(!is_array($node_data) && $hg_attr['node_en'])
		{
			if(!class_exists('hg_get_node'))
			{
				include_once(ROOT_DIR . 'get_node.php');
			}
			/** $hg_attr['expand']是扩展数据，一维数组格式，如：$hg_attr['expand']=array('a'=>1,'b'=>2)，如根据条件选择对应节点，传递数据到节点方法，节点直接$this->input['a'] */
			$node_object = new hg_get_node($hg_attr['node_en'],$hg_attr['expand']);
			if($_exclude)
			{
				$node_info = $node_object->get_level1_node($_INPUT['id']);
			}
			else
			{
				$_exclude .= '_exclude=-1';
				$node_info = $node_object->get_level1_node();
			}			$node_data = $node_info['data'];
			if($node_info['curl_info'])
			{
				foreach($node_info['curl_info']as $k=>$v)
				{
					$$k = $v;
				}
			}
			if($formdata['father_org_id'])
			{
				$ret = $node_object->getNodeInfoByIds($formdata['father_org_id']);
				$formdata['father_org_id'] = $ret[0];
			}
		}
	}
	$hg_attr['fid'] = $hg_attr['fid'] ? $hg_attr['fid'] : 0;
	$hg_attr['request_url'] = '_fetch_node.php?';
	if($_exclude)
	{
		$hg_attr['request_url'] .= $_exclude;
	}
	if($hg_attr['multiple_node'])
	{
		$hg_sites = $hg_attr['multiple_node'];
			if(count($hg_sites) == 1)
			{
				$hg_attr['multiple_site'] = 0;
			}
	}
 ?>
<script type="text/javascript">
	if(typeof hg_itoggle != 'object')
	{
		var hg_itoggle = {};
	}
	hg_itoggle['<?php echo $__hg_Pre;?>'] = 0;
	if(typeof ghasChangedColor != 'object')
	{
		var ghasChangedColor = {};
	}
	ghasChangedColor['<?php echo $__hg_Pre;?>'] = [];
	if(typeof gCurrentlist != 'object')
	{
		var gCurrentlist = {};
	}
	gCurrentlist['<?php echo $__hg_Pre;?>'] = 1;
	if(typeof gColTempFid != 'object')
	{
		var gColTempFid = {};
	}
	gColTempFid['<?php echo $__hg_Pre;?>'] = 0;
</script>
<?php if($hg_attr['slidedown']){ ?>
<script type="text/javascript">
	hg_itoggle['<?php echo $__hg_Pre;?>'] = 1;	$(document).ready(function(){		$("form input[name^='_node_id']").change(function(event){			var nid = $(this).val();
			var state = $(this).attr("checked");
			$("#node"+nid).show();
			$("#node"+nid).find('input').attr('disabled',false);
			var vv = $("#node"+nid).attr('id');
			if(state == 'checked')
			{
				if(!vv)
				{
					var node_name = $(this).next().text() + '的设置';					var str = $("#node_one .node_moban").html();
					re=new RegExp("nodeid","g");
					var newstr=str.replace(re,nid);
					$(newstr).prependTo("#clone_node");					$("#clone_node h2").first().text(node_name);
					$("#clone_node").find('ul').first().attr("id",'node'+nid);
					$("#clone_node").find('div').first().show();
				}			}
			else
			{
				$("#node"+nid).find('input').attr('disabled','disabled');
				$("#node"+nid).hide();
			}
		});
	});
	function hg_change_multinode(counter, formname,formtype,url, siteid)
	{
		$('#'+counter+'column_id').html('');
		$('#'+counter+'hg_selected_hidden').html('');
		siteid = $('#'+counter+'siteid').val() ? $('#'+counter+'siteid').val() : siteid;
		var url = url+'&counter='+counter+'&formtype='+formtype+'&formname='+formname+'&formurl='+url+'&multi='+siteid;
		hg_request_to(url,{}, '','hg_show_coltype');
		if(hg_itoggle[counter] == 0)
		{
			hg_openall(counter);
			hg_itoggle[counter] = 1;
		}
	}
</script>
<?php } ?>
<div class="info_all_node clear" style="float: left; width: 525px;">
<!--多站点切换-->
<?php if($hg_attr['multiple_site']){ ?>
<div class="info_top clear">
<?php 
			/*select样式*/
			$site_style = array(
			'class' => 'down_list i',
			'show' => $__hg_Pre.'site_ul',
			'width' => 95,
			'state' => 0,
			'is_sub'=>1,
			'onclick'=>"hg_change_multinode('$__hg_Pre','father_org_id$hg_multiple_suffix','$inputtype','$hg_attr[request_url]','$hg_attr[siteid]')",
			);
			$_default_site =  $hg_attr['siteid'];
		 ?>
		<?php 
$site_style['class'] = $site_style['class'] ? $site_style['class']:'transcoding down_list';
$site_style['show'] = $site_style['show'] ? $site_style['show'] :'transcoding_show';
$site_style['type'] = $site_style['type'] ? 1:0;
if($site_style['width'] && $site_style['width'] != 104 ){
	$width = $site_style['width'];
}else{
	$width = 90;
}
 ?>
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="<?php echo $site_style['class'];?>" style="width:<?php  echo $width . 'px' ?>;"   onmouseover="hg_search_show(1,'<?php echo $site_style['show'];?>','<?php echo $site_style['extra_div'];?>', this);" onmousemove="<?php echo $site_style['extra_over'];?>"  onmouseout="hg_search_show(0,'<?php echo $site_style['show'];?>','<?php echo $site_style['extra_div'];?>', this);<?php echo $site_style['extra_out'];?>">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_<?php echo $site_style['show'];?>" class="overflow" <?php if($site_style['state'] == 4){ ?>onclick="hg_open_column(this)"<?php } ?>><?php echo $hg_sites[$_default_site];?></label></a></span>
	<ul id="<?php echo $site_style['show'];?>" style="display:none;"  class="<?php echo $site_style['show'];?> defer-hover-target">
		<?php if($site_style['state'] == 2){ ?>
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="<?php echo $__hg_Pre.siteid; ?>_key" class="<?php echo $__hg_Pre.siteid; ?>_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '<?php echo $site_style['method'];?>', '<?php echo $site_style['key'];?>'  )){};" />
	 	</div>
		<?php } ?>
		<?php if($hg_sites){ ?>
		<?php foreach ($hg_sites as $k => $v){ ?>
			<?php if($site_style['state'] == 4){ ?>
			<li><a class="overflow"><?php echo $v;?></a></li>
			<?php } else { ?>
		<?php 
			if($site_style['is_sub'])
			{
				$is_sub = 0;
			}
			else
			{
				$is_sub = 1;
				if($k === 'other')
				{
					$is_sub = 0;
				}
			}
			if($site_style['href'])
			{
				if(!strpos($site_style['href'],'fid='))
				{
					$expandhref=$site_style['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$site_style['href'].$k;
				}
			}
		 ?>
				<li style="cursor:pointer;" <?php echo $site_style['extra_li'];?>><a <?php if($site_style['href']){ ?>href="<?php echo $expandhref;?>"<?php } else { ?>href="###" onclick="if(hg_select_value(this,<?php echo $site_style['state'];?>,'<?php echo $site_style['show'];?>','<?php echo $__hg_Pre.siteid; ?><?php  echo $site_style['more']?'_'.$site_style['more']:''; ?>',<?php echo $is_sub;?>)){<?php echo $site_style['onclick'];?>};"<?php } ?>   attrid="<?php echo $k;?>" class="overflow"><?php echo $v;?></a></li>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php if($site_style['state'] == 4){ ?><input type="hidden" name="pub_column_name" value="<?php echo $site_style['select_column'];?>" /><?php } ?>
</div>
<?php if($site_style['state'] == 1){ ?>
<?php 
$start_time = 'start_time' . $site_style['time_name'];
$end_time = 'end_time' . $site_style['time_name'];
 ?>
	<div class="input" <?php if($_default_site == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;border-right:1px solid #cfcfcf;float: left;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;display:none;float: left;border-right:1px solid #cfcfcf;" <?php } ?> id="start_time_box<?php echo $__hg_Pre.siteid; ?>">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="<?php echo $start_time;?>" id="start_time<?php echo $__hg_Pre.siteid; ?>" autocomplete="off" size="12" value="<?php echo $_INPUT[$start_time];?>" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
	<div class="input" <?php if($_default_site == 'other'){ ?> style="width:<?php  echo $width . 'px' ?>;display:block;float: left;border-right:1px solid #cfcfcf;" <?php } else { ?> style="width:<?php  echo $width . 'px' ?>;float: left;display:none;border-right:1px solid #cfcfcf;" <?php } ?>  id="end_time_box<?php echo $__hg_Pre.siteid; ?>">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="<?php echo $end_time;?>" id="end_time<?php echo $__hg_Pre.siteid; ?>" autocomplete="off" size="12" value="<?php echo $_INPUT[$end_time];?>" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" <?php if($_default_site == 'other'){ ?>style="display: block;margin-top:10px;" <?php } else { ?> style=" display:none;" <?php } ?>id="go_date<?php echo $__hg_Pre.siteid; ?>" class="btn_search" />
<?php } ?>
<?php if($site_style['more']){ ?>
	<input type="hidden" name="<?php echo $__hg_Pre.siteid; ?>[<?php echo $site_style['more'];?>]"  id="<?php echo $__hg_Pre.siteid; ?>_<?php echo $site_style['more'];?>"  value="<?php echo $_default_site;?>"/>
<?php } else { ?>
<?php if(strstr($hg_name,'[]')){ ?>
<input type="hidden" name="<?php echo $__hg_Pre.siteid; ?>" value="<?php echo $_default_site;?>"/>
<?php } else { ?>
<input type="hidden" name="<?php echo $__hg_Pre.siteid; ?>"  id="<?php echo $__hg_Pre.siteid; ?>"  value="<?php echo $_default_site;?>"/>
<?php } ?>
<?php } ?>
<?php if($site_style['para']){ ?>
	<?php foreach ($site_style['para'] as $k => $v){ ?>
	<input type="hidden" name="<?php echo $k;?>"  id="<?php echo $k;?>"  value="<?php echo $v;?>"/>
	<?php } ?>
<?php } ?></div>
<?php } else { ?>
<input type="hidden" value="<?php echo $hg_attr['node_en'];?>" id="<?php echo $__hg_Pre.siteid;?>">
<?php } ?>
<div class="info_show" style="padding: 0px;">
	<ul class="part clear">
		<li id="<?php echo $__hg_Pre;?>show">
<!--		   <span class=<?php if($hg_attr['multiple_site']){ ?>"col_sort l"<?php } else { ?>"col_sort"<?php } ?> ></span>-->
		   <ul id="<?php echo $__hg_Pre;?>column_id" class="clear css_column_id">
			<?php if($formdata['father_org_id']){ ?>
				<?php foreach ($formdata['father_org_id'] as $k=>$v){ ?>
					<li id="<?php echo $__hg_Pre;?>li_<?php echo $k;?>">
						<span class="a"></span>
						<span class="b"></span>
						<span class="c overflow"><?php echo $v;?></span>
						<span class="close" onclick="hg_cancell_selected('<?php echo $k;?>', '<?php echo $__hg_Pre;?>')" ></span>
					</li>
				<?php } ?>
			<?php } ?>
		   </ul>
		</li>
		<li id="<?php echo $__hg_Pre;?>column" class="clear shows" style="border:0px;padding: 0px;">
			<div id="<?php echo $__hg_Pre;?>all" class='css_all'>
				<div class="pub_div_bg clear" id="<?php echo $__hg_Pre;?>allcol">
					 <div class="pub_div clear" id="<?php echo $__hg_Pre;?>level_1">
						<ul id="<?php echo $__hg_Pre;?>level1col">							<li class="first"><span class="checkbox"></span><a href="##">最近使用<strong>»</strong></a></li>
							<?php foreach ($node_data as $index=>$value){ ?>
							<?php 
								$checked = '';
								if(in_array($value['name'], $formdata['father_org_id']))
								{
									$checked = 'checked = "checked"';
								}
							 ?>
							<li>
							<input name="_father_org_id<?php echo $hg_multiple_suffix;?>" type="<?php echo $inputtype;?>" <?php echo $checked;?> value="<?php echo $value['id'];?>" class="checkbox" onclick="hg_selected_col('<?php echo $value['name'];?>',this.value,event,'<?php echo $__hg_Pre;?>','father_org_id<?php echo $hg_multiple_suffix;?>','<?php echo $inputtype;?>')" id="<?php echo $__hg_Pre;?>checkbox_<?php echo $value['id'];?>"/>
							<a class="overflow" href="javascript:void(0)" <?php if(!$value['is_last']){ ?>onclick="hg_getcol_childs(event,'<?php echo $__hg_Pre;?>','father_org_id<?php echo $hg_multiple_suffix;?>','<?php echo $inputtype;?>','<?php echo $hg_attr[request_url];?>',<?php echo $value['id'];?>,1)"<?php } else { ?>onclick="hg_coldbclick('<?php echo $value['name'];?>',<?php echo $value['id'];?>,event,'<?php echo $__hg_Pre;?>','father_org_id<?php echo $hg_multiple_suffix;?>','<?php echo $inputtype;?>')"<?php } ?> id="<?php echo $__hg_Pre;?>hg_colid_<?php echo $value['id'];?>"    ondblclick="hg_coldbclick('<?php echo $value['name'];?>',<?php echo $value['id'];?>,event,'<?php echo $__hg_Pre;?>','father_org_id<?php echo $hg_multiple_suffix;?>','<?php echo $inputtype;?>')"><?php echo $value['name'];?><?php if(!$value['is_last']){ ?><strong>»</strong><?php } ?></a>
							</li>
							<?php } ?>
						</ul>
					 </div>
					 <div class="pub_div" id="<?php echo $__hg_Pre;?>level_2" onclick="hg_roll_col(this.id,0,'<?php echo $__hg_Pre;?>')" showit="yes">
						 <ul id="<?php echo $__hg_Pre;?>level2col">
						 </ul>
					 </div>
					 <div class="pub_div" id="<?php echo $__hg_Pre;?>level_3" onclick="hg_roll_col(this.id,0,'<?php echo $__hg_Pre;?>')" showit="yes">
						 <ul id="<?php echo $__hg_Pre;?>level3col">
						 </ul>
					 </div>
				</div>
			</div>
			<div id="<?php echo $__hg_Pre;?>hg_selected_hidden">
				<?php if($formdata['father_org_id']){ ?>
				<?php foreach ($formdata['father_org_id'] as $k=>$v){ ?>
					<input type="hidden" name="father_org_id<?php echo $hg_multiple_suffix;?>" value="<?php echo $k;?>" id="<?php echo $__hg_Pre;?>hg_hidden_<?php echo $k;?>">
				<?php } ?>
				<?php } ?>
			</div>
			<?php if($hg_attr['rule']){ ?>
			<?php 
				$checked2 = $checked1 = '';
				if($hg_attr['rule_selected'] == 1)
				{
					$checked1 = 'checked="checked"';
				}
				else if ($hg_attr['rule_selected'] == 2)
				{
					$checked2 = 'checked="checked"';
				}
				else if($hg_attr['rule_selected'] == 3)
				{
					$checked1 = 'checked="checked"';
					$checked2 = 'checked="checked"';
				}
				else
				{
					$checked1 = 'checked="checked"';
				}
			 ?>
			<div class="clear" style="margin-top:8px;">
			<input type="checkbox" name="father_org_id_attr[]" style="vertical-align:middle;margin-right:7px;" value="1" <?php echo $checked1;?>/><span>栏目本身</span>
			<input type="checkbox" name="father_org_id_attr[]" style="vertical-align:middle;margin-right:7px;" value="2" <?php echo $checked2;?>/><span>子栏目</span>
			</div>
			<?php } ?>
		</li>
	</ul>
	<input type="hidden" name="_type_" value="1" id="<?php echo $__hg_Pre;?>changecoltype"/>
</div>
</div>
<?php 
$__call_column_count++;
 ?>
   </div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">登录域名：</span><input type="text" name='domain' value="<?php echo $formdata['domain'];?>" class="title"/>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title"><?php if($formdata['cardid']){ ?><font color="red">重新绑定</font><?php } else { ?>绑定密保<?php } ?>：</span><input type="checkbox" name='cardid' value=1 class="title">
		<?php if($formdata['cardid']){ ?><font class="important">*谨慎操作 重新绑定需要重新发放密保卡至该用户</font><?php } ?>
	</div>
</li><li class="i">
	<div class="form_ul_div clear">
		<span class="title">修改密码：</span><input type="checkbox" name='forced_change_pwd' value=1 class="title" <?php if($formdata['forced_change_pwd']){ ?> checked="checked" <?php } ?>><font class="important">下次登录需要修改密码</font>
	</div>
</li><li class="i">
	<div class="form_ul_div clear">
		<span class="title">上传头像：</span>
		<?php 
			$index_img = '';
			if($formdata['avatar'])
			{	
				$pic = $formdata['avatar'];
				$index_img = $pic['host'] . $pic['dir'] .'100x75/'. $pic['filepath'] . $pic['filename'];
			}
		 ?>
		<?php if($index_img){ ?>
			<img src="<?php echo $index_img;?>"/>
		<?php } ?>
		<input type="file" value='' name='Filedata'/>
	</div>
</li>
</ul>
<input type="hidden" value="<?php echo $formdata['info'][0]['id'];?>" id="id" name="id" />
<input type="hidden" name="a" value="<?php echo $a;?>" />
<input type="hidden" name="<?php echo $primary_key;?>" value="<?php echo $$primary_key;?>" />
<input type="hidden" name="referto" value="<?php echo $_INPUT['referto'];?>" />
<input type="hidden" name="infrm" value="<?php echo $_INPUT['infrm'];?>" />
<br />
<input type="submit" id="submit_ok" name="sub" value="<?php echo $optext;?>用户" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid=<?php echo $_INPUT['mid'];?>">返回前一页</a></h2>
</div>
</div>
<script type="text/javascript">
	$(function(){
		$.globalRender = <?php  echo $appendRole ?  json_encode($appendRole) : '{}';  ?>;
		$.globalSelect = <?php  echo $role_id ?  json_encode($role_id) : '{}';  ?>;
		$('.select_role').role_select({
			source : $.globalRender,
			select : $.globalSelect,
			name : 'admin_role_id'
		});
	});
</script>
<?php if((!$_INPUT['infrm'] && !$__top)){ ?>
	<?php if(SCRIPT_NAME != 'login'){ ?>
		<div class="footer <?php echo $hg_name;?>"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span>
<?php if($licenseInfo['expire_time']){ ?>
<span<?php if($licenseInfo['leftday'] < 30){ ?> class="alert"<?php } ?>>到期时间：<?php echo $licenseInfo['expire'];?>, 还有<?php echo $licenseInfo['leftday'];?>天到期</span>
<?php } else { ?>
<span>永久授权</span>
<?php } ?>
<span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
	<?php } else { ?>
		<div class="footer login_footer"><?php if(SCRIPT_NAME != 'login'){ ?><div class="img"></div><?php } else { ?>LivMCP<?php } ?> <span class="c_1"><?php echo $_settings['version'];?></span>|<span>License Info:</span><span class="c_1"><?php echo $_settings['license'];?></span>
<?php if($licenseInfo['expire_time']){ ?>
<span<?php if($licenseInfo['leftday'] < 30){ ?> class="alert"<?php } ?>>到期时间：<?php echo $licenseInfo['expire'];?>, 还有<?php echo $licenseInfo['leftday'];?>天到期</span>
<?php } else { ?>
<span>永久授权</span>
<?php } ?>
<span class="c_3"><a><?php echo $_user['user_name'];?></a>|<a href="infocenter.php" title="个人设置">个人设置</a>|<a href="login.php?a=logout" title="退出系统">退出</a></span> </div>
	<?php } ?>
<?php } ?>
<div id="<?php echo $dialog['id'];?>" class="lightbox" style="display:none;width:452px;">
	<div class="lightbox_top">
		<span class="lightbox_top_left"></span>
		<span class="lightbox_top_right"></span>
		<span class="lightbox_top_middle"></span>
	</div>
	<div class="lightbox_middle">
		<span style="position:absolute;right:25px;top:25px;z-index:1000;"><img width="14" height="14" id="<?php echo $dialog['id'];?>Close" src="<?php echo $RESOURCE_URL;?>close.gif" style="cursor:pointer;"></span>
		<div id="<?php echo $dialog['id'];?>body" class="text" style="max-height:500px">
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
			dialog body<br />
		</div>
	</div>
	<div class="lightbox_bottom">
		<span class="lightbox_bottom_left"></span>
		<span class="lightbox_bottom_right"></span>
		<span class="lightbox_bottom_middle"></span>
	</div>
</div>
<script>
$(function($){
	var MC = $('#livUpload_div');
	/*初始化本地存储*/
	MC.find('.set-area').on( 'initlocalStorage', function(){
		$(this).find('.set-item').each( function(){
			var key = $(this).data( 'name' );
			var localData = localStorage.getItem( key );
			if( localData ){
				localData = localData.split( '|' );
				if( localData.length ){
					MC.find('input[name="'+ key + '"]').val( localData[0] );
					$(this).find('li').filter( function(){
						var id = $(this).data('id');
						return ( id == localData[0] );
					} ).trigger('click');
					MC.find( 'li.' +key ).find('.select-item').text( localData[1] );
				}
			}
		} );
	} );
	MC.on('click','.water_pic li',function(e){
		var self = $(e.currentTarget);
		var obj =self.find('p');
		obj.toggleClass('select');
		self.siblings().find('p').removeClass('select');
	});
	MC.on( 'click', '.set-upload', function(){
		if( $(this).hasClass('disable') ) return;
		//if( window.numFilesQueued ){
			window.livUpload.start();
			$(this).addClass('disable');
		//}
	} );
	MC.on( 'click', '.set-area-nav>li', function( event ){
		var self = $( event.currentTarget ),
			index = self.index(),
			current_item = MC.find( '.fast-set>div' ).eq( index );
		self.addClass( 'select' ).siblings().removeClass( 'select' );
		current_item.addClass( 'show' ).siblings().removeClass( 'show' );
	} );
	MC.on( 'click', '.set-item li', function( event ){
		var self = $( event.currentTarget ),
			item = self.closest( '.set-item' ),
			hidden_name = item.data('name'),
			type = self.data('type'),
			hidden_input = MC.find( 'input[name="' + hidden_name + '"]' ),
			no_water = MC.find( 'input[name="no_water"]' ),
			current_nav = MC.find('.set-area-nav>li.select').find('.select-item'),
			id = '',
			name = '无';
		if( type == 'server' ){
			name = '空闲';
		}
		self.toggleClass( 'select' ).siblings().removeClass('select');
		if( self.hasClass('select') ){
			id = self.data( 'id' ),
			name = self.data( 'name' );
			if( type == 'water' ){
				no_water.val('');
			}
		}else{
			if( type == 'water' ){
				no_water.val('1');
			}
		}
		self.data( 'set' ) && current_nav.text( name );
		hidden_input.val( id );
		localStorage.setItem( hidden_name, id + '|' + name );
	} );
});
function hg_open_widows(){
	var id = $('#livUpload_div');
	if(id.css('display')=='none')
	{
		$('#livUpload_small_windows').animate({'width':'406px'},function(){
				id.show();
				id.animate({'height':'auto'});
				$('.livUpload_text').addClass('b');
			});
	}
	else{
		id.animate({'height':'0px'},function(){
				id.hide();
				$('#livUpload_small_windows').animate({'width':'406px'},function(){
					$('.livUpload_text').removeClass('b');
				});
			});
	}
}
function livUpload_text_move()
{
	if($('.livUpload_text_b').css('top')=='28px')
	{
		$('.livUpload_text_a').animate({'top':'-28px'});
		$('.livUpload_text_b').animate({'top':'0'},function(){$('.livUpload_text_a').css({'top':'28px'});});
	}
	else{
		$('.livUpload_text_b').animate({'top':'-28px'});
		$('.livUpload_text_a').animate({'top':'0'},function(){$('.livUpload_text_b').css({'top':'28px'});});
	}
}
function hg_goToTop()
{
	$('.livUpload_text_b').css({'top':'28px'});
	$('.livUpload_text_a').css({'top':'0'});
}
function hg_closeProgress()
{
	var frame = hg_findNodeFrame();
	frame.hg_closeButtonX(true);
}
</script>
<span class="upload_flash" id="flash_wrap" style="left:0px;top:0px;"><span id="UploadPlace"></span></span>
<div id="livUpload_windows" style="display:none;">
	<div id="livUpload_div" style="display:none;">
		<div class="livUpload_bg">
			<span class="a"></span>
			<span class="b"></span>
			<span class="c"></span>
		</div>
		<div id="livUploadProgress" ></div>
		<span class="close-tip"></span>
		<div class="set-button set-upload">上传</div>
		<div class="set-button set-editor">编辑</div>
		<div class="set-area"></div>
	</div>
	<div id="livUpload_small_windows">
		<span class="close"></span>
		<div class="livUpload_text" id="livUpload_text">
			<span id="livUpload_text_a"    class="livUpload_text_a"></span>
			<span id="livUpload_text_b"    class="livUpload_text_b" style="top:28px"></span>
			<span id="livUpload_speed"></span>
			<span id="livUpload_rate"></span>
		</div>
		<div id="livUpload_windows_b" style="width:0;"></div>
	</div>
</div>
<?php echo $this->mFooterCode;?>
<script>
/*
 * 把nodeFrame中的搜查和几个按钮提升到mainwin中；resize nodeFrame；
 */
<?php if($_INPUT['infrm']){ ?>
	$(function ($){hg_resize_nodeFrame(true<?php echo $_INPUT['_firstload'] ? ','.$_INPUT['_firstload']: ''; ?>);});
	hg_repos_top_menu();
	setTimeout("hg_resize_nodeFrame(true);", 100);
<?php } else { ?>
<?php } ?>
/*实例化日期选择器*/
$(window).load( function(){
	$('html').find('.date-picker').removeClass('hasDatepicker').hg_datepicker();
} );
/*
if(top.livUpload.SWF)
{
	top.$('#flash_wrap').css({'left':'0px','top':'0px','position':'absolute'});
	top.setTimeout(function(){top.livUpload.SWF.setButtonDimensions(1,1);},500);
	top.livUpload.currentFlagId = 0;
}
*/
</script>
<div id="dragHelper" style="position: absolute; display: none; cursor: move; list-style-type: none; list-style-position: initial; list-style-image: initial; overflow-x: hidden; overflow-y: hidden; -webkit-user-select: none; "></div>
</body>
</html>