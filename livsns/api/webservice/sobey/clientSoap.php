<?php

    $client = new SoapClient("VodWebservice.wsdl");
    echo $client->create('<?xml version="1.0" encoding="UTF-8"?><CMSContentInfo><ContentID>3f1a636904d6418ea9e3a232118fc640</ContentID><TypeID>Pgm</TypeID><EntityData><TypeID>Pgm</TypeID><TypeName>节目</TypeName><AttributeItem><ItemCode>Name</ItemCode><ItemName>Name</ItemName><Value>来了来了</Value></AttributeItem><AttributeItem><ItemCode>Description</ItemCode><ItemName>内容描述</ItemName><Value></Value></AttributeItem><AttributeItem><ItemCode>CreateDate</ItemCode><ItemName>创建日期</ItemName><Value>2012-11-06 14:47:33</Value></AttributeItem><AttributeItem><ItemCode>PgmType</ItemCode><ItemName>节目分类</ItemName><Value></Value></AttributeItem><AttributeItem><ItemCode>PublishedType</ItemCode><ItemName>发布类型</ItemName><Value>0</Value></AttributeItem><AttributeItem><ItemCode>CatalogID</ItemCode><ItemName>节目分类ID</ItemName><Value></Value></AttributeItem><AttributeItem><ItemCode>PublishedTerminer</ItemCode><ItemName>发布终端ID</ItemName><Value>0</Value></AttributeItem></EntityData><ContentFile><FileItem><FileGUID>782AF1B23437406aBBC419BAB4D01136</FileGUID><TrackID>0</TrackID><QualityType>0</QualityType><MediaChannel>0</MediaChannel><FileTypeID>FLV</FileTypeID><FileName>900x48/2012/11/1352346328.ssm/video_1352346328.flv</FileName><FileState>1</FileState><FileInpoint>0</FileInpoint><FileOutpoint>0</FileOutpoint><VerifyCode/><FileLength>0</FileLength></FileItem></ContentFile></CMSContentInfo>');



?>
