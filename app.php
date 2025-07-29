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
    echo "5 -> Criar Categoria\n";
    echo "6 -> Listar Categorias\n";
    echo "7 -> Associar Receita a Categoria\n";
    echo "8 -> Desassociar Receita de Categoria\n";
    echo "9 -> Listar Receitas por Categoria\n";
    echo "10 -> Adicionar Ingrediente\n";
    echo "11 -> Listar Ingredientes\n";
    echo "12 -> Associar Ingrediente à Receita\n";
    echo "13 -> Atualizar Ingrediente de Receita\n";
    echo "14 -> Remover Ingrediente de Receita\n";
    echo "15 -> Mostrar Detalhes de uma Receita\n";
    echo "16 -> Listar receitas por categoria (ID ou nome)\n";
    echo "17 -> Listar receitas por ingrediente\n";
    echo "18 -> Ver detalhes completos de uma receita\n";
    echo "19 -> Pesquisar receitas por parte do título\n";

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

        case 5:
            criarCategoria($con);
            break;

        case 6:
            listarCategorias($con);
            break;

        case 7:
            associarReceitaCategoria($con);
            break;

        case 8:
            desassociarReceitaCategoria($con);
            break;

        case 9:
            listarReceitasPorCategoria($con);
            break;

        case 10:
            adicionarIngrediente($con);
            break;

        case 11:
            listarIngredientes($con);
            break;

        case 12:
            associarIngredienteReceita($con);
            break;

        case 13:
            atualizarIngredienteReceita($con);
            break;

        case 14:
            removerIngredienteReceita($con);
            break;

        case 15:
            mostrarDetalhesReceita($con);
            break;

        case 16:
            listarReceitasPorNomeOuIdCategoria($con);
            break;

        case 17:
            listarReceitasPorIngrediente($con);
            break;
        case 18:
            detalhesCompletosReceita($con);
            break;
        case 19:
            pesquisarReceitasPorTitulo($con);
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

    $sql = "DELETE FROM receita WHERE id = $id";
    if (mysqli_query($con, $sql)) {
        echo "Receita removida com sucesso!\n";
    } else {
        echo "Erro ao remover receita: " . mysqli_error($con) . "\n";
    }
}

function criarCategoria($con)
{
    echo "\nCriar nova categoria\n";
    $nome = readline("Nome da categoria: ");

    $sql = "INSERT INTO categoria (nome) VALUES ('$nome')";
    if (mysqli_query($con, $sql)) {
        echo "Categoria criada com sucesso!\n";
    } else {
        echo "Erro ao criar categoria: " . mysqli_error($con) . "\n";
    }
}

function listarCategorias($con)
{
    echo "\nLista de categorias:\n";

    $sql = "SELECT * FROM categoria";
    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id']} | Nome: {$linha['nome']}\n";
        }
    } else {
        echo "Nenhuma categoria encontrada.\n";
    }
}

function associarReceitaCategoria($con)
{
    echo "\nAssociar Receita a Categoria\n";
    listarReceitas($con, false);
    $id_receita = readline("ID da receita: ");

    listarCategorias($con);
    $id_categoria = readline("ID da categoria: ");

    $sql = "INSERT INTO receita_categoria (id_receita, id_categoria) 
            VALUES ($id_receita, $id_categoria)";

    if (mysqli_query($con, $sql)) {
        echo "Associação realizada com sucesso!\n";
    } else {
        echo "Erro ao associar: " . mysqli_error($con) . "\n";
    }
}

function desassociarReceitaCategoria($con)
{
    echo "\nDesassociar Receita de Categoria\n";
    $id_receita = readline("ID da receita: ");
    $id_categoria = readline("ID da categoria: ");

    $sql = "DELETE FROM receita_categoria 
            WHERE id_receita = $id_receita AND id_categoria = $id_categoria";

    if (mysqli_query($con, $sql)) {
        echo "Desassociação realizada com sucesso!\n";
    } else {
        echo "Erro ao desassociar: " . mysqli_error($con) . "\n";
    }
}

function listarReceitasPorCategoria($con)
{
    echo "\nListar receitas por categoria\n";
    listarCategorias($con);
    $id_categoria = readline("ID da categoria: ");

    $sql = "SELECT r.id, r.nome, r.duracao, r.doses, r.modo_preparacao 
            FROM receita r
            JOIN receita_categoria rc ON r.id = rc.id_receita
            WHERE rc.id_categoria = $id_categoria";

    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id']} | Nome: {$linha['nome']} | Duração: {$linha['duracao']} | Doses: {$linha['doses']}\n";
            echo "Modo de preparação: {$linha['modo_preparacao']}\n";
            echo "---------------------------\n";
        }
    } else {
        echo "Nenhuma receita encontrada para essa categoria.\n";
    }
}

function adicionarIngrediente($con)
{
    echo "\nAdicionar novo ingrediente\n";
    $nome = readline("Nome do ingrediente: ");

    $sql = "INSERT INTO ingrediente (nome) VALUES ('$nome')";
    if (mysqli_query($con, $sql)) {
        echo "Ingrediente adicionado com sucesso!\n";
    } else {
        echo "Erro ao adicionar ingrediente: " . mysqli_error($con) . "\n";
    }
}

function listarIngredientes($con)
{
    echo "\nLista de ingredientes:\n";

    $sql = "SELECT * FROM ingrediente";
    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id']} | Nome: {$linha['nome']}\n";
        }
    } else {
        echo "Nenhum ingrediente encontrado.\n";
    }
}

function associarIngredienteReceita($con)
{
    echo "\nAssociar Ingrediente à Receita\n";
    listarReceitas($con, false);
    $id_receita = readline("ID da receita: ");

    listarIngredientes($con);
    $id_ingrediente = readline("ID do ingrediente: ");

    $quantidade = readline("Quantidade: ");
    $unidade_de_medida = readline("Unidade (ex: g, ml, colheres): ");

    $sql = "INSERT INTO receita_ingrediente (id_receita, id_ingrediente, quantidade, unidade)
            VALUES ($id_receita, $id_ingrediente, $quantidade, '$unidade_de_medida')";

    if (mysqli_query($con, $sql)) {
        echo "Ingrediente associado com sucesso!\n";
    } else {
        echo "Erro ao associar ingrediente: " . mysqli_error($con) . "\n";
    }
}

function atualizarIngredienteReceita($con)
{
    echo "\nAtualizar ingrediente da receita\n";
    $id_receita = readline("ID da receita: ");
    $id_ingrediente = readline("ID do ingrediente: ");

    $quantidade = readline("Nova quantidade: ");
    $unidade_de_medida = readline("Nova unidade: ");

    $sql = "UPDATE receita_ingrediente
            SET quantidade = $quantidade, unidade = '$unidade_de_medida'
            WHERE id_receita = $id_receita AND id_ingrediente = $id_ingrediente";

    if (mysqli_query($con, $sql)) {
        echo "Ingrediente atualizado com sucesso!\n";
    } else {
        echo "Erro ao atualizar ingrediente: " . mysqli_error($con) . "\n";
    }
}

function removerIngredienteReceita($con)
{
    echo "\nRemover ingrediente de uma receita\n";
    $id_receita = readline("ID da receita: ");
    $id_ingrediente = readline("ID do ingrediente: ");

    $sql = "DELETE FROM receita_ingrediente
            WHERE id_receita = $id_receita AND id_ingrediente = $id_ingrediente";

    if (mysqli_query($con, $sql)) {
        echo "Ingrediente removido da receita com sucesso!\n";
    } else {
        echo "Erro ao remover ingrediente: " . mysqli_error($con) . "\n";
    }
}

function mostrarDetalhesReceita($con)
{
    echo "\nDetalhes da Receita\n";
    listarReceitas($con, false);
    $id = readline("ID da receita: ");

    // Receita principal
    $sql = "SELECT * FROM receita WHERE id = $id";
    $resultado = mysqli_query($con, $sql);

    if ($linha = mysqli_fetch_assoc($resultado)) {
        echo "ID: {$linha['id']} | Nome: {$linha['nome']} | Duração: {$linha['duracao']} | Doses: {$linha['doses']}\n";
        echo "Modo de preparação: {$linha['modo_preparacao']}\n";
    } else {
        echo "Receita não encontrada.\n";
        return;
    }

    // Ingredientes | receita.ingrediente
    echo "\nIngredientes:\n";
    $sql_ing = "SELECT i.nome, ri.quantidade, ri.unidade_de_medida
                FROM receita_ingrediente ri
                JOIN ingrediente i ON ri.id_ingrediente = i.id
                WHERE ri.id_receita = $id";

    $resultado_ing = mysqli_query($con, $sql_ing);

    if (mysqli_num_rows($resultado_ing) > 0) {
        while ($ing = mysqli_fetch_assoc($resultado_ing)) {
            echo "- {$ing['nome']}: {$ing['quantidade']} {$ing['unidade_de_medida']}\n";
        }
    } else {
        echo "Sem ingredientes associados.\n";
    }
}

function listarReceitasPorNomeOuIdCategoria($con)
{
    echo "\nListar receitas por nome ou ID da categoria\n";
    $input = readline("Digite o ID ou nome da categoria: ");

    if (is_numeric($input)) {
        $sql = "SELECT r.id, r.nome, r.duracao, r.doses 
                FROM receita r
                JOIN receita_categoria rc ON r.id = rc.id_receita
                WHERE rc.id_categoria = $input";
    } else {
        $sql = "SELECT r.id, r.nome, r.duracao, r.doses 
                FROM receita r
                JOIN receita_categoria rc ON r.id = rc.id_receita
                JOIN categoria c ON c.id = rc.id_categoria
                WHERE LOWER(c.nome) = LOWER('$input')";
    }

    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id']} | Nome: {$linha['nome']} | Duração: {$linha['duracao']} | Doses: {$linha['doses']}\n";
        }
    } else {
        echo "Nenhuma receita encontrada para essa categoria.\n";
    }
}

function listarReceitasPorIngrediente($con)
{
    echo "\nListar receitas por ingrediente\n";
    $nome = readline("Digite o nome do ingrediente: ");

    $sql = "SELECT DISTINCT r.id, r.nome, r.duracao, r.doses
            FROM receita r
            JOIN receita_ingrediente ri ON r.id = ri.id_receita
            JOIN ingrediente i ON i.id = ri.id_ingrediente
            WHERE LOWER(i.nome) = LOWER('$nome')";

    $resultado = mysqli_query($con, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id']} | Nome: {$linha['nome']} | Duração: {$linha['duracao']} | Doses: {$linha['doses']}\n";
        }
    } else {
        echo "Nenhuma receita encontrada com esse ingrediente.\n";
    }
}

function detalhesCompletosReceita($con)
{
    echo "\nDetalhes completos de uma receita\n";
    $input = readline("Digite o ID ou nome da receita: ");

    if (is_numeric($input)) {
        $sql = "SELECT * FROM receita WHERE id = $input";
    } else {
        $sql = "SELECT * FROM receita WHERE LOWER(nome) = LOWER('$input')";
    }

    $resultado = mysqli_query($con, $sql);
    if (mysqli_num_rows($resultado) == 0) {
        echo "Receita não encontrada.\n";
        return;
    }

    $linha = mysqli_fetch_assoc($resultado);
    $id_receita = $linha['id'];

    echo "\nReceita: {$linha['nome']}\n";
    echo "Duração: {$linha['duracao']} | Doses: {$linha['doses']}\n";
    echo "Modo de preparação:\n{$linha['modo_preparacao']}\n";

    echo "\nIngredientes:\n";
    $sql_ing = "SELECT i.nome, ri.quantidade, ri.unidade_de_medida
                FROM receita_ingrediente ri
                JOIN ingrediente i ON ri.id_ingrediente = i.id
                WHERE ri.id_receita = $id_receita";

    $resultado_ing = mysqli_query($con, $sql_ing);
    if (mysqli_num_rows($resultado_ing) > 0) {
        while ($ing = mysqli_fetch_assoc($resultado_ing)) {
            echo "- {$ing['nome']}: {$ing['quantidade']} {$ing['unidade_de_medida']}\n";
        }
    } else {
        echo "Nenhum ingrediente encontrado.\n";
    }

    echo "\nCategorias:\n";
    $sql_cat = "SELECT c.nome 
                FROM categoria c
                JOIN receita_categoria rc ON rc.id_categoria = c.id
                WHERE rc.id_receita = $id_receita";
    $resultado_cat = mysqli_query($con, $sql_cat);
    if (mysqli_num_rows($resultado_cat) > 0) {
        while ($cat = mysqli_fetch_assoc($resultado_cat)) {
            echo "- {$cat['nome']}\n";
        }
    } else {
        echo "Nenhuma categoria associada.\n";
    }
}

function pesquisarReceitasPorTitulo($con)
{
    echo "\nPesquisar receitas por parte do título\n";
    $busca = readline("Digite parte do nome da receita: ");

    $sql = "SELECT * FROM receita WHERE LOWER(nome) LIKE LOWER('%$busca%')";

    $resultado = mysqli_query($con, $sql);
    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id']} | Nome: {$linha['nome']} | Duração: {$linha['duracao']} | Doses: {$linha['doses']}\n";
        }
    } else {
        echo "Nenhuma receita encontrada com esse título.\n";
    }
}

function voltarMenu()
{
    echo "Digite 0 para voltar ao menu: ";
    while (readline("") != "0") {}
}

mysqli_close($con);
?>