<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .factura {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }
        .factura h1 {
            text-align: center;
            color: #0073e6;
        }
        .factura p {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
        }
        .factura .label {
            font-weight: bold;
            color: #0073e6;
        }
        .factura .info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .factura table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .factura table, th, td {
            border: 1px solid #ddd;
        }
        .factura th, td {
            padding: 12px;
            text-align: left;
        }
        .factura th {
            background-color: #0073e6;
            color: white;
        }
        .factura .total {
            text-align: right;
            font-size: 18px;
            margin-top: 20px;
        }
        .factura .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="factura">
        <h1>Factura</h1>

        <p><span class="label">Fecha:</span> {{ $fecha }}</p>
        <p><span class="label">Factura #:</span> {{ $numero_factura }}</p>
        <p><span class="label">Nombre del Vuelo:</span> {{ $nombre_vuelo }}</p>


        <div class="info">
            <p><span class="label">Email:</span> {{ $email_cliente }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto['descripcion'] }}</td>
                        <td>{{ $producto['cantidad'] }}</td>
                        <td>{{ number_format($producto['precio_unitario'], 2, ',', '.') }} €</td>
                        <td>{{ number_format($producto['total'], 2, ',', '.') }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p><span class="label">Total a Pagar:</span> {{ number_format($total, 2, ',', '.') }} €</p>
        </div>

        <div class="footer">
            <p>Gracias por su compra. ¡Esperamos verle pronto!</p>
        </div>
    </div>
</body>
</html>
