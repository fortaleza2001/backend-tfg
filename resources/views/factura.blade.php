<!DOCTYPE html>
<html>
<head>
    <title>Factura #{{ $factura->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background-color: #4CAF50; color: white; padding: 10px; }
        .content { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Factura #{{ $factura->id }}</h1>
    </div>
    <div class="content">
        <p><strong>Usuario:</strong> {{ $factura->usuario }}</p>
        <p><strong>Vuelo:</strong> {{ $factura->numero }}</p>
        <p><strong>Ticket:</strong> {{ $factura->codigo }}</p>
        <p><strong>Fecha de Emisión:</strong> {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>
