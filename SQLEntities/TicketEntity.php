<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\TicketTbl;
use SQLEntities\TicketHasEtatEntity;
use SQLEntities\TypeTicket;
use vendor\easyFrameWork\Core\Main;
use Exception;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;

/**
* Class personnalisée pour la table `Ticket`.
* Hérite de `TicketTbl`. Ajoutez ici vos propres méthodes.
*/
class TicketEntity extends TicketTbl
{
  /**
   * Récupère les ticket issus du service passé en paramètre
   * @param \vendor\easyFrameWork\Core\Master\SQLFactory $sqlF
   * @param int $idService
   * @return mixed
   */
  public static function getTicketFrom(SQLFactory $sqlF, int $idService){
    $all=TicketEntity::getAll($sqlF);
   
    if($all==false){
      return 0;
    }else{
      $arr=[];
      if(gettype($all)!="array"){
        $arr[]=$all;
      }else{
        $arr=$all;
      }
      $i=0;
      return array_reduce($arr,function($c,$t)use(&$i,$idService,$sqlF){
        $user=AgentTbl::getAgentTblBy($sqlF,"idAgent",$t->auteur);
        if($user->service==$idService){
          $c[$i]=Main::fixObject($t,"SQLEntities\TicketEntity");
          $i++;
        }
        return $c;
      },[]);
    }
    
  }
  public function changeState(SQLFactory $sqlF, int $state,string $comment=""){
    date_default_timezone_set('Europe/Paris');
    $tcktHasEtat=new TicketHasEtatEntity;
    $tcktHasEtat->ticket_tbl_idTicket=$this->idTicket;
    $tcktHasEtat->Etat_Ticket_idEtatTicket=$state;
    $tcktHasEtat->commentEtat=$comment;
    $tcktHasEtat->dateEtat=date("Y-m-d H-i-s");
    return TicketHasEtatEntity::add($sqlF,$tcktHasEtat);
  }
  /**
   * Summary of getArrayWithState
   * @param \vendor\easyFrameWork\Core\Master\SQLFactory $sqlF
   * @return array
   */
  public function getArrayWithState(SQLFactory $sqlF){
    $return = $this->getArray();
    $state=$this->getStates($sqlF);
    usort($state,function($a,$b){
      return MAin::DateCompare($a["dateEtat"],$b["dateEtat"]);
    }); 
    $return["states"]=$state;
    $return["dataTicket"]=$return["dataTicket"]?json_decode($return["dataTicket"],true):"";
    $lastStat=Main::utf8ize(end($state)["libEtat"]);
    $return["lastStatut"]=mb_convert_encoding($lastStat, "UTF-8", mb_detect_encoding($lastStat, "UTF-8, ISO-8859-1, ISO-8859-15", true));
    $return["libService"]=Main::utf8ize(Service::getServiceBy($sqlF,"idService",$this->service)->libService);
    $return["libType"]=Main::utf8ize(TypeTicket::getTypeTicketBy($sqlF,"idTypeTicket",$this->typeTicket)->libTypeTicket);
    return $return;
  }
  public function getStates($sqlF){
    $state=TicketHasEtatEntity::getTicketHasEtatBy($sqlF,"ticket_tbl_idTicket",$this->idTicket);
    if($state!=false){
      $a=[];
      if(gettype($state)!="array"){
        $a[0]=$state;
      }else
        $a=$state;
        $i=0;
    return array_reduce($a,function($carry,$el) use(&$i,$sqlF){
      $carry[$i]=$el->getArray();
      $carry[$i]["libEtat"]=Main::utf8ize(EtatTicket::getEtatTicketBy($sqlF,"idEtatTicket",$el->Etat_Ticket_idEtatTicket)->libEtatTicket);
      $i++;
      return $carry;
    },[]);
  }
    else
      return false;
  }
  public static function update(SQLFactory $sqlF, TicketTbl $item, $callBack = null)
  {
    $item->dataTicket=str_replace('"',"\\\"",$item->dataTicket);
    return TicketTbl::update( $sqlF,$item, $callBack);
  }
   // Ajoutez vos méthodes ici
   public static function  add(SQLFactory $sqlF,TicketTbl &$item,$callBack=null,$debug=false){
      $return=TicketTbl::add($sqlF,$item,$callBack,$debug);
      if($return){
        $state=new TicketHasEtatEntity;
        $state->Etat_Ticket_idEtatTicket=1;
        $state->ticket_tbl_idTicket=$item->idTicket;
        $state->dateEtat=date("Y-m-d");
        TicketHasEtatEntity::add($sqlF,$state);
      if($callBack!=null){
        call_user_func($callBack,$item);
      }
      return true;
    }else
      return false;
  }
   public static function getAll($sqlF){
    $arr=TicketTbl::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(TicketTbl::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\TicketEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getTicketTblBy($sqlF,$key,$value,$filter=null){
      $arr=TicketTbl::getTicketTblBy($sqlF,$key,$value,$filter);
  //  EasyFrameWork::Debug($arr);
      if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\TicketEntity");
        return $c;
      },[]);
    }else return Main::fixObject($arr,"SQLEntities\TicketEntity");
    }else{
      return false;
    }
      }
 }