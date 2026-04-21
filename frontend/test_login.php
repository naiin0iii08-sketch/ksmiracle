<?php
$data = ['username' => 'admin2', 'password' => '12345678'];
$ch = curl_init('http://localhost/ksmiracle/frontend/api/login.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
echo curl_exec($ch);
?>
