<?php
namespace app\modules\api\controllers;
use yii\web\Controller;
//解决ajax跨域问题设置的header
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class LianController extends Controller{
    //禁用Yii2的csrf验证
    public $enableCsrfValidation = false;
    //表单上传
    public function actionUpload(){
        //表单文件上传转存到uploads目录下
        //$_FILES["file"]["name"] => rand(1000,time());
        move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/lian/".$_FILES["file"]["name"]);
        echo  $_FILES["file"]["name"];
        //随机数生成ID
        $id = rand(1000,time());
        //从表单拿数据
        $t1 =\Yii::$app->request->post('t1');
        $t2 =\Yii::$app->request->post('t2');
        $price =\Yii::$app->request->post('price');
        //拼接文件路径
        $url ='http://localhost/yii2/web/uploads/lian/'.$_FILES["file"]["name"];
        //开启数据库连接
        $conn = \Yii::$app->db;
        //向数据库插入一条数据
        $conn->createCommand()->insert('lgoods', [
            'id' => $id,
            'url'=> $url,
            't1'=>$t1,
            't2'=>$t2,
            'price'=>$price,
            ])->execute();
    }

    public function actionFindbyid($id)
    {
        $conn = \Yii::$app->db;
        //$id =\Yii::$app->request->get('id');
        $sql = "select * from lgoods where id = ".$id;
        $command =$conn->createCommand($sql);
        $data = $command->queryOne();
        return  $res=json_encode($data);
    }
    public function actionAll()
    {
        $conn = \Yii::$app->db;
        $sql = "select * from lgoods";
        $command =$conn->createCommand($sql);
        $data = $command->queryAll();
        return  $res=json_encode($data);
    }

    public function actionSearch($search)
    {
        $conn = \Yii::$app->db;
        $sql = "select * from lgoods where t1 like "."'%".$search."%'";
        $command =$conn->createCommand($sql);
        $data = $command->queryAll();
        return  $res=json_encode($data);
    }



}
