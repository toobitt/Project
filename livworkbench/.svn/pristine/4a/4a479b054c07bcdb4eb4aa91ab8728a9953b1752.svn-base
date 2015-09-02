/**
 * @author kinch
 * @param npage
 * @return display a empty editor and save the inputed content to a javascript array
 */
 var cur_page = 1;
 var last_page = 1;
 function next_page(npage)
 {
	var content = document.getElementById(ElementName);
	var pagenum = document.getElementById("pagenum");
	var page_nav = document.getElementById("page_nav");
	var tempcontent = document.getElementById("tempcontent");

	onsubmit_new();
	
	pagecount = pagenum.value;
	last_page = npage;
	if(content.value == "<P>&nbsp;</P>" || content.value == "<DIV>&nbsp;</DIV>" || content.value == "")
	{//如果内容为空的处理
		
		alert("系统提示:\n\n当前页中内容为空, 不能跳转到下一页!");
	}
	else if(content.value.length > 0)
	{
		nextpage = npage + 1;
		if(npage > pagecount)
		{
			cur_page = npage;
			pagenum.value = npage;
			//如果不存在当前的表单元素,则添加表单元素
			
			if(!document.getElementById(ElementName + (npage-1)))
			{
				tempcontent.innerHTML +=  "<textarea name='" + ElementName +eval(npage-1)+"' id='" + ElementName + eval(npage-1) + "' style='display:none;'>" + content.value +"</textarea>" ;
				page_nav.innerHTML = "";
				page_nav.innerHTML = "<a href='javascript:pre_page("+ eval(npage-1) +");'>上一页</a>&nbsp;";
				for(i = 1; i <= npage; i++)
				{
					var scolor = "#000000";
					if(i == cur_page)
					{
						scolor = "#FF0000";
					}
					page_nav.innerHTML += "<a href='javascript:gotopage(" + i + ")'>[<span style='color:" + scolor + ";'>" + i + "</span>]</a>&nbsp;";
					if(i % 10 == 0)
					{
						page_nav.innerHTML += "<br />";
					}
				}

				page_nav.innerHTML += "&nbsp;&nbsp;&nbsp;<a href='javascript:next_page("+(eval(npage) + 1)+");'>新建页</a>";				
				content.value = "";
				oEdit1.clearAll0();
			}
			else
			{
				oEdit1.clearAll0();
				page_nav.innerHTML = "";
				page_nav.innerHTML = "<a href='javascript:pre_page("+ eval(npage-1) +");'>上一页</a>&nbsp;";
				for(i = 1; i <= npage; i++)
				{
					var scolor = "#000000";
					if(i == cur_page)
					{
						scolor = "#FF0000";
					}
					page_nav.innerHTML += "<a href='javascript:gotopage(" + i + ")'>[<span style='color:" + scolor + ";'>" + i + "</span>]</a>&nbsp;";
					if(i % 10 == 0)
					{
						page_nav.innerHTML += "<br />";
					}
				}
				
				page_nav.innerHTML += "&nbsp;&nbsp;&nbsp;<a href='javascript:next_page("+(eval(npage) + 1)+");'>新建页</a>";
			}
		}
		else
		{
			gotopage(npage);
		}
	}
 }
 
 /**
  * @author kinch
  * @param cur_page
  * @return display the current page content which has been saved in javascript array
  */
  function pre_page(ppage) 
  {
	//alert(last_page);
	var content = document.getElementById(ElementName);
	var pagenum = document.getElementById("pagenum");
	var page_nav = document.getElementById("page_nav");
	var tempcontent = document.getElementById("tempcontent");
	
	onsubmit_new();
	if(content.value == "<P>&nbsp;</P>" || content.value == "<DIV>&nbsp;</DIV>" || content.value == "")
	{
		alert('系统提示:\n\n内容为空,不作保存,将直接跳转到第　' + ppage +'　页!');
	}
	else
	{
		if(document.getElementById(ElementName + cur_page))
		{
			document.getElementById(ElementName + cur_page).value = content.value;
		}
		else
		{
			tempcontent.innerHTML +=  "<textarea name='" + ElementName + eval(cur_page) + "' id='" + ElementName + eval(cur_page) + "' style='display:none;'>" + content.value +"</textarea>" ;
			pagenum.value = cur_page;
		}
	}

	page_nav.innerHTML = "";
	prepage = ppage -1;
	nextpage = ppage + 1;
	cur_page = ppage;
	if(prepage < 1)
	{
		page_nav.innerHTML += "上一页&nbsp;";
	}
	else
	{
		page_nav.innerHTML += "<a href='javascript:pre_page("+ prepage +");'>上一页</a>&nbsp;";
	}
	for(i = 1; i <= pagenum.value; i++)
	{
		var scolor = "#000000";
		if(i == cur_page) 
		{
			scolor = "#FF0000";
		}
		page_nav.innerHTML += "<a href='javascript:gotopage(" + i + ")'>[<span style='color:" + scolor + ";'>" + i + "</span>]</a>&nbsp;";
		if(i % 10 == 0)
		{
			page_nav.innerHTML += "<br />";
		}
	}
	page_nav.innerHTML += "&nbsp;&nbsp;&nbsp;<a href='javascript:next_page("+(eval(pagenum.value) + 1)+");'>新建页</a>";
	
	var toshowcontent = document.getElementById(ElementName + ppage);
	content.value = toshowcontent.value;
	oEdit1.applypage(ppage,ElementName);
	last_page = page;
  }


  function check_content(lpage)
  {
	var tempcontent = document.getElementById("tempcontent");
	var pagenum = document.getElementById("pagenum");
	var children = tempcontent.childNodes;
	var str = '';
	var j = 1;
	for(var i = 0; i < children.length; i++)
	{
		if(lpage != (eval(i)+1))
		{
			//alert( children[i].value);
			str += '<textarea name="' + ElementName + j + '" id="' + ElementName + j + '" style="display:none;">' + children[i].value +'</textarea>';
			j++;
		}
	}
//	pagenum.value = cur_page;
	tempcontent.innerHTML = str;
  }

/**
 * @author kinch
 * @argument page
 * @return to show the needed page and process the current page content
 */
 function gotopage(page)
 {
	var content = document.getElementById(ElementName);
	var pagenum = document.getElementById("pagenum");
	var page_nav = document.getElementById("page_nav");
	var tempcontent = document.getElementById("tempcontent");

	onsubmit_new();
	if(content.value == "<P>&nbsp;</P>" || content.value == "<DIV>&nbsp;</DIV>" || content.value == "")
	{
		cur_page = page;
		alert('系统提示:\n\n内容为空,不作任何处理,直接跳转到第' + page +"页!");

		if(document.getElementById(ElementName + cur_page))//如果已经存在
		{
			if(!oEdit1.is_content())
			{
				pagenum.value = page;
			//	tempcontent.removeChild(document.getElementById(ElementName + cur_page));
				check_content(last_page);
				if(page < 2)
				{
					cur_page = 1;
				}
				else
				{
					cur_page = page;
				}
			}
		}
	}
	else
	{
		if(document.getElementById(ElementName + cur_page))//如果已经存在
		{
			document.getElementById(ElementName + cur_page).value = content.value;
		}
		else
		{
			tempcontent.innerHTML +=  "<textarea name='" + ElementName + eval(cur_page) + "' id='" + ElementName + eval(cur_page) + "' style='display:none;'>" + content.value +"</textarea>" ;
			pagenum.value = cur_page;
		}
		cur_page = page;
	}
	
	page_nav.innerHTML = "";
	prepage = page -1;
	nextpage = page + 1;
	if(prepage < 1)
	{
		page_nav.innerHTML += "上一页&nbsp;";
	}
	else
	{
		page_nav.innerHTML += "<a href='javascript:pre_page("+ prepage +");'>上一页</a>&nbsp;";
	}

	for(i = 1; i <= pagenum.value; i++)
	{
		var scolor = "#000000";
		if(i == cur_page) 
		{
			scolor = "#FF0000";
		}
		page_nav.innerHTML += "<a href='javascript:gotopage(" + i + ")'>[<span style='color:" + scolor + ";'>" + i + "</span>]</a>&nbsp;";
		if(i % 10 == 0)
		{
			page_nav.innerHTML += "<br />";
		}
	}
	
	page_nav.innerHTML += "&nbsp;&nbsp;&nbsp;<a href='javascript:next_page("+(eval(pagenum.value) + 1)+");'>新建页</a>";
	
	var toshowcontent = document.getElementById(ElementName + cur_page);
	content.value = toshowcontent.value;
	oEdit1.applypage(cur_page,ElementName);
	last_page = page;
 }
 

  function upcontent()
  {
	var content = document.getElementById(ElementName);
	var pagenum = document.getElementById("pagenum");
	var page_nav = document.getElementById("page_nav");
	var tempcontent = document.getElementById("tempcontent");
  	//如果当前页为空,则当前就是第一页
  	if(!(cur_page))
	{
		cur_page = 1;
	}

  	if(document.getElementById(ElementName + cur_page))
  	{
		document.getElementById(ElementName + cur_page).value = content.value;
  	}
  	else if(content.value != "<P>&nbsp;</P>" && content.value != "<DIV>&nbsp;</DIV>" && content.value != "")
  	{
		tempcontent.innerHTML +=  "<textarea name='" + ElementName + eval(cur_page)+"' id='" + ElementName + eval(cur_page) + "' style='display:none;'>" + content.value +"</textarea>" ;
		pagenum.value = cur_page;
  	}
  }

  function insert_into(id,url,mark)
  {
	var str = '<a href="###" id="mat_' + id + '_' + mark + '"><img id="" src="' + url + '" /></a>';
    var obj = oUtil.obj;
	obj.insertHTML(str);
  }
 