<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/5
 * Time: 15:59
 */
class CurlService
{
    /**
     * @description 模拟浏览器传输数据（带验证）
     * @author liutao
     * @datetime 2016/12/5
     * @param     string    $url    url地址
     * @return    string    $res    服务商返回的数据
     */
    public static function httpGet($url)
    {
        //1.初始化，创建一个新cURL资源
        $curl = curl_init();
        $header = array(
            'apikey: a79124c4594c2e5a0799a39ea8f64c87',
        );
        //2.设置URL和相应的选项
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //3.抓取URL并把它传递给浏览器
        $res = curl_exec($curl);
        //4.关闭cURL资源，并且释放系统资源
        curl_close($curl);
        return $res;
    }

    /**
     * @description 模拟浏览器传输数据(不带验证）
     * @author liutao
     * @datetime 2016/12/5
     * @param     string    $url    url地址
     * @return    string    $res    服务商返回的数据
     */
    public static function httpGetInfo($url)
    {
        //1.初始化，创建一个新cURL资源
        $curl = curl_init();
        //2.设置URL和相应的选项
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //3.抓取URL并把它传递给浏览器
        $res = curl_exec($curl);
        //4.关闭cURL资源，并且释放系统资源
        curl_close($curl);
        return $res;
    }
}