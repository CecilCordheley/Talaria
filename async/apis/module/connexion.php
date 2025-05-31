<?php
 namespace apis\module\asyncModule;
 
 use SQLEntities\AgentEntity;
use vendor\easyFrameWork\Core\Master\Autoloader;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
 use vendor\easyFrameWork\Core\Master\SessionManager;
 use vendor\easyFrameWork\Core\Master\Cryptographer;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use vendor\easyFrameWork\Core\Master\EnvParser;
use vendor\easyFrameWork\Core\Master\SQLFactory;
//require_once "../../../SQLEntities/AgentEntity";
 class Connexion{
    private string $mail;
    private string $mdp;
    private SQLFactory $sqlFactory;
    public function __construct($mail,$mdp){
        $this->mail=$mail;
        $this->mdp=$mdp;
        $this->sqlFactory=new SQLFactory(null,"../include/config.ini");

    }
    public function handle(){
        $sessionManager=EasyGlobal::createSessionManager();
        $crypto=new Cryptographer;
        $env=new EnvParser(EasyFrameWork::$Racines["dirAccess"]."/.env");
        $mdp_=$crypto->hashString($this->mdp,$env->get("KEY"),Cryptographer::HASH_ALGO["MD2"]);
        $user=AgentEntity::connexion($this->sqlFactory,$this->mail,$mdp_);
              
        if(gettype($user)!="int"){
                $sessionManager->set("user",$user,SessionManager::PUBLIC_CONTEXT);
                return true;
               }else{
                return false;
               }
    }
 }