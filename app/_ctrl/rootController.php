<?php
namespace vendor\easyFrameWork\Core\Master\Controller;

use DateTime;
use vendor\easyFrameWork\Core\Master\Controller;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
use vendor\easyFrameWork\Core\Master\ResourceManager;
use vendor\easyFrameWork\Core\Master\EasyTemplate;
use vendor\easyFrameWork\Core\Master\Cryptographer;
use vendor\easyFrameWork\Core\Master\EnvParser;
use vendor\easyFrameWork\Core\Master\SessionManager;
use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use SQLEntities\AgentEntity;
use SQLEntities\AgentTbl;
use vendor\easyFrameWork\Core\Master\SQLFactory;
class rootController extends Controller{
    private array $cmd;
    private SQLFactory $sQLFactory;
    public function __construct(){
        parent::__construct();
        
        $this->sQLFactory=new SQLFactory();
        $this->cmd=[
            "updatePassword"=>function($template){
                $mail=$_POST["mailchecker"];
                $mdp=$_POST["pwdChecker"];
                $crypto=new Cryptographer;
                $env=new EnvParser(EasyFrameWork::$Racines["dirAccess"]."/.env");
                $user=AgentEntity::getAgentTblBy($this->sQLFactory,"mailAgent",$mail);
                if($user==false){
                    $this->setData("MainContent","<div class=\"alert alert-danger\" role=\"alert\">
                    Mail inconnu !!
                  </div>");
                      exit;
                }
                $user[0]->mdpAgent=$crypto->hashString($mdp,$env->get("KEY"),Cryptographer::HASH_ALGO["MD2"]);
                $arrive = date("Y-m-d");
                $date = new DateTime($arrive);
                // Ajout de 6 mois
        $date->modify('+1 months');
        
        // Récupération de la date modifiée
        $valid = $date->format('Y-m-d');
                $user[0]->validiteMdp=$valid;
                AgentEntity::update($this->sQLFactory,$user[0]);
                Main::redirectWithAlert($template,"Votre mot de passe a été mis à jour","./index.php");
            },
            "firstConnexion"=>function($template){
                $template->remplaceTemplate("MainContent","validMdp.tpl");
            },
            "deconnexion"=>function($template){
                $sessionManager=EasyGlobal::createSessionManager();
                if($sessionManager->sessionExist())
                        $sessionManager->clean();
                    Main::redirectWithAlert($template,"Vous avez été déconnecté <br>Au revoir","index.php");
            },
            "connexion"=>function($template){
                $sessionManager=EasyGlobal::createSessionManager();
             //   EasyFrameWork::Debug($_POST);
                if(!isset($_POST["mailConnexion"])){
                    $this->setData("MainContent","<div class=\"alert alert-danger\" role=\"alert\">
  Mail parameter is missing !!
</div>
");
                    exit;
                }
                if(!isset($_POST["mdpConnexion"])){
                    $this->setData("MainContent","<div class=\"alert alert-danger\" role=\"alert\">
  Mail parameter is missing !!
</div>
");
                    exit;
                }
                $mail=$_POST["mailConnexion"];
                $mdp=$_POST["mdpConnexion"];
                $crypto=new Cryptographer;
                $env=new EnvParser(EasyFrameWork::$Racines["dirAccess"]."/.env");
                $user=AgentEntity::connexion($this->sQLFactory,$mail,$crypto->hashString($mdp,$env->get("KEY"),Cryptographer::HASH_ALGO["MD2"]));
             //  var_dump(gettype($user));
               if($user==false){
                 Main::redirectWithAlert($template,"Votre mot de passe est incorrect","./index.php");
               }else
                if($user==-1){
                Main::redirectWithAlert($template,"Vous n'avez pas encore de mot de passe !","./index.php");
               }else{
                $serv=$user[0]->getService(new SQLFactory());
             //   EasyFrameWork::Debug($user);
                if($user[0]->blockAgent!=""){
                    Main::redirectWithAlert($template,"Votre compte a été bloqué, veuillez vous mettre en rapport avec votre responsable","./index.php");
                }elseif($serv!=false && $serv->isActif=="0"){
 Main::redirectWithAlert($template,"Votre service a été suspendu, veuillez vous mettre en rapport avec votre responsable","./index.php");
                }else{
                $sessionManager->set("user",$user,SessionManager::PUBLIC_CONTEXT);
                Main::redirectWithAlert($template,"Bienvenue","./index.php");
               }
            }
            }
        ];
    }
    public function handleRequest(){
        $config=parse_ini_file("include/config.ini",true)["localhost"];
        $template = new EasyTemplate($config,new ResourceManager());
        if(isset($_GET["root"])){
           if(array_key_exists($_GET['root'],$this->cmd)){
                $this->cmd[$_GET["root"]]($template);
           }else{
            EasyFrameWork::Debug($_GET);
           }
        }
        $template->setVariables($this->getData());
        // Rendre le template
        $template->render();
    }
}