<?php include_once("validar_sessao.php"); ?>

<!DOCTYPE html>

<html>

<head>
	<title>TCC</title>
	<meta charset="utf-8" />
	<link rel='stylesheet' href="./css/menu.css">	
 	<link rel='stylesheet' href="./css/formularios.css">	
	<link rel='stylesheet' href="./css/cad.css">	


</head>

<body>

	<?php 
	    include_once("conectar.php");
	    include_once("funcoes.php");
	    include_once("monta_menu.php"); 
	    
	    $id_usuario = $_SESSION["id_usuario"];
	    $tipo = $_SESSION["tipo"];

	    if ($tipo == "C")
	    	$sqlExtra = " and lote.id_usuario = $id_usuario";
	    else
			$sqlExtra = "";

	    $podeAlterar = ""; 
	    $mensagem = "";
	    $tabela = "";

		$id_lote = $_GET['id_lote'];

		$sql_listar = "select lote.*, fornecedor.nome, usuario.nome as nome_usuario
		               from lote, fornecedor, usuario
		               where lote.id_lote = $id_lote and
		               		 lote.id_fornecedor = fornecedor.id_fornecedor and
		               		 usuario.id_usuario = lote.id_usuario
		               $sqlExtra 
		               order by fornecedor.nome, lote.data_chegada desc";

		$lista = mysqli_query($bd, $sql_listar);

		if ( mysqli_num_rows($lista) > 0 ) {
					
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Número do lote</th><th>Fornecedor</th><th>Data de chegada</th><th>Quantidade Recebida</th><th>Peso de Chegada</th><th>Data de saída</th><th>Quantidade de saída</th><th>Peso de saída</th><th>Situação</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
				$vIdLote         = $dados["id_lote"];
				$vNumLote        = $dados["num_lote"];
				$vNomeUsuario    = $dados["nome_usuario"];
				$vIdFornecedor   = $dados["id_fornecedor"];
				$vNomeFornecedor = $dados["nome"];
				$vDataChegada    = $dados["data_chegada"];
				$vDataChegada    = date( 'd-m-Y' , strtotime( $vDataChegada ) );
				$vQuantChegada   = $dados["quant_chegada"];
				$vPesoChegada    = $dados["peso_chegada"];
				$vDataSaida      = $dados["data_saida"];
				if (!empty($vDataSaida))
				   $vDataSaida   = date( 'd-m-Y' , strtotime( $vDataSaida ) );
				$vQuantSaida     = $dados["quant_saida"];
				$vPesoSaida      = $dados["peso_saida"];
				$vSituacao       = $dados["situacao"];

				$tabela = $tabela."<tr><td>$vNumLote - $vNomeUsuario </td><td>$vNomeFornecedor</td><td>$vDataChegada</td><td>$vQuantChegada</td><td>$vPesoChegada</td><td>$vDataSaida</td><td>$vQuantSaida</td><td>$vPesoSaida</td><td>$vSituacao</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
		
		} else {
			$tabela = "Não há verificações para listar";
		}

		echo "<center>";
		echo "<h1>Relatório do Lote $vNumLote</h1>";
		echo "</center>";

		echo "<center>";
		echo "<h2>Produtor(a) $vNomeUsuario</h2>";
		echo "</center>";

		echo $tabela;
		
		echo "<center>";
		echo "<h3>Suínos Vacinados</h3>";
		echo "</center>";


		$sql_listar = "select vacinado.*, vacina.nome, lote.num_lote, usuario.nome as nome_usuario, nome_doenca
		               from vacinado, vacina, lote, usuario, doenca
		               where vacinado.id_vacina = vacina.id_vacina and
		                     vacinado.id_lote = lote.id_lote and
		                     lote.id_usuario = usuario.id_usuario and
		                     doenca.id_doenca = vacina.id_doenca
		               $sqlExtra	
		               order by data";

		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {		
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Data</th><th>Vacina </th></tr>";
		
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
				$vIdVacinado  = $dados["id_vacinado"];
				$vData        = $dados["data"];
				$vData        = date( 'd-m-Y' , strtotime( $vData ) );
				$vIdVacina    = $dados["id_vacina"];
			    $vNomeVacina  = $dados["nome"];
				$vLote        = $dados["id_lote"];
				$vNumLote     = $dados["num_lote"];
				$vNomeUsuario = $dados["nome_usuario"];
				$vNomeDoenca  = $dados["nome_doenca"];

				
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vData</td><td>$vNomeVacina - $vNomeDoenca</td></tr>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}
		echo $tabela;
		
		echo "<center>";
		echo "<h3>Recebimento de ração</h3>";
		echo "</center>";


				$sql_listar = "select recebimento_racao.*, concat(racao.nome, ' (', racao.codigo, ')') as nome, lote.num_lote, usuario.nome as nome_usuario
		               from recebimento_racao, racao, lote, usuario
		               where recebimento_racao.id_racao = racao.id_racao and
		               		 recebimento_racao.id_lote = lote.id_lote and
		               		 lote.id_usuario = usuario.id_usuario and
		               		 lote.id_lote = $id_lote
		               order by racao.nome ";


		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Data</th><th>Quantidade</th><th>Ração</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdRecebimentoRacao = $dados["id_recebimento"];
				$vData               = $dados["data"];
				$vData               = date( 'd-m-Y' , strtotime( $vData ) );
				$vQuantidade         = $dados["quantidade"];
				$vRacao              = $dados["id_racao"];
				$vNomeRacao          = $dados["nome"];
				$vLote               = $dados["id_lote"];
				$vNumLote            = $dados["num_lote"];
				$vNomeUsuario        = $dados["nome_usuario"];
				
				$tabela = $tabela."<tr><td>$vData</td><td>$vQuantidade</td><td>$vNomeRacao</td></tr>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

		echo $tabela;
		
		echo "<center>";
		echo "<h3>Morte</h3>";
		echo "</center>";


		$sql_listar = "select morte.*, doenca.nome_doenca, lote.num_lote, usuario.nome as nome_usuario
		               from morte, doenca, lote, usuario
		               where morte.id_doenca = doenca.id_doenca and
		                     morte.id_lote = lote.id_lote and 
		                     lote.id_usuario = usuario.id_usuario and
		                      lote.id_lote = $id_lote
		                     $sqlExtra
		               order by doenca.nome_doenca ";

		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {			
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Peso</th><th>Data</th><th>Sexo</th><th>Doença</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
				$vIdMorte     = $dados["id_morte"];
				$vPeso        = $dados["peso"];
				$vData        = $dados["data"];
				$vData        = date( 'd-m-Y' , strtotime( $vData ) );
				$vSexo        = $dados["sexo"];
				$vIdDoenca    = $dados["id_doenca"];
			    $vNomeDoenca  = $dados["nome_doenca"];
				$vLote        = $dados["id_lote"];
				$vNumLote     = $dados["num_lote"];
				$vNomeUsuario = $dados["nome_usuario"];
	
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vPeso</td><td>$vData</td><td>$vSexo</td><td>$vNomeDoenca</td></tr>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}
		echo $tabela;
	  ?>

	   
</body>
</html>


