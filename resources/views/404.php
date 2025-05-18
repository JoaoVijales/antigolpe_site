<?php // TODO: Refatorar - Esta view de erro não utiliza o layout base (layouts/base.php) e contém estilos CSS inline. Refatorar para usar o layout base e mover os estilos para um arquivo CSS externo para consistência e manutenção.
 ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página não encontrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e74c3c;
            font-size: 3rem;
            margin: 0;
        }
        p {
            color: #666;
            margin: 1rem 0;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <p>Página não encontrada</p>
        <p>A página que você está procurando não existe ou foi movida.</p>
        <a href="/">Voltar para a página inicial</a>
    </div>
</body>
</html> 