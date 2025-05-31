<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use Exception;
 class TypeAgent{
    private $attr=["idTypeAgent"=>'',"libTypeAgent"=>''];
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
    public static function  add(SQLFactory $sqlF,TypeAgent &$item,$callBack=null){
     $return= $sqlF->addItem($item->getArray(),"type_agent");
    if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
      echo "<pre>$return</pre>";
      return false;
    } else {
      $item->idTypeAgent=$sqlF->lastInsertId("type_agent");
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }
    }
    public static function  update(SQLFactory $sqlF,TypeAgent $item,$callBack=null){
      $return=$sqlF->updateItem($item->getArray(),"type_agent");
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
    public static function  del(SQLFactory $sqlF,TypeAgent $item){
      $sqlF->deleteItem($item->idTypeAgent,"type_agent");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM type_agent");
      $return=[];
      foreach($query as $element){
      $entity=new TypeAgent();
         $entity->idTypeAgent=$element["idTypeAgent"];
$entity->libTypeAgent=$element["libTypeAgent"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getTypeAgentBy($sqlF,$key,$value,$filter=null){
      $query=$sqlF->prepareQuery("SELECT * FROM type_agent WHERE $key=:val",$key,$value);
      $return=[];
      foreach($query as $element){
      $entity=new TypeAgent();
         $entity->idTypeAgent=$element["idTypeAgent"];
$entity->libTypeAgent=$element["libTypeAgent"];
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