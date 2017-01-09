<?php
/**
 * 邮编查询接口
 * Class PostcodeApiController
 * @author liutao
 * @datetime 2016/12/28
 * @site http://avatardata.cn/Docs/Api/b3d25cbd-449d-41c3-8765-21649658789e
 */
class PostcodeApiController extends ApiController
{
    //服务商key
    private $key = "6e4c0e1e074a402d83ff5c9cac051b00";

    /**
     * 根据邮编查询地名
     * @author liutao
     * @datetime 2016/12/28
     */
    public function addressAction()
    {
        $page = $this->_request->getPost("page");
        $postcode = $this->_request->getPost("postcode");
        $url = "http://api.avatardata.cn/PostNumber/QueryPostnumber?key=".$this->key."&rows=50&page=".$page."&postnumber=".$postcode;
        $response = CurlService::httpGetInfo($url);
//        echo $response;die;
        $res = json_decode($response, true);
        if($res['error_code'] == 0)
        {
            $address = array();
            if(empty($res['result']) && $res['total'] == 0)
            {
                $result['code'] = 1037;
                $result['errorMsg'] = "请输入正确的邮编";
            }
            else
            {
                foreach ((array)$res['result'] as $key => $value)
                {
                    $address[$key]['district'] = $value['jd'];
                    $address[$key]['address'] = $value['address'];
                }
                $result['code'] = 0;
                $result['data'] = $address;
            }
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'HEALTH');
        }
        $this->ajaxReturn($result);
    }

    /**
     * 根据地名查询邮编
     * @author liutao
     * @datetime 2016/12/28
     */
    public function postcodeAction()
    {
        $page = $this->_request->getPost("page");
        $address = $this->_request->getPost("address");
        $url = "http://api.avatardata.cn/PostNumber/QueryAddress?key=".$this->key."&rows=10&page=".$page."&address=".$address;
        $response = CurlService::httpGetInfo($url);
//        echo $response;die;
        $res = json_decode($response, true);
        if($res['error_code'] == 0)
        {
            $postcode = array();
            foreach ((array)$res['result'] as $key => $value) {
                $postcode[$key]['postcode'] = $value['postnumber'];
                $postcode[$key]['district'] = $value['jd'];
                $postcode[$key]['address'] = $value['address'];
            }
            $result['code'] = 0;
            $result['data'] = $postcode;
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'HEALTH');
        }
        $this->ajaxReturn($result);
    }


}