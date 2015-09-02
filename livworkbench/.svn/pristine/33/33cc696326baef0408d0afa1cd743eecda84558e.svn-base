<?php
//check登陆与权限
require './prms.php';
require './lib.php';
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>区块编辑</title>
    <?php
        $bs = 'q';
    ?>
    <script>
    var mainConfig = {
        gmid : <?php echo $main->gmid;?>,
        layout : '<?php echo $main->layout; ?>',
        search : 'magic.php?a=searchCell&<?php echo $main->ext . "&bs=" . $bs ."&data=1"; ?>',
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
            'block',
            'plugin',
            'jqueryui/jquery-ui',
        	'jqueryui/jquery-ui-custom',
            'ColorPicker/colorpicker'
        ),
    'css');
    $main->res(
        array(
            'jquery.min',
            'jquery-ui-min',
        	'hg_datepicker',
            'jquery.tmpl.min',
            'ColorPicker/colorpicker',
            'html2canvas',
            'ajaxload',
            'ajaxupload',
            'mask',
            'mask_block',
            'plugin',
            'iframe',
            'block',
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

<!-- 截图 start -->
<div id="jt">截图</div>
<!-- 截图 end -->


<!-- 开始转换成区块 start -->
<div id="start-block-box">
    <div></div>
</div>
<!-- 开始转换成区块 end -->

<!-- 数据 start -->
<?php
$sizes = array();
$sizes['inherit'] = '继承';
foreach(range(10, 30) as $val){
    $val .= 'px';
    $sizes[$val] = $val;
}
?>

<div id="source-box" class="sbox m2o-transition">
    <div class="zksq"><span class="zk">展开</span><span class="sq">收起</span></div>
    <div class="header">
        <span>区块管理</span>
        <span class="qk-jt">截图</span>
        <div class="shuxing">
	        <input type="checkbox" class="auto-update" id="auto-update"/> <label for="auto-update">自动更新</label><span><input class="auto-update-time"/>秒</span>
	        <label><input type="checkbox" class="auto-send"/> 支持推送</label>
	    </div>
    </div>
    <div class="bodyer">
        <ul class="list"></ul>
    </div>
    <div class="edit-set">
    	<a class="add-row">新增一行</a>
    	<a class="add">添加数据</a>
        <a class="preview" data-normal="预览" data-ing="取消预览">预览</a>
        <a class="ok">保存</a>
	</div>
    <div class="row-info">
        <div class="r-item">行属性设置</div>
        <div class="r-item">
            <span class="rc-color span">A</span>
            <span class="rc-bold span">B</span>
            <select name="size" class="rc-size">
            	<?php
            	    foreach($sizes as $key => $val){
            	    ?>
            	    <option value="<?php echo $key;?>"><?php echo $val;?></option>
            	    <?php
            	    }
            	?>
            </select>
        </div>
        <div class="r-item m2o-flex">
            <label>前缀：</label>
            <div class="rc-pre rc-pre-after m2o-flex-one">
            	<input name="preimg" class="rc-pre-img" placeholder="图标"/><span class="rc-pre-img-select"><img src=""/><span class="rc-del-img" type="pre">x</span></span>
                <input name="prewz" class="rc-pre-wz" placeholder="文字"/>
                <input name="prelink" class="rc-pre-link" placeholder="链接"/>
            </div>
        </div>
        <div class="r-item m2o-flex">
            <label>后缀：</label>
            <div class="rc-after rc-pre-after m2o-flex-one">
            	<input name="afterimg" class="rc-after-img" placeholder="图标"/><span class="rc-after-img-select"><img src=""/><span class="rc-del-img" type="after">x</span></span>
                <input name="afterwz" class="rc-after-wz" placeholder="文字"/>
                <input name="afterlink" class="rc-after-link" placeholder="链接"/>
            </div>
        </div>
        <div class="r-item"><label>背景：</label><span class="rc-bgcolor"></span></div>
        <div class="r-item"><span class="rc-save rc-button" data-type="row">保存</span><span class="rc-del rc-button" data-type="removeRow">删除</span><span class="rc-cancel rc-button">取消</span></div>
    </div>
    <div class="column-info">
		<div class="edit-title">编辑: <span></span></div>
        <div class="m2o-flex edit-content">
            <div class="c-property">
                <span class="rc-color span">A</span>
                <span class="rc-bold span">B</span>
                <select name="size" class="rc-size">
	            	<?php
                        foreach($sizes as $key => $val){
                        ?>
                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                        <?php
                        }
                    ?>
	            </select>
                <div class="rc-box">
                    <div class="rc-pre rc-pre-after">
                        <label>前缀：</label>
                        <input name="preimg" class="rc-pre-img" placeholder="图标"/><span class="rc-pre-img-select"><img src=""/><span class="rc-del-img" type="pre">x</span></span>
                        <input name="prewz" class="rc-pre-wz" placeholder="文字"/>
                        <input name="prelink" class="rc-pre-link" placeholder="链接"/>
                    </div>
                    <div class="rc-after rc-pre-after">
                        <label>后缀：</label>
                        <input name="afterimg" class="rc-after-img" placeholder="图标"/><span class="rc-after-img-select"><img src=""/><span class="rc-del-img" type="after">x</span></span>
                        <input name="afterwz" class="rc-after-wz" placeholder="文字"/>
                        <input name="afterlink" class="rc-after-link" placeholder="链接"/>
                    </div>
                </div>
            </div>
            <div class="c-img" title="点击上传图片"></div>
            <input type="file" style="display:none;" class="c-upload"/>
            <div class="m2o-flex-one">
                <div><input name="title" class="c-title c-input" placeholder="标题"/></div>
                <div><input name="link" class="c-link c-input" placeholder="链接"/></div>
                <div><textarea name="des" class="c-des c-input" placeholder="提要"></textarea></div>
            </div>
        </div>
        <div class="edit-set">
        	<span class="rc-save rc-button" data-type="column">保存</span>
        	<span class="rc-del rc-button" data-type="removeColumn">删除</span>
        	<span class="rc-cancel rc-button">取消</span>
    	</div>
    </div>
</div>


<script type="text/x-jquery-tmpl" id="source-item-tpl">
<li class="source-item" style="{{if color}}color:{{= color}};{{/if}}{{if bold}}font-weight:bold;{{/if}}{{if size}}font-size:{{= size}};{{/if}}{{if bgcolor}}background:{{= bgcolor}}{{/if}}">
    {{if $data.pre_wz || $data.pre_img}}
    <em {{if pre_img}}class="source-img"{{/if}}>
        {{if pre_img}}
            <img src="{{= pre_img_yulan}}"/>
        {{else}}
            {{= pre_wz}}
        {{/if}}
    </em>
    {{/if}}

    {{if placeholder}}
        <span class="placeholder"></span>
    {{else}}

        {{if column}}
        {{tmpl(column) '#source-column-item-tpl'}}
        {{/if}}

    {{/if}}

    {{if $data.after_wz || $data.after_img}}
    <em>
        {{if after_img}}
            <img src="{{= after_img_yulan}}"/>
        {{else}}
            {{= after_wz}}
        {{/if}}
    </em>
    {{/if}}

    <a class="option">O</a>
</li>
</script>

<script type="text/x-jquery-tmpl" id="source-column-item-tpl">
<span class="column" data-id="{{= id}}" style="{{if color}}color:{{= color}};{{/if}}{{if bold}}font-weight:bold;{{/if}}{{if size}}font-size:{{= size}};{{/if}}">
    {{if $data.pre_wz || $data.pre_img}}
    <em {{if pre_img}}class="source-img"{{/if}}>
        {{if pre_img}}
            <img src="{{= pre_img_yulan}}"/>
        {{else}}
            {{= pre_wz}}
        {{/if}}
    </em>
    {{/if}}

    <a>{{= title}}</a>

    {{if $data.after_wz || $data.after_img}}
    <em>
        {{if after_wz}}
            <img src="{{= after_img_yulan}}"/>
        {{else}}
            {{= after_wz}}
        {{/if}}
    </em>
    {{/if}}
</span>
</script>

<!-- 数据 end -->

<!-- 图标 start -->
<div id="pic-box" class="pc-box plugin-box">
    <input type="file" class="pc-file" multiple/>
    <div class="pc-inner plugin-inner m2o-border-box">
        <div class="pc-head plugin-head">选择图片：</div>
        <ul class="pc-body plugin-body">
            <li class="pc-add m2o-flex m2o-flex-center m2o-border-box"><div class="m2o-flex-one">上传图片</div></li>
        </ul>
        <div class="pc-pages plugin-pages"></div>
    </div>
    <div class="pc-option plugin-option">
    <span class="pc-save plugin-save">确定</span>
    <span class="pc-cancel plugin-cancel">关闭</span>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="pic-item-tpl">
    <li data-url="{{= url}}" data-id="{{= id}}" class="pc-item m2o-flex m2o-flex-center m2o-transition">
        <img src="{{= img}}"/>
    </li>
</script>
<!-- 图标 end -->

</body>
</html>