<?php
/**
 * index.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */

 

//para fazer o strtoupper funcionar com acentos
    setlocale(LC_CTYPE,"pt_BR");

//seta timezone
    date_default_timezone_set('America/Sao_Paulo');
    
//inicializa a sessão
    session_save_path("tmp");
    session_cache_expire(30);
    ini_set('session.gc_maxlifetime', 600); ////600s = 10 minutos para session inativar   
    ini_set('default_charset','UTF-8');    
    session_start(); 
    
//error_reporting(0); //zero impede de mostrar os erros
 
    
//ROTEANDO somente olhando os parâmetros e não o path               
if( isset($_REQUEST['router']) ) { 
    //if existir router, vou verificar se $_SESSION["staticAppLoaded"] é yes ou no   
    if( isset($_SESSION["staticAppLoaded"]) && $_SESSION["staticAppLoaded"] == 'yes' )
    {
        $rota = $_REQUEST['router'];     
    }
    else 
    {
        //é o primeiro acesso
        $_SESSION["staticAppLoaded"] = 'no';
        $rota = 'primeiroAcesso';
    }
} else { 
    //if não existir router, eu zero a session, como se fosse o primeiro acesso
    $_SESSION["staticAppLoaded"] = 'no'; 
    $rota = 'primeiroAcesso';
}
 

switch ($rota) {
    case 'primeiroAcesso': 
        require_once("app/PrimeiroAcesso.php");
        $_SESSION["staticAppLoaded"] = 'yes';
        $PrimeiroAcesso = new PrimeiroAcesso();
        $PrimeiroAcesso->carregaCasulo();
        break;    
    
    case 'home':   
        require_once("app/Home.php");
        $Home = new Home();
        $Home->view();
        break;
    
    case 'mapa':
        require_once("app/Mapa.php");
        $Mapa = new Mapa();
        $Mapa->view();        
        break;
    
    case 'widget':
        require_once("app/Widget.php");
        break;
    
    case 'login':
        require_once("app/Login.php");
        $Login = new Login();
        $Login->view();
        break;
    
    default:        
        echo "404 Not Found :( ";              
              
}


       