<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use Exception;
 class TicketHasEtat{
    private $attr=["Etat_Ticket_idEtatTicket"=>'',"ticket_tbl_idTicket"=>'',"dateEtat"=>'',"commentEtat"=>''];
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
    public static function  add(SQLFactory $sqlF,TicketHasEtat &$item,$callBack=null){
     $return= $sqlF->addItem($item->getArray(),"ticket_has_etat");
    if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
      echo "<pre>$return</pre>";
      return false;
    } else {
      $item->Etat_Ticket_idEtatTicket=$sqlF->lastInsertId("ticket_has_etat");
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }
    }
    public static function  update(SQLFactory $sqlF,TicketHasEtat $item,$callBack=null){
      $return=$sqlF->updateItem($item->getArray(),"ticket_has_etat");
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
    public static function  del(SQLFactory $sqlF,TicketHasEtat $item){
      $sqlF->deleteItem($item->Etat_Ticket_idEtatTicket,"ticket_has_etat");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM ticket_has_etat");
      $return=[];
      foreach($query as $element){
      $entity=new TicketHasEtat();
         $entity->Etat_Ticket_idEtatTicket=$element["Etat_Ticket_idEtatTicket"];
$entity->ticket_tbl_idTicket=$element["ticket_tbl_idTicket"];
$entity->dateEtat=$element["dateEtat"];
$entity->commentEtat=$element["commentEtat"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getTicketHasEtatBy($sqlF,$key,$value,$filter=null){
      $query=$sqlF->prepareQuery("SELECT * FROM ticket_has_etat WHERE $key=:val",$key,$value);
      $return=[];
      foreach($query as $element){
      $entity=new TicketHasEtat();
         $entity->Etat_Ticket_idEtatTicket=$element["Etat_Ticket_idEtatTicket"];
$entity->ticket_tbl_idTicket=$element["ticket_tbl_idTicket"];
$entity->dateEtat=$element["dateEtat"];
$entity->commentEtat=$element["commentEtat"];
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