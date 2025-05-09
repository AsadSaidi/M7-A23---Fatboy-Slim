<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response){
    $pdo = new PDO("sqlite:" . __DIR__ . "/../Slim/data/database.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta a la tabla cantantes
    $stmt = $pdo->query("SELECT * FROM cantantes");
    $cantantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // HTML y CSS
    $htmlContent = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Cantantes</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 2rem;
                color: #333;
            }
            h1 {
                text-align: center;
                color: #222;
            }
            .cantante {
                position: relative;
                background-color: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                padding: 20px;
                margin: 20px auto;
                max-width: 700px;
                display: flex;
                gap: 20px;
                align-items: flex-start;
            }
            .cantante img {
                border-radius: 10px;
            }
            .info {
                flex: 1;
            }
            .info h2 {
                margin-top: 0;
                color: #111;
            }
            .info p {
                margin: 4px 0;
            }
            .spotify-link {
                position: absolute;
                top: 15px;
                right: 15px;
            }
            .spotify-link img {
                width: 30px;
                transition: transform 0.2s ease;
            }
            .spotify-link img:hover {
                transform: scale(1.2);
            }
        </style>
    </head>
    <body>
        <h1>Cantantes</h1>
    ";

    // Contenido dinámico
    foreach ($cantantes as $cantante) {
        $htmlContent .= "
        <div class='cantante'>
            <a class='spotify-link' href='" . htmlspecialchars($cantante['link_spotify']) . "' target='_blank'>
                <img src='https://cdn-icons-png.flaticon.com/512/174/174872.png' alt='Spotify'>
            </a>
            <img src='" . htmlspecialchars($cantante['url_imagen_spotify']) . "' alt='imagen cantante' width='150'>
            <div class='info'>
                <h2>" . htmlspecialchars($cantante['nombre_artistico']) . "</h2>
                <p><strong>Nombre real:</strong> " . htmlspecialchars($cantante['nombre_real']) . "</p>
                <p><strong>País:</strong> " . htmlspecialchars($cantante['pais']) . "</p>
                <p><strong>Género:</strong> " . htmlspecialchars($cantante['genero']) . "</p>
                <p><strong>Biografía:</strong> " . htmlspecialchars($cantante['biografia']) . "</p>
            </div>
        </div>
        ";
    }

    $htmlContent .= "</body></html>";

    $response->getBody()->write($htmlContent);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->run();
?>
