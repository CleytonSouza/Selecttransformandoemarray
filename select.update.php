<?
public function actionUpdate($id) {

		$this->layout = '//layouts/crud';
		$model = $this->loadModel($id);
		$model->scenario = 'update';
		
		if (isset($_POST['Movimento'])) {
			$model->attributes = $_POST['Movimento'];
			$track_lote = $model->track_lote;
			$track_malote = $model->track_malote;
			#Origem 
			$d_email = $model->d_email;
			$destinatario = $model->destinatario;
            $remetente = $model->remetente;
            $ts_impressao = $model->ts_impressao;


			include 'conexao.php';
			{
			$sql_espo = "UPDATE easytracking.tb_protocolo_lote SET track_malote = '$track_malote' WHERE track_lote = '$track_lote'";
			$result = mysql_query($sql_espo, $conecta) or print(mysql_error());
			
			}
			{
			$sql1 = "INSERT INTO easytracking.tb_fecha_malote (track_malote, d_email, remetente, destinatario, status, ts_impressao, qtd_volta) VALUES ('".$track_malote."', '".$d_email."', '".$remetente."', '".$destinatario."', 'Impresso', '".$ts_impressao."', '0')";
			$result1 = mysql_query($sql1, $conecta) or print(mysql_error());

            }
			{
			#No servidor 54.94.199.66 não é necessario os campos endereço, rota 	
			$sql2 = "INSERT INTO easytracking.tb_dispara_malote (track_malote, nome, endereco, rota, sequencia) VALUES ('".$track_lote."','".$destinatario."', 'teste', '0', '0')";
			
			$result2 = mysql_query($sql2, $conecta) or print(mysql_error());
            
            }        

            {
                     #$destinatario = $model->destinatario;
            
             		$sqlselet= "SELECT n_condominio, nome, endereco, numero, andar, baia, bairro,
                        	cidade, uf, cep, ramal, area, email, rota, sequencia FROM easytracking.tb_rotas WHERE nome = '$destinatario'";

						$resultado1 = mysql_query($sqlselet, $conecta);
						$resultado = mysql_fetch_array($resultado1);

						$n_condominio = $resultado['0'];
						$nome = $resultado['1'];
						$endereco = $resultado['2'];
						$numero = $resultado['3'];
						$andar = $resultado['4'];
						$baia = $resultado['5'];
						$bairro = $resultado['6'];
						$cidade = $resultado['7'];
						$uf = $resultado['8'];
						$cep = $resultado['9'];
						$ramal = $resultado['10'];
						$area = $resultado['11'];
						$email = $resultado['12'];
						$rota = $resultado['13'];
						$sequencia = $resultado['14'];

                   $atualiza = "UPDATE `easytracking`.`tb_dispara_malote` SET `n_condominio` = '$n_condominio', `endereco` = '$endereco', 
                        `numero` = '$numero', `andar` = '$andar', `baia` = '$baia', `bairro` = '$bairro',
                        	`cidade` = '$cidade', `uf` = '$uf', `cep` = '$cep', `ramal` = '$ramal', `area` = '$area', 
                        	`email` = '$email', `rota` = '$rota', `sequencia` = '$sequencia' 
                            WHERE `nome` = '$destinatario' AND status = 'Impresso' ";

                     $insere = mysql_query($atualiza, $conecta) or print_r(mysql_error());

                        #mysql_close($conecta) or print(mysql_error());

            }    	
            mysql_close($conecta) or print(mysql_error());

         	$this->redirect(array('index')); 

		} 


		$this->render('update', array('model' => $model));
	}
