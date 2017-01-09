<?php
/**
 * 健康知识接口
 * Class HealthApiController
 * @author liutao
 * @datetime 2017/1/4
 * @site http://www.avatardata.cn/Docs/Api?id=3ebe4ed3-c038-4aa1-bac8-009537c35a23&detailId=c46a6eab-110a-41ba-ad46-de46f9572b67
 */
class HealthApiController extends ApiController
{
    //服务商key
    private $key = "0696cbfd2b66447f83d2c3d6369f4ae5";

    /**
     * 健康知识分类接口
     * @author liutao
     * @datetime 2017/1/4
     */
    public function classifyAction()
    {
        $url = "http://api.avatardata.cn/Lore/LoreClass?key=".$this->key;
        $response = CurlService::httpGetInfo($url);
//        echo $response;die;
        $res = json_decode($response, true);
        if($res['error_code'] == 0)
        {
            $classify = array();
            foreach ((array)$res['result'] as $key => $value) {
                $classify[$key]['id'] = $value['id'];
                $classify[$key]['name'] = $value['name'];
            }
            $result['status'] = 1;
            $result['data'] = $classify;
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'HEALTH');
        }
        $this->ajaxReturn($result);
    }

    /**
     * 健康知识列表接口
     * @author liutao
     * @datetime 2017/1/4
     */
    public function listAction()
    {
        $page = $this->_request->getPost("page");
        $id = $this->_request->getPost("id");
        $url = "http://api.avatardata.cn/Lore/List?key=".$this->key."&page=".$page."&rows=5&id=".$id;
        $response = CurlService::httpGetInfo($url);
//        echo $response;die;
        $res = json_decode($response, true);
        if($res['error_code'] == 0)
        {
            $list = array();
            foreach ((array)$res['result'] as $key => $value) {
                $list[$key]['id'] = $value['id'];
                $list[$key]['img'] = $value['img'];
                $list[$key]['title'] = $value['title'];
            }
            $result['status'] = 1;
            $result['data'] = $list;
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'HEALTH');
        }
        $this->ajaxReturn($result);
    }

    /**
     * 健康知识详细信息接口
     * @author liutao
     * @datetime 2017/1/5
     */
    public function detailAction()
    {
        $id = $this->_request->getPost("id");
        $url = "http://api.avatardata.cn/Lore/Show?key=".$this->key."&id=".$id;
        $response = CurlService::httpGetInfo($url);
//        echo $response;die;
        $res = json_decode($response, true);
        if($res['error_code'] == 0)
        {
            $list['title'] = $res['result']['title'];
            $list['content'] = $res['result']['message'];
            $result['status'] = 1;
            $result['data'] = $list;
        }
        else
        {
            $result = $this->errorCode($res['error_code'],'HEALTH');
        }
        $this->ajaxReturn($result);
    }


}