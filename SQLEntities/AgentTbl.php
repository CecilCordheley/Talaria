<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use Exception;
 class AgentTbl{
    private $attr=["blockAgent"=>'',"idAgent"=>'',"NomAgent"=>'',"PrenomAgent"=>'',"mailAgent"=>'',"mdpAgent"=>'',"validiteMdp"=>'',"refAgent"=>'',"uuidAgent"=>'',"typeAgent"=>'',"service"=>'',"dataAgent"=>''];
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
    public static function  add(SQLFactory $sqlF,AgentTbl &$item,$callBack=null){
     $return= $sqlF->addItem($item->getArray(),"agent_tbl");
    if (gettype($return) === "string" && strpos($return, "Error") !== -1) {
      echo "<pre>$return</pre>";
      return false;
    } else {
      $item->idAgent=$sqlF->lastInsertId("agent_tbl");
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }
    }
    public static function  update(SQLFactory $sqlF,AgentTbl $item,$callBack=null){
      $table="agent_tbl";
      $return=$sqlF->updateItem($item->getArray(),$table);
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
    public static function  del(SQLFactory $sqlF,AgentTbl $item){
      $sqlF->deleteItem($item->idAgent,"agent_tbl");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM agent_tbl");
      $return=[];
      foreach($query as $element){
      $entity=new AgentTbl();
         $entity->idAgent=$element["idAgent"];
$entity->NomAgent=$element["NomAgent"];
$entity->PrenomAgent=$element["PrenomAgent"];
$entity->mailAgent=$element["mailAgent"];
$entity->mdpAgent=$element["mdpAgent"];
$entity->validiteMdp=$element["validiteMdp"];
$entity->refAgent=$element["refAgent"];
$entity->uuidAgent=$element["uuidAgent"];
$entity->typeAgent=$element["typeAgent"];
$entity->service=$element["service"];
$entity->dataAgent=$element["dataAgent"];
$entity->blockAgent=$element["blockAgent"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getAgentTblBy($sqlF,$key,$value,$filter=null){
      $query=$sqlF->prepareQuery("SELECT * FROM agent_tbl WHERE $key=:val",$key,$value);
      $return=[];
      foreach($query as $element){
      $entity=new AgentTbl();
         $entity->idAgent=$element["idAgent"];
$entity->NomAgent=$element["NomAgent"];
$entity->PrenomAgent=$element["PrenomAgent"];
$entity->mailAgent=$element["mailAgent"];
$entity->mdpAgent=$element["mdpAgent"];
$entity->validiteMdp=$element["validiteMdp"];
$entity->refAgent=$element["refAgent"];
$entity->uuidAgent=$element["uuidAgent"];
$entity->typeAgent=$element["typeAgent"];
$entity->service=$element["service"];
$entity->dataAgent=$element["dataAgent"];
$entity->blockAgent=$element["blockAgent"];
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