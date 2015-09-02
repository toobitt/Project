

<?php
/**
 * Created by livtemplates.
 * User: wangleyuan
 * Date: 14-5-28
 * Time: 上午11:13
 */
?>
<?php 
$search_condition = $_configs['used_search_condition'];
$default_weight = array(
    'title' => "所有权重",
    'begin_w' => 0,
    'end_w' => 100,
    'default' => 1
);
array_unshift(  $formdata['weight'],1);
$formdata['weight'][0] = $default_weight; ?><!-- 标题搜索模版 -->
<div class="new-search-item new-search-title" data-type="title">
	<span class="label"><?php if($hg_attr['title']){ ?><?php echo $hg_attr['title'];?><?php } else { ?>标题<?php } ?></span>
	<span class="condition-area"><input type="text" name="key" value="<?php echo $_INPUT['key'];?>"/></span>
</div><!-- 作者搜索模版 -->
<div class="new-search-item new-search-author">
	<span class="label"><?php if($hg_attr['title']){ ?><?php echo $hg_attr['title'];?><?php } else { ?>作者<?php } ?></span>
	<span class="condition-area"><input type="text" name="author" /></span>
</div><!-- 栏目搜索模版 -->
<div class="new-search-item new-search-column" data-widget="hg_search_column">
	<span class="label"><?php if($hg_attr['title']){ ?><?php echo $hg_attr['title'];?><?php } else { ?>栏目<?php } ?></span>
	<span class="condition-area">
		<input type="text" readonly="true" name="pub_column_name" class="open-column-input" />
		<input type="hidden" name="pub_column_id" />
	</span>
</div><!--  添加人搜索模版 -->
<div class="new-search-item new-search-creater" data-widget="hg_search_creater">
	<span class="label"><?php if($hg_attr['title']){ ?><?php echo $hg_attr['title'];?><?php } else { ?>添加人<?php } ?></span>
	<span class="condition-area"><input type="text" name="user_name" /></span>
</div><?php 
    $_INPUT['status'] = $_INPUT['status'] ? $_INPUT['status'] : 0;
    if (empty($_configs['state_search'])) {
        $_configs['state_search'] = array(
        '0'   => '全部状态',
        '1'   => '未审核',
        '2'   => '已审核',
        '3'   => '已打回',
        );
    }
 ?>
<!--  状态搜索模版 -->
<?php 
$value = $_INPUT['status'];
 ?>
<div class="new-search-item new-search-status" data-type="select">
	<span class="label"><?php if($hg_attr['title']){ ?><?php echo $hg_attr['title'];?><?php } else { ?>状态<?php } ?></span>
	<span class="condition-area">
		<span class="current-condition-show"><?php echo $_configs['state_search'][$value];?></span>
		<?php if($_configs['state_search']){ ?>
		<ul class="condition-area-list defer-hover-target">
			<?php foreach ($_configs['state_search'] as $k => $v){ ?>
			<li>
				<a data-id="<?php echo $k;?>"><?php echo $v;?></a>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
		<input type="hidden" name="status" value="<?php echo $value;?>" />
	</span>
</div><?php 
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
 ?>
<!--  时间搜索模版 -->
<?php 
$value = $_INPUT['date_search'];
 ?>
<div class="new-search-item new-search-time" data-widget="hg_search_time" data-type="time">
	<span class="label"><?php if($_attr['title']){ ?><?php echo $_attr['title'];?><?php } else { ?>时间<?php } ?></span>
	<span class="condition-area">
		<span class="current-condition-show"><?php echo $_configs['date_search'][$value];?></span>
		<?php if($_configs['date_search']){ ?>
		<ul class="condition-area-list defer-hover-target">
			<?php foreach ($_configs['date_search'] as $k => $v){ ?>
			<li>
				<a data-id="<?php echo $k;?>" data-type="time"><?php echo $v;?></a>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
		<input type="hidden" name="date_search" value="<?php echo $value;?>" />
	</span>
	<span class="define-condition-area"><input type="text" placeholder="开始时间" <?php if($_attr['hasSecond']){ ?>_second="true"<?php } ?> name="<?php echo $_attr['start_time'];?>" class="start-time search-date-picker" />-<input type="text" <?php if($_attr['hasSecond']){ ?>_second="true"<?php } ?> name="<?php echo $_attr['end_time'];?>" class="end-time search-date-picker" placeholder="结束时间" /></span>
</div><?php 
$_INPUT['weight'] = $_INPUT['weight'] ? $_INPUT['weight'] : 0;
$_attr = array('start_weight' => 'start_weight', 'end_weight' => 'end_weight');
 ?>
<!--  权重搜索模版 -->
<div class="new-search-item new-search-weight" data-widget="hg_search_weight" data-type="weight">
	<span class="label"><?php if($_attr['title']){ ?><?php echo $_attr['title'];?><?php } else { ?>权重<?php } ?></span>
	<span class="condition-area">
		<span class="current-condition-show"><?php echo $formdata['weight'][$_INPUT['weight']]['title'];?></span>
		<?php if($formdata['weight'] ){ ?>
		<ul class="condition-area-list defer-hover-target">
			<?php foreach ($formdata['weight'] as $k => $v){ ?>
			<li>
				<a data-id="<?php echo $v['begin_w'];?>,<?php echo $v['end_w'];?>" title="<?php echo $v['title'];?>" data-type="weight"><?php if(!$v['default']){ ?>>=<?php echo $v['begin_w'];?> <?php } ?><?php echo $v['title'];?></a>
			</li>
			<?php } ?>
			<li>
				<a data-id="other" title="自定义权重" data-type="weight">自定义权重</a>
			</li>
		</ul>
		<?php } ?>
		<input type="hidden" name="weight_hidden" />
		<input type="hidden"  class="start-weight-hidden" name="<?php echo $_attr['start_weight'];?>" value="0" />
		<input type="hidden"  class="end-weight-hidden" name="<?php echo $_attr['end_weight'];?>" value="100" />
	</span>
	<span class="define-condition-area">
		<span class="define-weight-input"><input type="text" class="start-weight-input" value="0" />-<input type="text" class="end-weight-input" value="100" /></span>
		<div class="define-weight-box">
			  <i class="start">0</i>
			  <div class="define-weight-slider"></div>
			  <i class="end">100</i>
		</div>
	</span>
</div><?php 
$_configs['outlink_list'] = array(
    0   => '全部内容',
    1   => '外链',
    2    => '非外链'
);
$_attr = array(
    'title'  => '外链',
);
if (!$_INPUT['outlink_status']) {
    $_INPUT['outlink_status'] = 0;
}
 ?>
<!--  下拉类型搜索模版 -->
<?php 
$value = $_INPUT['outlink_status'];
 ?>
<div class="new-search-item new-search-status" data-type="select">
	<span class="label"><?php if($_attr['title']){ ?><?php echo $_attr['title'];?><?php } else { ?>下拉类型<?php } ?></span>
	<span class="condition-area">
		<span class="current-condition-show"><?php echo $_configs['outlink_list'][$value];?></span>
		<?php if($_configs['outlink_list']){ ?>
		<ul class="condition-area-list defer-hover-target">
			<?php foreach ($_configs['outlink_list'] as $k => $v){ ?>
			<li>
				<a data-id="<?php echo $k;?>"><?php echo $v;?></a>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
		<input type="hidden" name="outlink_status" value="<?php echo $value;?>" />
	</span>
</div>