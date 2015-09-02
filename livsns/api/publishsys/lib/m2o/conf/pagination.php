<?php
/**
* @File: 		pagination.php
* @Description: TODO Describe the File
* 
* @copyright:	Copyright (c) 2013, EMAT All Rights Reserved.
* @author   	Dong (Dong@hoge.cn)
* @date     	2013-5-1
*/
return array(
	
	'default' => array(
		'base_url' => '',
		'per_page' => 4,
		'uri_segment' => 3,
		'num_links' => 3,
		'show_nums' => 5,
		'prev_link_hide'=>false,
		'first_link' =>'第一页',
		'last_link' =>'最后一页',
		'prev_tag_open'=>'',
		'prev_tag_close'=>'',
		'prev_link' => '上一页',
		'next_tag_open'=>'',
		'next_tag_close'=>'',
		'next_link' => '下一页',
		'page_tag_open'=>'',
		'page_tag_close'=>'',
		'cur_tag_open' => '<span class="current">',
		'cur_tag_close' => '</span>',
		'num_tag_open' => '',
		'num_tag_close' => '',
		'display_pages' => true,
		'use_page_numbers' => false,
		'page_query_string' => true,
		'full_tag_open' => '<div class="meneame">',
		'full_tag_close' => '</div>',
		'first_tag_open' => '<span class="disabled">',
		'first_tag_close' => '</span>',
		'last_tag_open' => '',
		'last_tag_close' => '',
		'query_string_segment'=>'pp',
		'need_show_all_pages'=>true,
		'all_link'=>'阅读全文',
                'is_show_total' => 0,
	),

);

?>