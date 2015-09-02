<script>
function  column_toedit(id)
{
	window.location = "./run.php?mid={$_INPUT['mid']}&a=set_form&infrm=1&id="+id ;
}
</script>
<li class="common-list-data public-list clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['id']}">
     <div class="common-list-left">
        <div class="common-list-item paixu">
            <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item wd300">
            <span>{$v['linkurl']}</span>
        </div>
        <div class="common-list-item wd50">
            <a class="btn-box" title="编辑" onclick="column_toedit({$v['id']});"><em class="b2"></em></a>
        </div>
        <div class="common-list-item wd50">
            <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
        </div>
        <div class="common-list-item wd150">
            <span class="common-user">{code}echo date('H:i:s',$v['create_time']){/code}</span>
            <span class="common-time">{code}echo date('Y-m-d',$v['create_time']){/code}</span>
        </div>
    </div>
    <div class="common-list-biaoti">
	     <div class="common-list-item biaoti-transition">
	          <a  {if !empty($v['have_child'])}href="./run.php?mid={$_INPUT['mid']}&a=show&set_id={$v['id']}"{/if}>
	          <span class="m2o-common-title">{$v['name']}{if !empty($v['have_child'])} >>{/if}</span></a>
	     </div>
	</div>
</li>   