<?php

/**
 * 车载应用接口
 * Class VehicleApiController
 * @author liutao
 * @datetime 2015/7/28
 */
class VehicleApiController extends My_Controller
{

    /**
     * app端注册账号
     * @author liutao
     * @datetime 2016/10/25
     */
    public function registerAction()
    {
        $postInfo = $this->_request->getPost();
        $userModel = new User_UserModel();
        if(empty($postInfo))
        {
            $response["code"] = 0;
            $response["msg"] = "empty data";
            $this->ajaxReturn($response);
        }
        $userInfo = $userModel->select(array('username' => $postInfo['name']));
        if(!empty($userInfo))
        {
            $response["code"] = 0;
            $response["msg"] = "用户名已存在";
            $this->ajaxReturn($response);
        }
        $data['username'] = $postInfo['name'];
        $data['password'] = $postInfo['password'];
        $id = $userModel->insert($data);
        if($id)
        {
            $response["code"] = 1;
            $response["msg"] = "注册成功";
        }
        else
        {
            $response["code"] = 0;
            $response["msg"] = "An error occurred.";
        }
        $this->ajaxReturn($response);
    }

    /**
     * 车载设备app端注册账号
     * @author liutao
     * @datetime 2016/11/2
     */
    public function vehicleRegisterAction()
    {
        $postInfo = $this->_request->getPost();
        $userModel = new Vehicle_UserModel();
        $response = array();
        if(empty($postInfo))
        {
            $response["code"] = 0;
            $response["msg"] = "empty data";
            $this->ajaxReturn($response);
        }
        $response = $userModel->addUser($postInfo['name'],$postInfo['password'],$postInfo['cellphone'],$postInfo['engine']);
        $this->ajaxReturn($response);
    }

    /**
     * 车载设备app端登录
     * @author liutao
     * @datetime 2016/11/3
     */
    public function vehicleLoginAction()
    {
        //判断是否有参数传过来
        if($this->_request->isPost())
        {
            //用户名
            $username = trim($this->_request->getPost('name'));
            //密码
            $password = trim($this->_request->getPost('password'));

            //判断用户名和密码都不为空
            if(!empty($username) && !empty($password))
            {
                //实例化用户表
                $userModel = new Vehicle_UserModel();
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
                            'msg'  => '登陆成功'
                        );
                    }
                    else
                    {
                        $msg = array(
                            'code' => 0,
                            'msg'  => '密码错误'
                        );
                    }
                }
                else
                {
                    $msg = array(
                        'code' => 2,
                        'msg'  => '用户名不存在'
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
     * 车载设备app端上报设备信息
     * @author liutao
     * @datetime 2016/11/8
     */
    public function reportInfoAction()
    {
        $json = $this->_request->getPost('json');
        $postInfo = json_decode($json,true);
        $deviceModel = new Vehicle_DeviceModel();
        if(empty($postInfo))
        {
            $response["code"] = 0;
            $response["msg"] = "empty data";
            $this->ajaxReturn($response);
        }
        $deviceInfo = $deviceModel->select(array('device' => $postInfo['emmcId']));
        $data = array(
            'device' => $postInfo['emmcId'],
            'model' => $postInfo['deviceType'],
            'version' => $postInfo['deviceVersion'],
            'mcu_version' => $postInfo['deviceMcuVersion'],
            'can_version' => $postInfo['deviceCanVersion'],
            'bt_version' => $postInfo['deviceBtVersion'],
            'setting_version' => $postInfo['carSettingVersion'],
            'android_version' => $postInfo['deviceAndroidVersion'],
            'core_version' => $postInfo['deviceKernelVersion'],
            'createtime' => SYS_TIME
        );
        if(!empty($deviceInfo))
        {
            $result = $deviceModel->updateData($data,array('device' => $postInfo['emmcId']));
            if($result !== false)
            {
                $response["code"] = 1;
                $response["msg"] = "更新成功";
            }
            else
            {
                $response["code"] = 0;
                $response["msg"] = "更新失败";
            }
        }
        else
        {
            $id = $deviceModel->insert($data);
            if($id)
            {
                $response["code"] = 1;
                $response["msg"] = "插入成功";
            }
            else
            {
                $response["code"] = 0;
                $response["msg"] = "插入失败";
            }
        }
        $this->ajaxReturn($response);
    }

    /**
     * 车载设备app端定时上报gps信息
     * @author liutao
     * @datetime 2016/11/8
     */
    public function reportGpsAction()
    {
        $json = $this->_request->getPost('json');
        $postInfo = json_decode($json,true);
        $gpsModel = new Vehicle_GpsInfoModel();
        $userModel = new Vehicle_UserModel();
        if(empty($postInfo))
        {
            $response["code"] = 0;
            $response["msg"] = "empty data";
            $this->ajaxReturn($response);
        }
        $uid = $userModel->find(array('username' => $postInfo['userName']),'uid');
        if(empty($uid))
        {
            $response["code"] = 0;
            $response["msg"] = "该用户不存在";
            $this->ajaxReturn($response);
        }
        $count = $gpsModel->count(array('uid' => $uid));
        $data = array(
            'longitude' => $postInfo['locationLongitude'],
            'latitude' => $postInfo['locationLatitude'],
            'accuracy' => $postInfo['locationAccuracy'],
            'address' => $postInfo['locationAddress'],
            'provider' => $postInfo['locationProvider'],
            'area_code' => $postInfo['locationAreaCode'],
            'city_code' => $postInfo['locationCityCode'],
            'uid' => $uid,
            'locate_time' => $postInfo['locateTime']
        );
        if($count < 1000)
        {
            $id = $gpsModel->insert($data);
            if($id)
            {
                $response["code"] = 1;
                $response["msg"] = "插入成功";
            }
            else
            {
                $response["code"] = 0;
                $response["msg"] = "插入失败";
            }
        }
        else
        {
            $locateTime = $gpsModel->scalar('min(locate_time)','uid='.$uid);
            $result = $gpsModel->updateData($data,array('locate_time' => $locateTime));
            if($result !== false)
            {
                $response["code"] = 1;
                $response["msg"] = "插入成功";
            }
            else
            {
                $response["code"] = 0;
                $response["msg"] = "插入失败";
            }
        }
        $this->ajaxReturn($response);
    }

    /**
     * 车载设备app端请求用户信息数据
     * @author liutao
     * @datetime 2016/11/8
     */
    public function userInfoAction()
    {
        $username = $this->_request->getPost('username');
        if(empty($username))
        {
            $response["code"] = 0;
            $response["msg"] = "empty data";
            $this->ajaxReturn($response);
        }
        $userModel = new Vehicle_UserModel();
        $where['status'] = 1;
        $where['username'] = $username;
        $data = $userModel->find($where,'username,cellphone,engine');
        header('Content-Type:application/json; charset=UTF-8');
        if(!empty($data))
        {
            $response["code"] = 1;
            $response["msg"] = "查询成功";
            $response['data'] = $data;
        }
        else
        {
            $response["code"] = 0;
            $response["msg"] = "用户信息不存在";
        }
        $this->ajaxReturn($response);
    }

    /**
     * 车载设备app端请求gps信息数据
     * @author liutao
     * @datetime 2016/11/21
     */
    public function gpsInfoAction()
    {
        $username = $this->_request->getPost('username');
        if(empty($username))
        {
            $response["code"] = 0;
            $response["msg"] = "empty data";
            $this->ajaxReturn($response);
        }
        $gpsModel = new Vehicle_GpsInfoModel();
        $userModel = new Vehicle_UserModel();
        $uid = $userModel->find(array('username' => $username),'uid');
        if(empty($uid))
        {
            $response["code"] = 0;
            $response["msg"] = "该用户不存在";
            $this->ajaxReturn($response);
        }
        $where['status'] = 1;
        $where['uid'] = $uid;
        $data = $gpsModel->select($where,'longitude,latitude','','locate_time asc','');
        header('Content-Type:application/json; charset=UTF-8');
        if(!empty($data))
        {
            $response["code"] = 1;
            $response["msg"] = "查询成功";
            $response['data'] = $data;
        }
        else
        {
            $response["code"] = 0;
            $response["msg"] = "gps信息不存在";
        }
        $this->ajaxReturn($response);
    }

    /**
     * 车载设备app端修改密码
     * @author liutao
     * @datetime 2016/11/8
     */
    public function updatePasswordAction()
    {
        $username = $this->_request->getPost('username');
        $password = $this->_request->getPost('password');
        if(empty($username) || empty($password))
        {
            $response["code"] = 0;
            $response["msg"] = "用户名，密码不能为空";
            $this->ajaxReturn($response);
        }
        $userModel = new Vehicle_UserModel();
        $where['status'] = 1;
        $where['username'] = $username;
        $data = array(
            'password' => $password
        );
        $result = $userModel->updateData($data,$where);
        if($result !== false)
        {
            $response["code"] = 1;
            $response["msg"] = "密码修改成功";
        }
        else
        {
            $response["code"] = 0;
            $response["msg"] = "密码修改失败";
        }
        $this->ajaxReturn($response);
    }

    /**
     * 车载设备app端修改电话号码
     * @author liutao
     * @datetime 2016/11/14
     */
    public function updateCellphoneAction()
    {
        if($this->_request->isPost())
        {
            $username = $this->_request->getPost('username');
            $cellphone = $this->_request->getPost('cellphone');
            $userModel = new Vehicle_UserModel();
            $where['status'] = 1;
            $where['username'] = $username;
            $data['cellphone'] = $cellphone;
            $result = $userModel->updateData($data,$where);
            if($result !== false)
            {
                $response["code"] = 1;
                $response["msg"] = "修改手机号成功";
            }
            else
            {
                $response["code"] = 0;
                $response["msg"] = "修改手机号失败";
            }
            $this->ajaxReturn($response);
        }

    }

    /**
     * 车载设备app端修改发动机型号
     * @author liutao
     * @datetime 2016/11/14
     */
    public function updateEngineAction()
    {
        if($this->_request->isPost())
        {
            $username = $this->_request->getPost('username');
            $engine = $this->_request->getPost('engine');
            $userModel = new Vehicle_UserModel();
            $where['status'] = 1;
            $where['username'] = $username;
            $data['engine'] = $engine;
            $result = $userModel->updateData($data,$where);
            if($result !== false)
            {
                $response["code"] = 1;
                $response["msg"] = "修改发动机成功";
            }
            else
            {
                $response["code"] = 0;
                $response["msg"] = "修改发动机失败";
            }
            $this->ajaxReturn($response);
        }

    }


    /**
     * test
     * @author liutao
     * @datetime 2016/11/14
     */
    public function testAction()
    {
        $data_string = "json=地方技术";
        $header=array(
            'Content-Type:application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data_string)
        );
        $ch = curl_init();   //初始化一个curl对象
        curl_setopt($ch, CURLOPT_URL, "http://192.168.50.22/login/new");  //定义表单提交地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");//定义提交类型
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);//定义提交的数据，这里是json格式
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);//定义是否直接输出返回流
        $result = curl_exec($ch);
        curl_close($ch);//关闭
        echo $result;

    }

}

