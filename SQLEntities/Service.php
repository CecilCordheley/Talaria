<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use Exception;
 class Service{
    private $attr=["idService"=>'',"RefService"=>'',"libService"=>'',"desc_service"=>'',"create_enable"=>'',"update_enable"=>'',"isActif"=>'',"archivable"=>'',"parent_service"=>''];
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
    public static function  add(SQLFactory $sqlF,Service &$item,$callBack=null){
     $return= $sqlF->addItem($item->getArray(),"service");
    if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
      echo "<pre>$return</pre>";
      return false;
    } else {
      $item->idService=$sqlF->lastInsertId("service");
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }
    }
    public static function  update(SQLFactory $sqlF,Service $item,$callBack=null){
      $return=$sqlF->updateItem($item->getArray(),"service");
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
    public static function  del(SQLFactory $sqlF,Service $item){
      $sqlF->deleteItem($item->idService,"service");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM service");
      $return=[];
      foreach($query as $element){
      $entity=new Service();
         $entity->idService=$element["idService"];
$entity->RefService=$element["RefService"];
$entity->libService=$element["libService"];
$entity->desc_service=$element["desc_service"];
$entity->create_enable=$element["create_enable"];
$entity->update_enable=$element["update_enable"];
$entity->isActif=$element["isActif"];
$entity->archivable=$element["archivable"];
$entity->parent_service=$element["parent_service"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getServiceBy($sqlF,$key,$value,$filter=null){
      $query=$sqlF->prepareQuery("SELECT * FROM service WHERE $key=:val",$key,$value);
      $return=[];
      foreach($query as $element){
      $entity=new Service();
         $entity->idService=$element["idService"];
$entity->RefService=$element["RefService"];
$entity->libService=$element["libService"];
$entity->desc_service=$element["desc_service"];
$entity->create_enable=$element["create_enable"];
$entity->update_enable=$element["update_enable"];
$entity->isActif=$element["isActif"];
$entity->archivable=$element["archivable"];
$entity->parent_service=$element["parent_service"];
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