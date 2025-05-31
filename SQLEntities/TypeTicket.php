<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use Exception;
 class TypeTicket{
    private $attr=["idTypeTicket"=>'',"libTypeTicket"=>''];
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
    public static function  add(SQLFactory $sqlF,TypeTicket &$item,$callBack=null){
     $return= $sqlF->addItem($item->getArray(),"type_ticket");
    if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
      echo "<pre>$return</pre>";
      return false;
    } else {
      $item->idTypeTicket=$sqlF->lastInsertId("type_ticket");
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }
    }
    public static function  update(SQLFactory $sqlF,TypeTicket $item,$callBack=null){
      $return=$sqlF->updateItem($item->getArray(),"type_ticket");
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
    public static function  del(SQLFactory $sqlF,TypeTicket $item){
      $sqlF->deleteItem($item->idTypeTicket,"type_ticket");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM type_ticket");
      $return=[];
      foreach($query as $element){
      $entity=new TypeTicket();
         $entity->idTypeTicket=$element["idTypeTicket"];
$entity->libTypeTicket=$element["libTypeTicket"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getTypeTicketBy($sqlF,$key,$value,$filter=null){
      $query=$sqlF->prepareQuery("SELECT * FROM type_ticket WHERE $key=:val",$key,$value);
      $return=[];
      foreach($query as $element){
      $entity=new TypeTicket();
         $entity->idTypeTicket=$element["idTypeTicket"];
$entity->libTypeTicket=$element["libTypeTicket"];
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