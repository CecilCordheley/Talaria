<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\JournalLicence;
use vendor\easyFrameWork\Core\Main;
use Exception;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;

/**
* Class personnalisée pour la table `JournalLicence`.
* Hérite de `JournalLicence`. Ajoutez ici vos propres méthodes.
*/
class JournalLicenceEntity extends JournalLicence
{
 private static function getMAP(){
  return [
    "delegateUser"=>function($sqlF,$journal){
      $agent=AgentEntity::getAgentTblBy($sqlF,"idAgent",$journal->cible);
      $data=json_decode($agent[0]->dataAgent??"{}",true);
      $param=$journal->getAction()["param"];
      $data["delegate"]=["debut"=>$param["debut"],"fin"=>$param["fin"]];
      $newData=json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      $agent[0]->dataAgent=str_replace('"',"\\\"",$newData);
      return AgentEntity::update($sqlF,$agent[0]);
    },
    "restore_service"=>function($sqlF,$journal){
        $serv=ServiceEntity::getServiceBy($sqlF,"idService",$journal->cible);
          if($serv==false){
            return false;
          }
          $serv->isActif="1";
          return ServiceEntity::update($sqlF,$serv);
    },
    "resetStateTicket"=>function($sqlF,$journal){
      try{
      $ticket=TicketEntity::getTicketTblBy($sqlF,"idTicket",$journal->cible);
      #getUUIDLicence;
      $licence=LicenceExceptionEntity::getLicenceExceptionBy($sqlF,"idLicence",$journal->licence);
      return $ticket->changeState($sqlF,1,"From Licence : ".$licence->uuidLicence);
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
  ];
}
   // Ajoutez vos méthodes ici
   function getAction(){
    $raw = str_replace("\\","",$this->action); // décodage HTML
    $decoded = json_decode($raw,true);       // tentative de décodage
    // Si c’est encore une chaîne, alors il faut décoder une deuxième fois
    if (is_string($decoded)) {
        $decoded = json_decode($decoded, true);
    }

    return $decoded;
   }
    public function execute($sqlF){
      $return=false;
     // EasyFrameWork::Debug($this->getAction());
      switch($this->getAction()["name"]){
        case "Delegate user":{
          $return= self::getMAP()["delegateUser"]($sqlF,$this);
          break;
        }
        case "restoreService":{
          $return= self::getMAP()["restore_service"]($sqlF,$this);
          break;
        }
         case "resetStateTicket":{
          $return= self::getMAP()["resetStateTicket"]($sqlF,$this);
          break;
        }
      }
      if($return){
        $l=LicenceExceptionEntity::getLicenceExceptionBy($sqlF,"idLicence",$this->licence);
        if($l==false){
          return false;
        }
        $l->estActive="1";
        return LicenceExceptionEntity::update($sqlF,$l);
      }
    }
  public static function getTypeCible(){
    return ["agent","service","ticket"];
  }
   public static function getAll($sqlF){
    $arr=JournalLicence::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(JournalLicence::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\JournalLicenceEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getJournalLicenceBy($sqlF,$key,$value,$filter=null){
      $arr=JournalLicence::getJournalLicenceBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\JournalLicenceEntity");
        return $c;
      },[]);
    }else return $arr;
    }else{
      return false;
    }
      }
 }