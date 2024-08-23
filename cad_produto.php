<?php
 ini_set ( 'display_errors' , 1);
 error_reporting (E_ALL);
// print_r(getallheaders());

	foreach (getallheaders() as $name => $value) {
    	if($name == "Api-Key"){
    		$token = $value;
    	}
    }


	


$method = $_SERVER['REQUEST_METHOD'];
date_default_timezone_set('America/Sao_Paulo');
$data_inic = date("Y-m-d H:i:s");

if($method == "POST"){


	
	include("../configs/conexao_checkout.php");
	
	mysql_set_charset('utf8');

	$validation_sql = "SELECT * FROM `api` WHERE `chave` LIKE '$token'";
	$result_validation = mysql_query($validation_sql);
	$total_validation = mysql_num_rows($result_validation);
	
	if($total_validation == 1){
			
		$retorno = file_get_contents("php://input");
		ob_start();
		$itens = json_decode($retorno, true);

		if (is_array($itens)) { 

				foreach ($itens as $e){ 
									$id_produto                 	= trim($e['id_produto']);
									$referencia                 	= trim($e['referencia']);
			                        $insert_nome                	= addslashes($e['insert_nome']);
			                        $insert_status              	= trim($e['insert_status']);
			                        $insert_modelo              	= trim($e['insert_modelo']);
			                        $insert_cdgbarras           	= trim($e['insert_cdgbarras']);
			                        $insert_ncm                 	= trim($e['insert_ncm']);
			                        $insert_garantia            	= trim($e['insert_garantia']);
			                        $insert_peso                	= trim($e['insert_peso']);
			                        $insert_altura              	= trim($e['insert_altura']);
			                        $insert_largura             	= trim($e['insert_largura']);
			                        $insert_comprimento         	= trim($e['insert_comprimento']);
			                        $insert_quantidade_estoque  	= trim($e['insert_quantidade_estoque']);
			                        $insert_preco_custo         	= trim($e['insert_preco_custo']);
			                        $insert_preco_venda         	= trim($e['insert_preco_venda']);
			                        $insert_preco_promocao      	= trim($e['insert_preco_promocao']);
			                        $insert_categoria      			= trim($e['insert_categoria']);
			                        $insert_fabricante      		= trim($e['insert_fabricante']);
			                        $insert_descricao           	= addslashes($e['insert_descricao']);
			                        $insert_descricao           	= preg_replace("/\r?\n/","", $insert_descricao);
			                        $insert_itens_inclusos      	= addslashes($e['insert_itens_inclusos']);
			                        $insert_itens_inclusos      	= preg_replace("/\r?\n/","", $insert_itens_inclusos);
			                        $insert_especificacao       	= addslashes($e['insert_especificacao']);
			                        $insert_especificacao       	= preg_replace("/\r?\n/","", $insert_especificacao);
			                        $insert_descricaocompleta   	= addslashes($e['insert_descricaocompleta']);
			                        $insert_descricaocompleta   	= preg_replace("/\r?\n/","", $insert_descricaocompleta);
			                        $insert_titulo_pagina       	= addslashes($e['insert_titulo_pagina']);
			                        $insert_titulo_pagina       	= preg_replace("/\r?\n/","", $insert_titulo_pagina);
			                        $insert_keywords            	= addslashes($e['insert_keywords']);
			                        $insert_keywords            	= preg_replace("/\r?\n/","", $insert_keywords);
			                        $insert_descriptions        	= addslashes($e['insert_descriptions']);
			                        $insert_descriptions        	= preg_replace("/\r?\n/","", $insert_descriptions);
			                        $insert_destaque            	= $e['insert_destaque'];
			                        $insert_lancamento          	= $e['insert_lancamento'];
									
									/*
			                        $insert_descricao           	= htmlentities($insert_descricao);
			                        $insert_descricaocompleta   	= htmlentities($insert_descricaocompleta);
			                        */

			                        $new_insert_keywords            = str_replace(";", ".", $insert_keywords);
			                        $new_insert_descriptions        = str_replace(";", ".", $insert_descriptions);
			                        $new_insert_descricao           = str_replace(";", ".", $insert_descricao);
			                        $new_insert_descricaocompleta   = str_replace(";", ".", $insert_descricaocompleta);

			                        $foto1              			= trim($e['foto1']);
			                        $foto2           				= trim($e['foto2']);
			                        $foto3                 			= trim($e['foto3']);
			                        $foto4            				= trim($e['foto4']);
			                        $foto5                			= trim($e['foto5']);

			                        $var_sku_pai                	= trim($e['var_sku_pai']);
			                        $var_tipo                		= trim($e['var_tipo']);
			                        $var_nome                		= trim($e['var_nome']);
			                        $var_sub_tipo                	= trim($e['var_sub_tipo']);
			                        $var_sub_nome                	= trim($e['var_sub_nome']);

									$validation_sql = "SELECT * FROM `produtos` WHERE `id` LIKE '{$id_produto}'";
									$result_validation = mysql_query($validation_sql);
									$contador = mysql_num_rows($result_validation);

									if($id_produto != ""){
										$id_produto = $id_produto;
									}else{
										$id_produto = 'NULL';
									}

									if($contador > 0){
										if($id_produto != ""){
											echo '
												[{
													"retorno":"422",
													"mensagem":"ja existe um produto com o id '.$id_produto.'."
												}]
											';
											http_response_code(422);
										}
									}else if($contador == 0){

										if ($var_sku_pai == '') {
											$id_prod_principal = '';
											$variacao = 0;
										}else{
											$busca_sku = mysql_query("SELECT id FROM `produtos` WHERE `id` LIKE '{$var_sku_pai}'");
											$contagem_busca_sku = mysql_num_rows($busca_sku);

											if ($contagem_busca_sku > 0) {
												while ($row_var = mysql_fetch_assoc($busca_sku)) {
													$id_prod_principal = $row_var['id'];
													$variacao = 1;
												}
											}else{
													$id_prod_principal = '';
													$variacao = 0;
											}
										}
										

										$infoPages      = mysql_query("INSERT INTO `produtos`(`id`, `nome`, `codigo`, `status`, `cdgbaras`, `ncm`, `modelo`, `garantia`, `peso`, `altura`, `largura`, `comprimento`, `quantidadeestoque`, `precocusto`, `precovenda`, `precopromocao`, `descricao`, `descricaocompleta`, `especificao`, `itensinclusos`, `titulo_pagina`, `keywords`, `descriptions`, `destaque`, `lancamento`, `data`, `data_inic`, `data_fim`, `venda`, `id_produto`, `variacao_1`, `nome_var_1`, `variacao`, `estado`, `desconto`, `variacao_2`, `nome_var_2`) VALUES (".$id_produto.",'".$insert_nome."','".$referencia."','".$insert_status."','".$insert_cdgbarras."','".$insert_ncm."','".$insert_modelo."','".$insert_garantia."','".$insert_peso."','".$insert_altura."','".$insert_largura."','".$insert_comprimento."','".$insert_quantidade_estoque."','".$insert_preco_custo."','".$insert_preco_venda."','".$insert_preco_promocao."','".$insert_descricao."','".$insert_descricaocompleta."','".$insert_especificacao."','".$insert_itens_inclusos."','".$insert_titulo_pagina."','".$insert_keywords."','".$insert_descriptions."','".$insert_destaque."','".$insert_lancamento."','".$data_inic."','".$data_inic."','2030-03-19 05:03:25', '1','".$id_prod_principal."','".$var_tipo."','".$var_nome."','".$variacao."', '1', '1','','');");
										$id_prod_inserido = mysql_insert_id();

										if ($insert_fabricante != "") {
											$busca_fabricante    = mysql_query("SELECT * FROM `fabricantes` WHERE `nome` LIKE '".$insert_fabricante."';");
											$contagem_fabricante = mysql_num_rows($busca_fabricante);
											if ($contagem_fabricante > 0) {
												while ($row_categoria = mysql_fetch_assoc($busca_fabricante)) {
													$id_fabricante = $row_categoria['id'];
												}
												$salva_fabricante   = mysql_query("UPDATE `produtos` SET `fabricante` = '".$id_fabricante."' WHERE `produtos`.`id` = '".$id_prod_inserido."';");
											}else{
												$inserir_fabricante = mysql_query("INSERT INTO `fabricantes` (`id`, `status`, `nome`, `site`, `foto`, `exibir_slider`, `exibir_pagina`) VALUES (NULL, '1', '".$insert_fabricante."', '', '', '0', '0');");
												$id_cat_inserido = mysql_insert_id();

												$salva_fabricante   = mysql_query("UPDATE `produtos` SET `fabricante` = '".$id_cat_inserido."' WHERE `produtos`.`id` = '".$id_prod_inserido."';");
											}
										}


										if ($id_prod_inserido != "") {
											if ($foto1 != "") { 
										    	$content_1   = file_get_contents($foto1);
								            	$nome_img_1  = md5(uniqid(time()));
								            	$dados_img_1 = file_put_contents('../uploads/'.$nome_img_1.'.jpg', $content_1);
								            	$add_img_1   = mysql_query("INSERT INTO `fotos_produtos` (`id`, `id_produto`, `foto`, `ordem`, `nome`) VALUES (NULL, '".$id_prod_inserido."', '".$nome_img_1.".jpg', '1', '');");
											}
											if ($foto2 != "") { 
												$content_2   = file_get_contents($foto2);
								            	$nome_img_2  = md5(uniqid(time()));
								            	$dados_img_2 = file_put_contents('../uploads/'.$nome_img_2.'.jpg', $content_2);
								            	$add_img_2   = mysql_query("INSERT INTO `fotos_produtos` (`id`, `id_produto`, `foto`, `ordem`, `nome`) VALUES (NULL, '".$id_prod_inserido."', '".$nome_img_2.".jpg', '2', '');");
											}
											if ($foto3 != "") { 
												$content_3   = file_get_contents($foto3);
								            	$nome_img_3  = md5(uniqid(time()));
								            	$dados_img_3 = file_put_contents('../uploads/'.$nome_img_3.'.jpg', $content_3);
								            	$add_img_3   = mysql_query("INSERT INTO `fotos_produtos` (`id`, `id_produto`, `foto`, `ordem`, `nome`) VALUES (NULL, '".$id_prod_inserido."', '".$nome_img_3.".jpg', '3', '');");
											}
											if ($foto4 != "") { 
												$content_4   = file_get_contents($foto4);
								            	$nome_img_4  = md5(uniqid(time()));
								            	$dados_img_4 = file_put_contents('../uploads/'.$nome_img_4.'.jpg', $content_4);
								            	$add_img_4   = mysql_query("INSERT INTO `fotos_produtos` (`id`, `id_produto`, `foto`, `ordem`, `nome`) VALUES (NULL, '".$id_prod_inserido."', '".$nome_img_4.".jpg', '4', '');");
											}
											if ($foto5 != "") { 
												$content_5   = file_get_contents($foto5);
								            	$nome_img_5  = md5(uniqid(time()));
								            	$dados_img_5 = file_put_contents('../uploads/'.$nome_img_5.'.jpg', $content_5);
								            	$add_img_5   = mysql_query("INSERT INTO `fotos_produtos` (`id`, `id_produto`, `foto`, `ordem`, `nome`) VALUES (NULL, '".$id_prod_inserido."', '".$nome_img_5.".jpg', '4', '');");
											}




											if ($insert_categoria != "") {
												$array=explode(" > ",$insert_categoria);

												$categoria0 = trim($array[0]);
											    $categoria1 = trim($array[1]);
												$categoria2 = trim($array[2]);

												
												if ($categoria0 != "") {
													$consulta_nivel1 = mysql_query("SELECT * FROM  `categoria` WHERE  `nome` LIKE  '".$categoria0."'");
													$contagem_nivel_1 = mysql_num_rows($consulta_nivel1);
													if ($contagem_nivel_1 == 0) {
													  $add_cat1 = mysql_query("INSERT INTO `categoria` (`id`, `nome`, `children`, `ordem`, `status`, `description`, `keywords`, `titulo_page`, `google_shop`) VALUES (NULL, '".$categoria0."', '0', '0', '1', '', '', '', '');");

													  $id_inserido_categoria0 = mysql_insert_id();

													  mysql_query("INSERT INTO `produtos_categoria` (`id`, `id_produto`, `id_categoria`) VALUES (NULL, '{$id_prod_inserido}', '{$id_inserido_categoria0}');");
													}else{
														while($row = mysql_fetch_assoc($consulta_nivel1)){
															$id_inserido_categoria0 = $row['id'];	
														}

													  mysql_query("INSERT INTO `produtos_categoria` (`id`, `id_produto`, `id_categoria`) VALUES (NULL, '{$id_prod_inserido}', '{$id_inserido_categoria0}');");
													}
												}

												if($categoria1 != "") {
													$consulta_nivel2 = mysql_query("SELECT * FROM  `categoria` WHERE  `nome` LIKE  '".$categoria1."' AND `children` = '".$id_inserido_categoria0."'");
													$contagem_nivel_2 = mysql_num_rows($consulta_nivel2);
													if ($contagem_nivel_2 == 0){
														$add_cat2 = mysql_query("INSERT INTO `categoria` (`id`, `nome`, `children`, `ordem`, `status`, `description`, `keywords`, `titulo_page`, `google_shop`) VALUES (NULL, '".$categoria1."', '".$id_inserido_categoria0."', '0', '1', '', '', '', '');");

													  $id_inserido_categoria1 = mysql_insert_id();

													  mysql_query("INSERT INTO `produtos_categoria` (`id`, `id_produto`, `id_categoria`) VALUES (NULL, '{$id_prod_inserido}', '{$id_inserido_categoria1}');");
													}else{
														while($row1 = mysql_fetch_assoc($consulta_nivel2)){
															$id_inserido_categoria1 = $row1['id'];	
														}

													    mysql_query("INSERT INTO `produtos_categoria` (`id`, `id_produto`, `id_categoria`) VALUES (NULL, '{$id_prod_inserido}', '{$id_inserido_categoria1}');");
													
													}	
												}

												if($categoria2 != "") {
													$consulta_nivel3 = mysql_query("SELECT * FROM  `categoria` WHERE  `nome` LIKE  '".$categoria2."' AND `children` = '".$id_inserido_categoria1."'");
													$contagem_nivel_3 = mysql_num_rows($consulta_nivel3);
													if ($contagem_nivel_3 == 0){
																$add_cat3 = mysql_query("INSERT INTO `categoria` (`id`, `nome`, `children`, `ordem`, `status`, `description`, `keywords`, `titulo_page`, `google_shop`) VALUES (NULL, '".$categoria2."', '".$id_inserido_categoria1."', '0', '1', '', '', '', '');");

																  $id_inserido_categoria2 = mysql_insert_id();

																  mysql_query("INSERT INTO `produtos_categoria` (`id`, `id_produto`, `id_categoria`) VALUES (NULL, '{$id_prod_inserido}', '{$id_inserido_categoria2}');");
													}else{
																	while($row2 = mysql_fetch_assoc($consulta_nivel3)){
																		$id_inserido_categoria2 = $row2['id'];	
																	}

																    mysql_query("INSERT INTO `produtos_categoria` (`id`, `id_produto`, `id_categoria`) VALUES (NULL, '{$id_prod_inserido}', '{$id_inserido_categoria2}');");
													}


												}
											}




											echo '
												[{
													"retorno":"200",
													"id_visual":"'.$id_prod_inserido.'",
													"mensagem":"produto inserido com sucesso."
												}]
											';
										}else{
											echo '
												[{
													"retorno":"422",
													"mensagem":"erro ao cadastrar produto, revise os dados."
												}]
											';
											http_response_code(422);
										}

										
									}

				}

		}else{
				echo "json invalido";
				http_response_code(422);
		}

	}else{
		echo '{"retorno":"Token invalido"}';
		http_response_code(401);
	}

	mysql_close($conecta1);
	
}

if($method == "PUT"){
	
		echo '{"retorno":"Método não aceito."}';
		http_response_code(401);


}