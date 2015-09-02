{template:head}
{code}
   $data = array(
   		0 => array('id' => 1,'img' => 'http://localhost/livtemplates/tpl/lib/images/tu1.jpg','title' => '你好','description' =>'嘎嘎'),
   		1 => array('id' => 2,'img' => 'http://localhost/livtemplates/tpl/lib/images/tu1.jpg','title' => '你好','description' =>'嘎嘎'),
   		2 => array('id' => 3,'img' => 'http://localhost/livtemplates/tpl/lib/images/tu1.jpg','title' => '你好','description' =>'嘎嘎'),
   		3 => array('id' => 4,'img' => 'http://localhost/livtemplates/tpl/lib/images/tu1.jpg','title' => '你好','description' =>'嘎嘎'),
   );
{/code}
{template:form/select_image,trans_status,$_INPUT['trans_status'],$data,$data}
{template:foot}