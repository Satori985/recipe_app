<?php

$con = mysqli_connect('localhost', 'root', '', 'recipe');

if ($con) {
    echo "Conexão com a base de dados concluída!\n";
} else {
    echo "Erro na conexão com a base de dados\n";
    
}

mysqli_close($con);
?>