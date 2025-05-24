<?php
/**
 * app/RemoteWidget.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */



require_once("app/frameWork/Database.php");

class RemoteWidget 
{
    
    //variaveis de escopo global da classe
    
    public function __construct() 
    {              
        //testando
        //echo $_SERVER['REMOTE_HOST'];
        //echo $_SERVER['HTTP_REFERER'];
        //echo "<script>document.write(window.location.host)</script>";
        
        $this->view();                                                    
    }
    
    
    
    
    public function view() 
    {       
        $array_status = [];
        $array_bandeira = [];
        $array_cidade = [];
        $array_praia = [];
        $array_praia_id = [];
                
        $db = new Database();
        
        //aqui vou converter  a key do remoteWidget         
        $remoteWidget_original = base64_decode($_REQUEST['remoteWidget']);
        

        if( !is_numeric($remoteWidget_original) ) 
        {
            $remoteWidget_original=0;
        }
        
        //aqui tenho que consultar o banco de dados para adicionar na variavel 
        $selectDB = $db->select("SELECT meuswidgets.id, meuswidgets.praia_id, meuswidgets.localpermitido, meuswidgets.localpermitido_status, p.cidade_cetesb, p.praia_cetesb, (SELECT balneabilidade_status FROM balneabilidades WHERE praia_id=p.id ORDER BY balneabilidade_data DESC LIMIT 0,1) AS balneabilidade_status FROM meuswidgets INNER JOIN praias p ON(meuswidgets.praia_id=p.id) WHERE meuswidgets.id = $remoteWidget_original");
        if( $selectDB ) 
        {
            //aqui vou checar se o valor do HTTP_REFERER vindo do cliente é igual ao valor do campo previamente cadastrado localpermitido
            
            if( ($_SERVER['HTTP_REFERER'] === $selectDB[0]['localpermitido']) ||  $selectDB[0]['localpermitido']=='' ) 
            {
                //tratando os dados vindos do BD
                if ( $selectDB[0]['balneabilidade_status'] == 'pverde.gif' ) 
                { 
                    $array_status[]='Própria para banho'; 
                    $array_bandeira[]='app/public/icone_verde52.png'; } 
                else 
                { 
                    $array_status[]='Imprópria para banho'; 
                    $array_bandeira[]='app/public/icone_vermelho52.png';                     
                }

                $array_cidade[] = htmlspecialchars($selectDB[0]['cidade_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia[] = htmlspecialchars($selectDB[0]['praia_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia_id[] = $selectDB[0]['praia_id'];
                $array_localpermitido[] = $selectDB[0]['localpermitido'];
                $array_localpermitido_status[] = $selectDB[0]['localpermitido_status'];
                $array_meuswidgets_id[] = $selectDB[0]['id']; 



                if( $array_status[0]==='Própria para banho' )
                {
                    $corBorda = "green";
                    $corImagem = "verde";                
                }
                else
                {
                    $corBorda = "red";
                    $corImagem = "vermelho";                
                }



                ?>
                <!DOCTYPE html>
                <html lang="pt-br">
                    <head>
                        <meta charset="UTF-8">                
                        <meta name="mobile-web-app-capable" content="yes">
                        <meta name="apple-mobile-web-app-capable" content="yes">
                        <meta name="application-name" content="Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget">
                        <meta name="apple-mobile-web-app-title" content="Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget">
                        <meta name="theme-color" content="#2D89EF">
                        <meta name="msapplication-navbutton-color" content="#00CED1">
                        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
                        <meta name="msapplication-starturl" content="/">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"> 
                        <title>Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget</title>
                        <link rel="icon" href="/projetos/UNIVESP-PI3/app/public/favicon.ico?v=<?php echo time();?>">    
                        <meta name="description" content="">
                        <meta name="author" content="">    
                        <link rel="stylesheet" href="app/public/app.css?v=<?php echo time();?>">
                        <link rel="stylesheet" href="app/public/w3v5.css?v=<?php echo time();?>">  
                        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">                
                        <script src="https://kit.fontawesome.com/eb5d2e7ab3.js" crossorigin="anonymous"></script>  
                        
                        <style>
                          .linha {
                            height: 4px;               /* espessura */
                            background-color: <?php echo $corBorda;?>; /* cor azul */
                            border: none;              /* remove borda padrão */
                            margin: 2px auto;         /* centraliza e adiciona espaçamento */
                          }

                          .linha1 { width: 100px; }
                          .linha2 { width: 60px; }
                          .linha3 { width: 20px; }
                        </style>   
                        
                    </head>

                    <body> 

                        <div style='display: grid;  place-items: center; margin: 20px;'>                            
                            <div style='border: solid <?php echo $corBorda;?> 5px; width: 200px;  border-radius: 25px; padding: 10px; text-align: center; background-color: white;'>
                                <img src='<?php echo $array_bandeira[0]; ?>' class='w3-bar-item w3-circle'>
                                <img src='app/public/cetesb_ico.jpg'>
                                <h4><?php echo $array_cidade[0]; ?></h4> 
                                <span style='font-size:13px;'>Praia:</span>
                                <br>
                                <h6 style='margin:0px;'> <?php echo $array_praia[0]; ?> </h6>
                                <span style='font-size:10px;'> <?php echo $array_status[0]; ?> </span>
                            </div>
                                <hr class="linha linha1">
                                <hr class="linha linha2">
                                <hr class="linha linha3">
                            
                                                                                    
                        </div>     

                    </body>

                </html>

                <?php                
            }
            else
            {
                echo "Acesso negado a este widget! :(";
            }

                                                                                               
        }
        else 
        {
            echo "Não encontrei este Widget. :(";
        }

        
    }
    
    
    function checkLocalPermitido($localpermitido)
    {
        //verificar se é ipv4, ipv6 ou hostname        
        if(filter_var($localpermitido, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) 
        {
            return("IPv4");
        } 
        else
        {
            if(filter_var($localpermitido, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) 
            {
                return("IPv6");
            } 
            else 
            {
                return("Inválido");
            } 
        }       
    }
    
    
}

