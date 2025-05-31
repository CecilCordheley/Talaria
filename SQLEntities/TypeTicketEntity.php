<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\TypeTicket;
use vendor\easyFrameWork\Core\Main;
use Exception;
/**
* Class personnalisée pour la table `TypeTicket`.
* Hérite de `TypeTicket`. Ajoutez ici vos propres méthodes.
*/
class TypeTicketEntity extends TypeTicket
{
   // Ajoutez vos méthodes ici

   public static function getAll($sqlF){
    $arr=TypeTicket::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(TypeTicket::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\TypeTicketEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getTypeTicketBy($sqlF,$key,$value,$filter=null){
      $arr=TypeTicket::getTypeTicketBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\TypeTicketEntity");
        return $c;
      },[]);
    }else return $arr;
    }else{
      return false;
    }
      }
 }