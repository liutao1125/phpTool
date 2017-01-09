<?php
/**
 * api接口基类
 * Class ApiController
 * @datetime 2017/1/5
 * @author liutao
 */

class ApiController extends My_Controller
{

    /**
     * @method init
     * @date   2017年1月5日
     * @author liutao
     */
    public function init()
    {
        parent::init();
        $apiKey = $this->_request->getPost("key");
        $company = $this->_request->getPost("company");
        $models = $this->_request->getPost("models");
        if (!in_array($apiKey, $GLOBALS['APIKEY'])) {
            $result['status'] = 2001;
            $result['errorMsg'] = "错误的api请求key";
            $this->ajaxReturn($result);
        }
        if (!in_array($company, $GLOBALS['COMPANY'])) {
            $result['status'] = 2002;
            $result['errorMsg'] = "错误的公司名称";
            $this->ajaxReturn($result);
        }
        if (!in_array($models, $GLOBALS['MODELS'])) {
            $result['status'] = 2003;
            $result['errorMsg'] = "错误的车机型号";
            $this->ajaxReturn($result);
        }
    }

    /**
     * 重新封装ajax返回方法
     * @date   2017年1月5日
     * @author liutao
     * @param array $data
     * @return string
     *
     */
    public function ajaxReturn($data)
    {
        header('Content-Type:application/json; charset=UTF-8');
        echo json_encode($data);
        exit;
    }

    /**
     * 错误码转化
     * @date   2017年1月5日
     * @author liutao
     * @param  string $data  原错误码
     * @param  string $type  类别
     * @return array
     *
     */
    public function errorCode($data,$type)
    {
        $status = $GLOBALS[$type][$data];
        $errorMsg = C("api_code_vehicle",$status);
        $result['status'] = $status;
        $result['errorMsg'] = $errorMsg;
        return $result;
    }


}
