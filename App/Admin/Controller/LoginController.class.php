<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
   /**
    * [index 主页]
    * @AuthorHTL                          孙忠汉
    * @DateTime  2016-07-11T11:03:25+0800
    * @return    [type]                   [null]
    */
     public function index(){
        $this->display();
    }

    public function loginHandle(){
        if(IS_POST){
            $map=I('post.');
            $map['password']=md5($map['password']);
            $data=M('Users')->where($map)->find();
            if (empty($data)) {
                $this->error('账号或密码错误');
            }else{
                $_SESSION['user']=array(
                    'id'=>$data['id'],
                    'username'=>$data['username'],
                    'avatar'=>$data['avatar']
                    );
                $this->success('登录成功、前往管理后台',U('Admin/Index/index'));
            }
        }else{
           $this->error('非法操作!');
        }
    }

    /**
     * [logout description]
     * @Author   HanGeGe
     * @DateTime 2016-04-27T20:10:29+0800
     * @return   [type]                   [退出页面]
     */
    public function logout(){
        session_destroy();
        session_unset();
        $this->success('退出系统成功!',U('Admin/index/index'));

     }


}