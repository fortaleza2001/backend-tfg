<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
    }

    /**
     * Iniciar proceso de compra
     */
    public function buyFlight()
    {
        $amount = 199.99; // Puedes pasarlo desde request o base de datos

        // Crear la orden en PayPal
        $order = $this->paypal->createOrder($amount);

        // Buscar el link de aprobación
        $approvalLink = collect($order['links'])->firstWhere('rel', 'approve')['href'];

        // Redirigir al cliente al link de aprobación (para pagar con tarjeta)
        return redirect($approvalLink);
    }

    /**
     * Confirmación de pago exitoso
     */
    public function success(Request $request)
    {
        $token = $request->query('token');  // Obtener el token de la URL

        // Capturar la orden usando el token recibido
        $capture = $this->paypal->captureOrder($token);

        // Validar si la orden fue capturada correctamente
        if (isset($capture['status']) && $capture['status'] === 'COMPLETED') {
            // Aquí podrías guardar la orden en la base de datos, asociarla a un vuelo, etc.
            return response()->json([
                'message' => 'Pago completado correctamente.',
                'paypal_response' => $capture,
            ]);
        }

        // Si algo falla al capturar el pago
        return response()->json([
            'message' => 'Error al capturar el pago.',
            'paypal_response' => $capture,
        ], 400);
    }

    /**
     * Cancelación del pago
     */
    public function cancel()
    {
        return response()->json([
            'message' => 'Pago cancelado por el usuario.',
        ]);
    }
}
