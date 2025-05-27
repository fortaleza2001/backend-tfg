<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Compra</title>
    <style>
       /* Estilos Generales */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9fafb;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.ticket {
    background-color: #ffffff;
    border-radius: 16px;
    padding: 40px;
    width: 100%;
    max-width: 700px;
    margin: 40px auto;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    font-size: 18px;
    display: flex;
    flex-direction: column;
}

.ticket h1 {
    text-align: center;
    color: #0056b3;
    font-size: 36px;
    font-weight: 600;
    margin-bottom: 30px;
}

.ticket p {
    margin: 12px 0;
    font-size: 18px;
    color: #444;
}

.ticket .label {
    font-weight: 600;
    color: #0056b3;
}

.ticket .info {
    margin-top: 20px;
    padding: 24px;
    background-color: #f1f5f9;
    border-radius: 10px;
    margin-bottom: 20px;
}

.ticket .info p {
    font-size: 16px;
}

.ticket .footer {
    text-align: center;
    font-size: 16px;
    color: #777;
    margin-top: 40px;
}

.ticket .qr {
    text-align: center;
    margin-top: 30px;
}

.ticket .qr img {
    width: 160px;
    height: 160px;
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    margin-top: 20px;
}

.ticket .section-title {
    font-size: 20px;
    margin-bottom: 12px;
    font-weight: 700;
    color: #333;
}

/* Corregir alineación de pie de página */
.ticket .footer p {
    font-style: italic;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="ticket">
        <h1>Ticket de Compra</h1>

        <!-- Detalles del Vuelo -->
        <p class="section-title">Detalles del Vuelo</p>
        <p><span class="label">Vuelo:</span> {{ $vuelo }}</p>
        <p><span class="label">Fecha de salida:</span> {{ $fecha_salida }}</p>
        <p><span class="label">Desde:</span> {{ $desde }}</p>
        <p><span class="label">Destino:</span> {{ $destino }}</p>

        <!-- Información del Comprador -->
        <div class="info">
            <p><span class="label">Comprador:</span> {{ $comprador }}</p>
            <p><span class="label">Email:</span> {{ $email }}</p>
            <p><span class="label">Precio:</span> {{ $precio }}</p>
        </div>

        <!-- Código QR -->
        <div class="qr">
            <p><span class="label">Código QR:</span></p>
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
        </div>

        <!-- Pie de Página -->
        <div class="footer">
            <p>Gracias por comprar con nosotros. ¡Buen viaje!</p>
        </div>
    </div>
</body>
</html>
