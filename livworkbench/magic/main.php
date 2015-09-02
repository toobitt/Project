<?php
//check登陆与权限
require './prms.php';
require './lib.php';
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php
        $title = '';
        switch ($bs) {
            case 'm':
                $title = '魔力视图';
                break;
            case 'p':
                $title = '模板预设';
                break;
            case 'k':
                $title = '快速专题';
                break;
        }
    ?>
    <title><?php echo $title;?></title>
    <script>
    var mainConfig = {
        gmid : <?php echo $main->gmid;?>,
        layout : '<?php echo $main->layout; ?>',
        search : 'magic.php?a=searchCell&<?php echo $main->ext . "&bs=" . $bs; ?>',
        save   : 'magic.php?a=cellUpdate&<?php echo $main->ext . "&bs=" . $bs; ?>&html=1',
        cancel : 'magic.php?a=cellCancle&<?php echo $main->ext . "&bs=" . $bs; ?>',
        build : '<?php echo $main->build; ?>',
        edit : '<?php echo $main->edit . "&" . $main->ext; ?>',
        staticSave : 'plug.php?a=cell_static&is_static=1&html=1',
        datasourcePreview : 'plug.php?a=getDatasourceRecord',
        which : '<?php echo $bs; ?>'
    };
    <?php if($bs == 'k'){ ?>
        mainConfig['getLayoutInfo'] = '<?php echo $main->getLayoutInfo; ?>';
        mainConfig['updateLayout'] = '<?php echo $main->updateLayout . "&" . $main->ext; ?>';
        mainConfig['updateLayoutTitle'] = '<?php echo $main->updateLayoutTitle; ?>';
    <?php } ?>
    </script>
    <?php
    $main->res(
        array(
            'base',
            'main',
            'jqueryui/jquery-ui',
            'plugin',
            'ColorPicker/colorpicker',
            'jquery.alerts'
        ),
    'css');
    $main->res(
        array(
            'jquery.min',
            'jquery-ui-min',
            'jquery.tmpl.min',
            'html2canvas',
            'ajaxload',
            'ajaxupload',
            'ColorPicker/colorpicker',
            'ueditor/ueditor.config',
            'ueditor/ueditor.all',
            'ace/ace',
            'jquery.alerts',
            'plugin',
            'mask',
            'mask_main',
            'mode',
            $bs == 'k' ? 'property.k' : 'property',
            'iframe',
            'main',
            $bs == 'k' ? 'layout' : ''
        )
    );
    echo $main->res('echo');
    ?>
</head>
<body>

<?php include 'view/iframe.php'; ?>

<?php include 'view/mask.php'; ?>

<!-- 样式 start -->
<div id="mode-box" class="m2o-transition">
    <span class="mode-btn" data-open="展开样式" data-close="收&nbsp;起">展开样式</span>
    <div class="shenmi-box">
        <div class="shenmi-title">选中单元，然后朝我拖样式吧</div>
        <div class="shenmi-drop">
            <span class="shenmi-cell" pre="［向我拖动］当前所选单元：" default-title="先选择单元">先选择单元</span>
        </div>
    </div>
    <div class="mode-head">
        <div class="mode-cat"></div>
        <div class="mode-search m2o-transition">
            <input class="mode-search-text m2o-input m2o-border-box" placeholder="输入查询"/>
            <span class="mode-search-btn"></span>
        </div>
    </div>
    <div class="mode-format">
        <label>格式刷：</label>
        <span data-id="{{= id}}" data-type="format" title="{{= name}}">
            ({{= id}}){{= name}}
        </span>
    </div>
    <div class="mode-list-box">
        <div class="mode-list clearfix"></div>
        <div class="mode-pn">
            <span class="mode-p" which="-1" title="上一页">&lt;</span>
            <span class="mode-n" which="1" title="下一页">&gt;</span>
        </div>
    </div>
</div>

<script type="text/x-jquery-tmpl" id="mode-item-tpl">
<div class="mode-item" data-id="{{= id}}">
    <img src="{{= img}}"/>
    <div class="mode-info" title="{{= name}}">
        ({{= id}}){{= name}}
    </div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="mode-cat-tpl">
<span class="mode-cat-current" data-id={{= defaultId}}>{{= defaultCat}}</span>
<ul>
    {{each list}}
    <li data-id="{{= $value.id}}">
        {{= $value.name}}
    </li>
    {{/each}}
</ul>
</script>
<!-- 样式 end -->

<!-- 属性 start -->
<?php if($bs == 'k'){ ?>
<div id="property-box" class="m2o-transition">
    <div>
        <div class="py-head"></div>
        <div class="py-items m2o-border-box">
            <ul class="py-tab m2o-flex">
                <li type="source">数据源</li>
                <li type="bt">标题</li>
            </ul>
            <div class="py-inner m2o-transition">
                <form class="py-source"></form>
                <form class="py-bt">
                    <div class="py-item"><label>标题：</label><input type="text" name="header_text"/><span class="bind-btn" data-type="column-title" title="点击快速选择栏目标题">点选</span></div>
                    <div class="py-item"><label>更多：</label><input type="text" name="more_href"/></div>
                </form>
            </div>
        </div>
        <div class="py-btns">
            <span class="py-submit">确定</span>
        </div>
    </div>
</div>
<?php }else{ ?>
<div id="property-box" class="m2o-transition">
    <div>
        <div class="py-tip">所选择的多个单元并没有设置了相同的样式</div>
        <div class="py-nottip">
            <div class="py-main">
                <div class="py-head">
                    <div class="py-which">
                        <img src=""/>
                        <span class="py-which-title"><em>[点击收起]</em><span></span></span>
                    </div>
                    <span class="py-type-box"></span>
                </div>
                <div class="py-body">
                    <div class="py-items m2o-border-box">
                        <ul class="py-tab m2o-flex">
                            <li type="mode">样式参数</li>
                            <li type="source">数据源</li>
                            <li type="js">JS</li>
                            <li type="css">CSS</li>
                        </ul>
                        <div class="py-inner m2o-transition">
                            <form class="py-mode"></form>
                            <form class="py-source"></form>
                            <form class="py-js"></form>
                            <form class="py-css"></form>
                        </div>
                    </div>
                    <div class="py-btns">
                        <span class="py-submit">确定</span>
                        <!-- <span class="py-change">重设</span> -->
                        <span class="py-huifu">恢复</span>
                        <span class="py-watch">查看</span>
                        <span class="py-format">设为格式刷</span>
                    </div>
                </div>
            </div>


            <div class="py-static-box" style="display:none;">
                该单元是已编辑过的静态化单元<span class="py-static-edit">重编辑</span>
            </div>

            <div class="py-dbox">
                <div class="py-d-tip">该操作将删除单元的设置</div>
                <div class="py-d-option">
                    <span class="py-d-ok">确定</span>
                    <span class="py-d-no">取消</span>
                </div>
            </div>

        </div>
    </div>
</div>
<?php } ?>

<script type="text/x-jquery-tmpl" id="property-type-tpl">
<select class="py-type">
{{each $data}}
<option value="{{= $index}}">{{= $value}}</option>
{{/each}}
</select>
</script>

<script type="text/x-jquery-tmpl" id="property-item-tpl">
    <div class="py-item">
        {{if $data.empty || $data.notong}}
            {{if $data.notong}}
            <div style="text-align:center;margin-top:10px;">无相同样式参数</div>
            {{else}}
            <div style="text-align:center;margin-top:10px;">无参数</div>
            {{/if}}
        {{else}}
            <label>{{= name}}：</label>
            {{if type == 'select'}}
                <select name="{{= sign}}">
                {{each other_value}}
                    <option value="{{= $index}}" {{if $index == $data.value}}selected="selected"{{/if}}>{{= $value}}</option>
                {{/each}}
                </select>
            {{/if}}
            {{if type == 'text' || (type == 'auto' && sign != 'column_id')}}
                <input type="text" name="{{= sign}}" value="{{= value}}"/>
            {{/if}}

            {{if type == 'textarea'}}
                <textarea name="{{= sign}}" rows="6" style="vertical-align:middle;">{{= value}}</textarea>
            {{/if}}

            {{if type == 'date'}}
                <input type="text" readonly="readonly" class="bind-date" data-type="date" name="{{= sign}}" value="{{= value}}" placeholder="选择时间"/>
            {{/if}}

            {{if type == 'column' || type == 'special_column' || (type == 'auto' && sign == 'column_id')}}
                <input type="text" name="{{= sign}}" value="{{= value}}" placeholder="选择栏目"/><span class="bind-column bind-btn" data-type="column" title="点击选择栏目">点选</span>
            {{/if}}

            {{if type == 'pic'}}
                <input type="text" name="{{= sign}}" value="{{= value}}" placeholder="选择图片"/><span class="bind-pic bind-btn" data-type="pic" title="点击选择图片">点选</span>
            {{/if}}

            {{if type == 'bgpic'}}
                <input type="text" name="{{= sign}}" value="{{= value}}" placeholder="选择背景图"/><span class="bind-bgpic bind-btn" data-type="bgpic" title="点击选择背景图">点选</span>
            {{/if}}

            {{if type == 'color'}}
                <input type="text" name="{{= sign}}" value="{{= value}}" placeholder="设置颜色"/><span class="bind-color" data-type="color" title="点击设置颜色"></span>
            {{/if}}

            {{if type == 'margin'}}
                <div class="bind-margin"></div>
                <input type="hidden" name="{{= sign}}" value="{{= value}}"/>
            {{/if}}
        {{/if}}
    </div>
</script>

<script type="text/x-jquery-tmpl" id="property-source-tpl">
    <div class="py-item">
        <label>数据源：</label>
        <select id="source" style="width:130px;">
        {{each source}}
        <option value="{{= $index}}" {{if $index == $data.pid}}selected="selected"{{/if}}>{{= $value}}</option>
        {{/each}}
        </select>
        <span class="ds-watch">查看</span>
    </div>
    {{tmpl($data.data) '#property-item-tpl'}}
</script>

<script type="text/x-jquery-tmpl" id="property-css-tpl">
    {{if $data.empty}}
        <div class="py-item"><div style="text-align:center;margin-top:10px;">无参数</div></div>
    {{else}}
    <div class="py-item">
        <label>CSS：</label>
        <select id="cssid">
        {{each cssList}}
        <option value="{{= $value.id}}" {{if $value.id == $data.cssId}}selected="selected"{{/if}}>{{= $value.title}}</option>
        {{/each}}
        </select>

        <span class="py-gjcss">高级</span>
    </div>
    {{tmpl($data.data) '#property-item-tpl'}}
    {{/if}}
</script>
<!-- 属性 end -->

<!-- 布局 start -->
<div id="layout-box">
    <div class="layout-main m2o-flex m2o-transition"></div>
    <span class="layout-btn m2o-transition">布局</span>
    <span class="layout-close">X</span>
</div>

<script type="text/x-jquery-tmpl" id="layout-tpl">
<div class="layout-cat-each m2o-transition">
    <div class="layout-cat-list m2o-border-box">
        <div class="layout-cat-list-inner">
        {{each list}}
            <div class="layout-item" data-id="{{= $value.id}}" title="{{= $value.title}}">
                <img src="{{= $value.img}}"/>
                <span>{{= $value.title}}</span>
            </div>
        {{/each}}
        </div>
    </div>
    <span class="layout-cat-name">{{= title}}</span>
</div>
</script>

<script type="text/x-jquery-tmpl" id="layout-bt-tpl">
<div class="layout-bt-box 1m2o-transition">
    <div class="lt-bt-title">布局设置</div>
    <div class="lt-bt-item"><label>标题：</label><input type="text" name="header_text" value="{{= headerText}}"/></div>
    <div class="lt-bt-item"><label>更多：</label><input type="text" name="more_href" value="{{= moreHref}}"/></div>
    <div><span class="lt-bt-ok">确定</span><span class="lt-bt-no">取消</span></div>
</div>
</script>
<!-- 布局 end -->

<!-- 布局Mask start -->
<div id="layout-mask-box">
</div>

<script type="text/x-jquery-tmpl" id="layout-mask-item-tpl">
<div class="layout-mask-item" data-hash="{{= hash}}" data-id="{{= id}}">
    <div class="layout-mask-title m2o-flex">
        <div><label>布局标题：</label><input type="text" name="header_text" oldvalue="{{= headerText}}" value="{{= headerText}}"/></div>
        <div><label>更多：</label><input type="text" name="more_href" oldvalue="{{= moreHref}}" value="{{= moreHref}}"/></div>
        <span class="layout-mask-fast">快速选择</span>
    </div>
</div>
</script>
<!-- 布局Mask end -->


<!-- 栏目 start -->
<div id="column-box" class="cl-box plugin-box">
    <div class="cl-inner plugin-inner m2o-border-box">
        <div class="cl-head plugin-head">选择栏目：</div>
        <div class="cl-body plugin-body m2o-flex">
            <ul class="cl-result m2o-border-box"></ul>
            <div class="cl-list m2o-flex-one">
                <div class="cl-list-inner m2o-flex m2o-transition"></div>
            </div>
        </div>
    </div>
    <div class="cl-option plugin-option">
    <span class="cl-save plugin-save">确定</span>
    <span class="cl-cancel plugin-cancel">关闭</span>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="column-level-tpl">
    <ul class="m2o-border-box">
    {{each $data}}
    <li data-id="{{= $value.id}}" data-name="{{= $value.name}}" data-child="{{= $value.is_last}}">
        <input type="checkbox" value="{{= $value.id}}"/>
        {{= $value.name}}
        {{if !($value.is_last > 0)}}
        <span class="cl-child">&gt;</span>
        {{/if}}
    </li>
    {{/each}}
    </ul>
</script>
<script type="text/x-jquery-tmpl" id="column-result-item-tpl">
    <li data-id="{{= id}}" data-name="{{= name}}" title="{{= name}}">
        <input type="checkbox" checked="checked" value="{{= id}}"/>
        {{= name}}
    </li>
</script>
<!-- 栏目 end -->

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

<!-- 背景图标 start -->
<div id="bgpic-box" class="bgpic-box pc-box plugin-box">
    <input type="file" class="pc-file" multiple/>
    <div class="pc-inner plugin-inner m2o-border-box">
        <div class="pc-head plugin-head">选择背景图片：</div>
        <div class="pc-bottom m2o-flex">
            <div class="pc-left m2o-flex-one">
                <ul class="pc-body plugin-body">
                    <li class="pc-add m2o-flex m2o-flex-center m2o-border-box"><div class="m2o-flex-one">上传图片</div></li>
                </ul>
                <div class="pc-pages plugin-pages"></div>
            </div>
            <div class="pc-right">
                <div class="bgpic-item m2o-flex">
                    <div class="bgpic-item-left">
                        重复：
                    </div>
                    <div class="bgpic-repeat m2o-flex-one">
                        <label><input type="radio" name="repeat" value="no-repeat" checked="checked"/>图片不重复</label>
                        <label><input type="radio" name="repeat" value="repeat-x"/>水平重复</label>
                        <label><input type="radio" name="repeat" value="repeat-y"/>垂直重复</label>
                        <label><input type="radio" name="repeat" value="repeat"/>全重复</label>
                    </div>
                </div>
                <div class="bgpic-item m2o-flex">
                    <div class="bgpic-item-left">
                        位置：
                    </div>
                    <div class="bgpic-position m2o-flex-one">
                        <div class="m2o-flex m2o-flex-center">
                            <div>水平位置</div>
                            <div class="bgpic-x my-auto"></div>
                        </div>
                        <div class="m2o-flex m2o-flex-center">
                            <div>垂直位置</div>
                            <div class="bgpic-y my-auto"></div>
                        </div>
                    </div>
                </div>
                <div class="bgpic-item m2o-flex">
                    <div class="bgpic-item-left">
                        背景色：
                    </div>
                    <div class="m2o-flex-one">
                        <input type="text" class="bgpic-color"/><span class="bgpic-color-box bind-color"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pc-option plugin-option">
    <span class="bgpic-save-bg plugin-btn">只设置背景颜色</span>
    <span class="pc-save plugin-save">确定</span>
    <span class="pc-cancel plugin-cancel">关闭</span>
    </div>
</div>
<!-- jquery-tmpl 用的是上面图标的模板 id="pic-item-tpl" -->
<!-- 背景图标 end -->


<!-- 高级CSS start -->
<div id="gjcss-box" class="gjc-box plugin-box">
    <div class="gjc-inner plugin-inner m2o-border-box">
        <div class="gjc-head plugin-head">CSS高级编辑：</div>
        <div class="gjc-body plugin-body">
            <div class="gjc-edit"></div>
        </div>
    </div>
    <div class="cl-option plugin-option">
    <span class="cl-save plugin-save">确定</span>
    <span class="cl-cancel plugin-cancel">关闭</span>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="gjcss-tpl">

</script>
<!-- 高级CSS end -->


<!-- 查看工具 start -->
<div id="magic-watch" class="m2o-border-box">
    <span class="gw-close plugin-cancel">关闭</span>
    <div class="m2o-border-box">
        <div class="gw-titles">
            <span data-type="html" class="gw-title on">html</span>
            <span data-type="css" class="gw-title">css</span>
            <span data-type="js" class="gw-title">js</span>
        </div>
        <div class="gw-texts">
            <div class="gw-html"></div>
            <div class="gw-css" style="display:none;"></div>
            <div class="gw-js" style="display:none;"></div>

            <!--
            <textarea class="gw-html"></textarea>
            <textarea class="gw-css" style="display:none;"></textarea>
            <textarea class="gw-js" style="display:none;"></textarea>
            -->
        </div>
    </div>
</div>
<!-- 查看工具 end -->


<!-- 栏目选择 start -->
<div id="qhcolumn-box" class="qhc-box m2o-flex m2o-transition">
    <div class="qhc-left">
        <div class="qhc-inner m2o-flex m2o-transition">

        </div>
    </div>
    <div class="qhc-right">
        <ul></ul>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="qhcolumn-item-tpl">
    <div class="qhc-item">
        <div class="qhc-title">
        {{if canBack}}
        <span>返回</span>
        {{else}}
        <a style="color:red;">刷新</a>
        {{/if}}
        {{= title}}
        </div>
        <ul>
        {{each list}}
            <li data-id="{{= $value['id']}}" data-pageid="{{= $value['page_id']}}" data-pagedataid="{{= $value['page_data_id']}}" data-name="{{= $value['title']}}">
                {{= $value['title']}}
                {{if !($value['is_last'] > 0)}}
                <span class="qhc-child">&gt;</span>
                {{/if}}
            </li>
        {{/each}}
        </ul>
    </div>
</script>
<script type="text/x-jquery-tmpl" id="qhcolumn-page-tpl">
<li><a href="{{= href}}">{{= name}}</a></li>
</script>
<!-- 栏目选择 end -->


<!-- 页面属性 start -->
<div id="pagepp-box" class="ppp-box plugin-box">
    <div class="ppp-inner plugin-inner m2o-border-box">
        <div class="ppp-head plugin-head">设置页面属性：</div>
        <div class="ppp-body plugin-body">
            <form>
            <div class="ppp-item m2o-flex">
                <label>body：</label>
                <div class="m2o-flex-one">
                    <span>宽度：<input type="text" name="body-width"/></span>
                    <span>背景：<input type="text" name="body-bg"/><span class="bind-btn"></span></span>
                </div>
            </div>
            <div class="ppp-item m2o-flex">
                <label>main：</label>
                <div class="ppp-hmf m2o-flex-one">
                    <div class="ppp-h">头部</div>
                    <div class="ppp-m">主体 <span class="" style="margin-left:25px;">宽度：<input type="text" name="main-width"/></span></div>
                    <div class="ppp-f">底部</div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <div class="ppp-option plugin-option">
        <span class="ppp-save plugin-save">确定</span>
        <span class="ppp-cancel plugin-cancel">关闭</span>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="ppp-item-tpl">

</script>
<!-- 页面属性 end -->

<!-- 单元静态 start -->
<div id="cell-static-box" class="plugin-box">
    <div class="plugin-inner m2o-border-box">
        <div class="plugin-head">静态单元编辑：</div>
        <div class="plugin-body">
            <script id="cell-static-content" type="text/plain"></script>
        </div>
    </div>
    <div class="plugin-option" style="z-index:10000;">
        <span class="plugin-save">确定</span>
        <span class="plugin-cancel">关闭</span>
    </div>
</div>
<!-- 单元静态 end -->

<!-- 栏目标题快速选择 start -->
<div id="column-title-box" class="ctb-box plugin-box">
    <div class="plugin-inner m2o-border-box">
        <div class="plugin-head">栏目标题选择：</div>
        <div class="plugin-body">
            <ul></ul>
        </div>
    </div>
    <div class="plugin-option">
        <span class="plugin-save" style="display:none;">确定</span>
        <span class="plugin-cancel">关闭</span>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="column-title-item-tpl">
<li data-id={{= id}}>{{= name}}</li>
</script>
<!-- 栏目标题快速选择 end -->


<!-- 数据源预览 start -->
<div id="ds-preview-box" class="plugin-box">
    <div class="plugin-inner m2o-border-box">
        <div class="plugin-head"></div>
        <div class="plugin-body m2o-flex">
            <div class="ds-preview-list"></div>
            <div class="ds-preview-code m2o-flex-one"></div>
        </div>
    </div>
    <div class="plugin-option">
        <span class="plugin-cancel">关闭</span>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="ds-preview-item-tpl">
<div>{{= title}}</div>
</script>
<!-- 数据源预览 end -->

<!-- 引入素材列表 start -->
<div id="reslink-box" class="plugin-box">
    <div class="plugin-inner m2o-border-box">
        <div class="plugin-head">页面素材编辑<span style="color:#fece67;font-size:14px;margin-left:10px;">(温馨提醒：编辑完需要刷新页面查看效果)</span></div>
        <div class="plugin-body m2o-flex">
            <div class="reslink-list"></div>
            <div class="reslink-code m2o-flex-one">
                <div class="code-tip">请从左边列表选择要编辑的素材</div>
                <div class="code-content"></div>
                <div class="code-img">
                    <img src=""/>
                    <div>替换</div>
                    <input type="file" id="reslink-file" style="display:none;"/>
                </div>
                <span class="code-update">保存</span>
            </div>
        </div>
    </div>
    <div class="plugin-option">
        <span class="plugin-cancel">关闭</span>
    </div>

    <div class="ds-html-check m2o-transition">
        <iframe class="ds-html-iframe" borderframe="0"></iframe>
        <!--
        <div class="m2o-flex">
            <div class="table-1"></div>
            <div class="table-2"></div>
            <div class="ds-html-cell m2o-flex-one"></div>
        </div>
        -->
        <div class="ds-html-cell">
            <dl class="ds-cell-add">
                <dt>新增的单元</dt>
            </dl>
            <dl class="ds-cell-del">
                <dt>删除的单元</dt>
            </dl>
        </div>
        <div class="ds-html-option"><span class="ds-html-ok">确定</span><span class="ds-html-no">取消</span></div>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="reslink-item-tpl">
<div>模板</div>
<ul>
    <li type="html" template-id="{{= templateId}}">当前模板</li>
</ul>
{{if cssList || jsList || picList}}
{{if cssList}}
<div>CSS</div>
<ul>
    {{each cssList}}
    <li path="{{= $value.path}}" type="{{= $value.type}}">{{= $value.name}}</li>
    {{/each}}
</ul>
{{/if}}
{{if jsList}}
<div>JS</div>
<ul>
    {{each jsList}}
    <li path="{{= $value.path}}" type="{{= $value.type}}">{{= $value.name}}</li>
    {{/each}}
</ul>
{{/if}}
{{if picList}}
<div>图片</div>
<ul>
    {{each picList}}
    <li path="{{= $value.path}}" type="{{= $value.type}}" url="{{= $value.url}}">{{= $value.name}}</li>
    {{/each}}
</ul>
{{/if}}
{{else}}
    <span class="reslink-nolist">没有素材</span>
{{/if}}
</script>
<!-- 引入素材列表 end -->

<?php include 'view/dylist.php'; ?>

</body>
</html>