<?php

/**
 * NBA赛事查询接口
 * Class NbaApiController
 * @author liutao
 * @datetime 2016/12/13
 * @site https://www.juhe.cn/docs/api/id/92
 */
class NbaApiController extends ApiController
{
    //服务商key
    private $key = "0c4e339a15af07cd2ce6e5b6aa86b62c";

    /**
     * NBA常规赛赛程赛果查询接口
     * @author liutao
     * @datetime 2016/12/13
     */
    public function nbaAction()
    {
        $url = "http://op.juhe.cn/onebox/basketball/nba?key=".$this->key;
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
            $result = $this->errorCode($res['error_code'],'NBA');
        }
        $this->ajaxReturn($result);

    }



}



