<?php
/* $Id: list.php 31001 2014-03-18 04:08:00Z wangleyuan $ */
?>
{code}
{/code}

{template:head}
{code}
$attrs_for_edit = array('pub_url');
{/code}
{template:list/common_list}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
{template:unit/news_search}
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
    {if !$list}
    <p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
    <script>hg_error_html('#emptyTip',1);</script>
    {else}
    <form method="post" action="" name="listform" class="common-list-form">
        <!-- 头部，记录的列属性名字 -->
        <ul class="common-list news-list">
            <li class="common-list-head public-list-head clear">
                <div class="common-list-left">

                </div>
                <div class="common-list-right">
                    <div class="common-list-item" style="width:550px;">描述</div>
                    <div class="common-list-item wd70">状态</div>
                    <div class="common-list-item wd70">操作</div>
                </div>
                <div class="common-list-biaoti">
                    <div class="common-list-item">标题</div>
                </div>
            </li>
        </ul>
        <!-- 主题，记录的每一行 -->
        <ul class="common-list public-list">
            {foreach $list as $k => $v}
                {template:unit/payconfiglist}
            {/foreach}
        </ul>
        <!-- foot，全选、批处理、分页 -->
        <ul class="common-list public-list">
            <li class="common-list-bottom clear">
                <div class="common-list-left">

                </div>
            </li>
        </ul>
    </form>
    {/if}
</div>
<script>
</script>
{template:foot}     				