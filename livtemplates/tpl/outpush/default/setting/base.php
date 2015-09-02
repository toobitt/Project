<?php 

?>

<ul class="form_ul push-set">
    <li class="i">
        <div class="form_ul_div">
            <span class="title" style="text-align:left;">文稿</span>
            <div class="alternate">
                <img class="img" alt="" src="{$RESOURCE_URL}outpush/alt.png"/>
                <label name="0"><img alt="" src="{$RESOURCE_URL}outpush/alt_btn.png"/></label>
                <input type="hidden" value="0"/>
            </div>
        </div>
    </li>
    
    <li class="i">
        <div class="form_ul_div">
            <span  class="title" style="text-align:left;">图集</span>
            <div class="alternate">
                <img class="img" alt="" src="{$RESOURCE_URL}outpush/alt.png"/>
                <label name="0"><img alt="" src="{$RESOURCE_URL}outpush/alt_btn.png"/></label>
                <input type="hidden" value="0"/>
            </div>
        </div>
    </li>

    <li class="i">
        <div class="form_ul_div">
            <span  class="title" style="text-align:left;">视频</span>
            <div class="alternate">
                <img class="img" alt="" src="{$RESOURCE_URL}outpush/alt.png"/>
                <label name="0"><img alt="" src="{$RESOURCE_URL}outpush/alt_btn.png"/></label>
                <input type="hidden" value="0"/>
            </div>
        </div>
    </li>
    
</ul>



<style type="text/css">
    .push-set{ margin-left:20px;}
    .push-set li .form_ul_div .title{ height:33px; line-height:33px; float:left;}
    .push-set li .form_ul_div .alternate{ position:relative; width:62px; height:33px; display:inline-block;}
    .push-set li .form_ul_div .alternate label{ position:absolute; width:34px; height:33px; left:0px; top:0px;}
    .setting_button{ margin: 15px 0 0 64px;}
</style>

<script type="text/javascript">
   $(".form_ul_div label").on("click",function(){
      var value=$(this).attr("name");
      if(value==0){
          $(this).attr("name","1");
          $(this).animate({"left":"28px"},200);
          $(this).parents(".alternate").find(".img").attr("src","{$RESOURCE_URL}outpush/alt_on.png");
      }
      else{
         $(this).attr("name","0");
         $(this).animate({"left":"0"},200);
         $(this).parents(".alternate").find(".img").attr("src","{$RESOURCE_URL}outpush/alt.png");
      }
   })
</script>
