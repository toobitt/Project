<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
             <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item">
            <a class="btn-box" title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" ></em></a>
        </div>
        <div class="common-list-item">
            <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
	        <span class="m2o-common-title">{$v['name']}</span>
	     </div>
	</div>
</li>