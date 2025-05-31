<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use Exception;
 class LicenceException{
    private $attr=["idLicence"=>'',"uuidLicence"=>'',"agent"=>'',"dateAttribution"=>'',"estActive"=>'',"isAutoAttribution"=>''];
    public function __set($name,$value){
      if (array_key_exists($name, $this->attr)) {
         $this->attr[$name]=$value;
     } else {
         throw new Exception("Propriété non définie : $name");
     }
    }
    public function getArray(){
      return $this->attr;
    }
    public function __get($name){
      if (array_key_exists($name, $this->attr)) {
         return $this->attr[$name];
     } else {
         throw new Exception("Propriété non définie : $name");
     }
    }
    public static function  add(SQLFactory $sqlF,LicenceException &$item,$callBack=null){
     $return= $sqlF->addItem($item->getArray(),"licence_exception",true);
    if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
      return $return;
    } else {
      $item->idLicence=$sqlF->lastInsertId("licence_exception");
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }
    }
    public static function  update(SQLFactory $sqlF,LicenceException $item,$callBack=null){
      $return=$sqlF->updateItem($item->getArray(),"licence_exception");
      if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
        echo "<pre>$return</pre>";
        return false;
      } else {
        if($callBack!=null){
          call_user_func($callBack,$item);
        }
        return true;
      }
    }
    public static function  del(SQLFactory $sqlF,LicenceException $item){
      $sqlF->deleteItem($item->idLicence,"licence_exception");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM licence_exception");
      $return=[];
      foreach($query as $element){
      $entity=new LicenceException();
         $entity->idLicence=$element["idLicence"];
$entity->uuidLicence=$element["uuidLicence"];
$entity->agent=$element["agent"];
$entity->dateAttribution=$element["dateAttribution"];
$entity->estActive=$element["estActive"];
$entity->isAutoAttribution=$element["isAutoAttribution"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getLicenceExceptionBy($sqlF,$key,$value,$filter=null){
      $query=$sqlF->prepareQuery("SELECT * FROM licence_exception WHERE $key=:val",$key,$value);
      $return=[];
      foreach($query as $element){
      $entity=new LicenceException();
         $entity->idLicence=$element["idLicence"];
$entity->uuidLicence=$element["uuidLicence"];
$entity->agent=$element["agent"];
$entity->dateAttribution=$element["dateAttribution"];
$entity->estActive=$element["estActive"];
$entity->isAutoAttribution=$element["isAutoAttribution"];
      $return[]=$entity;
      }
      if($filter!=null && count($return)>0){
        $return = array_filter($return,$filter);
      }
      if(count($return))
      return (count($return) > 1) ? $return : $return[0];
    else
      return false;
    }
 }