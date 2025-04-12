<?php
/**
 * app/Login.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */


class Login 
{
    //1 mostrar o form solicitando o email do usuário
    //2 gravar este email no banco de dados, caso não exista, se transformando em usuario
    //3 enviar para este email um token "Pin" com validade de 10 minutos para que seja possivel o login 
    //4 mostrar a tela 2 solicitando que informe este Pin recebido
    //5 caso esteja certo mostro uma mensagem na Tela de que o usuário está logado do contrário peço o Pin novamente
    //6 altero o menu principal mostrando o LOGOUT no lugar do LOGIN adicionando o MENU "Meus Widgets" 
    
    public function __construct() 
    {
       
    }
    
    public function view() 
    { 
        ?>
        <div id="id02" class="w3-margin" >
          <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

            <div class="w3-center">        
              <img src="/projetos/UNIVESP-PI3/app/public/img_avatar4.png" alt="Avatar" style="width:30%" class="w3-circle w3-margin-top">
            </div>

            <form class="w3-container" action="/action_page.php">
              <div class="w3-section">
                <label><b>Email</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Informe seu email" name="usrname" required>

                <button class="w3-button w3-block w3-blue w3-section w3-padding" type="submit">Enviar</button>
                <p>Autenticação de Dois Fatores<br>(2FA - Two-Factor Authentication)</p>
              </div>
            </form>



          </div>
        </div>


        <?php        
    }
    
}



    
    
    


           
    
    
   