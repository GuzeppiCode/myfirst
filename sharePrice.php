<?php
$aaa = $_GET["company"];
$url = 'https://www.borzamalta.com.mt/reports/' .  $aaa ;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Connection timeout
curl_setopt($ch, CURLOPT_TIMEOUT, 15);       // Response timeout

$response = curl_exec($ch);

if (curl_errno($ch)) {
    error_log('cURL Error: ' . curl_error($ch));
    echo 'Error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "Failed to fetch data. HTTP Status Code: $httpCode";
    exit;
}

if (empty($response)) {
    echo 'No response from the server.';
    exit;
}

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($response);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

// Adjusted XPath query
$priceNode = $xpath->query('//tr[td[b[contains(text(), "Price")]]]/td[@class="text-right"]');

if ($priceNode->length > 0) {
    $price = trim($priceNode->item(0)->textContent);
    echo $price;
} else {
    error_log("Price not found. Response:\n" . $response);
    echo 'Price not found. Please check the XPath or source page structure.';
}
?>
