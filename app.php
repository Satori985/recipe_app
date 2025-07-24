<?php

$con = mysqli_connect('localhost', 'root', '', 'recipe');

if ($con) {
    echo "Conexão com a base de dados concluída!\n";
} else {
    echo "Erro na conexão com a base de dados\n";
    
}

$fim = false;

while (!$fim) {
    // Menu
    echo "\nEscolha uma opção:\n";
    echo "1 -> Inserir uma Receita\n";
    echo "2 -> Listar Receitas\n";
    echo "3 -> Atualizar Receita\n";
    echo "4 -> Remover Receita\n";
    echo "0 -> Sair do programa\n";

    $menu = readline("Opção: ");

    switch ($menu) {
        case 0:
            echo "Adeus!\n";
            $fim = true;
            break;

        case 1:
            criarReceita($con);
            break;

        case 2:
            listarReceitas($con, true);
            break;

        case 3:
            atualizarReceita($con);
            break;

        case 4:
            removerReceita($con);
            break;

        default:
            echo "Opção inválida!\n";
            break;
    }
}

// --- Funções ---

function criarReceita($con)
{
    echo "\nInserir nova receita\n";
    $nome = readline("Nome da receita: ");
    $modo = readline("Modo de preparação: ");
    $duracao = readline("Duração: ");
    $doses = readline("Doses: ");

    $sql = "INSERT INTO receita (nome, modo_preparacao, duracao, doses) 
            VALUES ('$nome', '$modo', '$duracao', $doses)";
    
    if (mysqli_query($con, $sql)) {
        echo "Receita inserida com sucesso!\n";
    } else {
        echo "Erro ao inserir receita: " . mysqli_error($con) . "\n";
    }
}

function listarReceitas($con, $voltarMenu)
{
    echo "\nLista de receitas:\n";

    $sql = "SELECT * FROM receita";
    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id']} | Nome: {$linha['nome']} | Duração: {$linha['duracao']} | Doses: {$linha['doses']}\n";
            echo "Modo de preparação: {$linha['modo_preparacao']}\n";
            echo "---------------------------\n";
        }
    } else {
        echo "Nenhuma receita encontrada.\n";
    }

    if ($voltarMenu) {
        voltarMenu();
    }
}

function atualizarReceita($con)
{
    echo "\nAtualizar receita\n";
    listarReceitas($con, false);

    $id = readline("ID da receita a atualizar: ");
    $sql_verifica = "SELECT * FROM receita WHERE id = $id";
    $verificacao = mysqli_query($con, $sql_verifica);

    if (mysqli_num_rows($verificacao) == 0) {
        echo "Receita não encontrada.\n";
        return;
    }

    $nome = readline("Novo nome: ");
    $modo = readline("Novo modo de preparação: ");
    $duracao = readline("Nova duração: ");
    $doses = readline("Novo nº de doses: ");

    $sql = "UPDATE receita 
            SET nome='$nome', modo_preparacao='$modo', duracao='$duracao', doses=$doses 
            WHERE id=$id";
    
    if (mysqli_query($con, $sql)) {
        echo "Receita atualizada com sucesso!\n";
    } else {
        echo "Erro ao atualizar receita: " . mysqli_error($con) . "\n";
    }
}

function removerReceita($con)
{
    echo "\nRemover receita\n";
    listarReceitas($con, false);

    $id = readline("ID da receita a remover: ");
    $sql_verifica = "SELECT id FROM receita WHERE id = $id";
    $verificacao = mysqli_query($con, $sql_verifica);

    if (mysqli_num_rows($verificacao) == 0) {
        echo "Receita não encontrada.\n";
        return;
    }

    // Remover relações nas tabelas associativas
    mysqli_query($con, "DELETE FROM receita_ingrediente WHERE id_receita = $id");
    mysqli_query($con, "DELETE FROM receita_categoria WHERE id_receita = $id");

    // Remover receita
    $sql = "DELETE FROM receita WHERE id = $id";
    if (mysqli_query($con, $sql)) {
        echo "Receita removida com sucesso!\n";
    } else {
        echo "Erro ao remover receita: " . mysqli_error($con) . "\n";
    }
}

function voltarMenu()
{
    echo "Digite 0 para voltar ao menu: ";
    while (readline("") != "0") {}
}

mysqli_close($con);
?>