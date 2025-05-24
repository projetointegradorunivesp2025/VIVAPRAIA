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

require_once("app/frameWork/Database.php");

class Login 
{
    
    public function __construct() 
    {
        ?>
            <script>
                window.scrollTo(0, 0);
            </script>
        <?php
        
        //1.1 verificar se quer deslogar
        //1.1 verificar se esta logado        
        //1.2 mostrar o form solicitando o email do usuário
        //1.3 checar a validade do email, solicitando novamente caso não seja válido
        //2 gravar este email no banco de dados, caso não exista, se transformando em usuario
        //2.1 criar uma tabela com os PINs criados com id,usuario_id,pin,data_de_validade
        //2.2 verificar se para este usuario já existe algum PIN dentro da data de validade, até amanha
        //2.3 se não existir devo criar um PIN novo com data de validade de 24hs 
        //3 enviar para este email um token "Pin" com validade até amanha para que seja possivel o login 
        //4 mostrar a tela 2 solicitando que informe este Pin recebido
        //5 caso esteja certo mostro uma mensagem na Tela de que o usuário está logado do contrário peço o Pin novamente
        //6 altero o menu principal mostrando o LOGOUT no lugar do LOGIN adicionando o MENU "Meus Widgets" 
                 
        
        //verificando se quer deslogar e se sim esvaziando variaveis
        if(isset($_GET['logout']))
        {
            
            //trocando menu de logout para login
            ?>
            <script>
            document.getElementById("login").innerHTML = "<i class='fas fa-sign-in-alt'></i> Login";
            </script>                
            <?php
            
            
            $_SESSION["logado"] = "";
            $_SESSION["logado_usuario"]="";
            $this->view(); 
            exit();
        }
        
        
        //verificar se já está logado
        if( isset($_SESSION["logado"]) && $_SESSION["logado"] == 'sim' )
        {
            ?>
            <div class="w3-panel w3-gree">
                <h3>Você está Logado com o usuário: <?php echo $_SESSION["logado_usuario"];?></h3>
                <p>Deseja realmente sair ?</p>
                <button class="w3-btn w3-red" onclick="ajax_get('?router=login&logout');">Sair</button>
            </div>  
            <br>
            <?php
            exit();
        }
        
            
        
        
        
        if(isset($_POST['email'])) 
        {
            //vou checar se o email é valido
            $result_validarEmail = $this->validarEmail($_POST['email']);
            
            if( $result_validarEmail == "E-mail válido!" )
            {
                //email válido
                $email_valido = $_POST['email'];
                
                //USUARIO
                //consultar para ver se já existe este email na tabela usuarios
                //se não existir devo inserir
                $db = new Database();                
                $select_usuarios = $db->select("SELECT * FROM usuarios WHERE usuario = ? ", ["$email_valido"]);                
                if( count($select_usuarios)==0 ) 
                {
                    //como não existe devo inserir o usuario                    
                    $execute = $db->execute("INSERT INTO usuarios (usuario) VALUES (?)", ["$email_valido"]);  
                                        
                    if( $db->rowCount() <= 0 ) 
                    {
                        //Nenhum registro foi inserido
                        //devo mostrar o erro a tela de inserção de email novamente
                        ?>
                        <div class="w3-panel w3-red">
                            <h3>Erro!</h3>
                            <p>Não conseguimos inserir seu email, tente novamente.</p>
                        </div>  
                        <br>
                        <?php
                        $this->view(); 
                        exit();
                    }
                    else
                    {
                        //capturando o id do usuario para usar na tabela PINs
                        $usuario_id = $db->lastInsertId();
                    }
                    
                }
                else
                {
                    $usuario_id = $select_usuarios[0]['id'];
                }
                      
                
                //PIN
                //verificar se para este usuario já existe algum PIN dentro da data de validade, MAIOR OU IGUAL A HOJE
                //se não existir, devo inserir um usuario + pin + data de validade como sendo amanha, independente das horas
                $dataDeHoje = date('Y-m-d');
                
                $db2 = new Database();                
                $select_pins = $db2->select("SELECT * FROM pins WHERE usuario_id = ? and data_de_validade > ? ", ["$usuario_id","$dataDeHoje"]);                
                if( count($select_pins)==0 ) 
                {
                    
                    $dataDeAmanha = date('Y-m-d', strtotime('+1 day')); 
                    $pin = strtotime($dataDeAmanha) + $usuario_id;                    
                    
                    //como não existe devo inserir o pin                    
                    $execute = $db2->execute("INSERT INTO pins (usuario_id,pin,data_de_validade) VALUES (?,?,?)", ["$usuario_id","$pin","$dataDeAmanha"]);  
                                        
                    if( $db2->rowCount() <= 0 ) 
                    {
                        //Nenhum registro foi inserido
                        //devo mostrar o erro a tela de inserção de email novamente
                        ?>
                        <div class="w3-panel w3-red">
                            <h3>Erro!</h3>
                            <p>Não conseguimos inserir o pin, tente novamente.</p>
                        </div>  
                        <br>
                        <?php
                        $this->view(); 
                        exit();
                    }
                    else
                    {
                        //capturando o id do pin
                        $pin_id = $db2->lastInsertId();
                    }
                    
                }
                else
                {                    
                    $pin_id = $select_pins[0]['id'];
                }
                
                
                //enviar por email o pin do pin_id gerado
                $result_email = $this->enviarEmailPin($pin_id);
                //$result_email = 'true'; //aqui para funcionar local e não enviar o email devo alternar
                
                if($result_email=="falha")
                {
                    ?>
                    <div class="w3-panel w3-red">
                        <h3>Erro!</h3>
                        <p>Falha no envio do email, tente novamente.</p>
                    </div>  
                    <br>
                    <?php
                    $this->view();                    
                }
                else
                {
                    ?>
                    <div class="w3-panel w3-green">
                        <h3>Sucesso!</h3>
                        <p>Email enviado, insira o PIN na tela abaixo.</p>
                    </div>  
                    <br>
                    <?php
                    $this->viewPin($usuario_id);                    
                    
                }
                               
            }
            else
            {
                ?>
                <div class="w3-panel w3-red">
                    <h3>Erro!</h3>
                    <p><?php echo $result_validarEmail; ?></p>
                </div>  
                <br>
                <?php
                $this->view();
            }
            
                        
            
        }
        else
        {
            if(isset($_POST['pin']))
            {
                //aqui devo tratar o pin
                $this->tratarPin($_POST['pin'],$_POST['usuario_id']);
            }
            else
            {
                //nao existe o PIN enviado para fazer o seu tratamento, mostro o view
                $this->view();                
            }
            
        }
       
    }
    
    
    public function tratarPin($pin,$usuario_id)
    {
        //5 caso esteja certo mostro uma mensagem na Tela de que o usuário está logado do contrário peço o Pin novamente
        //6 altero o menu principal mostrando o LOGOUT no lugar do LOGIN adicionando o MENU "Meus Widgets" 
        
        $dataDeHoje = date('Y-m-d');
        
        $db = new Database();                
        $select_usuario_pin = $db->select("SELECT usuarios.usuario, pins.data_de_validade FROM usuarios INNER JOIN pins ON(usuarios.id=pins.usuario_id) WHERE pins.pin=? and usuario_id=? and data_de_validade > ?", ["$pin","$usuario_id","$dataDeHoje"]);                
        if( count($select_usuario_pin)==1 ) 
        {
            //valido
            $_SESSION["logado"] = 'sim';
            $_SESSION["logado_usuario"] = $select_usuario_pin[0]['usuario'];
            $_SESSION["logado_usuario_id"] = $usuario_id;
            
            //trocando menu de login para logout
            ?>
            <script>
            document.getElementById("login").innerHTML = "<i class='fas fa-sign-out-alt'></i> Logout";
            document.getElementById("login").classList.remove("w3-metro-blue");           
            document.getElementById("widgetmenu").classList.add("w3-metro-blue");
            </script>                
            <?php
            
            //aqui devo enviar para o menu widget
            require_once("app/Widget.php");
            $Widget = new Widget();
            
            
            

        }
        else
        {
            ?>
            <div class="w3-panel w3-red">
                <h3>Erro!</h3>
                <p>PIN inválido, insira novamente.</p>
            </div>  
            <br>
            <?php
            $this->viewPin($usuario_id);
            
        }
        
        
        
    }
    
    
    public function enviarEmailPin($idPin)
    {
        //aqui vou consultar o idPin gerado para capturar e enviar por email o pin e a data de validade
        $db = new Database();                
        $select_usuario_pin = $db->select("SELECT usuarios.usuario, pins.pin, pins.data_de_validade FROM usuarios INNER JOIN pins ON(usuarios.id=pins.usuario_id) WHERE pins.id= ?", ["$idPin"]);                
        if( count($select_usuario_pin)==1 ) 
        {
            //enviar email
            $para = $select_usuario_pin[0]['usuario'];
            $assunto = "UNIVESP - PROJETO VIVAPRAIA 2025";
            $mensagem = "Olá! seu PIN é [".$select_usuario_pin[0]['pin']."] e tem validade até [".date("d/m/Y", strtotime($select_usuario_pin[0]['data_de_validade']))."].";

            // Cabeçalhos recomendados
            $cabecalhos = "MIME-Version: 1.0\r\n";
            $cabecalhos .= "Content-type: text/plain; charset=UTF-8\r\n";
            $cabecalhos .= "From: Grupo de alunos da UNIVESP <grupo@projetointegradorunivesp.com.br>\r\n";
            $cabecalhos .= "Bcc: andreencinas@gmail.com\r\n"; 
            $cabecalhos .= "Reply-To: grupo@projetointegradorunivesp.com.br\r\n";

            if (mail($para, $assunto, $mensagem, $cabecalhos)) 
            {
                return("sucesso");
            } 
            else 
            {
                return("falha");
            }
            
        }
        else
        {
            return("falha");
        }
        

    }
    
    
    public function view() 
    { 
                        
        ?>
        <div id="id02" class="w3-margin" >
          <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

            <div class="w3-center">        
              <img src="app/public/img_avatar4.png" alt="Avatar" style="width:30%" class="w3-circle w3-margin-top">
            </div>
            
            <form class="w3-container" name="form" id="form" action="" method="post" enctype="multipart/form-data">    
              <div class="w3-section">
                <label><b>Email</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" required placeholder="Informe seu email" name="email" id="email" maxLength="100">
                <input type="hidden" value="" name="token">
                
                <button class="w3-button w3-block w3-blue w3-section w3-padding" title="" type="submit" name="botaosubmit" onclick="ajax_post('?router=login','form'); return false;">Enviar</button>
                                                
                <p>Autenticação de Dois Fatores<br>(2FA - Two-Factor Authentication)</p>
              </div>
            </form>



          </div>
        </div>


        <?php        
    }
    

    public function viewPin($usuario_id) 
    { 
                        
        ?>
        <div id="id02" class="w3-margin" >
          <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

            <div class="w3-center">        
              <img src="app/public/img_avatar4.png" alt="Avatar" style="width:30%" class="w3-circle w3-margin-top">
            </div>
            
            <form class="w3-container" name="form" id="form" action="" method="post" enctype="multipart/form-data">    
              <div class="w3-section">
                <label><b>PIN</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" required placeholder="Informe o PIN que você recebeu em seu email" name="pin" id="pin" maxLength="50">
                <input type="hidden" value="<?php echo $usuario_id;?>" name="usuario_id" id="usuario_id">
                
                <button class="w3-button w3-block w3-blue w3-section w3-padding" title="" type="submit" name="botaosubmit" onclick="ajax_post('?router=login','form'); return false;">Enviar</button>
                                                
                <p>Autenticação de Dois Fatores<br>(2FA - Two-Factor Authentication)</p>
              </div>
            </form>

          </div>
        </div>


        <?php        
    }

    
    function validarEmail($email) 
    {
        //verificando a quantidade de caracteres, limitando em 100        
        $quantidade = mb_strlen($email, 'UTF-8');
        if( $quantidade>100 )
        {
            return "Limite de caracteres excedido.";   
        }
        
        // Verifica o formato do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            return "Formato de e-mail inválido.";
        }

        // Extrai o domínio do e-mail
        $dominio = substr(strrchr($email, "@"), 1);

        // Verifica se o domínio possui registro MX
        if (!checkdnsrr($dominio, "MX")) 
        {
            return "Domínio inválido ou não aceita e-mails.";
        }

        return "E-mail válido!";
    }    
    
}



    
    
    


           
    
    
   