<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\EtatTicket;
use vendor\easyFrameWork\Core\Main;
use Exception;
/**
* Class personnalisée pour la table `EtatTicket`.
* Hérite de `EtatTicket`. Ajoutez ici vos propres méthodes.
*/
class EtatTicketEntity extends EtatTicket
{
   // Ajoutez vos méthodes ici

   public static function getAll($sqlF){
    $arr=EtatTicket::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(EtatTicket::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\EtatTicketEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getEtatTicketBy($sqlF,$key,$value,$filter=null){
      $arr=EtatTicket::getEtatTicketBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\EtatTicketEntity");
        return $c;
      },[]);
    }else return $arr;
    }else{
      return false;
    }
      }
 }