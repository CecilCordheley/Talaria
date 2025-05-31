<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\TypeAgent;
use vendor\easyFrameWork\Core\Main;
use Exception;
/**
* Class personnalisée pour la table `TypeAgent`.
* Hérite de `TypeAgent`. Ajoutez ici vos propres méthodes.
*/
class TypeAgentEntity extends TypeAgent
{
   // Ajoutez vos méthodes ici

   public static function getAll($sqlF){
    $arr=TypeAgent::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(TypeAgent::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\TypeAgentEntity");
      return $c;
    },[]);
  }else
    return $arr;
    }else
    return false;
  }
    public static function getTypeAgentBy($sqlF,$key,$value,$filter=null){
      $arr=TypeAgent::getTypeAgentBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\TypeAgentEntity");
        return $c;
      },[]);
    }else return $arr;
    }else{
      return false;
    }
      }
 }