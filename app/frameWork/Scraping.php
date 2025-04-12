<?php
/**
 * app/frameWork/Scraping.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */

// Usando XPath e DOMDocument para encontrar uma tabela pelo conteúdo
//anotando coordenadas da planilha da cetesb em cada tabela
//observando a tabela chegamos a conclusão que todo Domingo é divulgado a balneabilidade das praias
//separado por uma coluna em branco 
//sendo que na linha 0 e coluna 0 temos o nome da cidade
//e a começar da coluna 2 da linha 1 temos as datas, sendo que cada mês é separado por uma coluna 
//e a começar da linha 2 sempre na coluna 0 temos o nome da praia 
//e a começar da linha 2 temos a informação da qualidade da agua das praias nas mesma colunas das datas
//precisamos cadastrar as praias no banco de dados com as suas respectivas latitude e longitude capturadas do google maps 
//e adicionar o nome da PRAIA exatamente como está na CETESB para servir de LINK
// o banco de dados terá as tabelas:
// (praias) 
// id (index primary incremental INT)
// praia_cetesb (nome da praia exatamente igual da planilha da cetesb) (varchar 100 unique)
// cidade_cetesb (varchar 100 unique) (exatamente igual da planilha da cetesb)
// latitude (FLOAT)
// longitude (FLOAT)
//
// (balneabilidades)
// id (index primary incremental INT)
// praia_id (index unique integer)
// balneabilidade_data (index unique datetime)
// balneabilidade_status (varchar 10)
// 

require_once("app/Database.php");

class Scraping 
{
    
    //variaveis de escopo global da classe
    
    public function __construct() 
    {                
        
    }
    
    public function praias() 
    {        
        //aqui devo alimentar a tabela de praias
        //deve ser executado apenas 1 vez porque não se altera muito os pontos de coletas
        //campos
        // id
        // cidade_cetesb
        // praia_cetesb
        // latitude 
        // longitude  
        
        
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $html = file_get_contents("https://sistemasinter.cetesb.sp.gov.br/praias/excel/boletim.php");
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $tabelas = $xpath->query("//table"); // Pega todas as tabelas

        foreach ($tabelas as $keytabela => $tabela) {

            //pulando a primeira tabela
            if ($keytabela!=0) {
                               
                $rows = $tabela->getElementsByTagName("tr");

                foreach ($rows as $keyrow => $row) {

                    $cols = $row->getElementsByTagName("td");

                    foreach ($cols as $keycol => $col) {
                        
                        //na linha 0 e coluna 0 está o nme da cidade 
                        if( $keyrow==0 and $keycol==0 ) { 
                            
                            //tirando os espaços em branco e convertendo caracteres especiais em entidades HTML
                            //evitando XSS
                            $cidade = trim(htmlspecialchars($col->nodeValue)); 
                            
                        }
                        
                        //nas linhas acima de 1 e na coluna 0 estão os nomes das praias
                        if( $keyrow>=2 and $keycol==0 ) { 
                                                                                     
                            //aqui eu posso gravar as cidades e praias
                            //antes de gravar poderia ver se já não existe 
                            //devo verificar tbem se tanto cidade qto praia não são vazios
                            
                            //tirando os espaços em branco e convertendo caracteres especiais em entidades HTML
                            //evitando XSS
                            $praia = trim(htmlspecialchars($col->nodeValue));
                            
                            if( strlen($cidade)>0 and strlen($praia)>0 ) {
                                                                                           
                                $db = new Database();
                                
                                //consultar para ver se já existe a combinação cidade + praia
                                //se não existir devo inserir
                                
                                $result = $db->select("SELECT id FROM praias WHERE cidade_cetesb = ? and praia_cetesb = ? ", ["$cidade", "$praia"]);
                                if( !$result ) {
                                
                                    $db->execute("INSERT INTO praias (cidade_cetesb, praia_cetesb) VALUES (?, ?)", ["$cidade", "$praia"]);                                    
                                   
                                }
                            
                            }
                            
                        }
                                                                                                                                                                
                    }

                }

            }                         
            
        }
        
        echo "Processo concluído!";
                
    }
    
    public function balneabilidades() 
    {
        //aqui devo alimentar a tabela de balneabilidades
        //campos
        // id
        // praia_id
        // balneabilidade_data
        // balneabilidade_status
        
        //primeiro preciso ter as datas dos domingos do ano todo de 2025
        //seguindo a tabulação da CETESB a primeira coluna deve ser a (2)
        //ao trocar de mês devo adicionar + 1 coluna até o mês 12

        $ano = 2025;
        $mes = 1;
        $coluna = 1; 
        $coluna_data_array = [];
        $data = new DateTime("$ano-01-01");
        $intervalo = new DateInterval('P1D');
        $cidade = "";
        $praia = "";
        
        while ($data->format('Y') == $ano) {
            if ($data->format('w') == 0) { // 0 representa domingo
                if ($data->format('n') == $mes) 
                { // se o mes corrente (n) for igual a mes adiciono 1 coluna  
                    $coluna = $coluna+1;
                }
                else
                { // se o mes corrente (n) for diferente do mes setado adiciono 2 colunas e altero o mes
                    $coluna = $coluna+2;
                    $mes = $mes+1;            
                }

                $coluna_data_array[$coluna] = $data->format("Y-m-d");
                
            }
            $data->add($intervalo);
        }
                
                        
        //agora que já tenho as colunas vou navegar no DOM        
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $html = file_get_contents("https://sistemasinter.cetesb.sp.gov.br/praias/excel/boletim.php");
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $tabelas = $xpath->query("//table"); // Pega todas as tabelas

        foreach ($tabelas as $keytabela => $tabela) {

            //pulando a primeira tabela
            if ($keytabela!=0) {
               
                $rows = $tabela->getElementsByTagName("tr");

                foreach ($rows as $keyrow => $row) {

                    $cols = $row->getElementsByTagName("td");
                                        
                    //daqui em diante eu já tenho todas as colunas desta linha                    
                    
                    //na linha 0 e coluna 0 está o nme da cidade
                    if( $keyrow==0 )
                    {                                            
                        //tirando os espaços em branco e convertendo caracteres especiais em entidades HTML
                        //evitando XSS
                        $cidade = trim(htmlspecialchars($cols[0]->nodeValue));                        
                    }
                    
                    //nas linhas acima de 1 e na coluna 0 estão os nomes das praias
                    if( $keyrow>1 ) 
                    { 
                        //tirando os espaços em branco e convertendo caracteres especiais em entidades HTML
                        //evitando XSS
                        $praia = trim(htmlspecialchars($cols[0]->nodeValue));
                        
                        //devo verificar tbem se tanto cidade qto praia não são vazios
                        //antes de gravar devo capturar o id da combinação(cidade+praia) no banco de dados
                        if( strlen($cidade)>0 and strlen($praia)>0 ) {

                            $db = new Database();

                            //consultar para capturar o id da combinação cidade + praia                                                               
                            $select_id_praias = $db->select("SELECT id FROM praias WHERE cidade_cetesb = ? and praia_cetesb = ? ", ["$cidade", "$praia"]);
                            if( $select_id_praias ) {                                
                                //como existe a combinação cidade + praia, 
                                $praia_id = $select_id_praias[0]['id'];
                                
                                //percorrendo o array de datas do ano                                                                
                                foreach ($coluna_data_array as $key => $coluna_data) {
                                    //aqui vou percorrer somente as datas inferiores a data de hoje
                                    if( strtotime($coluna_data) <= time() )
                                    {
                                        //vou verificar o que existe dentro da celula de acordo com key da $coluna_data 
                                        //e se existir um item img eu devo prosseguir
                                        $img = $cols[$key]->getElementsByTagName("img")->item(0);
                                        if ($img) {
                                            $imagem_url = $img->getAttribute("src");

                                            ////vou deixar para depois a verificação se já existe ou não no banco de dados
                                            ////vou verificar se não existe a combinação praia_id + balneabilidade_data($coluna_data) para poder inserir a balneabilidade
                                            //$select_id_balneabilidades = $db->select("SELECT id FROM balneabilidades WHERE praia_id = ? and balneabilidade_data = ? ", [$praia_id, "$coluna_data"]);
                                            //if( !$select_id_balneabilidades ) {
                                            //    //ainda não existe, posso inserir                                                                                                                                    
                                            //}
                                            
                                            //vou inserir no banco de dados
                                            $db->execute("INSERT INTO balneabilidades (praia_id, balneabilidade_data,balneabilidade_status) VALUES (?, ?, ?)", [$praia_id, "$coluna_data", "$imagem_url"]);                                    
                                            
                                        }                 
                                                                                                                                                                
                                    }    
                                                                                                            
                                }                                

                            }

                        }
                                                
                    }
                    
                }
                            
            } 
           
        }
        
        echo "Processo concluído!";
                
    }
            
    public function exibe_dados() {
        
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $html = file_get_contents("https://sistemasinter.cetesb.sp.gov.br/praias/excel/boletim.php");
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $tabelas = $xpath->query("//table"); // Pega todas as tabelas

        foreach ($tabelas as $keytabela => $tabela) {

            //pulando a primeira tabela
            if ($keytabela!=0) {

                echo "<table border='1' >";

                //comparando o conteudo 
                //if (strpos($tabela->textContent, "Nome do Produto") !== false) {
                    $rows = $tabela->getElementsByTagName("tr");

                    foreach ($rows as $keyrow => $row) {

                        echo "<tr>";

                        $cols = $row->getElementsByTagName("td");

                        foreach ($cols as $keycol => $col) {

                            echo "<td>";

                            echo "($keyrow)($keycol)";
                            if( $keyrow==0 and $keycol==0 ) { echo "Cidade<br>"; }
                            if( $keyrow>=2 and $keycol==0 ) { 
                                echo "Praia<br>";                                 
                            }

                            $img = $col->getElementsByTagName("img")->item(0);
                            if ($img) {
                                $imagem_url = $img->getAttribute("src");
                                echo "$imagem_url";
                            } else {                                                                        
                                echo trim($col->nodeValue);
                            }                 

                            //mostra o texto dentro da td
                            //echo trim($col->nodeValue) . " | ";

                            echo "</td>";

                        }

                        echo "</tr>";

                    }

                //}

                echo "</table>";    

            } 

            echo "<br><br>";


        }
        
    }
    
}