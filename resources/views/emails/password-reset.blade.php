<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer tu contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
  <table style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <tr>
      <td>
        <h2 style="color: #333333;">¿Olvidaste tu contraseña?</h2>
        <p style="color: #555555;">
          No te preocupes, puedes restablecerla con el siguiente botón. El enlace expirará en 60 minutos por seguridad.
        </p>

        <div style="text-align: center; margin: 30px 0;">
          <a href="{{ $url }}" style="background-color: #007BFF; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Restablecer contraseña
          </a>
        </div>

        <p style="color: #777777; font-size: 14px;">
          Si no solicitaste este correo, puedes ignorarlo.
        </p>
        <hr>
        <p style="font-size: 12px; color: #aaaaaa;">
          Este correo fue enviado automáticamente por el sistema. No respondas a este mensaje.
        </p>
      </td>
    </tr>
  </table>
</body>
</html>
