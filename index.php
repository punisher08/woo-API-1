<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'http://compaireplugin.test', 
    'ck_66a6b22afe1275d77af4bc46381370a1582aa8f0', 
    'cs_704ff9f6065a7a6ad20981846b7abfa7b055d6b3',
    [
        // 'version' => 'wc/v3',
        'wp_api' => true,
        'version' => 'wc/v3',
        'timeout' => 400
    ]
);
echo '<pre>';
print_r($woocommerce->get('products'));
echo '</pre>';

?>
</body>
</html>