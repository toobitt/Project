/*文本框判断*/
function isNull(obj)
{
	if(!$.trim(obj.val())) {
		obj.css('background-color','rgb(255, 200, 200)');
		setTimeout(function () {
			obj.css('background-color','white');
		},600);
		return false;
	}else {
		return true;
	}
}
var Backbone=window.Backbone;
var View=Backbone.View;
var App=new View;
/*评论列表视图*/
var CommentItem=View.extend({
      events:{
          'click .comment-link':'showComment',
          'click .comment_btn':'pubComment',
          'click .transmit-link':'showTransmit'
      },
      elements:{
          'comment_area':'.twitter-comment',
          'comment_link':'.comment-link',
          'comment_txt':'.comment-txt',
          'comment_btn':'.comment-btn',
          'twitter_con':'.twitter-con',
          'user_name':'.user-name',
          'load':'.loading'
      },
      addElements:function(){
          for(var el in this.elements){
             this[el]=this.$(this.elements[el]);
          }
      },
      initialize:function(){
      	  this.addElements();
      	  this.comment_list=this.comment_area.find('ul');
      	  this._status_id=this.comment_link.attr('data-id');
      },
      showComment:function(){
          var comment_area=this.comment_area,
              load=this.load,
              comment_list=this.comment_list,
              _status_id=this._status_id;
          if(comment_area.is(':hidden')){
             comment_list.empty();
             load.show();
             comment_area.slideDown();
             $.get('user.php',{
                 a:'get_comment',
                 status_id:_status_id
             },function(data){
                  load.hide();
                  comment_list.html(data);
             });
          }
          else{
              comment_area.slideUp();
          }
      },
      pubComment:function(){
      	  var comment_list=this.comment_list,
      	      comment_txt=this.comment_txt,
      	      comment_btn=this.comment_btn,
      	      _status_id=this._status_id;
          if(isNull(comment_txt)){
              $.post('user.php',{
              	 a:'add_comment',
	             con:comment_txt.val(),
	             sid:this._status_id,
	             cid:0
	          },function(data){
	             comment_list.prepend(data);
	             comment_txt.val('');
            });
          }
      },
      showTransmit:function(event){
         var _user=this.user_name.text(),
             _con=this.twitter_con.text();
         	 _status_id=this._status_id;
             App.trigger('openDialog',_user,_con,_status_id);
             App.trigger('showArrow');
             App.trigger('initTip');
      }
});

/*转发弹窗视图*/
var TransItem=View.extend({
     events:{
           'click .transmit-btn':'pubTransmit',
           'click .W_arrow':'showAllInfo',
           'keyup .transmit-txtarea':'numTip'
     },
     initialize:function(){
    	   this._sid = 0;
     	   this.transmit_dialog=$('#transmit-dialog');
     	   this.title_box=$('.transmit-text');
     	   this.title_area=$('.transmit-text .title');
           this.name=$('.author-name');
           this.con=$('.transmit-con');
           this.txtArea=$('.transmit-txtarea');
           this.W_arrow=$('.W_arrow');
           this.tip=$('.tip');
           this.number=$('.tip-number');
           App.bind('openDialog',this.openDialog, this);
           App.bind('showArrow',this.showArrow,this);
           App.bind('initTip',this.initTip,this);
     },
     openDialog:function(_user,_con,_sid){
     	   var transmit_dialog=this.transmit_dialog;
               transmit_dialog.dialog({
		        autoOpen:true,
		        width:500,
		        modal:true
		   });
		   this.name.text(_user);
		   this.con.text(_con);
		   this._sid = _sid;
     },
     pubTransmit:function(){
           	var _value=this.txtArea.val();
           	    _twitter_list=$('.conList>ul'),
           	    isOut=this.numTip();
           	    if(isOut){
	             $.post('user.php',{
	              	 a:'transmit',
		             text:_value,
		             sid:this._sid
		          },function(data){
		          	 var li = $(data);
		          	 new CommentItem({el:li});
		          	 _twitter_list.prepend(li);
	            });
	            this.transmit_dialog.dialog('close');
           	}
     },
     showArrow: function(){
          var h=this.title_area.height();
          this.title_box.css({height:20+'px'});
          if(h>20){
              $('.W_arrow').show();
          }  
     },
     showAllInfo:function(){
     	 var _height=this.title_area.height();
         this.title_box.css({height:_height+'px'});
         this.W_arrow.hide();
     },
     countStr:function(text){
          var sum=0,halfEn=false;
          for(var i=0; i<text.length; i++){
             if(text.charCodeAt(i)<128){
                halfEn||sum++;
                halfEn=!halfEn;
             }
             else{
                sum++;
             }
          }
          return sum;
     },
     numTip:function(){
          var Total=140;
              realNum=this.countStr(this.txtArea.val());
              stillNum=Total-realNum;
              outNum=-stillNum;
              if(stillNum>=0){
              	this.tip.html("还可以输入<b class='number-normal'>"+stillNum+"</b>字");
              	return true;
              }
              else{
                 this.tip.html("已经超过<b class='number-red'>"+outNum+"</b>字");
                 return false;
              }
     },
     initTip:function(){
     	 var Total=140;
         this.txtArea.val('');
         this.tip.html("还可以输入<b class='number-normal'>"+Total+"</b>字");
     }
});

/*微博列表视图*/
var TwiItem=View.extend({
     events:{
        'focus #content':'showBtn',
        'click .pub-btn':'pubTwitter'
     },
     initialize: function () {
     },
     showBtn:function(){
        $(".btn_list").show(600);
     },
     pubTwitter:function(e){
    	 $(".btn_list").show();
     	var _input=$('#content'),
     	    _twitter_list=$('.conList>ul'),
     	    _img=[];
     		_video=[];
     	$('#img_info input[name="img_ids[]"]').each(function() {
     		_img.push($(this).val());
     	});
     	_img = _img.join(',');
     	$('#video_info input[name="video_ids[]"]').each(function() {
     		_video.push($(this).val());
     	});
     	_video = _video.join(',');
        if(isNull(_input)){
              $.post('user.php',{
              	 a:'add_status',
	             text:_input.val(),
	             p_id:_img,
	             v_id:_video
	          },function(data){
	          	 var li = $(data);
	          	 new CommentItem({el:li});
	          	 _twitter_list.prepend(li);
	             _input.val('');
	             $('#img_info').empty();
	             $('#video_info').empty();
	             $('#fsUploadProgress').empty();
	             $('#usedPic').remove();
	             $('#notUsedPic').remove();
            });
        }
     }
});
jQuery(function($){
	var twiItem=new TwiItem({el:$('.pub')});
	var transItem=new TransItem({el:'#transmit-dialog'});
	$('.conList .comment-item').each(function () {
		new CommentItem({ el: this });
	});
})
