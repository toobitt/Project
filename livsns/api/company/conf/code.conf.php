<?php
define('UNKNOW', 'Unknow');               	    //未知错误
define('OBJECT_NULL','0x0000');           	    //对象为空
define('PARAM_WRONG', '0x1000');  			    //参数错误
define('NAME_EXISTS', '0x2000');  			    //名称重复
define('SUCCESS',true);           			    //成功
define('FAILED',false);           			    //失败
define('NOID','0x000001');           		    //没有id
define('NOT_EXISTS_CONTENT','0x000002');        //内容不存在
define('OLDPWD_WRONG', '0x3000');               //原密码不正确
define('MODIFY_OTHER', '0x4000');               //不能编辑其他人数据
define('NO_APP_CONFIG', '0x400000');            //缺少应用配置参数
define('NO_ACCOUNT_ID', '0x400001');            //未选择账号
define('NO_USER_ID','0x400002');			    //没有user_id
define('NO_DATA','0x400003');				    //没有数据
define('NOTEL','0x400004');					    //没有手机号
define('ERROR_FORMAT_TEL','0x400005');		    //手机格式有误
define('TELEPHONE_RATE_FAST','0x400006');	    //手机号发送频率过快
define('BEYOND_THE_IP_LIMIT','0x400007');	    //超出ip次数限制
define('SEND_CODE_FALSE','0x400008');		    //验证码发送失败
define('NO_AUTHCODE','0x400009');			    //没有验证码
define('THIS_TEL_EXISTS','0x400010');		    //该手机号已被注册
define('ERR_AUTHCODE','0x400011');			    //验证码有误
define('ERR_TEL_NOT_EXTSTS','0x400012');	    //该号码未被注册过
define('LOGIN_M2O_FAIL','0x400013');		    //登陆m2o失败
define('NO_NEW_PASSWORD','0x400014');		    //未传新密码
define('MODIFY_PASSWORD_FAIL','0x400015');	    //密码修改失败
define('NOT_SYN_USER_ID','0x400016');		    //未同步user_id
define('YOU_HAVE_BIND_THIS_PHONE','0x400017');  //您已经绑定了该号码
define('THIS_PHONE_HAVE_BINED','0x400018');     //该号码已经被其他用户绑定
define('PHONE_BIND_FAIL','0x400019');	        //手机号绑定失败
define('THIS_USER_NOT_EXISTS','0x400020');	    //该用户不存在
define('NO_CONTENT','0x400021');		        //没有内容
define('NO_AVATAR', '0x400022');                //请上传头像
define('NO_EMAIL', '0x400023');                 //没有邮箱
define('ERROR_FORMAT_EMAIL', '0x400024');       //邮箱格式有误
define('NO_EMAIL_TOKEN', '0x400025');           //没有邮箱令牌
define('VERIFY_FAIL', '0x400026');              //验证失败
define('YOU_HAVE_ACTIVATED', '0x400027');       //您已经激活
define('THIS_EMAIL_HAS_EXISTS', '0x400028');    //该邮箱已经存在
define('CONTRIBUTE_NO_TITLE','0x400029');		//投稿时title为空
define('CONTRIBUTE_NO_CONTENT','0x400030');//投稿时content为空
define('CONTRIBUTE_NO_SITEID','0x400031');//投稿时siteid为空
define('CONTRIBUTE_APPID_W','0x400032');//投稿时传过来的会员ID不在对应的用户下
define('UPDATE_DEFAULT_SITE_FAIL','0x400033');//更新演示站点失败
define('BAIXING_DATA_NULL','0x400034');//百姓网collect数据data空
define('BAIXING_APP_ID_NULL','0x400035');//百姓网collect数据app_id空
define('BAIXING_MODULE_ID_NULL','0x400036');//百姓网collect数据module_id空
define('BAIXING_INFO_WRONG','0x400037');//百姓网collect数据发过来的id有错误
define('THE_NEW_EMAIL_IS_SAME_TO_OLD','0x400038');//新邮箱与原邮箱相同
define('TITLE_EQUAL_CONTENT','0x400039');//新邮箱与原邮箱相同
define('YOU_HAVE_NOT_CHANGE_EMAIL','0x400040');//您还未提交修改邮箱
define('EMAIL_ACTIVATE_TIME_OVER','0x400041');//邮件激活超时
define('TOKEN_VALIDATE_FAIL','0x400042');//access_token验证失败
define('EMAIL_SEND_FAIL','0x400043');//邮件发送失败
define('EMAIL_RATE_FAST','0x400044');	    //邮件发送频率过快
define('VERIFICATION_CODE_WRONG','0x400045');	    //邮件发送频率过快
define('CODE_OVERDUE','0x400046');	    //邮件发送频率过快
define('NO_ROLE_ID','0x400047');//没有叮当角色ID
define('YOU_ARE_NOT_BUSINESS','0x400048');//您不是商业用户
define('NO_SITE_ID','0x400049');//没有站点id
define('NO_WXCODE', '0x400050');//微信验证码错误
define('USER_ERROR', '0x400051');//用户名或者密码错误
define('UNBIND_WX_ERROR', '0x400052');//解除微信失败
define('INDENTITY_IS_NULL', '0x400053');//第三方登录标志为空
define('NO_COLUMN', '0x400054');//没有模块栏目
define('NO_COLUMN_PATH', '0x400055');//没有模块栏目 路径