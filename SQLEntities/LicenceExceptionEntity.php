<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\LicenceException;
use vendor\easyFrameWork\Core\Main;
use Exception;
/**
* Class personnalisée pour la table `LicenceException`.
* Hérite de `LicenceException`. Ajoutez ici vos propres méthodes.
*/
class LicenceExceptionEntity extends LicenceException
{
   // Ajoutez vos méthodes ici
   
   public static function getAll($sqlF){
    $arr=LicenceException::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(LicenceException::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\LicenceExceptionEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getLicenceExceptionBy($sqlF,$key,$value,$filter=null){
      $arr=LicenceException::getLicenceExceptionBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\LicenceExceptionEntity");
        return $c;
      },[]);
    }else return $arr;
    }else{
      return false;
    }
      }
 }