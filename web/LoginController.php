<?php

/**
 * 登录控制器
 * Class LoginController
 * @author liutao
 * @datetime 2015/7/28
 */
class LoginController extends My_Controller
{
    /**
     * 登录界面
     * @author liutao
     * @datetime 2016/10/25
     */
    public function indexAction()
    {
        //加载js
        $this->assign('loadModuleScript', true);
        //渲染模板
        $this->display(array(), "login/index.html");
    }

    /**
     * ajax登录后台
     * @author liutao
     * @datetime 2016/10/25
     */
    public function ajaxLoginAction()
    {
        //判断是否有参数传过来
        if($this->_request->isPost())
        {
            //用户名
            $username = trim($this->getRequest()->getPost('username',''));
            //密码
            $password = trim($this->getRequest()->getPost('password',''));

            //判断用户名和密码都不为空
            if(!empty($username) && !empty($password))
            {
                //实例化用户表
                $userModel = new User_UserModel();
                //根据用户名查询一条用户信息
                $where = array(
                    'status' => 1,
                    'username' => $username
                );
                $user = $userModel->find($where);

                //判断查询结果
                if($user)
                {
                    //根据查询结果匹配登录密码
                    if($password == $user['password'])
                    {
                        $msg = array(
                            'code' => 1,
                            'msg'  => ''
                        );
                        set_session("service", $user);
                    }
                    else
                    {
                        $msg = array(
                            'code' => 0,
                            'msg'  => '密码不正确'
                        );
                    }
                }
                else
                {
                    $msg = array(
                        'code' => 2,
                        'msg'  => '用户名不正确'
                    );
                }

            }
            else
            {
                $msg = array(
                    'code' => 3,
                    'msg'  => '用户名和密码不能为空'
                );
            }
            $this->ajaxReturn($msg);
        }
    }

    /**
     * 退出登录
     * @author liutao
     * @datetime 2016/10/25
     */
    public function loginOutAction()
    {
        unset_session("service");
        $this->_redirect("login/index");
    }


}