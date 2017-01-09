<?php
/**
 * 快递查询接口
 * Class ExpressApiController
 * @author liutao
 * @datetime 2017/1/3
 * @site http://www.kdniao.com/api-track
 */
class ExpressApiController extends ApiController
{

    /**
     * 查询物流轨迹接口
     * @author liutao
     * @datetime 2017/1/3
     */
    public function queryAction()
    {
        $EBusinessID = "1273654";
        $appKey = "af875c15-9682-4fe5-903b-026b7c3a0e14";

        $param['OrderCode'] = "";
        $param['ShipperCode'] = $this->_request->getPost("shipperCode");
        $param['LogisticCode'] = $this->_request->getPost("logisticCode");
        $requestData = json_encode($param);

        $postData = array(
            'EBusinessID' => $EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2'
        );
        $postData['DataSign'] = $this->encrypt($requestData, $appKey);
        $url = "http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx";
        $response = CurlService::httpPost($url,$postData);
//        echo $response;die;
        $res = json_decode($response,true);
        if($res['Success'] == true)
        {
            $trace = array();
            foreach ((array)$res['Traces'] as $key => $value) {
                $trace[$key]['time'] = $value['AcceptTime'];
                $trace[$key]['station'] = $value['AcceptStation'];
            }
            $result['status'] = 1;
            $result['trace'] = $trace;
        }
        else
        {
            $result['status'] = 0;
            $result['errorMsg'] = $res['Reason'];
        }
        $this->ajaxReturn($result);
    }


    /**
     * 电商Sign签名生成
     * @param    string   $data      内容
     * @param    string   $appKey    key
     * @return   string   DataSign   签名
     */
    public function encrypt($data, $appKey) {
        return urlencode(base64_encode(md5($data.$appKey)));
    }

}