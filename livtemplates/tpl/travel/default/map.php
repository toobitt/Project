<div>
	{code}
	$hg_map = array(
			'height'=>180,
			'width'=>600,							
			'longitude'=>$list['longitude'],         	//经度
			'latitude'=>$list['latitude'], 			    //纬度
			'zoomsize'=>13,          					//缩放级别，1－21的整数
			'areaname'=>$formdata,          			//显示地区名称，纬度,经度与地区名称二选1
			'is_drag'=>1,            					//是否可拖动 1－是，0－否
		);
	{/code}
	{template:form/google_map,longitude,latitude,$hg_map}
</div>