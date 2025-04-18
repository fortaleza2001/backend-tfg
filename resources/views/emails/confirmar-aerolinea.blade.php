<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmar Aerolínea</title>
</head>
<body>
    <h2>Solicitud de Confirmación de Aerolínea</h2>

    <h3>Datos de la Aerolínea</h3>
    <p><strong>Nombre:</strong> {{ $aerolinea['nombre'] ?? 'No especificado' }}</p>
    <p><strong>País:</strong> {{ $aerolinea['pais'] ?? 'No especificado' }}</p>
    <p><strong>Dirección:</strong> {{ $aerolinea['direccion'] ?? 'No especificado' }}</p>
    <p><strong>Código IATA:</strong> {{ $aerolinea['codigo_aita'] ?? 'No especificado' }}</p>
    <p><strong>Email:</strong> {{ $aerolinea['email'] ?? 'No especificado' }}</p>

    <h3>Datos del Creador</h3>
    <p><strong>Nombre:</strong> {{ $aerolinea->datosCreador->nombreCreador ?? 'No especificado' }}</p>
    <p><strong>DNI:</strong> {{ $aerolinea['datos_creador']['dniCreador'] ?? 'No especificado' }}</p>
    <p><strong>Dirección:</strong> {{ $aerolinea['datos_creador']['direccionCreador'] ?? 'No especificado' }}</p>
    <p><strong>Teléfono:</strong> {{ $aerolinea['datos_creador']['numeroTelefono'] ?? 'No especificado' }}</p>
    <p><strong>Fecha de Nacimiento:</strong> {{ $aerolinea['datos_creador']['fechaNacimientoCreador'] ?? 'No especificado' }}</p>

    <h3>Datos de Pago</h3>
    <p>✔️ Los datos de la forma de pago han sido verificados y son correctos.</p>

    <p>¿Deseas confirmar esta aerolínea?</p>

    <a href="{{ url('/aerolineas/confirmar/' . ($aerolinea['id'] ?? 0) . '/' . $aerolinea->token_confirmado) }}" style="padding: 10px 15px; background-color: green; color: white; text-decoration: none;">Aceptar</a>

    <a href="{{ url('/aerolineas/rechazar/' . ($aerolinea['id'] ?? 0) . '/' . $aerolinea->token_confirmado) }}" style="padding: 10px 15px; background-color: red; color: white; text-decoration: none; margin-left: 10px;">Rechazar</a>

</body>
</html>