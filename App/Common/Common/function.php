<?php

header("Content-type:text/html;charset=utf-8");

//传递数据以易于阅读的样式格式化后输出
function p($data){
    // 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}

/**
 * app 图片上传
 * @return string 上传后的图片名
 */
function app_upload_image($path,$maxSize=52428800){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'.');
    $path=trim($path,'/');
    $config=array(
        'rootPath'  =>'./',         //文件上传保存的根路径
        'savePath'  =>'./'.$path.'/',   
        'exts'      => array('jpg', 'gif', 'png', 'jpeg','bmp'),
        'maxSize'   => $maxSize,
        'autoSub'   => true,
        );
    $upload = new \Think\Upload($config);// 实例化上传类
    $info = $upload->upload();
    if($info) {
        foreach ($info as $k => $v) {
            $data[]=trim($v['savepath'],'.').$v['savename'];
        }
        return $data;
    }
}

/**
 * 返回用户id
 * @return integer 用户id
 */
function get_uid(){
    return $_SESSION['user']['id'];
}

/**
 * 返回iso、Android、ajax的json格式数据
 * @param  array  $data           需要发送到前端的数据
 * @param  string  $error_message 成功或者错误的提示语
 * @param  integer $error_code    状态码： 0：成功  1：失败
 * @return string                 json格式的数据
 */
function ajax_return($data='',$error_message='成功',$error_code=1){
    $all_data=array(
        'error_code'=>$error_code,
        'error_message'=>$error_message,
        );
    if ($data!=='') {
        $all_data['data']=$data;
        // app 禁止使用和为了统一字段做的判断
        $reserved_words=array('id','title','price','product_title','product_id','product_category','product_number');
        foreach ($reserved_words as $k => $v) {
            if (array_key_exists($v, $data)) {
                echo 'app不允许使用【'.$v.'】这个键名 —— 此提示是function.php 中的ajax_return函数返回的';
                die;
            }
        }
    }
    // 如果是ajax或者app访问；则返回json数据 pc访问直接p出来
    echo json_encode($all_data);
    exit(0);
}


/**
 * 检测是否登录
 * @return boolean 是否登录
 */
function check_login(){
    if (!empty($_SESSION['user']['id'])){
        return true;
    }else{
        return false;
    }
}

/**
 * 根据配置项获取对应的key和secret
 * @return array key和secret
 */
function get_rong_key_secret(){
    // 判断是需要开发环境还是生产环境的key
    if (C('RONG_IS_DEV')) {
        $key=C('RONG_DEV_APP_KEY');
        $secret=C('RONG_DEV_APP_SECRET');
    }else{
        $key=C('RONG_PRO_APP_KEY');
        $secret=C('RONG_PRO_APP_SECRET');
    }
    $data=array(
        'key'=>$key,
        'secret'=>$secret
        );
    return $data;
}

// 设置验证码
function show_verify($config=''){
    if($config==''){
        $config=array(
            'codeSet'=>'1234567890',
            'fontSize'=>30,
            'useCurve'=>false,
            'imageH'=>60,
            'imageW'=>240,
            'length'=>4,
            'fontttf'=>'4.ttf',
            );
    }
    $verify=new \Think\Verify($config);
    return $verify->entry();
}

// 检测验证码
function check_verify($code){
    $verify=new \Think\Verify();
    return $verify->check($code);
}

/**
 * 取得根域名
 * @param type $domain 域名
 * @return string 返回根域名
 */
function get_url_to_domain($domain) {
    $re_domain = '';
    $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
    $array_domain = explode(".", $domain);
    $array_num = count($array_domain) - 1;
    if ($array_domain[$array_num] == 'cn') {
        if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
            $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        } else {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
    } else {
        $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
    }
    return $re_domain;
}

/**
 * 按符号截取字符串的指定部分
 * @param string $str 需要截取的字符串
 * @param string $sign 需要截取的符号
 * @param int $number 如是正数以0为起点从左向右截  负数则从右向左截
 * @return string 返回截取的内容
 */
/*  示例
    $str='123/456/789';
    cut_str($str,'/',0);  返回 123
    cut_str($str,'/',-1);  返回 789
    cut_str($str,'/',-2);  返回 456
    具体参考 http://www.baijunyao.com/index.php/Home/Index/article/aid/18
*/
function cut_str($str,$sign,$number){
    $array=explode($sign, $str);
    $length=count($array);
    if($number<0){
        $new_array=array_reverse($array);
        $abs_number=abs($number);
        if($abs_number>$length){
            return 'error';
        }else{
            return $new_array[$abs_number-1];
        }
    }else{
        if($number>=$length){
            return 'error';
        }else{
            return $array[$number];
        }
    }
}


/**
 * 获取一定范围内的随机数字
 * 跟rand()函数的区别是 位数不足补零 例如
 * rand(1,9999)可能会得到 465
 * rand_number(1,9999)可能会得到 0465  保证是4位的
 * @param integer $min 最小值
 * @param integer $max 最大值
 * @return string
 */
function rand_number ($min=1, $max=9999) {
    return sprintf("%0".strlen($max)."d", mt_rand($min,$max));
}

/**
 * 生成一定数量的随机数，并且不重复
 * @param integer $number 数量
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @return string
 */
function build_count_rand ($number,$length=4,$mode=1) {
    if($mode==1 && $length<strlen($number) ) {
        //不足以生成一定数量的不重复数字
        return false;
    }
    $rand   =  array();
    for($i=0; $i<$number; $i++) {
        $rand[] = rand_string($length,$mode);
    }
    $unqiue = array_unique($rand);
    if(count($unqiue)==count($rand)) {
        return $rand;
    }
    $count   = count($rand)-count($unqiue);
    for($i=0; $i<$count*3; $i++) {
        $rand[] = rand_string($length,$mode);
    }
    $rand = array_slice(array_unique ($rand),0,$number);
    return $rand;
}

/**
 * 生成不重复的随机数
 * @param  int $start  需要生成的数字开始范围
 * @param  int $end 结束范围
 * @param  int $length 需要生成的随机数个数
 * @return array       生成的随机数
 */
function get_rand_number($start=1,$end=10,$length=4){
    $connt=0;
    $temp=array();
    while($connt<$length){
        $temp[]=rand($start,$end);
        $data=array_unique($temp);
        $connt=count($data);
    }
    sort($data);
    return $data;
}

/**
 * 实例化page类
 * @param  integer  $count 总数
 * @param  integer  $limit 每页数量
 * @return subject       page类
 */
function new_page($count,$limit=10){
    return new \Org\Nx\Page($count,$limit);
}

/**
 * 获取分页数据
 * @param  subject  $model  model对象
 * @param  array    $map    where条件
 * @param  string   $order  排序规则
 * @param  integer  $limit  每页数量
 * @return array            分页数据
 */
function get_page_data($model,$map,$order='',$limit=10){
    $count=$model
        ->where($map)
        ->count();
    $page=new_page($count,$limit);
    // 获取分页数据
    $list=$model
            ->where($map)
            ->order($order)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    $data=array(
        'data'=>$list,
        'page'=>$page->show()
        );
    return $data;
}

// *
 // * @param  string   $path    字符串 保存文件路径示例： /Upload/image/
 // * @param  string   $format  文件格式限制
 // * @param  integer  $maxSize 允许的上传文件最大值 52428800
 // * @return booler            返回ajax的json格式数据
 
function post_upload($path='file',$format='empty',$maxSize='52428800'){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'/');
    // 添加Upload根目录
    $path=strtolower(substr($path, 0,6))==='upload' ? ucfirst($path) : 'Upload/'.$path;
    // 上传文件类型控制
    $ext_arr= array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'photo' => array('jpg', 'jpeg', 'png'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
        );
    if(!empty($_FILES)){
        // 上传文件配置
        $config=array(
                'maxSize'   =>  $maxSize,       //   上传文件最大为50M
                'rootPath'  =>  './',           //文件上传保存的根路径
                'savePath'  =>  './'.$path.'/',         //文件上传的保存路径（相对于根路径）
                'saveName'  =>  array('uniqid',''),     //上传文件的保存规则，支持数组和字符串方式定义
                'autoSub'   =>  true,                   //  自动使用子目录保存上传文件 默认为true
                'exts'    =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
            );
        // 实例化上传
        $upload=new \Think\Upload($config);
        // 调用上传方法
        $info=$upload->upload();
        $data=array();
        if(!$info){
            // 返回错误信息
            $error=$upload->getError();
            $data['error_info']=$error;
            return $data;
        }else{
            // 返回成功信息
            foreach($info as $file){
                $data['name']=trim($file['savepath'].$file['savename'],'.');
                return $data;
            }               
        }
    }
}

/**
 * 上传文件类型控制   此方法仅限ajax上传使用
 * @param  string   $path    字符串 保存文件路径示例： /Upload/image/
 * @param  string   $format  文件格式限制
 * @param  integer  $maxSize 允许的上传文件最大值 52428800
 * @return booler       返回ajax的json格式数据
 */
function upload($path='file',$format='empty',$maxSize='52428800'){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'/');
    // 添加Upload根目录
    $path=strtolower(substr($path, 0,6))==='upload' ? ucfirst($path) : 'Upload/'.$path;
    // 上传文件类型控制
    $ext_arr= array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'photo' => array('jpg', 'jpeg', 'png'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
        );
    if(!empty($_FILES)){
        // 上传文件配置
        $config=array(
                'maxSize'   =>  $maxSize,       //   上传文件最大为50M
                'rootPath'  =>  './',           //文件上传保存的根路径
                'savePath'  =>  './'.$path.'/',         //文件上传的保存路径（相对于根路径）
                'saveName'  =>  array('uniqid',''),     //上传文件的保存规则，支持数组和字符串方式定义
                'autoSub'   =>  true,                   //  自动使用子目录保存上传文件 默认为true
                'exts'    =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
            );
        // 实例化上传
        $upload=new \Think\Upload($config);
        // 调用上传方法
        $info=$upload->upload();
        $data=array();
        if(!$info){
            // 返回错误信息
            $error=$upload->getError();
            $data['error_info']=$error;
            echo json_encode($data);
        }else{
            // 返回成功信息
            foreach($info as $file){
                $data['name']=trim($file['savepath'].$file['savename'],'.');
                echo json_encode($data);
            }               
        }
    }
}

/**
 * 使用curl获取远程数据
 * @param  string $url url连接
 * @return string      获取到的数据
 */
function curl_get_contents($url){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                //设置访问的url地址
    //curl_setopt($ch,CURLOPT_HEADER,1);                //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);               //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);   //用户访问代理 User-Agent
    curl_setopt($ch, CURLOPT_REFERER,_REFERER_);        //设置 referer
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);          //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
    $r=curl_exec($ch);
    curl_close($ch);
    return $r;
}



/**
 * 不区分大小写的in_array()
 * @param  string $str   检测的字符
 * @param  array  $array 数组
 * @return boolear       是否in_array
 */
function in_iarray($str,$array){
    $str=strtolower($str);
    $array=array_map('strtolower', $array);
    if (in_array($str, $array)) {
        return true;
    }
    return false;
}


/**
 * 检测是否是手机访问
 */
function is_mobile(){
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
    function _is_mobile($substrs,$text){
        foreach($substrs as $substr)
            if(false!==strpos($text,$substr)){
                return true;
            }
            return false;
    }
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');

    $found_mobile=_is_mobile($mobile_os_list,$useragent_commentsblock) ||
              _is_mobile($mobile_token_list,$useragent);
    if ($found_mobile){
        return true;
    }else{
        return false;
    }
}

/**
 * 将utf-16的emoji表情转为utf8文字形
 * @param  string $str 需要转的字符串
 * @return string      转完成后的字符串
 */
function escape_sequence_decode($str) {
    $regex = '/\\\u([dD][89abAB][\da-fA-F]{2})\\\u([dD][c-fC-F][\da-fA-F]{2})|\\\u([\da-fA-F]{4})/sx';
    return preg_replace_callback($regex, function($matches) {
        if (isset($matches[3])) {
            $cp = hexdec($matches[3]);
        } else {
            $lead = hexdec($matches[1]);
            $trail = hexdec($matches[2]);
            $cp = ($lead << 10) + $trail + 0x10000 - (0xD800 << 10) - 0xDC00;
        }

        if ($cp > 0xD7FF && 0xE000 > $cp) {
            $cp = 0xFFFD;
        }
        if ($cp < 0x80) {
            return chr($cp);
        } else if ($cp < 0xA0) {
            return chr(0xC0 | $cp >> 6).chr(0x80 | $cp & 0x3F);
        }
        $result =  html_entity_decode('&#'.$cp.';');
        return $result;
    }, $str);
}

/**
 * 获取当前访问的设备类型
 * @return integer 1：其他  2：iOS  3：Android
 */
function get_device_type(){
    //全部变成小写字母
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $type = 1;
    //分别进行判断
    if(strpos($agent, 'iphone')!==false || strpos($agent, 'ipad')!==false){
        $type = 2;
    } 
    if(strpos($agent, 'android')!==false){
        $type = 3;
    }
    return $type;
}

