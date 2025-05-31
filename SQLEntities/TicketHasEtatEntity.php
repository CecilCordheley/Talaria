<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\TicketHasEtat;
use vendor\easyFrameWork\Core\Main;
use Exception;
/**
* Class personnalisée pour la table `TicketHasEtat`.
* Hérite de `TicketHasEtat`. Ajoutez ici vos propres méthodes.
*/
class TicketHasEtatEntity extends TicketHasEtat
{
   // Ajoutez vos méthodes ici

   public static function getAll($sqlF){
    $arr=TicketHasEtat::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(TicketHasEtat::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\TicketHasEtatEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getTicketHasEtatBy($sqlF,$key,$value,$filter=null){
      $arr=TicketHasEtat::getTicketHasEtatBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\TicketHasEtatEntity");
        return $c;
      },[]);
    }else return $arr;
    }else{
      return false;
    }
      }
 }