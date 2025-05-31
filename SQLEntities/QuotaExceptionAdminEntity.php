<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\QuotaExceptionAdmin;
use vendor\easyFrameWork\Core\Main;
use Exception;
/**
* Class personnalisée pour la table `QuotaExceptionAdmin`.
* Hérite de `QuotaExceptionAdmin`. Ajoutez ici vos propres méthodes.
*/
class QuotaExceptionAdminEntity extends QuotaExceptionAdmin
{
   // Ajoutez vos méthodes ici

   public static function getAll($sqlF){
    $arr=QuotaExceptionAdmin::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(QuotaExceptionAdmin::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\QuotaExceptionAdminEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getQuotaExceptionAdminBy($sqlF,$key,$value,$filter=null){
      $arr=QuotaExceptionAdmin::getQuotaExceptionAdminBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\QuotaExceptionAdminEntity");
        return $c;
      },[]);
    }else return $arr;
    }else{
      return false;
    }
      }
 }