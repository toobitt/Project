<script>
function  column_toedit(id)
{
	window.location = "./run.php?mid={$_INPUT['mid']}&a=form&infrm=1&id="+id ;
}
</script>
<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
             <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item">
            <a class="btn-box" title="编辑" onclick="column_toedit({$v['id']});"><em class="b2" ></em></a>
        </div>
        <div class="common-list-item wd150">
            <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&client_id={$v['id']}"><em class="b3" ></em></a>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
	         {$v['name']}
	     </div>
	</div>
</li>   