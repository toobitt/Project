<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livmedia/common/common.css" />


<?php if($formdata['row_id']){ ?>
<?php 
 $v = $formdata;
 $v['id'] = $formdata['row_id'];
 ?>
<li class="common-list-data clear" _id="<?php echo $v['id'];?>" id="r_<?php echo $v['id'];?>" name="<?php echo $v['id'];?>" video_order_id="<?php echo $v['video_order_id'];?>" cname="<?php echo $v['cid'];?>" corderid="<?php echo $v['order_id'];?>">
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input id="<?php echo $v[$primary_key];?>" type="checkbox" name="infolist[]"  value="<?php echo $v[$primary_key];?>" title="<?php echo $v[$primary_key];?>" onclick="hg_get_ids()" /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
        	<?php 
	        	$img = '';
	        	if( is_array($v['img_info']) && $v['img_info']['filename'] ){
	        		$img = hg_bulid_img($v['img_info'], 40, 30);
	        	}else{
	        		$img =	$RESOURCE_URL.'video/video_default.png';
	        	}
        	 ?> 
        	<img _src="<?php echo $img;?>" width="40" height="30" onclick="hg_get_img(<?php echo $v['id'];?>);" id="img_<?php echo $v['id'];?>" title="点击(显示/关闭)截图 " />
        </div>
    </div>
    <div class="common-list-right">
        <div class="vod-fabu common-list-item common-list-pub-overflow">
            	<div class="common-list-pub-overflow">
                <?php 
                $step = '';
                 ?>
                <?php if($v['pub']){ ?>           	
                    <?php foreach ($v['pub'] as $kk => $vv){ ?>
					    	<?php if($v['pub_url'][$kk]){ ?>
					    		<?php if(is_numeric($v['pub_url'][$kk])){ ?>
					    			<a href="./redirect.php?id=<?php echo $v['pub_url'][$kk];?>" target="_blank"><span class="common-list-pub"><?php echo $step;?><?php echo $vv;?></span></a>
					    		<?php } else { ?>
					    		    <a href="<?php echo $v['pub_url'][$kk];?>" target="_blank"><span class="common-list-pub"><?php echo $step;?><?php echo $vv;?></span></a>
					    		<?php } ?>												    	
					    	<?php } else { ?>
					    		<span class="common-list-pre-pub"><?php echo $step;?><?php echo $vv;?></span>
					    	<?php } ?>
                           <?php 
                          	 $step = ' ';
                            ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="vod-maliu common-list-item wd70">
                <span style="background:<?php echo $v['bitrate_color'];?>"  id="bitrate_<?php echo $v['id'];?>"><?php echo $v['bitrate'];?></span>
        </div>
        <div class="vod-fenlei common-list-item wd80">
            <div class="overflow"><span style="color:<?php echo $v['vod_sort_color'];?>" id="sortname_<?php echo $v['id'];?>"><?php echo $v['vod_sort_id'];?></span></div>
        </div>
<?php 
$v['weight'] = $v['weight'] ? $v['weight'] : 0;
 ?>
<div class="common-list-item wd60 news-quanzhong vod-quanzhong open-close">
	<div class="common-quanzhong-box">
		<div class="common-quanzhong-box<?php echo $v['weight'];?>" _level="<?php echo $v['weight'];?>">
			<div class="common-quanzhong" style="background:<?php echo create_rgb_color($v['weight']); ?>">
				<span class="common-quanzhong-label"><?php echo $v['weight'];?></span>
			</div>
		</div>
	</div>
</div>
        <div class="vod-zhuangtai common-list-item wd60" >
               <div <?php if(in_array($v['status_display'], array(1, 2, 3))){ ?>class="common-switch-status"<?php } ?>>
                <span id="text_<?php echo $v['id'];?>" class="zt_a <?php if($v['status_display']== -1 || $v['status_display']== 0){ ?>show-transcode-box<?php } ?>" _id="<?php echo $v['id'];?>" _state="<?php echo $v['status_display'];?>" _stateflag="<?php if($v['status_display']==-1){ ?>transcode-failed<?php } elseif($v['status_display']==0) { ?>transcoding<?php } ?>" style="color:<?php echo $list_setting['status_color'][$v['status']];?>;"><?php echo $v['status'];?></span>
               </div>
                <span id="tool_<?php echo $v['id'];?>" style="display:<?php if($v['status_display'] == 0){ ?>block;<?php } else { ?>none;<?php } ?>" class="zt_b"  title="" >
                    <span class="jd" id="status_<?php echo $v['id'];?>" style="width:0px;" ></span>
                </span>
        </div>
        <div class="vod-ren common-list-item wd100">
                 <span id="hg_t_<?php echo $v['id'];?>" class="hg_t_time" style="display:none;background:#EEEFF1;height:38px;" onmouseover="hg_control_status(this,1);" onmouseout="hg_control_status(this,0);">
                 <?php if($v['status_display'] == -1 && $v['vod_leixing'] != 4){ ?>
                	<a class="button_6"  href="run.php?mid=<?php echo $_INPUT['mid'];?>&id=<?php echo $v['id'];?>&a=retranscode" onclick="return hg_ajax_post(this,'重新转码',0);"  style='margin-left:24px;margin-top:7px;' >重新转码</a>
                 <?php } elseif($v['status_display'] == 0) { ?>
                	<input type='button' value='暂停' class='button_6' style='margin-left:24px;margin-top:7px;'  onclick="hg_controlTranscodeTask(<?php echo $v['id'];?>,1);" />
                 <?php } elseif($v['status_display'] == 4) { ?>
                	<input type='button' value='恢复' class='button_6' style='margin-left:24px;margin-top:7px;'  onclick="hg_controlTranscodeTask(<?php echo $v['id'];?>,0);"  />
                 <?php } elseif($v['vod_leixing'] != 4) { ?>
                	<!--
                	<a class="button_6"  href="run.php?mid=<?php echo $_INPUT['mid'];?>&id=<?php echo $v['id'];?>&a=multi_bitrate" onclick="return hg_ajax_post(this,'新增多码流',0);"  style='margin-left:24px;margin-top:7px;' >新增多码流</a>
                	-->
                 <?php } ?>
                </span>
                <span class="vod-name"><?php echo $v['addperson'];?></span>
                <span class="vod-time"><?php echo $v['create_time'];?></span>
        </div>
    </div>
    <div class="common-list-i" onclick="hg_show_opration_info(<?php echo $v['id'];?>);"></div>
    <div class="common-list-biaoti" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=<?php echo $v['id'];?>&infrm=1">
        <div class="common-list-item biaoti-transition">
                <span class="c_a">
                    <?php if($v['collects']){ ?>
                        <span class="jh"><em id="img_jh_<?php echo $v['id'];?>"  onclick="hg_get_collect_info(<?php echo $v['id'];?>,<?php echo $_INPUT['mid'];?>);"    onmouseover="hg_fabu_jh(<?php echo $v[id];?>)"  onmouseout="hg_back_fabu_jh(<?php echo $v[id];?>)" ></em></span>
                    <?php } ?>
                    <?php if($v['colname']){ ?>
                        <?php echo $v['colname'];?>
                    <?php } else { ?>
                        <?php if($v['pubinfo'][1]){ ?>
                             <span class="lm"><em class="<?php if($v['status_display'] == 2){ ?><?php } else { ?>b<?php } ?>"  id="img_lm_<?php echo $v['id'];?>"    onmouseover="hg_fabu(<?php echo $v[id];?>)"  onmouseout="hg_back_fabu(<?php echo $v[id];?>)"></em></span>
                        <?php } ?>
                        <?php if($v['pubinfo'][2]){ ?>
                            <span class="sj"><em class="<?php if($v['status_display'] == 2){ ?><?php } else { ?>b<?php } ?>"  id="img_sj_<?php echo $v['id'];?>"    onmouseover="hg_fabu_phone(<?php echo $v[id];?>)"  onmouseout="hg_back_fabu_phone(<?php echo $v[id];?>)"></em></span>
                        <?php } ?>
                    <?php } ?>
                </span>
                <a id="t_<?php echo $v['id'];?>" href="run.php?mid=<?php echo $_INPUT['mid'];?>&a=form&id=<?php echo $v['id'];?>&infrm=1" target="formwin">
                <?php $each_title = $v['title'] ? $v['title'] : '无标题'; ?>
                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;"><?php echo $each_title;?></span>
                <?php if($v['starttime']){ ?>
                <span class="vod-date"><?php echo $v['starttime'];?></span>
                <?php } ?>
                <?php if(!$v['is_link']){ ?><span class="vod-duration" id="duration_<?php echo $v['id'];?>"><?php echo $v['duration'];?></span><?php } ?>
                </a>
                <?php if($v['is_link']){ ?>
                <a class="link-upload" title="外部视频"></a>
                <?php } ?>
      </div>
    </div>
    <div class="content_more clear" id="content_<?php echo $v['id'];?>"  style="display:none;">
         <div id="show_list_<?php echo $v['id'];?>" class="pic_list_r">
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
        </div>
         <div id="add_img_content_<?php echo $v['id'];?>"   class="add_img_content">
           <div id="add_from_compueter_<?php echo $v['id'];?>"></div>
         </div>
    </div>
</li>
<?php if($v['childs']){ ?>
	<?php foreach ($v['childs'] as $c){ ?>
		                 <li class="clear"   onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]" style="visibility:hidden;" /></a>
							<a class="slt"><img src="<?php echo $c['img'];?>"  width="40" height="30" />
							</a>
						</span>
	                        <span class="right">
								<a class="fb"><em class="b2" ></em></a>
								<a class="ml" ><em style="background:<?php echo $c['bitrate_color'];?>"><?php echo $c['bitrate'];?></em></a>
								<a class="fl"><em style="color:<?php echo $c['vod_sort_color'];?>" class=" overflow"><?php echo $c['vod_sort_id'];?></em></a>
								<a class="zt">
								   <em><span  class="zt_a"><?php echo $c['status'];?></span></em>
								</a>
								<span  class="hg_t_time" style="display:none"></span>
								<a class="tjr"><em><?php echo $c['addperson'];?></em><span><?php echo $c['create_time'];?></span></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;" >
								<span class="c_a">
									<?php if($c['collects']){ ?>
										<span class="jh"><em id="img_jh_<?php echo $c['id'];?>"></em></span>
									<?php } ?>
									<?php if($c['colname']){ ?>
										<?php echo $c['colname'];?>
									<?php } ?>
									<?php if($c['pubinfo'][2]){ ?>
										<span class="sj"><em class="<?php if($c['status_display'] == 2){ ?><?php } else { ?>b<?php } ?>" ></em></span>
									<?php } ?>
								</span>
								<a><?php echo $c['title'];?>
								<?php if($c['starttime']){ ?>
								<span class="date"><?php echo $c['starttime'];?></span>
								<?php } ?>
								<strong ><?php echo $c['duration'];?></strong></a>
						</span>
                    </li>   
	<?php } ?>
<?php } ?>
<?php } else { ?><?php echo $formdata['ErrorCode']; ?><?php } ?>