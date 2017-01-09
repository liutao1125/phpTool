<?php
/**
 * 新闻头条接口
 * Class NewsApiController
 * @author liutao
 * @datetime 2016/12/23
 * @site https://www.juhe.cn/docs/api/id/235
 */
class NewsApiController extends ApiController
{
    //服务商key
    private $key = "7d016732c38ba7c7c3c8d97986e155de";

    /**
     * 新闻类接口
     * @author liutao
     * @datetime 2016/12/23
     */
    public function newsAction()
    {
        $type = $this->_request->getPost("type");
        $url = "http://v.juhe.cn/toutiao/index?key=".$this->key."&type=".$type;
        $response = CurlService::httpGetInfo($url);
//        echo $response;die;
        $res = json_decode($response, true);
        if($res['error_code'] == 0)
        {
            $news = array();
            foreach ((array)$res['result']['data'] as $key => $value) {
                $news[$key]['title'] = $value['title'];
                $news[$key]['author'] = $value['author_name'];
                $news[$key]['thumb'] = $value['thumbnail_pic_s'];
                $news[$key]['url'] = $value['url'];
            }
            $result['status'] = 1;
            $result['data'] = $news;
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'NEWS');
        }
        $this->ajaxReturn($result);
    }


}