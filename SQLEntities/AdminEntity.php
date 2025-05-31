<?php
namespace SQLEntities;

use DateTime;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;

use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\AgentTbl;
use vendor\easyFrameWork\Core\Main;
use SQLEntities\ServiceEntity;
use SQLEntities\AgentEntity;
use SQLEntities\QuotaExceptionAdminEntity;
class AdminEntity extends AgentEntity{
    public function __construct(){
        self::__construct();
        $this->attr["quota"]="";
    }
    public static function createAdmin(SQLFactory $SQLF,string $nom,string $prenom,string $mail){
        $agent=new AgentEntity;
        $agent->NomAgent=$nom;
        $agent->PrenomAgent=$prenom;
        $agent->mailAgent=$mail;
        $agent->refAgent=strtoupper(substr($nom,0,3).substr($prenom,0,2));
        $agent->typeAgent=1;
        $agent->uuidAgent=uniqid();
        $arrive = date("Y-m-d");
        $date = new DateTime($arrive);
        // Ajout de 6 mois
$date->modify('+1 months');

// Récupération de la date modifiée
$valid = $date->format('Y-m-d');
        $agent->validiteMdp=$valid;
        return AgentEntity::add($SQLF,$agent);
    }
}