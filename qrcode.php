<?php
/**
 * 参数详情：处理带图的二维码图片
 * @param intger $qrcode_path 类型
 * @param string $content 生成二维码内容
 * @param intger $matrixPointSize 二维码尺寸大小
 * @param intger $$matrixMarginSize 生成二维码边距
 * @param intger $errorCorrectionLevel 容错级别
 * @param string $url 生成的带logo的二维码地址
 * @param bool $all 是否返回全部类型
 * @author huajie <banhuajie@163.com>
 */

function makecode($qrcode_path,$content,$matrixPointSize,$matrixMarginSize,$errorCorrectionLevel,$url,$id){
        ob_clean ();
        Vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();
        $qrcode_path_new = './Public/Admin/images/code_'.$id.'.png';//定义生成二维码的路径及名称
        $object::png($content,$qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
        $QR = imagecreatefromstring(file_get_contents($qrcode_path_new));//imagecreatefromstring:创建一个图像资源从字符串中的图像流
        $logo = imagecreatefromstring(file_get_contents($qrcode_path));
        $QR_width = imagesx($QR);// 获取图像宽度函数
        $QR_height = imagesy($QR);//获取图像高度函数
        $logo_width = imagesx($logo);// 获取图像宽度函数
        $logo_height = imagesy($logo);//获取图像高度函数
        $logo_qr_width = $QR_width / 4;//logo的宽度
        $scale = $logo_width / $logo_qr_width;//计算比例
        $logo_qr_height = $logo_height / $scale;//计算logo高度
        $from_width = ($QR_width - $logo_qr_width) / 2;//规定logo的坐标位置
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        /**     imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
         *      参数详情：
         *      $dst_image:目标图象连接资源。
         *      $src_image:源图象连接资源。
         *      $dst_x:目标 X 坐标点。
         *      $dst_y:目标 Y 坐标点。
         *      $src_x:源的 X 坐标点。
         *      $src_y:源的 Y 坐标点。
         *      $dst_w:目标宽度。
         *      $dst_h:目标高度。
         *      $src_w:源图象的宽度。
         *      $src_h:源图象的高度。
         * */
        Header("Content-type: image/png");
        header('Content-Disposition: attachment; filename=download_name.png');
        //$url:定义生成带logo的二维码的地址及名称
        imagepng($QR,$url);
      echo json_encode($url);exit;

    }

    /**
     * 参数详情：处理不带图的二维码图片
     * @param intger $qrcode_path 类型
     * @param string $content 生成二维码内容
     * @param intger $matrixPointSize 二维码尺寸大小
     * @param intger $$matrixMarginSize 生成二维码边距
     * @param intger $errorCorrectionLevel 容错级别
     * @param string $url 生成的带logo的二维码地址
     * @param bool $all 是否返回全部类型
     * @author huajie <banhuajie@163.com>
     */
    function makecode_no_pic($content,$qrcode_path_new,$matrixPointSize,$matrixMarginSize,$errorCorrectionLevel){
        ob_clean ();
        Vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();
        $object::png($content,$qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
    }





上部分为图片处理方法  下方为图片上传接受处理方法






   /**
       * 生成带图片的二维码
       * @author xiewen <18215582009@163.com>
       */

    public function img_qrcode(){
        $id = I('id');
        $title = I('title');
        $imgs = I('imgs');
        $qrcode_path_new = $this->saveBase64Image( $imgs,$id);
        if(!$qrcode_path_new) {// 上传错误提示错误信息
            $qrcode_path_new="";
            $content = 'http://126wenren.ejar.com.cn/Index/shareList.html';
            $matrixPointSize = 0;
            $matrixMarginSize = 1;
            $errorCorrectionLevel = 'M';
            makecode_no_pic($content,$qrcode_path_new,$matrixPointSize,$matrixMarginSize,$errorCorrectionLevel);
        }else{
            $qrcode_path =".".$qrcode_path_new;
            $content = 'http://126wenren.ejar.com.cn/Index/shareList.html';
            $matrixPointSize = 10;
            $matrixMarginSize = 1;
            $errorCorrectionLevel = M;
            $url =  "./Public/Admin/images/qrcode_".$id.".png";
            makecode($qrcode_path,$content,$matrixPointSize,$matrixMarginSize,$errorCorrectionLevel,$url,$id);
        }

    }
    /**
     * 处理basecode64图片
     * @author xiewen <18215582009@163.com>
     */

    public function saveBase64Image($base64_image_content,$id){

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){

            //图片后缀
            $type = $result[2];
            //保存位置--图片名
            $image_name=date('His').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".".$type;
            $image_url = '/Public/Admin/images/logo_'.$id.'.png';
            if(!is_dir(dirname('.'.$image_url))){
                mkdir(dirname('.'.$image_url),0777, true);
                chmod(dirname('.'.$image_url), 0777);
                umask($oldumask);
            }
            //解码
            $decode=base64_decode(str_replace($result[1], '', $base64_image_content));
            if (file_put_contents('.'.$image_url, $decode)){
                return $image_url;
            }else{
               return false;
            }
        }else{
            $result  = array('code'=>500,'data'=>null,'REQUEST_ERROR');
            echo json_encode( $result);exit;
        }
    }











?>