<?php
require_once __DIR__ . '/../lib/OnePEY.php';
require_once __DIR__ . '/test_shop_data.php';

\OnePEY\Logger::getInstance()->setLogLevel(\OnePEY\Logger::DEBUG);

$transaction = new \OnePEY\PaymentOperation;

$amount = rand(2, 20);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('USD');
//$transaction->setDescription('Trx desc '.rand(100,1000));
$transaction->setTrackingId('ORDER-'.date('ymdHis'));

$transaction->card->setCardNumber('4111110000000112');
$transaction->card->setCardHolder('John Doe');
$transaction->card->setCardExpMonth(1);
$transaction->card->setCardExpYear(2030);
$transaction->card->setCardCvc('001');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('GB');
$transaction->customer->setAddress('Demo Street 12');
$transaction->customer->setCity('London');
$transaction->customer->setZip('ATE223');
$transaction->customer->setIp('127.0.0.1');
$transaction->customer->setEmail('john@example.com');
$transaction->customer->setPhone('+441234567890');

$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);
print("Transaction status: " . $response->getStatus(). PHP_EOL);

if ($response->isSuccess() || $response->isFailed() ) {
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
}
?>
