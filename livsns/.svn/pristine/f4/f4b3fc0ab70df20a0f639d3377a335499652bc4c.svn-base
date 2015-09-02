function _hide() {
	$("#sea_name").hide();
}

function hg_urlencode(str)
{
    var ret = "";
    for(var i=0;i<str.length;i++) {
        var chr = str.charAt(i);
        if(chr == "+") {
            ret += " ";
        }else if(chr=="%") {
            var asc = str.substring(i+1,i+3);
            if(parseInt("0x"+asc)>0x7f) {
                ret += decodeURI("%"+ str.substring(i+1,i+9));
                i += 8;
            }else {
                ret += String.fromCharCode(parseInt("0x"+asc));
                i += 2;
            }
        }else {
            ret += chr;
        }
    }
    return ret;
}
$(document).ready(function (){
	
	var gSearchTarget = ['人' , '点滴' , '地盘', '帖子', '频道', '视频'];
	var gSearchUrl = [ 
								'http://t.hoolo.tv/n.php?search_name=' , 
								'http://t.hoolo.tv/k.php?q=' , 
								'http://city.hoolo.tv/?m=group&a=search4group&k=', 
								'http://city.hoolo.tv/?m=thread&a=search&t_t=', 
								'http://v.hoolo.tv/search.php?k=',
								'http://v.hoolo.tv/station_search.php?k='
							];
	var div = "<div id='sea_name' style='left:0;margin-left:1px;'></div>";
	$(div).appendTo("#form");
	
	$(".sea_name").keyup(function() {
		
		var keyWords = $(this).val();
		var len = gSearchTarget.length;
		if(keyWords.length > 0){
			var search_list = "<ul>";
			for(var i = 0 ; i < len ; i++)
			{
				search_list += '<li style="padding:5px;cursor:pointer;"><a href="'+gSearchUrl[i] +hg_urlencode(keyWords)+'" target="_blank" onclick="_hide();">搜索含有<span style="color:#ff0000;">'+ keyWords +'</span>的'+ gSearchTarget[i]  +'</a></li>';
			}	
			search_list += '</ul>';
			$('#sea_name').html(search_list);
			$('#sea_name').show();			
		}else{
			$("#sea_name").hide();
		}
	});
	
});
