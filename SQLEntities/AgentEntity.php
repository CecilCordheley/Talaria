<?php
namespace SQLEntities;

use vendor\easyFrameWork\Core\Master\EasyFrameWork;

use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\AgentTbl;
use vendor\easyFrameWork\Core\Main;
use SQLEntities\ServiceEntity;
use SQLEntities\LicenceExceptionEntity;
use SQLEntities\QuotaExceptionAdminEntity;
use Exception;
/**
* Class personnalisée pour la table `Agent`.
* Hérite de `AgentTbl`. Ajoutez ici vos propres méthodes.
*/
class AgentEntity extends AgentTbl
{
  public function getQuota($sqlf){
    if($this->type!=1)
      throw new Exception("Agent type has no licence !");
    return QuotaExceptionAdminEntity::getQuotaExceptionAdminBy($sqlf,"idAgent",$this->idAgent);
  }
  public function getService($sqlF){
    return ServiceEntity::getServiceBy($sqlF,"idService",$this->service);
  }
  /**
   * Ticket émanant de l'agent
   * @param SQLFactory $sqlF
   */
  public function getTicket($sqlF){
    return TicketEntity::getTicketTblBy($sqlF,"auteur",$this->idAgent);
  }
   public static function connexion($sqlF,$mail,$mdp){
    $r = self::getAgentTblBy($sqlF,"mailAgent",$mail,function($el) use ($mdp){
    //  EasyFrameWork::Debug($el);
      if($el->mdpAgent!=="")
        return $el->mdpAgent===$mdp;
       return -1;
    });
    return $r;
}
public function getLicences($sqlF){
  return LicenceExceptionEntity::getLicenceExceptionBy($sqlF,"agent",$this->idAgent);
}
public static function getAll($sqlF) {
  $arr = AgentTbl::getAll($sqlF);

  if (is_array($arr) || is_object($arr)) {
      $return = is_array($arr) ? $arr : [$arr];
      $i = 0;

      return array_reduce($return, function($c, $e) use (&$i, $sqlF) {
        //  EasyFrameWork::Debug($e, false);
          $c[$i] = Main::fixObject($e, 'SQLEntities\\AgentEntity');

          if (!empty($e->service)) {
              $c[$i]["serviceEntity"] = ServiceEntity::getServiceBy($sqlF, "idService", $e->service);
          }
          $i++;
          return $c;
      }, []);
  }

  return false;
}

public static function getAgentTblBy($sqlF, $key, $value, $filter = null)
{
  $arr = AgentTbl::getAgentTblBy($sqlF, $key, $value, $filter);

  if (is_array($arr) || is_object($arr)) {
      $return = is_array($arr) ? $arr : [$arr];
      $i = 0;

      return array_reduce($return, function($c, $e) use (&$i, $sqlF) {
      //    EasyFrameWork::Debug($e, false);
          $c[$i] = Main::fixObject($e, 'SQLEntities\\AgentEntity');

          if (!empty($e->service)) {
              $c[$i]["serviceEntity"] = ServiceEntity::getServiceBy($sqlF, "idService", $e->service);
          }
          $i++;
          return $c;
      }, []);
  }

  return false;
}

}