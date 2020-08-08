<!DOCTYPE html>

<?php
include "connectsql.php"; //Classe dedidaca à conexão feita por mim
include "operacoes.php"; //classe com operaçoes crud
$conn = conectar();
mysqli_set_charset($conn, 'utf8');

$aluno_id = -1;
$nome = "";
$email = "";
$data_nascimento = "";


//Validar a existência dos dados
if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["data_nascimento"])){
	if(empty($_POST["nome"])){
		$erro = "Nome obrigatório.";
	} else if(empty($_POST["email"])){
		$erro = "Email obrigatório.";
	} else{
		//cadastro  ou atualização acontece
		$aluno_id = $_POST["aluno_id"];
		$nome = mysqli_real_escape_string($conn, $_POST["nome"]);
		$email = mysqli_real_escape_string($conn, $_POST["email"]);
		$data_nascimento = mysqli_real_escape_string($conn, $_POST["data_nascimento"]);

		if ($aluno_id == -1){
			if (empty($_POST["data_nascimento"])){
			//caso user não dê data de nascimento
				$status = create_sem_data($conn, $nome, $email);

			} else{
			//caso user DÊ data de nascimento
				$status = create_aluno($conn, $nome, $email, $data_nascimento);

			}
		} else if (is_numeric($aluno_id) && $aluno_id >= 1){
			//realiza atualização
			$status = update_aluno($conn, $nome, $email, $data_nascimento, $aluno_id);
			header("Location:formv2.php");
			exit;

		} else{
			//numero inválido
			$erro = "Número inválido";
		}

		

	}
} else if(isset($_GET["aluno_id"]) && is_numeric($_GET["aluno_id"])){
	//verifica se o id passado existe
	$aluno_id = (int)$_GET["aluno_id"];

	if(isset($_GET["del"])){
		$status = delete_aluno($conn, $aluno_id);
		header("Location:formv2.php");
		exit;

	} else{
		//seta os parametros para os que o aluno_id passado "aponta"
		$statement = $conn->prepare("SELECT * FROM alunos WHERE aluno_id = ?");
		$statement->bind_param("i", $aluno_id);//o parâmetro será 'i' de Int (em contraste com os 's' de String)
		$statement->execute();
		$resultado = $statement->get_result();

		//atribuir o retorno como um array de valores, por meio do método fetch_assoc, que realiza um associação dos valores em forma de array
		$aux_query = $resultado->fetch_assoc();
		//Atribuir às variáveis.
		$nome = $aux_query["nome"];
		$email = $aux_query["email"];
		$data_nascimento = $aux_query["data_nascimento"];
		$statement->close();
	}
}


?>

<html lang="pt-br">
	<head>
		<title>Operações CRUD com PHP.</title>
	</head>
	<body>
		<?php
		if(isset($erro)){
			echo '<div style="color:#F00">'.$erro.'</div><br/><br/>';
		}
		else if(isset($sucesso)){
			echo '<div style="color:#00f">'.$sucesso.'</div><br/><br/>';
		}
		else if(isset($status)){
			echo '<div style="color:#00f">'.$status.'</div><br/><br/>';
		}
 		?>
		<h1>Formulário</h1>
		<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
			<!-- o comando $_SERVER[] serve para setar o arquivo que receberá as informações do form-->
			<!-- "PHP_SELF" significa que o arquivo atual/root será o recebidor do form -->
			<!-- Em respeito à modularização e OO, é melhor criar uma arquivo separado para cada elemento da página -->
			Nome: <br/>
			<input type="text" name="nome" placeholder="Digite seu nome:" value="<?=$nome?>" > <br/>
			Email: <br/>
			<input type="email" name="email" placeholder="Digite seu email:" value="<?=$email?>"><br/>
			Data de nascimento: <br/>
			<input type="date" name="data_nascimento" placeholder="Digite sua data de nascimento" value="<?=$data_nascimento?>">
			<br/><br/>
			<input type="hidden" name="aluno_id" value="<?=$aluno_id?>"> 
	  		<button type="submit"><?=($aluno_id==-1)?"Cadastrar":"Salvar"?></button>
		</form>
		<br>
		<br>
		<table width="600px" border="0" cellspacing="0">
			<tr>
				<td><strong>#</strong></td>
				<td><strong>Nome</strong></td>
				<td><strong>Email</strong></td>
				<td><strong>Data de Nascimento</strong></td>
				<td><strong>#</strong></td>
			</tr>

			<?php
				$resultado = read_aluno($conn);
				while ($aux_query = $resultado->fetch_assoc()) {
					echo '<tr>';
					echo ' <td>'.$aux_query["aluno_id"].'</td>';
					echo ' <td>'.$aux_query["nome"].'</td>';
					echo ' <td>'.$aux_query["email"].'</td>';
					echo ' <td>'.$aux_query["data_nascimento"].'</td>';
					echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?aluno_id='.$aux_query["aluno_id"].'">Editar</a></td>';
					echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?aluno_id='.$aux_query["aluno_id"].'&del=true">Excluir</a></td>';
					echo '<tr>';
				}
			?>
			
		</table>
	</body>
</html>