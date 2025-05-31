<?php
namespace SQLEntities;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\Service;
use vendor\easyFrameWork\Core\Main;
use SQLEntities\AgentEntity;
use Exception;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;

/**
* Class personnalisée pour la table `Service`.
* Hérite de `Service`. Ajoutez ici vos propres méthodes.
*/
class ServiceEntity extends Service
{
   // Ajoutez vos méthodes ici
  /**
   * get tickets To Service
   * @param mixed $sqlf
   */
  public function getTickets($sqlf){
    return TicketEntity::getTicketTblBy($sqlf,"service",$this->idService);
  }
  public function getChildren($sqlF){
    return ServiceEntity::getServiceBy($sqlF,"parent_service",$this->idService);
  }
  /**
   * Get agents from Service
   * @param \vendor\easyFrameWork\Core\Master\SQLFactory $sqlF
   */
  public function getAgents(SQLFactory $sqlF,$children=false){
    $return=AgentEntity::getAll($sqlF);
    if($return==false)
      return false;
    else{
      $arr = is_array($return) ? $return : [$return];
      $agents = array_filter($arr, fn($e) => $e->service === $this->idService);
       if ($children) {
         $childServices = ServiceEntity::getServiceBy($sqlF, "parent_service", $this->idService);
        $child_arr = is_array($childServices) ? $childServices : [$childServices];
        foreach($child_arr as $child){
          $childAgents = $child->getAgents($sqlF); // appel récursif
          if ($childAgents!=false && is_array($childAgents)) {
          $agents = array_merge($agents, $childAgents);
        }
        }
       }
      return $agents;
    }
  }
  /**
   * get tickets from service
   * @param \vendor\easyFrameWork\Core\Master\SQLFactory $sqlF
   */
  public function getTicketsFrom(SQLFactory $sqlF){
    $tickets=TicketEntity::getAll($sqlF);
    if($tickets!=false){
      $arr = is_array($tickets) ? $tickets : [$tickets];
    return array_reduce($arr,function($c,$e) use($sqlF){
      $user=AgentEntity::getAgentTblBy($sqlF,"idAgent",$e->auteur);
     // EasyFrameWork::Debug($user[0],false);
      if($user[0]->service==$this->idService){
        $c[]=$e;
      }
      return $c;
    },[]);
  }else{
    return $tickets;
  }
  }
   public static function getAll($sqlF){
    $arr=Service::getAll($sqlF);
    if($arr){
      if(gettype($arr)=="array"){
    return array_reduce(Service::getAll($sqlF),function($c,$e){
      $c[]=Main::fixObject($e,"SQLEntities\ServiceEntity");
      return $c;
    },[]);
  }else
    return Main::fixObject($arr,"SQLEntities\ServiceEntity");
    }else
    return false;
  }
    public static function getServiceBy($sqlF,$key,$value,$filter=null){
      $arr=Service::getServiceBy($sqlF,$key,$value,$filter);
    if($arr){
      if(gettype($arr)=="array"){
      return array_reduce($arr,function($c,$e){
        $c[]=Main::fixObject($e,"SQLEntities\ServiceEntity");
        return $c;
      },[]);
    }else return Main::fixObject($arr,"SQLEntities\ServiceEntity");
    }else{
      return false;
    }
      }
 }