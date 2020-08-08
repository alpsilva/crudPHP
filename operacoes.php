<?php

function createAluno($conn, $nome, $email, $data_nascimento ){
    $status = "";
    $statement = $conn->prepare("INSERT INTO alunos (nome, email, data_nascimento) VALUES (?, ?, ?)");
	$statement->bind_param("sss", $nome, $email, $data_nascimento);
	if(!$statement->execute()){
		$status = $statement-> error;
	} else{
		$status = "Dados cadastrados! (com data de nascimento)";
    }
    return $status;
}

function createSemData($conn, $nome, $email, $data_nascimento ){
    $status = "";
    $statement = $conn->prepare("INSERT INTO alunos (nome, email) VALUES (?, ?, ?)");
	$statement->bind_param("ss", $nome, $email);
	if(!$statement->execute()){
		$status = $statement-> error;
	} else{
		$status = "Dados cadastrados! (sem data de nascimento)";
    }
    return $status;
}

function read_table($conn, $table){
    $resultado = $conn->query("SELECT * FROM ".$table);
    return $resultado;
}

function updateAluno($conn, $nome, $email, $data_nascimento, $aluno_id){
    $status = "";
    $statement = $conn->prepare("UPDATE alunos SET nome=?,email=?,data_nascimento=? WHERE aluno_id = ?");
	$statement->bind_param("sssi", $nome, $email, $data_nascimento, $aluno_id);
	if(!$statement->execute()){
		$status = $statement->error;
	} else{
        $status = "Dados atualizados!";
	}
    return $status;
}

function deleteAluno($conn, $aluno_id){
    $status = "";
    $statement = $conn->prepare("DELETE FROM alunos WHERE aluno_id = ?");
    $statement->bind_param("i", $aluno_id);
    if(!$statement->execute()){
        $status = $statement->error;
    } else {
        $status = "Dado deletado!";
    }
    return $status;    
}
