<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Sin título') ?></title>
</head>
<body>
    <h1>Hola desde la vista</h1>
    <p>Título recibido: <?= htmlspecialchars($title ?? 'No definido') ?></p>
</body>
</html>
