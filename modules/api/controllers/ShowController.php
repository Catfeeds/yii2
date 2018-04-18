<?php
namespace app\modules\api\controllers;
use yii\web\Controller;
//解决ajax跨域问题设置的header
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class ShowController extends Controller{
    //禁用Yii2的csrf验证
    public $enableCsrfValidation = false;
    //表单上传
    public function actionUpload(){
        //表单文件上传转存到uploads目录下
        //$_FILES["file"]["name"] => rand(1000,time());
        move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/".$_FILES["file"]["name"]);
        echo  $_FILES["file"]["name"];
        //随机数生成ID
        $id = rand(1000,time());
        //从表单拿数据
        $name =\Yii::$app->request->post('name');
        $tel =\Yii::$app->request->post('tel');
        //拼接文件路径
        $url ='https://app.taobaomaichang.com/uploads/'.$_FILES["file"]["name"];
        //开启数据库连接
        $conn = \Yii::$app->db;
        //向数据库插入一条数据
        $conn->createCommand()->insert('showshow', [
            'id' => $id,
            'name' => $name,
            'tel'=> $tel,
            'url'=> $url,
            'number'=> '0',
            ])->execute();
    }
    public function actionUpdate($id,$number)
    {
        $number += 1;
        $conn = \Yii::$app->db;
        $conn->createCommand()->update('showshow', ['number' => $number], 'id = :id'
            )->bindParam(':id', $id)->execute();
        }
    public function actionAll()
    {
        $conn = \Yii::$app->db;
        $sql = "select * from showshow ";
        $command =$conn->createCommand($sql);
        $data = $command->queryAll();
        return  $res=json_encode($data);
    }

}
