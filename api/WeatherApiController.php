<?php

/**
 * 天气预报接口
 * Class WeatherApiController
 * @author liutao
 * @datetime 2016/12/5
 * @site http://apistore.baidu.com/apiworks/servicedetail/478.html
 */
class WeatherApiController extends ApiController
{

    /**
     * 查询未来七天天气接口
     * @author liutao
     * @datetime 2016/12/5
     */
    public function weatherAction()
    {
        $city = $this->_request->getPost("city");
        $url = "http://apis.baidu.com/heweather/weather/free?city=".$city;
        $response = CurlService::httpGet($url);
//        echo $response;die;
        $res = json_decode($response,true);
        $data = $res['HeWeather data service 3.0'];
        if(empty($data))
        {
            $result = $this->errorCode($res['errNum'],'WEATHER');
        }
        else
        {
            $status = $data[0]['status'];
            if($status == "ok")
            {
                $forecast = array();
                $weekArray = array("日","一","二","三","四","五","六");
                $now['city'] = $data[0]['basic']['city'];
                $now['temperature'] = $data[0]['now']['tmp'];
                $now['text'] = $data[0]['now']['cond']['txt'];
                $air['quality'] = $data[0]['aqi']['city']['aqi'];
                $air['text'] = $data[0]['aqi']['city']['qlty'];
                $air['pm10'] = $data[0]['aqi']['city']['pm10'];
                $air['pm25'] = $data[0]['aqi']['city']['pm25'];
                for($i=0;$i<7;$i++)
                {
                    $time = strtotime($data[0]['daily_forecast'][$i]['date']);
                    $week = date("w",$time);
                    $forecast[$i]['date'] = date('m-d', $time);
                    $forecast[$i]['week'] = "周".$weekArray[$week];
                    $forecast[$i]['max'] = $data[0]['daily_forecast'][$i]['tmp']['max'];
                    $forecast[$i]['min'] = $data[0]['daily_forecast'][$i]['tmp']['min'];
                }
                $result['status'] = 1;
                $result['now'] = $now;
                $result['air'] = $air;
                $result['forecast'] = $forecast;
            }
            else
            {
                $result['status'] = 1038;
                $result['errorMsg'] = $status;
            }
        }
        $this->ajaxReturn($result);

    }



}



