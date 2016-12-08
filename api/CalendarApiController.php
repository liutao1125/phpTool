<?php

/**
 * 老黄历接口
 * Class CalendarApiController
 * @author liutao
 * @datetime 2016/12/7
 * @site https://www.juhe.cn/docs/api/id/65
 */
class CalendarApiController extends My_Controller
{

    /**
     * 根据阳历日期查询老黄历
     * @author liutao
     * @datetime 2016/12/7
     */
    public function calendarAction()
    {
        $date = $this->_request->getPost("date");
        $url = "http://v.juhe.cn/laohuangli/d?date=".$date."&key=2e9ff26e4530ab6cede8a034cea9539b";
        $response = CurlService::httpGet($url);
        $res = json_decode($response,true);
        if($res['error_code'] == 0)
        {
            if(empty($res['result']))
            {
                $result['status'] = 0;
                $result['errorMsg'] = "请输入正确格式的日期";
            }
            else
            {
                $calendar['yinli'] = $res['result']['yinli'];
                $calendar['yi'] = $res['result']['yi'];
                $calendar['ji'] = $res['result']['ji'];
                $calendar['jishen'] = $res['result']['jishen'];
                $calendar['xiongshen'] = $res['result']['xiongshen'];
                $calendar['chongsha'] = $res['result']['chongsha'];
                $calendar['wuxing'] = $res['result']['wuxing'];
                $calendar['baiji'] = $res['result']['baiji'];
                $result['status'] = 1;
                $result['msg'] = $calendar;
            }
        }
        else
        {
            $result['status'] = $res['error_code'];
            $result['errorMsg'] = $res['reason'];
        }
        header('Content-Type:application/json; charset=UTF-8');
        echo json_encode($result,JSON_UNESCAPED_UNICODE);

    }

    /**
     * 测试接口
     * @author liutao
     * @datetime 2016/12/7
     */
    public function testAction()
    {
        $date = $this->_request->getPost("date");
        $url = "http://v.juhe.cn/laohuangli/d?date=".$date."&key=2e9ff26e4530ab6cede8a034cea9539b";
        $response = CurlService::httpGet($url);
        $res = json_decode($response,true);
        if($res['error_code'] == 0)
        {
            if(empty($res['result']))
            {
                $result['status'] = 0;
                $result['errorMsg'] = "请输入正确格式的日期";
            }
            else
            {
                $calendar['yinli'] = $res['result']['yinli'];
                $calendar['suit'] = $res['result']['yi'];
                $calendar['forbid'] = $res['result']['ji'];
                $calendar['jishen'] = $res['result']['jishen'];
                $calendar['xiongshen'] = $res['result']['xiongshen'];
                $calendar['chongsha'] = $res['result']['chongsha'];
                $calendar['wuxing'] = $res['result']['wuxing'];
                $calendar['pengzu'] = $res['result']['baiji'];
                $result['status'] = 1;
                $result['msg'] = $calendar;
            }
        }
        else
        {
            $result['status'] = $res['error_code'];
            $result['errorMsg'] = $res['reason'];
        }
        header('Content-Type:application/json; charset=UTF-8');
        echo json_encode($result,JSON_UNESCAPED_UNICODE);


    }


}



