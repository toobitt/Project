{code}//hg_pre($list);{/code}
<div id="record-edit">
    <div class="record-edit">
        <div class="record-edit-btn-area clear">
            <a href="run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1" target="formwin">编辑</a>
            <a href="run.php?mid={$_INPUT['mid']}&a=delete&id=${id}">删除</a>
            {{if state == 1}}
            <a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=0&id=${id}">打回</a>
            {{else}}
            <a href="run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id=${id}">审核</a>
            {{/if}}
        </div>
        <div class="record-edit-line mt20"></div>
        <div class="record-edit-info">
        </div>
        <span class="record-edit-close"></span>
    </div>
    <div class="record-edit-confirm">
        <p>确定要删除该内容吗？</p>
        <div class="record-edit-line"></div>
        <div class="record-edit-confirm-btn">
            <a>确定</a>
            <a>取消</a>
        </div>
        <span class="record-edit-confirm-close"></span>
    </div>
</div>