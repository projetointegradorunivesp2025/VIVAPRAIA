<?php
/**
 * app/widget.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */



require_once("app/frameWork/Database.php");

class Widget 
{
    
    //variaveis de escopo global da classe
    
    public function __construct() 
    {        
        ?>
            <script>
                window.scrollTo(0, 0);
            </script>
        <?php
        
        //verificar se já está logado
        if( isset($_SESSION["logado"]) && $_SESSION["logado"] == 'sim' )
        {            
            //aqui vou verificar se existe a variavel post "localpermitido"
            //caso exista significa que devo inserir na base "meuswidgets"
            if(isset($_GET['inserir'])) 
            {                
                $this->inserirWidget($_POST['localpermitido'],$_REQUEST['praia_id']);
            }
            
            //aqui vou verificar se é para excluir
            if(isset($_GET['excluir'])) 
            {                                
                $this->excluirWidget($_REQUEST['praia_id']);
            }
            
            
            $this->viewLogado();            
        }
        else
        {
            //não está logado
            $this->view();            
        }
                                        
    }
    
    
    public function excluirWidget($praia_id)
    {
        $usuario_id = $_SESSION["logado_usuario_id"];

        $db = new Database();    
        //deletar                     
        $execute = $db->execute("DELETE FROM meuswidgets WHERE praia_id = ? and usuario_id = ?;", [$praia_id,$usuario_id]);  

        if( $db->rowCount() <= 0 ) 
        {
            //Nenhum registro foi inserido
            //devo mostrar o erro a tela de inserção de email novamente
            ?>
            <div class="w3-panel w3-red w3-animate-fading" id="snackbar">
                <h3>Erro!</h3>
                <p>Não conseguimos excluir seu Widget, tente novamente.</p>
            </div>  
            <script>
                mySnackbar();
            </script>

            <?php
        }
        else
        {
            ?>
            <div class="w3-panel w3-green w3-animate-fading" id="snackbar">
                <h3>Sucesso!</h3>
                <p>Seu Widget foi excluído!</p>
            </div>  
            <script>
                mySnackbar();
            </script>
            <?php                

        }

            
        
        
    }
    
    
    public function myWidgetsView() 
    {       
        $usuario_id = $_SESSION["logado_usuario_id"];           
        $array_status = [];
        $array_bandeira = [];
        $array_cidade = [];
        $array_praia = [];
        $array_praia_id = [];
                
        $db = new Database();

        //aqui tenho que consultar o banco de dados para adicionar na variavel 
        $selectDB = $db->select("SELECT meuswidgets.id, meuswidgets.praia_id, meuswidgets.localpermitido, meuswidgets.localpermitido_status, p.cidade_cetesb, p.praia_cetesb, (SELECT balneabilidade_status FROM balneabilidades WHERE praia_id=p.id ORDER BY balneabilidade_data DESC LIMIT 0,1) AS balneabilidade_status FROM meuswidgets INNER JOIN praias p ON(meuswidgets.praia_id=p.id) WHERE meuswidgets.usuario_id = $usuario_id");
        if( $selectDB ) 
        {
            //se existir resultado devo criar o array contendo as linhas 

            for ($i = 0; $i < count($selectDB); $i++) 
            {

                //tratando os dados vindos do BD
                if ( $selectDB[$i]['balneabilidade_status'] == 'pverde.gif' ) 
                { 
                    $array_status[]='Própria para banho'; 
                    $array_bandeira[]='app/public/icone_verde52.png'; } 
                else 
                { 
                    $array_status[]='Imprópria para banho'; 
                    $array_bandeira[]='app/public/icone_vermelho52.png';                     
                }

                $array_cidade[] = htmlspecialchars($selectDB[$i]['cidade_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia[] = htmlspecialchars($selectDB[$i]['praia_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia_id[] = $selectDB[$i]['praia_id'];
                $array_localpermitido[] = $selectDB[$i]['localpermitido'];
                $array_localpermitido_status[] = $selectDB[$i]['localpermitido_status'];
                $array_meuswidgets_id[] = $selectDB[$i]['id']; 
            }        

        }

        foreach ($array_bandeira as $key => $content) 
        {
            if( $array_status[$key]==='Própria para banho' )
            {
                $corBorda = "green";
                $corImagem = "verde";                
            }
            else
            {
                $corBorda = "red";
                $corImagem = "vermelho";                
            }
            
            //aqui vou montar a key do router=widgetView
            $widgetView_key = base64_encode($array_meuswidgets_id[$key]);
            
            ?>
            <form class="w3-container" name="form<?php echo $key;?>" id="form<?php echo $key;?>" action="" method="post" enctype="multipart/form-data">    
            <div class='w3-row w3-margin'>
                <div class='w3-col l4 m4'>
                    <button class="w3-btn w3-red " title="" type="submit" name="botaosubmit" onclick="ajax_post('?router=widget&excluir&praia_id=<?php echo $array_praia_id[$key];?>','form<?php echo $key;?>'); return false;">Excluir</button>
                    <div style='display: grid;  place-items: center;'>                            
                        <div style='border: solid <?php echo $corBorda;?> 5px; width: 200px;  border-radius: 25px; padding: 10px; text-align: center;'>
                            <img src='<?php echo $content; ?>' class='w3-bar-item w3-circle'>
                            <img src='app/public/cetesb_ico.jpg'>
                            <h4><?php echo $array_cidade[$key]; ?></h4> 
                            <span style='font-size:13px;'>Praia:</span>
                            <br>
                            <h6 style='margin:0px;'> <?php echo $array_praia[$key]; ?> </h6>
                            <span style='font-size:10px;'> <?php echo $array_status[$key]; ?> </span>
                        </div>
                        <img src='app/public/rodape_widget_<?php echo $corImagem;?>.jpg' class='w3-bar-item'>                                                        
                    </div>                         
                </div>
                <div class='w3-col l8 ' >                    
                                         
                    <?php 
                        if( $array_localpermitido_status[$key] == "Acesso irrestrito." )
                        {                                                        
                            echo "<p style='color:red'>Cuidado: Seu Widget pode ser acessado de qualquer lugar!</p>";
                        }   
                        else
                        {
                            echo "<p style='color:blue'>Acesso somente da URL: {$array_localpermitido[$key]}</p> ";
                        }                         
                    ?>                         
                    
                    <h5>Código para a incorporação em seu site.</h5>
                    <textarea rows='5' class='w3-input w3-border' readonly>
<iframe src='https://www.projetointegradorunivesp.com.br/VIVAPRAIA/?remoteWidget=<?php echo $widgetView_key;?>' name='PI3' scrolling='no' width='240' height='290' frameborder='0' style='display: block; margin: 0 auto;'></iframe>
                    </textarea>
                </div>
            </div>  
            </form>  
            <hr style="border-width: 3px;">
            <?php
            
            
        }                                                                        
        
    }
    
    
    function checkLocalPermitido($localpermitido)
    {
        
        if( mb_strlen(trim($localpermitido))==0 )
        {
            return("Acesso irrestrito.");    
        }
        else
        {
            return("Acesso restrito."); 
        }
                
    }
    
    public function qntMeusWidgets()
    {
        $usuario_id = $_SESSION["logado_usuario_id"];

        //checar qtos já existem por usuario_id    
        $db = new Database();                
        $select_meuswidgets = $db->select("SELECT * FROM meuswidgets WHERE usuario_id = ?", ["$usuario_id"]);                
        return( count($select_meuswidgets) ); 
        
    }
    
    public function inserirWidget($localpermitido,$praia_id)
    {        
        //aqui devo inserir o widget do usuario com os campos:
        //id
        //usuario_id
        //praia_id
        //localpermitido
        //localpermitido_status (Inválido, Hostname, IPv4 ou IPv6)
        
        $localpermitido = trim($localpermitido);
        
        //tratando o localpermitido para criar o localpermitido_status
        $localpermitido_status = $this->checkLocalPermitido($localpermitido);
       
        //checar para ver quantos widgets ja foram inseridos por usuario
        $qntMeusWidgets = $this->qntMeusWidgets();
        
        if( $qntMeusWidgets<3 )
        {
            $usuario_id = $_SESSION["logado_usuario_id"];

            //checar se já existe usuario_id + praia_id        
            $db = new Database();                
            $select_meuswidgets = $db->select("SELECT * FROM meuswidgets WHERE usuario_id = ? and praia_id = ? ", ["$usuario_id","$praia_id"]);                
            if( count($select_meuswidgets)==0 ) 
            {

                //como não existe devo inserir                     
                $execute = $db->execute("INSERT INTO meuswidgets (usuario_id,praia_id,localpermitido,localpermitido_status) VALUES (?,?,?,?)", ["$usuario_id","$praia_id","$localpermitido","$localpermitido_status"]);  

                if( $db->rowCount() <= 0 ) 
                {
                    //Nenhum registro foi inserido
                    //devo mostrar o erro a tela de inserção de email novamente
                    ?>
                    <div class="w3-panel w3-red w3-animate-fading" id="snackbar">
                        <h3>Erro!</h3>
                        <p>Não conseguimos inserir seu Widget, tente novamente.</p>
                    </div>  
                    <script>
                        mySnackbar();
                    </script>
                    
                    <?php
                }
                else
                {
                    ?>
                    <div class="w3-panel w3-green w3-animate-fading" id="snackbar">
                        <h3>Sucesso!</h3>
                        <p>Seu Widget foi inserido! Confirme logo abaixo na seção "Meus Widget's".</p>
                    </div>  
                    <script>
                        mySnackbar();
                    </script>
                    <?php                

                }

            }
            else
            {    
                echo "já existe usuario_id + praia_id";            
            }
            
        }
        else
        {
            ?>
            <div class="w3-panel w3-red w3-animate-fading" id="snackbar">
                <h3>Erro!</h3>
                <p>Você já atingiu o limite de 3 widgets por usuário!</p>
            </div>   
            <script>
                mySnackbar();
            </script>
            <?php
            
        }
            
    }
    
    
    public function viewLogado() 
    {       
        $usuario_id = $_SESSION["logado_usuario_id"];           
        
        $db = new Database();

        //aqui tenho que consultar o banco de dados para adicionar na variavel 
        $selectDB = $db->select("SELECT *, (SELECT balneabilidade_status FROM balneabilidades WHERE praia_id=p.id ORDER BY balneabilidade_data DESC LIMIT 0,1) AS balneabilidade_status FROM praias p WHERE NOT EXISTS ( SELECT 1 FROM meuswidgets mw WHERE mw.praia_id = p.id and mw.usuario_id = {$usuario_id} );");
        if( $selectDB ) 
        {
            //se existir resultado devo criar o array contendo as linhas 

            for ($i = 0; $i < count($selectDB); $i++) 
            {

                //tratando os dados vindos do BD
                if ( $selectDB[$i]['balneabilidade_status'] == 'pverde.gif' ) 
                { 
                    $array_status[]='Própria para banho'; 
                    $array_bandeira[]='app/public/icone_verde52.png'; } 
                else 
                { 
                    $array_status[]='Imprópria para banho'; 
                    $array_bandeira[]='app/public/icone_vermelho52.png';                     
                }

                $array_cidade[] = htmlspecialchars($selectDB[$i]['cidade_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia[] = htmlspecialchars($selectDB[$i]['praia_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia_id[] = $selectDB[$i]['id'];
                
            }        

        }

        ?>
            <style>
                #resultados {
                    margin-top: 20px;
                    margin-bottom: 20px;
                    border: 1px solid #ccc;
                    padding: 10px;
                    max-height: 300px;
                    overflow-y: auto;
                }
                .item {
                    padding: 5px;
                    margin: 5px 0;
                    border-bottom: 1px solid #ddd;
                }
            </style>



            <script>
                
                // Função para filtrar os itens na div
                function filtrarItens() {
                    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
                    const itens = document.querySelectorAll('#resultados .item');

                    itens.forEach(item => {
                        // Obtém o texto de cada item
                        const itemTexto = item.textContent.toLowerCase();

                        // Verifica se o texto do item contém a string de pesquisa
                        if (itemTexto.includes(searchQuery)) {
                            item.style.display = 'block'; // Exibe o item
                        } else {
                            item.style.display = 'none'; // Oculta o item
                        }
                    });
                }

                // Evento de digitação no campo de pesquisa
                document.getElementById('searchInput').addEventListener('input', filtrarItens);        


            </script>


            <h2>Widget</h2>
            <h5 style="margin:0px;">Selecione a praia desejada e informe a URL exata do site autorizado a exibir o widget, ou deixe em branco para permitir o acesso irrestrito.</h5>            
            <p>Obs: A URL do site a ser autorizado deve seguir esta estrutura: https://www.minhapousada.com.br/ , com a barra no final!</p>
            <br>
            <br>
           
            Filtre por praia ou cidade: <input type="text" id="searchInput" placeholder="Digite para filtrar...">     
            <div id="resultados" class="w3-container">        
                <ul class="w3-ul w3-card-4 w3-hoverable ">
                    <?php 
                        foreach ($array_bandeira as $key => $content) 
                        {
                            ?>
                            <form class="w3-container" name="form<?php echo $key;?>" id="form<?php echo $key;?>" action="" method="post" enctype="multipart/form-data">    
                                <li class='item w3-bar'>     
                                    <img src='<?php echo $content; ?>' class='w3-bar-item w3-circle' >
                                    <div class='w3-bar-item'>
                                        <span class='w3-large'>Município: <?php echo $array_cidade[$key]; ?></span><br>
                                        <span>Praia: <?php echo $array_praia[$key]; ?></span>
                                        <input class="w3-input w3-border w3-margin-bottom" type="text" required placeholder="URL ou em branco" name="localpermitido" id="localpermitido" maxLength="100">
                                        <button class="w3-btn w3-block w3-blue w3-section w3-padding" title="" type="submit" name="botaosubmit" onclick="ajax_post('?router=widget&inserir&praia_id=<?php echo $array_praia_id[$key];?>','form<?php echo $key;?>'); return false;">Inserir</button>
                                    </div>
                                </li> 
                            </form>    
                            <?php
                        }                                                                        
                    ?>
                </ul>                
            </div>            
            
            <h4 style="margin:0px;">Meus Widget's:</h4>
            Você pode cadastrar até 3 widgets.
            <div id="widget">
                <?php $this->myWidgetsView();?>
            </div>
            
                                                          
        <?php
        
    }
    
    
    public function view() 
    {        

        $db = new Database();

        //aqui tenho que consultar o banco de dados 
        $selectDB = $db->select("SELECT *, (SELECT balneabilidade_status FROM balneabilidades WHERE praia_id=praias.id ORDER BY balneabilidade_data DESC LIMIT 0,1) AS balneabilidade_status FROM praias");
        if( $selectDB ) 
        {
            //se existir resultado devo criar o array contendo as linhas 

            for ($i = 0; $i < count($selectDB); $i++) 
            {

                //tratando os dados vindos do BD
                if ( $selectDB[$i]['balneabilidade_status'] == 'pverde.gif' ) 
                { 
                    $array_status[]='Própria para banho'; 
                    $array_bandeira[]='app/public/icone_verde52.png'; } 
                else 
                { 
                    $array_status[]='Imprópria para banho'; 
                    $array_bandeira[]='app/public/icone_vermelho52.png';                     
                }

                $array_cidade[] = htmlspecialchars($selectDB[$i]['cidade_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia[] = htmlspecialchars($selectDB[$i]['praia_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $array_praia_id[] = $selectDB[$i]['id'];
                
            }        

        }

        ?>
            <style>
                #resultados {
                    margin-top: 20px;
                    margin-bottom: 20px;
                    border: 1px solid #ccc;
                    padding: 10px;
                    max-height: 200px;
                    overflow-y: auto;
                }
                .item {
                    padding: 5px;
                    margin: 5px 0;
                    border-bottom: 1px solid #ddd;
                }
            </style>



            <script>
                
                function montarWidget(cidade,praia,status,bandeira,id)
                {
                    if(status==='Própria para banho')
                    {
                        document.getElementById('widget').innerHTML = "<div class='w3-row'><div class='w3-col l4 m4'><div style='display: grid;  place-items: center;'><div style='border: solid green 5px; width: 200px;  border-radius: 25px; padding: 10px; text-align: center;'><img src='" + bandeira+ "' class='w3-bar-item w3-circle'><img src='app/public/cetesb_ico.jpg'>" + "<h4>" + cidade + "</h4> <span style='font-size:13px;'>Praia:</span><br><h6 style='margin:0px;'>" + praia + "</h6>" + "<span style='font-size:10px;'>" + status + "</span></div><img src='app/public/rodape_widget_verde.jpg' class='w3-bar-item'></div></div><div class='w3-col l8 ' ><h4>Código para a incorporação em seu site.</h4><textarea rows='5' class='w3-input w3-border'><iframe src='https://www.projetointegradorunivesp.com.br/VIVAPRAIA/?router=widgetView&token=seutoken_"+ id +"' name='PI3' scrolling='no' width='240' height='290' frameborder='0' style='border: 1px solid #10658E;border-radius: 8px'></iframe></textarea></div></div>";
                    }
                    else
                    {
                        document.getElementById('widget').innerHTML = "<div class='w3-row'><div class='w3-col l4 m4'><div style='display: grid;  place-items: center;'><div style='border: solid red 5px; width: 200px;  border-radius: 25px; padding: 10px; text-align: center;'><img src='" + bandeira+ "' class='w3-bar-item w3-circle'><img src='app/public/cetesb_ico.jpg'>" + "<h4>" + cidade + "</h4> <span style='font-size:13px;'>Praia:</span><br><h6 style='margin:0px;'>" + praia + "</h6>" + "<span style='font-size:10px;'>" + status + "</span></div><img src='app/public/rodape_widget_vermelho.jpg' class='w3-bar-item'></div></div><div class='w3-col l8 ' ><h4>Código para a incorporação em seu site.</h4><textarea rows='5' class='w3-input w3-border'><iframe src='https://www.projetointegradorunivesp.com.br/VIVAPRAIA/?router=widgetView&token=seutoken_"+ id +"' name='PI3' scrolling='no' width='240' height='290' frameborder='0' style='border: 1px solid #10658E;border-radius: 8px'></iframe></textarea></div></div>";    
                    }
                    
                }
                
                // Função para filtrar os itens na div
                function filtrarItens() {
                    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
                    const itens = document.querySelectorAll('#resultados .item');

                    itens.forEach(item => {
                        // Obtém o texto de cada item
                        const itemTexto = item.textContent.toLowerCase();

                        // Verifica se o texto do item contém a string de pesquisa
                        if (itemTexto.includes(searchQuery)) {
                            item.style.display = 'block'; // Exibe o item
                        } else {
                            item.style.display = 'none'; // Oculta o item
                        }
                    });
                }

                // Evento de digitação no campo de pesquisa
                document.getElementById('searchInput').addEventListener('input', filtrarItens);        

            </script>


            <h2>Widget</h2>
            <h4 style="margin:0px;">Selecione uma praia para ver o Widget, logo abaixo.</h4>
            <p style="margin:0px 0px 10px 0px ; font-size: 10px;">(Você precisa estar logado para ter seu próprio Widget)</p>            
            
            <br>

           
            Filtre por praia ou cidade: <input type="text" id="searchInput" placeholder="Digite para filtrar...">     
            <div id="resultados" class="w3-container">        
                <ul class="w3-ul w3-card-4 w3-hoverable ">
                    <?php 
                        foreach ($array_bandeira as $key => $content) 
                        {
                            ?>
                            <li class='item w3-bar' onclick='montarWidget("<?php echo $array_cidade[$key];?>","<?php echo $array_praia[$key];?>","<?php echo $array_status[$key];?>","<?php echo $array_bandeira[$key];?>","<?php echo $array_praia_id[$key];?>")' style='cursor: pointer;'>     
                              <img src='<?php echo $content; ?>' class='w3-bar-item w3-circle' >
                              <div class='w3-bar-item'>
                                <span class='w3-large'>Município: <?php echo $array_cidade[$key]; ?></span><br>
                                <span>Praia: <?php echo $array_praia[$key]; ?></span>
                              </div>
                            </li>                                    
                            <?php
                        }                                                                        
                    ?>
                </ul>                
            </div>
            
            
            <h4 style="margin:0px;">Exemplo:</h4>
            <div id="widget"></div>
            
                      
            
            
        <?php
        
    }
    
}

