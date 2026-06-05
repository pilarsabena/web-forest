<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$tipo    = $_POST['tipo'] ?? '';
$nombre  = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''), ENT_QUOTES, 'UTF-8');

if (!$nombre || !$email) {
    header('Location: index.html');
    exit;
}

$destino = 'formularios@forestsrl.com.ar';

// ── COTIZACIÓN ─────────────────────────────────────────────────────────────
if ($tipo === 'presupuesto') {
    $empresa = htmlspecialchars(trim($_POST['empresa'] ?? ''), ENT_QUOTES, 'UTF-8');
    $mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''), ENT_QUOTES, 'UTF-8');

    $asunto = "=?UTF-8?B?" . base64_encode("Nueva cotización – $nombre") . "?=";
    $cuerpo  = "Nueva solicitud de cotización\n";
    $cuerpo .= "==============================\n";
    $cuerpo .= "Nombre:   $nombre\n";
    $cuerpo .= "Empresa:  $empresa\n";
    $cuerpo .= "Email:    $email\n";
    $cuerpo .= "Teléfono: $telefono\n\n";
    $cuerpo .= "Servicio requerido:\n$mensaje\n";

    $headers  = "From: noreply@forestsrl.com.ar\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";

    mail($destino, $asunto, $cuerpo, $headers);

    header('Location: index.html?enviado=1&tipo=presupuesto'
         . '&nombre=' . urlencode($nombre)
         . '&email='  . urlencode($email));
    exit;
}

// ── CV ─────────────────────────────────────────────────────────────────────
if ($tipo === 'cv') {
    $puesto = htmlspecialchars(trim($_POST['puesto'] ?? ''), ENT_QUOTES, 'UTF-8');

    $asunto = "=?UTF-8?B?" . base64_encode("Nuevo CV – $nombre") . "?=";
    $cuerpo  = "Nueva postulación\n";
    $cuerpo .= "==============================\n";
    $cuerpo .= "Nombre:   $nombre\n";
    $cuerpo .= "Email:    $email\n";
    $cuerpo .= "Teléfono: $telefono\n";
    $cuerpo .= "Puesto:   $puesto\n";

    $cvOk = isset($_FILES['cv'])
         && $_FILES['cv']['error'] === UPLOAD_ERR_OK
         && strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION)) === 'pdf';

    if ($cvOk) {
        $boundary = '----=_Part_' . md5(uniqid());
        $filename = basename($_FILES['cv']['name']);
        $filedata = chunk_split(base64_encode(file_get_contents($_FILES['cv']['tmp_name'])));

        $headers  = "From: noreply@forestsrl.com.ar\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $body  = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $cuerpo . "\r\n";
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: application/pdf; name=\"$filename\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
        $body .= $filedata . "\r\n";
        $body .= "--$boundary--";

        mail($destino, $asunto, $body, $headers);
    } else {
        $headers  = "From: noreply@forestsrl.com.ar\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        mail($destino, $asunto, $cuerpo, $headers);
    }

    header('Location: index.html?enviado=1&tipo=cv');
    exit;
}

header('Location: index.html');
exit;
