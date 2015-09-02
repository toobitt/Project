{template:head}

<div style="width:614px;height:538px;border:1px solid #7A7A7A;margin:0px auto;filter:alpha(opacity=90);
 -moz-opacity:0.9;-khtml-opacity: 0.9;opacity: 0.9;">
   <div style="width:612px;height:40px;border:1px solid white;background:#EFEFEF;">
      <div  style="margin-left:17px;margin-top:13px;"><b>编辑视频</b></div>
   </div>
 <form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="vodform" id="vodform" >
   <div style="width:612px;height:110px;border-bottom:1px dotted gray;border-top:1px dotted gray;">
	   <div style="width:420px;height:108px;float:left;">
	     <input id="hg_title" type="text" name="title" value="{$formdata['title']}"  style="width:388px;height:20px;border:1px solid gray;margin-left:12px;margin-top:13px;" />
	     <textarea id="hg_comment" name="comment" style="width:388px;height:39px;border:1px solid gray;margin-left:13px;margin-top:14px;">{$formdata["comment"]}</textarea>
	   </div>
     
	   <div style="width:184px;height:108px;float:right;"> 
		   <div style="float:left;">
		       <label  style="color:gray;">来源</label>
		       <select name="vod_sort_id" style="width:137px;height:24px;border:1px solid gray;margin-top:12px;">
			   {foreach $formdata['sort_name'] as $v}
			     <option value="{$v['id']}">{$v['sort_name']}</option>
			   {/foreach}
			  </select>
		   </div>
		    <div style="float:left;">
			  <label style="color:gray;">作者</label>
			  <input type="text" name="author" value="{$formdata['author']}"  style="width:120px;height:20px;border:1px solid gray;margin-top:15px;">
			</div>
	   </div>
   </div>
   <div style="width:612px;height:47px;border-bottom:1px dotted gray;">
	    <div style="float:left;">
	      <label  style="margin-left:15px;color:gray;">副题</label>
	      <input type="text" name="subtitle"  value="{$formdata['subtitle']}"   style="width:240px;height:20px;border:1px solid gray;margin-top:8px;">
	    </div>
	     <div style="float:left;margin-left:6px;">
	      <label  style="color:gray;">关键字</label>
	      <input type="text" name="keywords"  value="{$formdata['keywords']}"  style="width:210px;height:20px;border:1px solid gray;margin-top:8px;margin-left:5px;">
	     </div>
   </div>
  <input type="hidden" name="vod_leixing" value=0 >
  <input type="hidden" value="{$a}" name="a" />
  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
  <input type="submit" value="{$optext}" style="width:89px;height:31px;margin-left:13px;" />
 </form>
</div>

{template:foot}
