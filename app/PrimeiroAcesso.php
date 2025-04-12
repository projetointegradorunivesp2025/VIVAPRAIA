<?php
/**
 * app/PrimeiroAcesso.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */


class PrimeiroAcesso 
{
    
    //variaveis de escopo global da classe
    
    public function __construct() 
    {
        
    }

    public function carregaCasulo() 
    {
        ?>

        <!DOCTYPE html>
        <html lang="pt-br">
            <head>
                <meta charset="UTF-8">                
                <meta name="mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="application-name" content="Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget">
                <meta name="apple-mobile-web-app-title" content="Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget">
                <meta name="theme-color" content="#1e7145">
                <meta name="msapplication-navbutton-color" content="#00CED1">
                <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
                <meta name="msapplication-starturl" content="/">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"> 
                <title>Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget</title>
                <link rel="icon" href="/projetos/UNIVESP-PI3/app/public/favicon.ico?v=<?php echo time();?>">    
                <meta name="description" content="">
                <meta name="author" content="">    
                <link rel="stylesheet" href="/projetos/UNIVESP-PI3/app/public/app.css?v=<?php echo time();?>">
                <link rel="stylesheet" href="/projetos/UNIVESP-PI3/app/public/w3v5.css?v=<?php echo time();?>">  
                <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">                
                <script src="https://kit.fontawesome.com/eb5d2e7ab3.js" crossorigin="anonymous"></script>        
            </head>

            <body> 

                <nav class="w3-sidebar w3-bar-block w3-collapse w3-animate-left w3-card" style="z-index:3; width:300px; padding-bottom:50px;" id="mySidebar">
                    <img src="/projetos/UNIVESP-PI3/app/public/logo.png" alt="Avatar" style="width:100%;" onclick="w3_close()"> 
                    <img src="/projetos/UNIVESP-PI3/app/public/logo_univesp.jpg" alt="univesp" style="width:100%;" >
                    <br>                    
                    <button class='w3-bar-item w3-button link_menu w3-metro-blue' href='?router=home'><i class="fas fa-home"></i> Home </button>
                    <button class='w3-bar-item w3-button link_menu' href='?router=mapa'><i class="fas fa-map-marked-alt"></i> Mapa</button>
                    <button class='w3-bar-item w3-button link_menu' href='?router=widget'><i class="fas fa-cubes"></i> Widget</button>
                    <button class='w3-bar-item w3-button link_menu' href='?router=login'><i class="fas fa-sign-in-alt"></i> Login</button>
                </nav>

                <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

                <div id="main" class="w3-main" style="margin-left:300px; ">

                    <header class="w3-container w3-top w3-text-white w3-metro-blue w3-card-4" style="border-bottom: 2px solid black;">                                        
                        <p class="w3-large" style="font-weight: bold; ">
                            <i class="fa fa-bars w3-button w3-metro-blue w3-hide-large w3-xlarge" onclick="w3_open()"></i>            
                            <span class="w3-hide-small w3-hide-medium">Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget</span>  
                            <span class="w3-hide-large">VIVA PRAIA WIDGET</span>
                        </p>      
                    </header>

                    <div class="w3-container" name="thread_conteudo" id="thread_conteudo" style="padding-top: 90px; padding-bottom: 100px;">                        
                    </div>        

                    <footer class="w3-container w3-bottom w3-text-white w3-metro-blue w3-card-4 " style="border-top: 2px solid black;">
                        <p class="fontSizeScreen" style="padding: 2px; margin: 2px;">
                            UNIVESP - PROJETO INTEGRADOR (2025)                     
                        </p>
                    </footer>        

                </div>

                <div id="id01" class="w3-modal" onclick="this.style.display='none'">
                    <div class="w3-modal-content w3-card-4 w3-round-large">
                        <div class="w3-container w3-center">
                            <h2 id="mensagem_modal"></h2>
                        </div>
                        <footer class="w3-container w3-center">                    
                            <p><button class="w3-button w3-blue w3-round-large" onclick="document.getElementById('id01').style.display='none'"> OK </button></p>
                        </footer>
                    </div>
                </div>  

                <!-- color:#1e7145; -->
                <div id="aguarde" class="w3-modal" >
                    <div class="w3-modal-content w3-round-xxlarge w3-center" style="background: transparent; color:white;  font-weight: bold; margin-top: 100px; padding-top: 15px; padding-bottom: 15px; ">
                        <h1>    
                            Loading...
                            <i class="fa fa-hourglass w3-spin"></i>                    
                        </h1>    
                    </div>
                </div>  

            </body>

            <script>



            function w3_open() 
            {
                document.getElementById("mySidebar").style.display = "block";
                document.getElementById("myOverlay").style.display = "block";
            }



            function w3_close() 
            {
                document.getElementById("mySidebar").style.display = "none";
                document.getElementById("myOverlay").style.display = "none";

            }



            function ajax_get(param) 
            {

                //reset_scroll
                //window.scrollTo(0,0); 

                //document.getElementById('aguarde').style.display='block';

                var xmlHttp;

                if (param.length === 0) 
                {             
                    //fechando Aguarde...
                    //document.getElementById('aguarde').style.display='none';

                    document.getElementById('id01').style.display='block';
                    mensagem = "Faltam dados para iniciar a comunicação com o servidor!";
                    document.getElementById('mensagem_modal').innerHTML = mensagem;
                    return;
                }

                //aqui vou setar o history
                //window.history.pushState('page2', 'Title', '/page2.');
                //window.history.pushState('', '', param); //desativado ate termos os espacos definidos

                xmlHttp = new XMLHttpRequest();
                xmlHttp.open("GET", param, true);
                xmlHttp.send();                   
                xmlHttp.onreadystatechange = function() 
                {
                    if (this.readyState === 4 ) 
                    {
                        if (this.status === 200) 
                        {
                            //document.getElementById('thread_conteudo').innerHTML = xhttp.responseText;

                            //document.getElementById('thread_conteudo').textContent = '';
                            document.getElementById('thread_conteudo').innerHTML = '';                    
                            range = document.createRange();
                            var documentFragment = range.createContextualFragment(xmlHttp.responseText);
                            document.getElementById('thread_conteudo').appendChild(documentFragment);

                            //fechando Aguarde...
                            //document.getElementById('aguarde').style.display='none';

                        }
                        else
                        {
                            mensagem = "Você está off-line.";
                            document.getElementById('mensagem_modal').innerHTML = mensagem;

                            document.getElementById('id01').style.display='block';

                            //fechando Aguarde...
                            //document.getElementById('aguarde').style.display='none';

                        }
                    }

                };

            }



            function ajax_post(url,idForm)
            {
                //reset_scroll
                //window.scrollTo(0,0); 

                //document.getElementById('aguarde').style.display='block';

                var form = document.getElementById(idForm);
                var formData = new FormData(form); 

                var xmlHttp = new XMLHttpRequest();
                    xmlHttp.onreadystatechange = function()
                    {
                        if(xmlHttp.readyState === 4 )
                        {
                            if (xmlHttp.status === 200)
                            {                                                                    
                                //document.getElementById('thread_conteudo').textContent = '';
                                document.getElementById('thread_conteudo').innerHTML = '';
                                range = document.createRange();
                                var documentFragment = range.createContextualFragment(xmlHttp.responseText);
                                document.getElementById('thread_conteudo').appendChild(documentFragment);
                                //console.log(xmlHttp.responseText);

                                //fechando Aguarde...
                                //document.getElementById('aguarde').style.display='none';

                            }                        
                            else
                            {
                                mensagem = "Você está off-line.";
                                document.getElementById('mensagem_modal').innerHTML = mensagem;

                                document.getElementById('id01').style.display='block';

                                //fechando Aguarde...
                                //document.getElementById('aguarde').style.display='none';
                            }

                        }                        
                    }
                    xmlHttp.open("post", url); 
                    xmlHttp.send(formData); 
            }



            function modal_message(texto)
            {
                document.getElementById('mensagem_modal').innerHTML = texto;
                document.getElementById('id01').style.display='block';                        
            }



            var header = document.getElementById("mySidebar");
            var btns = header.getElementsByClassName("w3-button");
            for (var i = 0; i < btns.length; i++) 
            {
                btns[i].addEventListener("click", function() {

                    var current = header.getElementsByClassName("w3-metro-blue");
                    if (current.length > 0) 
                    { 
                        current[0].className = current[0].className.replace(" w3-metro-blue", "");
                    }

                    this.className += " w3-metro-blue";

                    if(this.className.indexOf("link_menu") !== -1)
                    {                                                               
                        w3_close();                                                        
                        ajax_get(this.getAttribute("href"));                                
                    }

                });
            }


            //qdo ocorrer o primeiro carregamento devo chamar o conteúdo 
            ajax_get('?router=home');

            </script>    
        </html>


        <?php         
    }
    
}
   