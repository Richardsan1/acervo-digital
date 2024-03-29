<?php
session_start();
include 'config.php';
if(isset($_SESSION['USRCODIGO']) == false)
{
  header('location:../pages/login.htm');
  die();
}
if(mysqli_connect_errno()){
  echo "<h1>Conexão falhou</h1>";
  die();
}

$intId = $_GET['bandaid'];
$strBandas = "SELECT BDSNOME, ARTNOME, BDSDTINICIO, BDSDTTERMINO, ARTDTINICIO, ARTDTTERMINO, BDSAPRESENTACAO, INSNOME 
FROM BANDAS 
LEFT JOIN INTEGRANTES ON INTEGRANTES.ITGBANDA = BDSCODIGO
LEFT JOIN ARTISTAS ON ARTISTAS.ARTCODIGO = ITGARTISTA
LEFT JOIN INSTRUMENTOS ON INSTRUMENTOS.INSCODIGO = ITGINSTRUMENTO
WHERE BDSCODIGO = ".$intId.
" ORDER BY ARTNOME;" ;
$queryBanda  = mysqli_query($conexao, $strBandas);


if(!$regBanda = mysqli_fetch_array($queryBanda)){
    echo "<h1>Error 404</h1>";
    echo "<label>Not Found</label>";
    die();
}
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/banda.css">
    <link rel="icon" type="image/x-icon" href="../images/logo-etec.png">
    <title>Acervo - <?php echo $regBanda['BDSNOME'];?></title>
</head>
<body>
    <div class="tudo">
    
        <header>

            <h3><a href="../php/">Inicio</a></h3>
            <h3><a href="biblioteca.php">Biblioteca</a></h3>
            <h3><a href="cadastromus.php">Cadastrar</a></h3>
          
            <form class="pesquisa" action="search.php" method="get">
                <input type='text' name='txtPesquisa' class='txtPesquisa' placeholder='Pesquisar...'/>
                <button type="submit" class="btnPesquisa"></button>
            </form>
          
            <div class="perfil">
              <img src="../images/unknown.ico">
              <h3>
                <a href="perfil.php">perfil</a>
              </h3>
            </div>    
        </header>


        <div class='conteiners'>
        <?php
            echo "<h1>". $regBanda['BDSNOME'] ."</h1>";
            echo "<p>". $regBanda['BDSAPRESENTACAO'] ."</p>";
        ?>
        </div>

        <div class="conteiners">
            <ul>
            <?php
                $time = strtotime($regBanda['BDSDTINICIO']);
                $myFormatForView = date("d/M/Y", $time);
                echo "<li>Nome de banda: ". $regBanda['BDSNOME'] ."</li>";
                echo "<li>Inicío de banda: ". $myFormatForView ."</li>";
                if($regBanda['BDSDTTERMINO'] != null)
                {
                    $time = strtotime($regBanda['BDSDTTERMINO']);
                    $myFormatForView = date("d/M/Y", $time);
                    echo "<li>Fim da banda: ". $myFormatForView ."</li>";
                }
                else
                {
                    echo "<li>Fim da banda: Não acabou</li>";
                }
            ?>    
            </ul>
        </div>
    
        <div class="tabelas">
            <table>
                
                <th>
                    <h5>Integrantes</h5>
                </th>
                <?php
                mysqli_free_result($queryBanda);
                $queryBanda  = mysqli_query($conexao, $strBandas);
                while ($contadorBandas = mysqli_fetch_assoc($queryBanda))
                {
                    echo "<tr>";
                    echo     "<td>". $contadorBandas['ARTNOME'] ."</td>";
                    echo "</tr>"; 
                }
                mysqli_free_result($queryBanda);
                $queryBanda  = mysqli_query($conexao, $strBandas);
                ?>
                
            </table>

            <table>
                <th>
                    <h5>Instrumento</h5>
                </th>
                
                <?php
                while ($contadorBandas = mysqli_fetch_assoc($queryBanda))
                {
                    echo "<tr>";
                    echo     "<td>". $contadorBandas['INSNOME'] ."</td>";
                    echo "</tr>"; 
                }
                mysqli_free_result($queryBanda);
                $queryBanda  = mysqli_query($conexao, $strBandas);
                ?>

            </table>
            
            <table>
                <th>
                    <h5>Inicío de carreira</h5>
                </th>
        
                <?php
                while ($contadorBandas = mysqli_fetch_assoc($queryBanda))
                {
                    $time = strtotime($contadorBandas['ARTDTINICIO']);
                    $myFormatForView = date("d/M/Y", $time);
                    echo "<tr>";
                    echo     "<td>". $myFormatForView ."</td>";
                    echo "</tr>"; 
                }
                mysqli_free_result($queryBanda);
                $queryBanda  = mysqli_query($conexao, $strBandas);
                ?>
                
            </table>

            <table>
                <th>
                    <h5>Final de carreira</h5>
                </th>
                
                <?php
                while ($contadorBandas = mysqli_fetch_assoc($queryBanda))
                {
                    if($contadorBandas['ARTDTTERMINO'] != null)
                    {
                        $time = strtotime($contadorBandas['ARTDTTERMINO']);
                        $myFormatForView = date("d/M/Y", $time);
                        echo "<tr>";
                        echo     "<td>". $myFormatForView ."</td>";
                        echo "</tr>";
                    }
                    else 
                    {
                        echo "<tr>";
                        echo     "<td>Não finalizou</td>";
                        echo "</tr>";
                    }
                }
                mysqli_free_result($queryBanda);
                mysqli_close($conexao);
                ?> 
            </table>

        </div>
        <div class="espaco"></div>
    </div>

</body>
</html>