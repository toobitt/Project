<?php 
        $hg_text_attr = array(
         20 => '全部视频',
         51 => '全部直播归档',
         58 =>'全部新闻',
         7 => '全部地盘',
         4 => '全部举报',
         13 => '全部图集',
         37 => '全部广告',
         27 => '全部广告位',
        270=>'组织机构',
        354	=>'全部频道',
        531 => '全部会员',
        766 => 'null'
        );
        $hg_attr['text'] = $hg_text_attr[$_INPUT['mid']];
 ?>   
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
<title><?php echo $this->mTemplatesTitle;?></title><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/public.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/upload.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/jquery-ui-min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/alert/jquery.alerts.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/node_drop.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/2013/iframe.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/vod_live.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/hg_sort_box.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>outpush/common/common_publish.css" />
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
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>tree/iframe_node.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>hg_sort_box.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/ajaxload_new.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>2013/new_search_init.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/ajax_cache.js"></script>
<script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>common/publish.js"></script>
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
<?php } ?>
<script type="text/javascript">
$(function(){
    top == self && $('#nodeFrame').attr('src', function(){
        return $(this).attr('_src');
    });
});
function hg_append_menu(obj) {
	$(obj).siblings().find('a').removeClass('append_cur');
	$(obj).find('a').addClass('append_cur');
}
</script>
<?php if($hg_data){ ?>
<script type="text/javascript">
$(function() {
	//iframe_node('<?php echo $hg_attr['nodeapi'];?>'.replace(/&amp;/g, '&'));
	new NodeTree({
		nodeapi: '<?php echo $hg_attr['nodeapi'];?>'.replace(/&amp;/g, '&'),
		el: $('#hg_node_node')
	});
});
</script>
<?php } ?>
<div class="channels_menu" id="channels_menu" style="display:none"></div>
<?php 
$isnode = $hg_node_template == "node" ? true : false;
 ?>
<style>
.leftmenu{margin-right:0;padding-bottom: 10px;overflow:hidden;}
#hg_node_node{position:relative;transition:left .3s;-webkit-transition:left .3s;width:3000px;}
.each-node{float:left;}
.each-node.with-loading{background:url(<?php echo $RESOURCE_URL;?>loading2.gif) no-repeat 50% 50%;height:200px;background-size:40px 40px;}
#hg_node_node .back.allcond{display:block;text-indent:19pt;}
.livnodewin{position:relative;overflow:hidden;}
#nodeFrame{width:100%;}
</style> 
<div class="wrap clear">
	<table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
		<tr>
			<td style="vertical-align: top;width:137px;">
				<div class="leftmenu">
				<?php if($hg_data){ ?>
					<div id="hg_node_node">
						<div class="each-node">
<?php 
if (!$hg_attr['level'])
{
	$hg_attr['level'] = 0;
}
if (!$hg_attr['text'])
{
	$hg_attr['text'] = '全部内容';
}else{
    $hg_attr['text'] == '全部新闻' && $hg_attr['text'] = '全部文稿';
}
if(!$_parenturl)
{
	$_parenturl = 'run.php?mid=' . $_INPUT['mid'];
}
$infrm = 'infrm';
$infrm_v = 1;
//print_r($hg_data);
$total = '';
if($hg_data[0]['total'])
{
	$total = $hg_data[0]['total'];
}
else if($hg_data['total']['total'])
{
	$total = $hg_data['total']['total'];
	unset($hg_data['total']);
}
$is_root = ($hg_data[0]['fid'] == '0' || $hg_data[0]['depath'] == 1);
$need_more = $total ? $total - count($hg_data) : false;
function create_node_href($v, $_selfurl)
{
	$input_k = $v['input_k'] ? $v['input_k'] : '_id';
	$href = "$_selfurl&$input_k=" . $v['id'];
	$href .= $v['para'] ? "&para=" . $v['para'] : '';	
	$href .= $v['_appid'] ? "&_appid=" . $v['_appid'] : '';
	$href .= "&_modid=" . $v['_modid'] ;
	return $href;	
}
 ?>
<div class="been_marked_second">
	<?php if($hg_attr['text'] != 'null'){ ?>
	<div class="first <?php if($is_root){ ?>top current<?php } else { ?>normal<?php } ?>">
		<a class="back <?php if($is_root){ ?>allcond<?php } ?>" <?php if($is_root){ ?>href="<?php echo $_parenturl;?>&amp;<?php echo $infrm;?>=<?php echo $infrm_v;?>" target="nodeFrame" onclick="if( clearPubResult() ){}" <?php } else { ?> href="javascript:;"<?php } ?>>
			<?php echo $hg_attr['text'];?>
		</a>
	</div>
	<?php } ?>
	<ul class="hg_node_depth_node_ul">
		<?php foreach ($hg_data as $v){ ?>
		<li>
			<span class="a">
				<?php if(!$v['is_last']){ ?>
				<a class="i" href="javascript:"data-name="<?php echo $v['name'];?>"  data-id="<?php echo $v['id'];?>" target="nodeFrame">
					<span class="img">&nbsp;</span>
				</a>
				<?php } ?>
				<a class="l"  title="<?php echo $v['brief'];?>"  href="<?php echo create_node_href($v, $_selfurl); ?>" target="nodeFrame"><?php echo $v['name'];?></a>
			</span>
		</li>
		<?php } ?>
	</ul>
	<?php if($need_more){ ?>
	<div class="addMoreNode" data-total="<?php echo $total;?>" data-fid="<?php echo $hg_data[0]['fid'];?>">展开更多<span class="addMoreNode-icon"></span><img class="addMoreNode-loading" src="<?php echo $RESOURCE_URL;?>loading2.gif" /></div>
	<?php } ?>
</div>
						</div>
					</div>
				<?php } elseif($append_menu) { ?>
					<ul class="been_marked_second first_none" id="append_menu" style="border-top-left-radius:0;">
			        <?php foreach ($append_menu AS $k => $v){ ?>
			            <?php 
			            if (!$k)
			            {
			                $fcls = ' class="append_cur"';
			            }
			            else
			            {
			                $fcls = '';
			            }
			             ?>
						<li class="<?php echo $v['class'];?>" onclick="hg_append_menu(this);"><a href="<?php echo $v['url'];?>&infrm=1&nav=1"<?php echo $fcls;?> target="nodeFrame"><?php echo $v['name'];?></a></li>
			        <?php } ?>
					</ul>
				<?php } ?>
        		</div>
			</td>
			<td style="vertical-align: top;border-left: 1px solid #D8D8D8;">
				<div id="livnodewin">
					<iframe name="nodeFrame" id="nodeFrame" frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" _src="<?php echo $node_iframe_attr['src'];?>" autocomplete="off" <?php echo $node_iframe_attr['attr'];?>></iframe>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php 
	define('FORMDATA', 0);
 ?>
<?php
if ((!isset($formdata) || !is_array($formdata)) && (defined('FORMDATA') && FORMDATA)) {} else { 
$item = $formdata;
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
}
$column = new column();
$publish = array();
$publish['sites'] = $column->getallsites();
list($default_site, $default_name) = each($publish['sites']);
reset($publish['sites']);
$publish['items'] = $column->getAuthoredColumns($default_site);
$publish['selected_ids'] = $item['column_id'] ? $item['column_id'] : '';
$publish['selected_items'] = $column->get_selected_column_path($publish['selected_ids']);
$publish['default_site'] = each($publish['sites']);
$publish['pub_time'] = $item['pub_time'];$hg_print_selected = array();
foreach ($publish['selected_items'] as $index => $item) {
	$hg_print_selected[$index] = array();
	$current = &$hg_print_selected[$index];
	$current['showName'] = '';
	foreach ($item as $sub_item) {
		if($sub_item['is_auth'])
		{
			$current['is_auth'] = 1;
		}
		$current['id'] = $sub_item['id'];
		$current['name'] = $sub_item['name'];
		$current['showName'] .= $sub_item['name'] . ' > ';
	}
	if(!$current['is_auth'])
	{
		$current['is_auth'] = 0;
	}
	$current['showName'] = substr($current['showName'], 0, -3);
	$selected_names[] = $current['name'];
}
$publish['selected_items'] = $hg_print_selected;
$publish['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';}
?><div class="common-form-pop" id="form_publish" _type="publish" style="top:-450px;position:fixed;z-index:99999;-webkit-transition:top .5s;left:50%;margin-left:-312px;"><?php 
if (!isset($publish)) {
	$publish = array(
		'selected_items' => array(),
		'selected_ids' => array(),
		'selected_names' => array(),
		'pub_time' => '',
		'default_site' => array(),
		'sites' => array(),
		'items' => array()
	);
}
 ?>
<style>
.common-hg-publish .publish-result ul, .common-hg-publish .publish-result-empty{height:190px;}
</style>
<div class="publish-box common-hg-publish" id="publish-box-1">
	<div class="publish-result <?php if(count($publish['selected_items']) == 0){ ?>empty<?php } ?>" >
		<p class="publish-result-title" _title="发布">发布至：</p>
		<ul>
			<?php foreach ($publish['selected_items'] as $item){ ?>
			<li _id="<?php echo $item['id'];?>" _name="<?php echo $item['name'];?>" data-auth="<?php echo $item['is_auth'];?>" _siteid="<?php echo $item['siteid'];?>" title="<?php echo $item['show_name'];?>">
				<input type="checkbox" checked="checked" class="publish-checkbox" <?php if(!$item['is_auth']){ ?>style="visibility: hidden;"<?php } ?>/>
				<span><?php echo $item['showName'];?></span>
			</li>
			<?php } ?>
		</ul>
		<div class="publish-result-empty">显示已选择的栏目</div>
		<div class="extend-item" style="margin-top:5px;">
			<label style="margin-left:-10px;">发布时间：</label><input style="width:113px;height:18px;margin:0;" name="pub_time" value="<?php echo $publish['pub_time'];?>" class="date-picker" _time=true/>
		</div>
	</div>
	<div class="publish-site">
		<div class="publish-site-current" _siteid="<?php echo $publish['default_site']['key'];?>">
			<?php echo $publish['default_site']['value'];?>
		</div>
		<span class="publish-site-qiehuan">切换</span>
		<ul>
			 <?php foreach ($publish['sites'] as $key => $each_site){ ?>
			 <li class="publish-site-item <?php if($key == $publish['default_site']['key']){ ?>publish-site-select<?php } ?>" _siteid="<?php echo $key;?>" _name="<?php echo $each_site;?>">
			 	<label><input type="radio" name="publish-sites-1" <?php if($key == $publish['default_site']['key']){ ?>checked="checked"<?php } ?> />
			 	<?php echo $each_site;?></label>
			 </li>
			 <?php } ?>
		</ul>
	</div>
	<div class="publish-list">
		<div class="publish-inner-list">
			<?php if($publish['items']){ ?>
			<div class="publish-each">
				<ul>
					<?php foreach ($publish['items'] as $kk => $vv){ ?>
					<li _id="<?php echo $vv['id'];?>" title="<?php echo $vv['name'];?>" _name="<?php echo $vv['name'];?>" class="one-column <?php if($vv['is_last']){ ?>no-child<?php } ?>">
						<input type="checkbox" class="publish-checkbox" <?php if($vv['is_auth']==2){ ?>style="visibility:hidden;"<?php } ?>/>
						<span class="publish-name"><?php echo $vv['name'];?></span>
						<span class="publish-child">&gt;</span>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>
	<input type="hidden" class="publish-hidden" name="column_id" value="<?php echo $publish['selected_ids'];?>" />
	<input type="hidden" class="publish-name-hidden" name="column_name" value="<?php echo $publish['selected_names'];?>" /></div>
	<div class="publish-box-close"></div>	
</div>
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