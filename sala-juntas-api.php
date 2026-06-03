<?php
// Proxy para webhooks de n8n - Sala de Juntas MTY
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : 'events';
$n8n_base = 'https://n8n.srv923680.hstgr.cloud/webhook';

if ($action === 'events') {
    $url = $n8n_base . '/sala-juntas';
} elseif ($action === 'book') {
    $params = array();
    foreach (array('name', 'email', 'subject', 'startDateTime', 'endDateTime') as $key) {
        if (isset($_GET[$key])) {
            $params[$key] = $_GET[$key];
        }
    }
    $url = $n8n_base . '/sala-juntas-reservar?' . http_build_query($params);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid action'));
    exit;
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(502);
    echo json_encode(array('error' => 'curl: ' . $curlError));
} elseif ($httpCode >= 200 && $httpCode < 300) {
    echo $response;
} else {
    http_response_code(502);
    echo json_encode(array('error' => 'n8n HTTP ' . $httpCode, 'body' => substr($response, 0, 500)));
}
?>
