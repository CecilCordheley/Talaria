<?php
namespace vendor\easyFrameWork\Core\Master\Controller;
use vendor\easyFrameWork\Core\Master\Controller;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
use vendor\easyFrameWork\Core\Master\ResourceManager;
use vendor\easyFrameWork\Core\Master\EasyTemplate;
use vendor\easyFrameWork\Core\Master\SessionManager;
use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use SQLEntities\AgentEntity;
use vendor\easyFrameWork\Core\Master\SQLFactory;

class userController extends Controller{
    private $user;
    private $isConnect;
    public function __construct(){
        parent::__construct();
        $sessionManager=EasyGlobal::createSessionManager();
        //EasyFrameWork::Debug($_SESSION);
      //  EasyFrameWork::Debug(AgentTbl::getAll(new SQLFactory()));
        $this->isConnect=(($sessionManager->get("user",SessionManager::PUBLIC_CONTEXT))!=null)?1:0;
            $this->setData("_isConnect",strval($this->isConnect));
        if($this->isConnect=="1"){
            $u=Main::fixObject($sessionManager->get("user",SessionManager::PUBLIC_CONTEXT),"SQLEntities\AgentEntity");
            $this->user=$u;

            $this->setData("user",$u->getArray());
        }
    }
    public function handleRequest(){
        $sessionManager=EasyGlobal::createSessionManager();
        $config=parse_ini_file("include/config.ini",true)["localhost"];
        $template = new EasyTemplate($config,new ResourceManager());

        $template->remplaceTemplate("MainContent","userManagement.tpl");
        $menu=[
            "1"=>[
                ["label"=>"Nouveau Ticket","action"=>"newTicket","href"=>"#"]
            ],
            "4"=>[
                ["label"=>"Gestion des utilisateurs","action"=>" ","href"=>"userManager"],
                ["label"=>"Gestion des services","action"=>" ","href"=>"serviceManager"],
                ["label"=>"Gestion de l'application","action"=>" ","href"=>"appManager"]
            ]
            ];
        $template->setLoop("Menu",$menu[$this->user->typeAgent]);
        $template->setVariables($this->getData());
        // Rendre le template
        $sqlfactory=new SQLFactory();
        $template->render([], $sqlfactory->getPdo());
    }
}