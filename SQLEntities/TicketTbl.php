<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use Exception;
 class TicketTbl{
    private $attr=["idTicket"=>'',"RefTicket"=>'',"contentTicket"=>'',"dateTicket"=>'',"auteur"=>'',"service"=>'',"objetTicket"=>'',"prioriteTicket"=>'',"dataTicket"=>'',"typeTicket"=>'',"agentResponsable"=>''];
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
    public static function  add(SQLFactory $sqlF,TicketTbl &$item,$callBack=null,$debug=false){
     $return= $sqlF->addItem($item->getArray(),"ticket_tbl");
    if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
      echo "<pre>$return</pre>";
      return false;
    } else {
      $item->idTicket=$sqlF->lastInsertId("ticket_tbl");
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }
    }
    public static function  update(SQLFactory $sqlF,TicketTbl $item,$callBack=null){
      $return=$sqlF->updateItem($item->getArray(),"ticket_tbl");
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
    public static function  del(SQLFactory $sqlF,TicketTbl $item){
      $sqlF->deleteItem($item->idTicket,"ticket_tbl");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM ticket_tbl");
      $return=[];
      foreach($query as $element){
      $entity=new TicketTbl();
         $entity->idTicket=$element["idTicket"];
$entity->RefTicket=$element["RefTicket"];
$entity->contentTicket=$element["contentTicket"];
$entity->dateTicket=$element["dateTicket"];
$entity->auteur=$element["auteur"];
$entity->service=$element["service"];
$entity->objetTicket=$element["objetTicket"];
$entity->prioriteTicket=$element["prioriteTicket"];
$entity->dataTicket=$element["dataTicket"];
$entity->typeTicket=$element["typeTicket"];
$entity->agentResponsable=$element["agentResponsable"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getTicketTblBy($sqlF,$key,$value,$filter=null){
      $query=$sqlF->prepareQuery("SELECT * FROM ticket_tbl WHERE $key=:val",$key,$value);
      $return=[];
      foreach($query as $element){
      $entity=new TicketTbl();
         $entity->idTicket=$element["idTicket"];
$entity->RefTicket=$element["RefTicket"];
$entity->contentTicket=$element["contentTicket"];
$entity->dateTicket=$element["dateTicket"];
$entity->auteur=$element["auteur"];
$entity->service=$element["service"];
$entity->objetTicket=$element["objetTicket"];
$entity->prioriteTicket=$element["prioriteTicket"];
$entity->dataTicket=$element["dataTicket"];
$entity->typeTicket=$element["typeTicket"];
$entity->agentResponsable=$element["agentResponsable"];
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