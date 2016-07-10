<?php
return array(
//*************************************附加设置***********************************
    'SHOW_PAGE_TRACE'        => false,                           // 是否显示调试面板
    'URL_CASE_INSENSITIVE'   => false,                           // url区分大小写
    'TAGLIB_BUILD_IN'        => 'Cx,Common\Tag\My',              // 加载自定义标签
    'LOAD_EXT_CONFIG'        => 'db,alipay,oauth',               // 加载网站设置文件
    'TMPL_PARSE_STRING'      => array(                           // 定义常用路径
        // '__OSS__'            => OSS_URL,
        // '__PUBLIC__'         => OSS_URL.'/Public',
        // '__HOME_CSS__'       => __ROOT__.trim(TPL,'.').'Home/Public/css',
        // '__HOME_JS__'        => __ROOT__.trim(TPL,'.').'Home/Public/js',
        // '__HOME_IMAGES__'    => OSS_URL.trim(TPL,'.').'Home/Public/images',
        // '__ADMIN_CSS__'      => __ROOT__.trim(TPL,'.').'Admin/Public/css',
        // '__ADMIN_JS__'       => __ROOT__.trim(TPL,'.').'Admin/Public/js',
        // '__ADMIN_IMAGES__'   => OSS_URL.trim(TPL,'.').'Admin/Public/images',
        // '__ADMIN_ACEADMIN__' => __ROOT__.trim(TPL,'.').'Admin/Public/aceadmin',
        // '__PUBLIC_CSS__'     => __ROOT__.trim(TPL,'.').'Public/css',
        // '__PUBLIC_JS__'      => __ROOT__.trim(TPL,'.').'Public/js',
        // '__PUBLIC_IMAGES__'  => OSS_URL.trim(TPL,'.').'Public/images',
        // '__USER_CSS__'       => __ROOT__.trim(TPL,'.').'User/Public/css',
        // '__USER_JS__'        => __ROOT__.trim(TPL,'.').'User/Public/js',
        // '__USER_IMAGES__'    => OSS_URL.trim(TPL,'.').'User/Public/images',
        // '__APP_CSS__'        => __ROOT__.trim(TPL,'.').'App/Public/css',
        // '__APP_JS__'         => __ROOT__.trim(TPL,'.').'App/Public/js',
        // '__APP_IMAGES__'     => OSS_URL.trim(TPL,'.').'App/Public/images'
    ),
//***********************************URL设置**************************************
    'MODULE_ALLOW_LIST'      => array('Home','Admin','Api','User','App'), //允许访问列表
    'URL_HTML_SUFFIX'        => '',  // URL伪静态后缀设置
    'URL_MODEL'              => 1,  //启用rewrite
//***********************************SESSION设置**********************************
    // 'SESSION_OPTIONS'        => array(
    //     'name'               => 'BJYADMIN',//设置session名
    //     'expire'             => 24*3600*15, //SESSION保存15天
    //     'use_trans_sid'      => 1,//跨页传递
    //     'use_only_cookies'   => 0,//是否只开启基于cookies的session的会话方式
    // ),
//***********************************页面设置**************************************
    // 'TMPL_EXCEPTION_FILE'    => APP_DEBUG ? THINK_PATH.'Tpl/think_exception.tpl' : './Template/default/Home/Public/404.html',
    // 'TMPL_ACTION_ERROR'      => TPL.'/Public/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    // 'TMPL_ACTION_SUCCESS'    => TPL.'/Public/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
//***********************************auth设置**********************************
    'AUTH_CONFIG'            => array(
            'AUTH_USER'      => 'users'                         //用户信息表
        ),


);
