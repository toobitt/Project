<?php
//check登陆与权限
require './prms.php';
require './lib.php';
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>数据编辑</title>
    <script>
    var mainConfig = {
        gmid : <?php echo $main->gmid;?>,
        layout : '<?php echo $main->layout; ?>',
        search : 'magic.php?a=searchCell&<?php echo $main->ext . "&bs=" . $bs ."&data=1"; ?>',
        build : '<?php echo $main->build; ?>',
        which : '<?php echo $bs; ?>',
        datasourceUrl : '../get_publish_content.php?a=get_content',
        siteUrl : '../get_publish_content.php?a=get_site',
        moduleUrl : '../get_publish_content.php?a=get_bundles',
        columnUrl : '../fetch_column.php'
    };
    <?php if($bs == 'k'){ ?>
        mainConfig['getLayoutInfo'] = '<?php echo $main->getLayoutInfo; ?>';
    <?php } ?>
    </script>
    <?php
    $main->res(
        array(
            'base',
            'data',
            'jqueryui/jquery-ui',
        	'jqueryui/jquery-ui-custom',
        	'plugin'
        ),
    'css');
    $main->res(
        array(
            'jquery.min',
            'jquery-ui-min',
        	'hg_datepicker',
            'jquery.tmpl.min',
            'html2canvas',
            'ajaxload',
            'ajaxupload',
            'plugin',
            'mask',
            'mask_data',
            'iframe',
            'data',
            'base_pop',
        	'pop_list'
        )
    );
    echo $main->res('echo');
    ?>
</head>
<body>

<?php include 'view/iframe.php'; ?>

<?php include 'view/mask.php'; ?>

<!-- 数据 start -->
<div id="source-box" class="sbox m2o-transition">
    <div class="sinner">
        <div class="inner clearfix m2o-transition">
        	<div class="list-box">
        		<div class="list-title">数据编辑</div>
            	<ul class="list"></ul>
            </div>
            <div class="info"></div>
            <input type="file" style="display:none;" class="fileupload"/>
        </div>
    </div>
</div>

<script type="text/x-jquery-tmpl" id="source-item-tpl">
<li class="source-item" data-id="{{= id}}">
    {{= title}}
</li>
</script>

<script type="text/x-jquery-tmpl" id="source-info-tpl">
<div class="list-title back"><em>{{= title}}</em></div>
<div class="edit-list">
	<div class="m2o-flex img-area">
	    <div class="img" title="点击上传图片">{{if img}}<img src="{{= img}}"/>{{/if}}</div>
	    <div class="m2o-flex-one">
	        <div><input name="title" id="input-title" value="{{= title}}" placeholder="标题"/></div>
	        <div><input name="link" id="input-link" value="{{= link}}" placeholder="链接"/></div>
	        <div><textarea name="des" id="input-des" placeholder="概要">{{= des}}</textarea></div>
	    </div>
	</div>
	<div class="edit-set"><span class="tihuan">替换</span><span class="preview" data-normal="预览" data-ing="取消预览">预览</span><span class="save">保存</span><span class="cancel">取消</span></div>
</div>
</script>
<!-- 数据 end -->

<?php include 'view/dylist.php'; ?>

</body>
</html>