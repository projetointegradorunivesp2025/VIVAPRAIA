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
        ?>
            <script>
                window.scrollTo(0, 0);
            </script>
        <?php        
        
    }
    
    public function view() 
    { 
        ?>

            <div style='display: grid;  place-items: center; margin: 20px;'>                
                <div class="w3-card-4 w3-center" style="width:100%;max-width:400px">
                    <h2>Projeto Integrador III</h2>
                    <img src="app/public/logo_univesp.jpg" alt="Logo Univesp" style="width:100%">
                </div>                                                                               
            </div>
            <div style='display: grid;  place-items: center; margin: 20px;'>   
                <h1>
                    VIVA PRAIA
                </h1> 
            </div>  

            <div class="w3-container w3-justify">
                <h2>Identificação do Grupo:</h2>
                <p><b>Orientadora:</b> Karina Cristina Chiari.</p>                    
                <p><b>Disciplina:</b> Projeto Integrador III - DRP02-PJI310-A2025S1-T001-GRUPO-004.</p>

                <p><b>Tema escolhido pelo grupo com base no tema norteador da Univesp:</b> Desenvolvimento de um webapp com framework CSS responsivo e Javascript, com banco de dados MySQL, consumindo API externa do Google Maps, pensando na acessibilidade,  hospedado em nuvem com controle de versão e integração contínua e testes.</p>


                <p><b>Título do Projeto:</b> VIVA PRAIA – Webapp para exibição da balneabilidade das praias de São Paulo, através de um widget, com base nas análises biológicas realizadas pela CETESB.</p>
                <p>
                <b>Componentes do Grupo:</b><br>
                André Luiz Encinas, RA 2107624.<br>
                Eduardo Garcia Da Silva, RA 1702178.<br>
                Elienai Luiz Da Silva, RA 2208826.<br>
                Iracema Alves Mascarenhas da Silva, RA 2203488.<br>
                Vinicius Costa Barbosa, RA 2106860.<br>
                Vinicius de Oliveira Silva, RA 2221543.<br>
                Marcela Dias Dos Santos, RA 2203745.<br>
                Marcio Melo Da Conceição, RA 2212158.<br>                    
                </p>  
                <p><b>Polo(s):</b> Polos de São Vicente Insular e Continental; Polo de São Sebastião; Polo de Guarujá e Polo de Pedro de Toledo.</p>
                <p><b>Curso(s):</b> Bacharelado em Tecnologia da Informação, Ciência de Dados e Engenharia da Computação.</p>
            </div>

            <hr>
            
            <div class="w3-container w3-justify">
                <h2>Justificativa e delimitação do problema:</h2>
                <p>  
                    No início do ano, um surto de virose foi amplamente divulgado pelas mídias, gerando preocupação entre turistas. No entanto, nem todas as praias estavam contaminadas. A falta de um meio rápido e confiável para verificar a balneabilidade levou ao cancelamento em massa de reservas, resultando em prejuízos para o setor de hospedagem e toda cadeia turística.                                                
                </p>
                <p>  
                    Esse acontecimento, somado ao fato observado de que muitos sites de pousadas já utilizam widgets de "Previsão do Tempo", nos levou a formular a seguinte pergunta: “A utilização de um widget incorporável em sites de hospedagem pode facilitar o acesso dos turistas a informações atualizadas sobre a balneabilidade das praias paulistas?”. Essa questão acabou direcionando o desenvolvimento da solução proposta.
                </p>                                        
                <p>
                    O projeto foi desenvolvido em parceria com uma pousada localizada em São Vicente, onde foi possível observar, por meio de entrevistas e questionários, a ausência de informações acessíveis e atualizadas sobre a qualidade da água disponibilizadas aos hóspedes. A hospedaria serviu como ambiente real para aplicação, avaliação e validação do widget proposto, evidenciando sua viabilidade e relevância no contexto turístico local.
                </p>  
            </div>
            
            <hr>
            
            <div class="w3-container w3-justify">
                <h2>Objetivo:</h2>
                <p>
                    Utilizando dados públicos da CETESB, desenvolvemos um webapp com um widget dinâmico, com tecnologias como PHP, JavaScript vanilla, frameworks CSS e banco de dados MySQL. A aplicação utiliza a integração à API do Google Maps, foi versionada por meio do GitHub com suporte a integração contínua (CI) e testes, além de estar hospedada em ambiente de nuvem e ter sido implementada seguindo boas práticas de acessibilidade.
                </p>
                <p>
                    O sistema disponibiliza um widget customizável, permitindo que estabelecimentos cadastrados, como hotéis e pousadas, integrem em seus sites um componente visual que exiba a qualidade da água das praias.
                </p>    
            </div>            

            <div class="w3-row">

                <div class="w3-container w3-half ">
                    <div class="w3-card-4 w3-center w3-padding" >
                        <header class="w3-container w3-blue">
                            <h1>Mapa</h1>
                        </header>
                        <img src="app/public/mapa.png" alt="Mapa" class="w3-image">
                        <footer class="w3-container w3-blue">
                          <h5>Usamos a API do Maps Google</h5>
                        </footer>            
                    </div>

                </div>

                <div class="w3-container w3-half ">         
                    <div class="w3-card-4 w3-center w3-padding" >
                        <header class="w3-container w3-blue">
                            <h1>Widget</h1>
                        </header>                        
                        <img src="app/public/widget.png?1" alt="Widget" class='w3-image w3-margin'>
                        <footer class="w3-container w3-blue">
                          <h5>Para ser 'Incorporado' ao Site do Hotel</h5>
                        </footer>            

                    </div>        
                </div>

            </div>                        
            <br>
            <hr>
            
            <div class="w3-container w3-justify w3-padding">
                <h2>Implementação:</h2>
                <p>  
                    Após a realização dos testes internos, foi realizada uma última visita à hospedaria, parceira do projeto, com o objetivo de conduzir testes de usabilidade com a proprietária, que já havia colaborado na fase de coleta de dados. Durante um período determinado de uso do aplicativo, foi possível coletar feedbacks relevantes quanto à facilidade de navegação, clareza das informações e eficiência das funcionalidades. A proprietária destacou a necessidade de aumentar o tamanho do ícone da bandeira de sinalização da balneabilidade, bem como a inclusão do logotipo da CETESB, a fim de conferir maior credibilidade às informações apresentadas. As sugestões foram prontamente acatadas e as devidas correções implementadas.
                </p>
            </div>
            
                

        <?php
        
    }
    
}






