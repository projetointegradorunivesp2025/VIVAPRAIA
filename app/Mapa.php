<?php
/**
 * app/Mapa.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */


require_once("app/frameWork/Database.php");

class Mapa 
{
    
    //variaveis de escopo global da classe
    
    public function __construct() 
    {        
        
    }
    
    public function view() 
    {        

        $db = new Database();

        //aqui tenho que consultar o banco de dados para adicionar na variavel "locais" e "locaisFilter"
        $selectDB = $db->select("SELECT *, (SELECT balneabilidade_status FROM balneabilidades WHERE praia_id=praias.id ORDER BY balneabilidade_data DESC LIMIT 0,1) AS balneabilidade_status FROM praias");
        if( $selectDB ) 
        {
            //se existir resultado devo criar o array contendo as linhas 

            for ($i = 0; $i < count($selectDB); $i++) 
            {

                //tratando os dados vindos do BD
                if ( $selectDB[$i]['balneabilidade_status'] == 'pverde.gif' ) { $status='Própria para banho'; $bandeira='app/public/icone_verde52.png'; } else { $status='Imprópria para banho'; $bandeira='app/public/icone_vermelho52.png'; }

                $cidade_cetesb = htmlspecialchars($selectDB[$i]['cidade_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $praia_cetesb = htmlspecialchars($selectDB[$i]['praia_cetesb'], ENT_QUOTES, 'UTF-8', false);
                $latitude = substr($selectDB[$i]['latitude'], 0, 7); 
                $longitude = substr($selectDB[$i]['longitude'], 0, 7);

                $locais[] = "{ cidade: '{$cidade_cetesb}', praia: '{$praia_cetesb}', lat: {$selectDB[$i]['latitude']}, lng: {$selectDB[$i]['longitude']}, status: '{$status}' }";
                $locaisFilter[] = "
                    <li class='item w3-bar' onclick='moverMapa({$latitude}, {$longitude})' style='cursor: pointer;'>     
                      <img src='{$bandeira}' class='w3-bar-item w3-circle' style='width:85px'>
                      <div class='w3-bar-item'>
                        <span class='w3-large'>Município: {$cidade_cetesb}</span><br>
                        <span>Praia: {$praia_cetesb}</span>
                      </div>
                    </li>                
                    ";

            }        

        }

        ?>
            <style>
                #resultados {
                    margin-top: 20px;
                    border: 1px solid #ccc;
                    padding: 10px;
                    max-height: 250px;
                    overflow-y: auto;
                }
                .item {
                    padding: 5px;
                    margin: 5px 0;
                    border-bottom: 1px solid #ddd;
                }
            </style>


            <script src="https://maps.googleapis.com/maps/api/js?key=aqui&loading=async&callback=initMap&v=weekly"></script>
            <script>

                let mapa;
                let marker; // Variável global 

                function initMap() {

                        // Criar o mapa centralizado 
                            mapa = new google.maps.Map(document.getElementById("mapa"), {
                                center: { lat: -24.156749860511553, lng: -46.73057167664511 }, 
                                zoom: 8,
                                mapTypeId: google.maps.MapTypeId.SATELLITE  
                            });


                        // Coordenadas do local desejado 
                        var locais = [ <?php echo implode( ',', $locais ); ?> ];


                        locais.forEach(function(local) {
                                var iconColor = local.status === "Própria para banho"
                                    ? "https://maps.google.com/mapfiles/ms/icons/green-dot.png"
                                    : "https://maps.google.com/mapfiles/ms/icons/red-dot.png";

                                var flatColor = local.status === "Própria para banho"
                                    ? "app/public/icone_verde52.png"
                                    : "app/public/icone_vermelho52.png";

                                var marcador = new google.maps.Marker({
                                    position: { lat: local.lat, lng: local.lng },
                                    map: mapa,
                                    title: local.cidade+"\n"+local.praia,
                                    icon: iconColor
                                });

                                var infoWindow = new google.maps.InfoWindow({
                                    content: `<img src='${flatColor}' alt='Ícone' style='width:32px;height:32px;'><h3>${local.cidade}<br>${local.praia}</h3><p>${local.status}</p>`
                                });

                                marcador.addListener("click", function() {
                                    infoWindow.open(mapa, marcador);
                                });
                        });

                }

                function moverMapa(lat, lng) {
                    mapa.setCenter({ lat, lng }); //com 3 digitos depois da virgula
                    mapa.setZoom(8);

                    // Remove o marcador anterior (se existir)
                    if (marker) {
                        marker.setMap(null);
                    }

                    // Adiciona um novo marcador na posição centralizada
                    marker = new google.maps.Marker({
                        position: { lat, lng },
                        map: mapa,
                        animation: google.maps.Animation.BOUNCE // Faz o marcador "pular"
                    });

                    // Para a animação após 4 segundos
                    setTimeout(() => marker.setAnimation(null), 4000);            

                }   

            </script>

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


            <h2>Mapa de Qualidade das Praias</h2>
            <div id="mapa" style="width: 100%; height: 400px;"></div>
            <br>

           

            Filtrar Praias: <input type="text" id="searchInput" placeholder="Digite para filtrar...">     
            <div id="resultados" class="w3-container">        
                <ul class="w3-ul w3-card-4 w3-hoverable">
                    <?php echo implode( ' ', $locaisFilter ); ?>
                </ul>                
            </div>
        <?php
        
    }
    
}




