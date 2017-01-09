<?php
/**
 * 笑话大全接口
 * Class JokeApiController
 * @author liutao
 * @datetime 2016/12/21
 * @site https://www.juhe.cn/docs/api/id/95/aid/281
 */
class JokeApiController extends ApiController
{
    //服务商key
    private $key = "8fd94abe06a31ecad396bbab28eb2322";

    /**
     * 最新笑话大全接口
     * @author liutao
     * @datetime 2016/12/21
     */
    public function jokeAction()
    {
        $page = $this->_request->getPost("page");
        $url = "http://japi.juhe.cn/joke/content/text.from?key=".$this->key."&page=".$page."&pagesize=10";
        $response = CurlService::httpGetInfo($url);
//        echo $response;die;
        $res = json_decode($response, true);
        if ($res['error_code'] === 0)
        {
            $joke = array();
                foreach ((array)$res['result']['data'] as $key => $value) {
                    $joke[$key]['content'] = $value['content'];
                }
            $result['status'] = 1;
            $result['data'] = $joke;
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'JOKE');
        }
        $this->ajaxReturn($result);
    }

    /**
     * 最新趣图接口
     * @author liutao
     * @datetime 2016/12/22
     */
    public function funnyImageAction()
    {
        $page = $this->_request->getPost("page");
        $url = "http://japi.juhe.cn/joke/img/text.from?key=".$this->key."&page=".$page."&pagesize=3";
        $response = CurlService::httpGetInfo($url);
        $res = json_decode($response, true);
        if ($res['error_code'] === 0)
        {
            $joke = array();
            foreach ((array)$res['result']['data'] as $key => $value) {
                $joke[$key]['content'] = $value['content'];
                $joke[$key]['image'] = $value['url'];
            }
            $result['status'] = 1;
            $result['data'] = $joke;
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'JOKE');
        }
        $this->ajaxReturn($result);
    }

}