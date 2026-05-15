<?php

function sendOrderConfirmation($to_email, $to_name, $comanda_id, $items, $total) {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mcorinaaaa@gmail.com';
        $mail->Password = 'voaqdbgbirjduozg';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('mcorinaaaa@gmail.com', 'Skincare Shop');
        $mail->addAddress($to_email, $to_name);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmare comanda #' . $comanda_id . ' - Skincare Shop';

        $body = '<h2>Buna ziua, ' . $to_name . '!</h2>';
        $body .= '<p>Comanda ta <strong>#' . $comanda_id . '</strong> a fost plasata cu succes!</p>';
        $body .= '<h3>Produse comandate:</h3>';
        $body .= '<table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse;width:100%">';
        $body .= '<tr><th>Produs</th><th>Cantitate</th><th>Pret</th></tr>';

        foreach($items as $item) {
            $body .= '<tr>';
            $body .= '<td>' . $item['nume'] . '</td>';
            $body .= '<td>' . $item['cantitate'] . '</td>';
            $body .= '<td>' . number_format($item['pret_la_moment'] * $item['cantitate'], 2) . ' lei</td>';
            $body .= '</tr>';
        }

        $body .= '</table>';
        $body .= '<p><strong>Total: ' . number_format($total, 2) . ' lei</strong></p>';
        $body .= '<p>Multumim pentru comanda!</p>';
        $body .= '<p>Echipa Skincare Shop</p>';

        $mail->Body = $body;
        $mail->send();
        return true;

    } catch(Exception $e) {
        return false;
    }
}

function sendOrderConfirmationCustom($to_email, $to_name, $subject, $body) {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mcorinaaaa@gmail.com';
        $mail->Password = 'voaqdbgbirjduozg';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('mcorinaaaa@gmail.com', 'Skincare Shop');
        $mail->addAddress($to_email, $to_name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;

    } catch(Exception $e) {
        return false;
    }
}