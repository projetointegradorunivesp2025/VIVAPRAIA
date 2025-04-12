<?php
/**
 * app/Home.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */


class Home 
{
    
    //variaveis de escopo global da classe
    
    public function __construct() 
    {
        
    }
    
    public function view() 
    { 
        ?>
            <div class="w3-container">
                <div class="w3-container">
                    <div class="w3-card-4 w3-center" style="width:100%;max-width:400px">
                        <h2>Projeto Integrador</h2>
                        <img src="/projetos/UNIVESP-PI3/app/public/logo_univesp.jpg" alt="Alps" style="width:100%">
                    </div>
                </div>
                <div class="w3-container w3-justify">
                    <p>
                    <br>
            Este é o nosso WebApp que faz parte do Projeto Integrador da UNIVESP e surgiu a partir de uma necessidade real identificada em uma hospedaria parceira.

            No início do ano, um surto de virose foi amplamente divulgado pelas mídias, gerando preocupação entre turistas. No entanto, nem todas as praias estavam contaminadas. A falta de um meio rápido e confiável para verificar a balneabilidade levou ao cancelamento em massa de reservas, resultando em prejuízos para o setor de hospedagem e toda cadeia turística.

            Para tentar evitar esse tipo de problema, utilizamos os dados públicos da CETESB e desenvolvemos um webapp com um widget dinâmico, que pode ser integrado diretamente aos sites das hospedarias e outros negócios do setor. Com ele, visitantes podem verificar diretamente no site, do local que desejam se hospedar, se a praia mais próxima está própria ou imprópria para banho.


                    </p>
                </div>
            </div>


            <div class="w3-row-padding w3-margin-top">
              <div class="w3-half w3-margin-bottom w3-margin-top">
                <div class="w3-container">
                    <div class="w3-card-4 w3-center" style="width:100%;max-width:400px">
                        <header class="w3-container w3-blue">
                            <h1>Mapa</h1>
                        </header>
                        <img src="/projetos/UNIVESP-PI3/app/public/mapa.png" alt="Mapa">
                        <footer class="w3-container w3-blue">
                          <h5>Usamos a API do Maps Google</h5>
                        </footer>            
                    </div>
                </div>
              </div>

              <div class="w3-half w3-margin-bottom w3-margin-top">
                <div class="w3-container">
                    <div class="w3-card-4 w3-center" style="width:100%;max-width:400px">
                        <header class="w3-container w3-blue">
                            <h1>Widget</h1>
                        </header>            
                        <img src="/projetos/UNIVESP-PI3/app/public/widget.png" alt="Widget">
                        <footer class="w3-container w3-blue">
                          <h5>Para ser 'Incorporado' ao Site do Hotel</h5>
                        </footer>            

                    </div>
                </div>
              </div>

            </div>

            <div class="w3-container">
                 <div class="w3-container">
                    <br>
                    <p>
                    <b>Componentes do Grupo:</b><br><br>
                    Iracema Alves Mascarenhas da Silva<br>
                    Vinicius Costa Barbosa<br>
                    Vinicius de Oliveira Silva<br>
                    Marcela Dias Dos Santos<br>
                    Eduardo Garcia Da Silva<br>
                    Elienai Luiz Da Silva<br>
                    André Luiz Encinas<br>
                    Marcio Melo Da Conceição<br>   
                    </p>  
                </div>
            </div>

        <?php
        
    }
    
}






