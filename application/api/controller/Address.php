<?php
namespace app\api\controller;
use app\common\controller\Api;
use  think\Db;
use  think\Request;
/*
 * address  地区xml
*/ 

Class  Address  extends Api{

  protected  $xml;
  public  function __construct(){
    $this->xml = simplexml_load_file('address.xml');
    }  
    //mysql  省 
  public  function  province(){
    $provice_data['list']  =  DB::name('province')->field('Code,Name')->select();
    return  $this->success($msg = '成功回调', $data =$provice_data, $code = 20006, $type = 'json');
   }
  //mysql  区
  public  function  city(Request $request= null){ 
    $provice  =  $request->get('province');
    if(empty($provice)){
      return  $this->error(__('无效的参数'),$data = null, $code = 60008, $type = 'json');
    }
    $city_data['list']  =  DB::name('city')->field('Code,Name')->where('provinceCode','=',$provice)->select();
    return  $this->success($msg = '成功回调', $data =$city_data, $code = 20006, $type = 'json');
   }
   //mysql  省
    public  function  area(Request $request= null){
    $city  =  $request->get('city');
    if(empty($city)){
      return  $this->error(__('无效的参数'),$data = null, $code = 60008, $type = 'json');
    }
    $city_data['list']  =  DB::name('area')->field('Code,Name')->where('CityCode','=',$city)->select();
    return  $this->success($msg = '成功回调', $data =$city_data, $code = 20006, $type = 'json');
   }
   //mysql   市区
  //xml所有省
  public  function  xml_province(){
    $array = array();
    foreach($this->xml->children() as $element => $node){
            $data = array();
            $attributes   = $node->attributes();
            foreach($attributes as $attr => $value)
            {
               $data[$attr] = (string)$value;
            } 
            $array[] = $data;
       }
       return  json_encode(array( 'msg' =>'回调成功', 'data' =>$array));
      // return  $this->success(__('无效的参数'), $data= [], $code = 60010); 
        }
  /*所有省=》市
  *测试中
  */
  public  function  xml_city(){
        $xml = new \DOMDocument();
        $xml->load('address.xml');
        $province = $xml->getElementsByTagName('province');
        // print_r($root);
        foreach ($province as $key => $item) {
           // print_r( $item->getAttribute('name'));
          if($item->getAttribute('name') == '广东省'){
            // echo '广东省';
           $city =  $item->getElementsByTagName('city');
           // var_dump($city);
           foreach ($city as $key => $node) {
               // print_r($node->getAttribute('name'));
                  if($node->getAttribute('name') == '湛江市'){
                       $area =  $node->getElementsByTagName('area');
                       // print_r($nodeson);
                       foreach ($area as $key => $nodeson) {
                              print_r($nodeson->getAttribute('name'));
                       }

                  }
           }
      
          }
         }
       // return  json_encode(array( 'msg' =>'回调成功', 'data' =>$array));
       }
  /*所有县市
  *xpath xml 的搜索
  */
  public  function  xml_area(){
         $xml = new \DOMDocument();
         $xml->load('address.xml');
         $xpath = new \DOMXpath($xml);
          /*国家*/
         $elements = $xpath->query('/root');
         foreach ($elements as $key => $node) {
            print_r($node->getAttribute('name'));
         }
         /*省份*/
         $province = $xpath->query('//province');
          foreach ($province as $key => $node) {
             print_r($node->getAttribute('name'));
          }
         /*市*/
           $city = $xpath->query('/root/province[@name="广东省"]/city');
           foreach ($city as $key => $node) {
            print_r($node->getAttribute('name'));
           }
         /*区*/
            $area = $xpath->query('/root/province[@name="广东省"]/city[@name="湛江市"]/area');
           foreach ($area as $key => $node) {
            print_r($node->getAttribute('name'));
           }
          // print_r($elements);
          // print_r($elements);
  }
 
}
