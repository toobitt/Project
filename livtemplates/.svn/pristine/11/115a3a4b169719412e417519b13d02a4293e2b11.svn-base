<?php
$states = array('失败','成功');
?>
<script type="text/javascript">
	function cdn_frontpush(id,type)
	{
		$.ajax({
			url:'./run.php?mid='+gMid+'&a=pushforfront&id='+id+'&type='+type+'&infrm=1',
			cache:false,
			type:'POST',
			success:function(datas)
			{
				
			}
		});
	}
	
</script>
<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}" >
    <div class="common-list-left">
        <div class="common-list-item" style="width:35px">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item caozuo">
             <div class="common-list-cell">
       			<a title="详细" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
       			<em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
				<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&tbname=cdn_log">
				<em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
    			{if $v['state']==0}
			 	<!--
	       		<a title="推送" href="./run.php?mid={$_INPUT['mid']}&a=pushforfront&id={$v['id']}&infrm=1&type={$v['type']}">
       			<em class="b4" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>-->
       			<button title="推送" onclick="cdn_frontpush(<?php echo $v['id'].',\''.$v['type'].'\'';?>)" style="width:36px;height:20px;margin:10px 0 0 0 ;">
       			推送</button>
	  			{/if}
        	</div>
        </div> 
        

        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['type']}</span>
            </div>
        </div>
        
        <div class="common-list-item circle-tjr"  style="width:70px">
            <div class="common-list-cell"  style="width:70px">
            		<?php
            		if($v['state']==0)
					{
						echo "<span>".$v['remsg']."</span>";
					}
					else
					{
						echo "<span></span>";		
					}
					?>
            </div>
        </div>
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$states[$v['state']]}</span>
            </div>
        </div>       
        <div class="common-list-item circle-tjr">
            <div class="common-list-cell">
                    <span>{$v['create_time']}</span>
            </div>
        </div> 
      
    </div>
    <div class="common-list-biaoti">
    	 <div class="common-list-item  biaoti-transition">
			   <div class="common-list-cell">
				<span class="m2o-common-title">
					<?php 
					$data = $v['data'];
					$data = unserialize($data);
					$data = json_decode($data['task'],1);
					$dirs = array();
					$urls = array();
					$urls = $data['urls'];
					$dirs = $data['dirs'];
					//echo "Urls:<br/>";
					foreach($urls as $url)
					{
						echo "$url<br/>";
						break;
					}
					?>
				</span>
			   </div>
		</div>
	</div>  
</li>