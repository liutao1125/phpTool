<?php

/**
 * NBA赛事查询接口
 * Class NbaApiController
 * @author liutao
 * @datetime 2016/12/13
 * @site https://www.juhe.cn/docs/api/id/92
 */
class NbaApiController extends My_Controller
{

    /**
     * NBA常规赛赛程赛果查询接口
     * @author liutao
     * @datetime 2016/12/13
     */
    public function nbaAction()
    {
        $apiKey = $this->_request->getPost("key");
        if(!in_array($apiKey,$GLOBALS['APIKEY']))
        {
            $result['status'] = 20001;
            $result['errorMsg'] = "错误的api请求KEY";
            header('Content-Type:application/json; charset=UTF-8');
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            exit;
        }
        $url = "http://op.juhe.cn/onebox/basketball/nba?key=0c4e339a15af07cd2ce6e5b6aa86b62c";
        $response = CurlService::httpGetInfo($url);
        $res = json_decode($response,true);
        if($res['error_code'] == 0)
        {
            $competition = array();
            $stateArray = array("未开赛","直播中","已结束");
            for($i=0;$i<3;$i++)
            {
                $competition[$i]['date'] = $res['result']['list'][$i]['title'];
                foreach((array)$res['result']['list'][$i]['tr'] as $key => $value)
                {
                    $competition[$i]['result'][$key]['time'] = $value['time'];
                    $competition[$i]['result'][$key]['player1'] = $value['player1'];
                    $competition[$i]['result'][$key]['player2'] = $value['player2'];
                    $competition[$i]['result'][$key]['logo1'] = $value['player1logobig'];
                    $competition[$i]['result'][$key]['logo2'] = $value['player2logobig'];
                    $competition[$i]['result'][$key]['player1url'] = $value['player1url']."&cid=100000";
                    $competition[$i]['result'][$key]['player2url'] = $value['player2url']."&cid=100000";
                    $competition[$i]['result'][$key]['text'] = $value['link1text'];
                    $competition[$i]['result'][$key]['link'] = $value['link1url'];
                    $competition[$i]['result'][$key]['state'] = $stateArray[$value['status']];
                    $competition[$i]['result'][$key]['score'] = $value['score'];
                }
            }
            $result['status'] = 1;
            $result['data'] = $competition;
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



