<?php
/* $Id: index.tpl.php 2254 2011-02-26 03:15:30Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<div style="background:#fff;">
<!--<div class="bg_body_main">
	<div class="main_in">
    	<div class="main_list">
        	<div class="main_list_left">
            	<span><img src="./res/img/pic1.jpg" width="127" height="77" /></span>
                <span><img src="./res/img/pic1.jpg" width="127" height="77" /></span>
                <span><img src="./res/img/pic1.jpg" width="127" height="77" /></span>
            </div>
            <div class="main_list_center">
            	<span class="center_header_top"></span>
                <span class="light"></span>
                <div class="center_body">
                	<div class="main_left">
                    	<ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                        <ul>
                        	<span>杭州综合频道:</span>
                            <img src="./res/img/pic2.jpg" width="30" height="30" />
                            <li>新闻60分</li>
                        </ul>
                    </div>
                    <div class="main_right">
                    	<img src="./res/img/pic3.jpg" width="222" height="165" />
                    </div>
                </div>
            </div>
            <div class="main_list_right">
            	<span class="top_pic_"><img src="./res/img/pic4.gif" width="138" height="112" /></span>
                <span class="play_button"></span>
                <a href="##"><strong>葫芦网独家专访</strong></a>
                <a href="##"><strong>葫芦网来了深入报道</strong></a>
                <a href="##">油老虎切诺基全新上市</a>
                <small class="name">新闻60分</small>
                <small class="date">2010-01-05</small>
                <small class="play_time">170</small>
            </div>
        </div>
    </div>
</div>
<div class="change_top">
	<div class="change_before">
    	<img src="./res/img/change_l.jpg" class="left" />
        <span class="now">正在播出</span>
        <span>热播</span>
        <span>推荐视频</span>
        <img src="./res/img/change_r.jpg" class="right" />
    </div>
</div>




-->
<div class="new">
	<span class="title"><a href="###"></a><img src="./res/img/new_epg.jpg" width="62" height="16" /></span>
	<div class="out">
	<?php 
		if($new_video)
		{?>
		<ul>
		<?php 
			foreach($new_video as $key=>$value)
			{?>
			<li>
				<a target="_blank" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><img src="<?php echo $value['schematic'];?>"/></a>
				<a target="_blank" class="video-name" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><?php echo $value['title'];?></a>
				<span class="toff"><?php echo hg_encode_time($value['toff'],':',3)?></span>
				<span class="create-time"><?php echo date("Y-m-d",$value['create_time']);?></span>
				
			</li>
		<?php		
			}
		?>
		</ul>
	<?php
		}
	?>
		
	</div>
	<img src="./res/img/new_bottom.jpg" width="950" height="7" class="bottom" />
</div>


<div class="recommend">
	<div class="recommend_left">
    	<span class="title"><a href="###"></a><img src="./res/img/recommend_r.jpg" width="49" height="17" /></span>
        <ul>
        <?php 
        if(!$stationInfo)
        {
        ?>
        	<li><a href="###"><img src="./res/img/pic4.jpg" width="138" height="103" /></a><a href="###">网络电视台一号</a><a href="###">汤唯不愿被比较武侠</a></li>
            <li><a href="###"><img src="./res/img/pic4.jpg" width="138" height="103" /></a><a href="###">网络电视台一号</a><a href="###">汤唯不愿被比较武侠</a></li>
            <li><a href="###"><img src="./res/img/pic4.jpg" width="138" height="103" /></a><a href="###">网络电视台一号</a><a href="###">汤唯不愿被比较武侠</a></li>
            <li><a href="###"><img src="./res/img/pic4.jpg" width="138" height="103" /></a><a href="###">网络电视台一号</a><a href="###">汤唯不愿被比较武侠</a></li>
            <li><a href="###"><img src="./res/img/pic4.jpg" width="138" height="103" /></a><a href="###">网络电视台一号</a><a href="###">汤唯不愿被比较武侠</a></li>
            <li><a href="###"><img src="./res/img/pic4.jpg" width="138" height="103" /></a><a href="###">网络电视台一号</a><a href="###">汤唯不愿被比较武侠</a></li>
        <?php         	
        }
        else
        {
	        foreach($stationInfo as $key => $value)
	        {
        	?>
        	<li>
	        	<a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$value['id']));?>"><img src="<?php echo $value['small'];?>" width="138" height="103" /></a>
	        	<a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$value['id']));?>"><?php echo $value['web_station_name'];?></a>
	        	<a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$value['id']));?>"><?php echo $value['brief']?$value['brief']:'暂无简介';?></a>
        	</li>
     <?php
			}  	
        }
 		?>
        </ul>
        <img src="./res/img/recommend_bottom.jpg" width="466" height="7" class="bottom" />
    </div>
    <div class="recommend_right">
    	<span class="title"><a href="###"></a><img src="./res/img/recommend_epg.jpg" width="81" height="16" /></span>
        <div class="out">
        <?php 
//        hg_pre($user_info);
        if($user_info)
        {
        	foreach($user_info as $key => $value)
        	{
        	?>
			<div>
            	<ul>
            		<li>
                	<a href="<?php echo hg_build_link('user.php', array('user_id'=>$value['id']));?>">
                		<img src="<?php echo $value['middle_avatar'];?>" width="53" height="56" class="header_photo"/>
                	</a></li>
                    <li><a href="<?php echo hg_build_link('user.php', array('user_id'=>$value['id']));?>"><?php echo $value['username'];?></a></li>
                    <li>人气：<span id="c_<?php echo $value['sta_id'];?>"><?php echo $value['collect_count'];?></span></li>
                    <li id="collect_<?php echo $value['sta_id'];?>">
                    <?php if($value['relation'])
                    {
                    	echo "已关注";  
                    }
                    else 
                    {?>
                     <a href="javascript:void(0);" onclick="add_collect(<?php echo $value['sta_id'];?>,1,<?php echo $value['id'];?>);"><img src="./res/img/gz_button.jpg" width="58" height="18" /></a>
                  <?php 	
                    }?>
                   </li>
                </ul>
                <ul class="epg">
                <?php 
                
                if(!$value['program'])
                {?>
                <li><a href="#">用户暂未设置节目单</a></li>
               <?php  	
                }
                else 
                {
                	foreach ($value['program'] as $k => $v)
                	{
                		if($v['play'])
                		{
                		?>
	                		<li class="nowplay">
	                		<?php echo hg_encode_time($v['start_time']);?>
	                		<a target="_blank" href="<?php echo hg_build_link('station_play.php', array('sta_id'=>$v['sta_id'],'user_id'=>$v['user_id']));?>"><?php echo $v['programe_name'];?></a>
	                		</li>
                		<?php 
                		}
                		else 
                		{
                		 ?>
	                		<li>
	                		<?php echo hg_encode_time($v['start_time']);?>
	                		<?php echo $v['programe_name'];?>
	                		</li>
                		<?php	
                		}
                		?>
                		
                <?php		
                	}
                }
                ?>
                </ul>
            </div>
       <?php  	
        	}
        }
        ?>

        </div>
        <img src="./res/img/recommend_bottom.jpg" width="466" height="7" class="bottom"/>
    </div>
</div>
</div>
<?php include hg_load_template('foot');?>