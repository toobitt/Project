<!-- 单元列表 start -->
<div id="dylist-box" class="m2o-transition">
    <?php include 'view/tip.php'; ?>
    <span class="dylist-btn" data-open="展开区块列表" data-close="收&nbsp;起">展开区块列表</span>
    <div class="dylist-inner m2o-border-box">
    </div>
</div>
<script type="text/x-jquery-tmpl" id="dylist-tpl">
{{each $data}}
<div class="dylist-item" hash="{{= $index}}">
<span class="dylist-title m2o-transition" title="{{= $value.cell_name}}"><span class="gss-btn"></span>{{= $value.cell_name}}</span>
<span class="gss-mask" title="点击为该单元应用格式刷"></span>
<em class="{{if $value.cell_mode>0}}yes{{else}}no{{/if}}"></em>
</div>
{{/each}}
</script>
<!-- 单元列表 end -->