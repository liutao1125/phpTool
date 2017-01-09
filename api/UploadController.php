<?php
/**
 * 上传文件接口
 * Class UploadController
 * @author liutao
 * @datetime 2016/12/23
 */
class UploadController extends My_Controller
{

    /**
     * 批量上传文件接口
     * @author liutao
     * @datetime 2016/12/23
     */
    public function uploadFileAction()
    {
        foreach($_FILES as $key=>$value)
        {
            $target_path = $_SERVER['SINASRV_UPLOAD'].'/'.basename( $value['name']);
            if(move_uploaded_file($value['tmp_name'], $target_path))
            {
                $array = array ("code" => "1", "message" => $value['name'] );
                echo json_encode ( $array );
            }
            else
            {
                $array = array ("code" => "0", "message" => "There was an error uploading the file, please try again!" . $value['error'] );
                echo json_encode ( $array );
            }
        }

    }


}