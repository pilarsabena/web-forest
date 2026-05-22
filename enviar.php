<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? 'presupuesto';
    
    $nombre   = $_POST['nombre'] ?? '';
    $email    = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $mensaje  = $_POST['mensaje'] ?? '';
    $empresa  = $_POST['empresa'] ?? '';
    $puesto   = $_POST['puesto'] ?? '';
    
    $mail = new PHPMailer(true);
    
    try {
        // Configuración SMTP de NutHost
        $mail->isSMTP();
        $mail->Host       = 'mail.forestsrl.com.ar';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'formularios@forestsrl.com.ar';
        $mail->Password   = 'Holahola271102';   // ⚠️ Cambiá esto si es necesario
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        
        $mail->setFrom('formularios@forestsrl.com.ar', 'Forest SRL');
        $mail->addAddress('formularios@forestsrl.com.ar', 'Forest SRL');
        
        if ($tipo === 'presupuesto') {
            $mail->Subject = "Solicitud de presupuesto - $nombre";
            $cuerpo = "<h2>Nueva solicitud de presupuesto</h2>
                       <p><strong>Nombre:</strong> $nombre</p>
                       <p><strong>Empresa:</strong> $empresa</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Teléfono:</strong> $telefono</p>
                       <p><strong>Mensaje:</strong><br>$mensaje</p>";
        } else {
            $mail->Subject = "CV recibido - $nombre";
            $cuerpo = "<h2>Nuevo CV recibido</h2>
                       <p><strong>Nombre:</strong> $nombre</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Teléfono:</strong> $telefono</p>
                       <p><strong>Puesto al que aplica:</strong> $puesto</p>
                       <p><strong>Mensaje:</strong><br>$mensaje</p>";
            
            if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                $mail->addAttachment($_FILES['cv']['tmp_name'], $_FILES['cv']['name']);
            }
        }
        
        $mail->isHTML(true);
        $mail->Body = $cuerpo;
        $mail->AltBody = strip_tags($cuerpo);
        
        $mail->send();
        
        header('Location: index.html?enviado=1');
        exit;
        
    } catch (Exception $e) {
        echo "Error al enviar: {$mail->ErrorInfo}";
    }
} else {
    header('Location: index.html');
    exit;
}
