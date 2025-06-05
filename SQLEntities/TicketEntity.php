<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\TicketTbl;
use SQLEntities\TicketHasEtatEntity;
use SQLEntities\TypeTicket;
use vendor\easyFrameWork\Core\Main;
use Exception;
use InvalidArgumentException;
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
  public static function getBnTicketWithStat(SQLFactory $sqlf,string $groupBy="service"){
    switch($groupBy){
      case "service":{
        return $sqlf->execQuery("SELECT s.libService AS label, e.libEtatTicket AS etat, COUNT(*) AS total 
        FROM ticket_tbl t JOIN service s ON s.idService = t.service 
        JOIN ( SELECT t1.ticket_tbl_idTicket, t1.Etat_Ticket_idEtatTicket 
        FROM ticket_has_etat t1 
        JOIN ( SELECT ticket_tbl_idTicket, MAX(dateEtat) 
        as maxDate FROM ticket_has_etat GROUP BY ticket_tbl_idTicket ) t2 
        ON t1.ticket_tbl_idTicket = t2.ticket_tbl_idTicket AND t1.dateEtat = t2.maxDate ) last_etat ON t.idTicket = last_etat.ticket_tbl_idTicket JOIN etat_ticket e ON e.idEtatTicket = last_etat.Etat_Ticket_idEtatTicket GROUP BY s.idService, e.idEtatTicket;");
      }
    }
  }
  public static function getNbTicket(SQLFactory $sqlF,string $groupBy=""){
    
    if($groupBy!=""){
      switch ($groupBy){
        case "type":
          return $sqlF->execQuery("SELECT t1.libTypeTicket as label, COUNT(t2.idTicket) as total FROM type_ticket t1 LEFT JOIN ticket_tbl t2 on t2.typeTicket=t1.idTypeTicket GROUP BY t1.idTypeTicket;");
        case "state":
          return $sqlF->execQuery("SELECT e.libEtatTicket AS label, COUNT(t.ticket_tbl_idTicket) AS total FROM ( SELECT ticket_tbl_idTicket, MAX(dateEtat) AS maxDate FROM ticket_has_etat GROUP BY ticket_tbl_idTicket ) last_etat JOIN ticket_has_etat t ON t.ticket_tbl_idTicket = last_etat.ticket_tbl_idTicket AND t.dateEtat = last_etat.maxDate RIGHT JOIN etat_ticket e ON e.idEtatTicket = t.Etat_Ticket_idEtatTicket GROUP BY e.idEtatTicket;");
        case "agent":{
          return $sqlF->execQuery("SELECT refAgent as label, COUNT(t.idTicket) as total FROM agent_tbl a  LEFT JOIN ticket_tbl t on t.auteur=a.idAgent GROUP BY a.idAgent");
        }
        case "service":{
          return $sqlF->execQuery("SELECT s.refService AS label, COUNT(t.idTicket) AS total FROM service s LEFT JOIN ticket_tbl t ON t.service = s.idService GROUP BY s.idService");
        }
        default:
  throw new InvalidArgumentException("Critère de groupement invalide : $groupBy");
      }
      
    }
    return $sqlF->execFnc("TotalTicket",[])[0]["TotalTicket"];
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
      usort($a,function($a,$b){
        return Main::DateCompare($a->dateEtat,$b->dateEtat);
      });
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