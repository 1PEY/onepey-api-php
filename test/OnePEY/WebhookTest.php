<?php
namespace OnePEY;

class WebhookTest extends TestCase {

  public function test_WebhookIsSentWithCorrectCredentials() {
   
    $w = $this->getTestObjectInstance();
    parse_str($this->webhookMessage(), $testWebhookMessage);
    
    $reflection = new \ReflectionClass('OnePEY\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);

    $this->assertTrue($w->isAuthorized());
  }
    
  public function test_WebhookIsSentWithIncorrectCredentials() {

    $w = $this->getTestObjectInstance();
    parse_str($this->webhookMessage('failed', '1234567890123456789012345678901234567890'), $testWebhookMessage);

    $reflection = new \ReflectionClass('OnePEY\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);

    $this->assertFalse($w->isAuthorized());
  }

  public function test_RequestIsValidAndItIsSuccess() {
    
  	$w = $this->getTestObjectInstance();
  	parse_str($this->webhookMessage(), $testWebhookMessage);

    $reflection = new \ReflectionClass('OnePEY\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);

    $property2 = $reflection->getProperty('_response');
    $property2->setAccessible(true);
    $property2->setValue($w,json_decode(json_encode($testWebhookMessage)));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isSuccess());
    $this->assertNotNull($w->getUid());
    $this->assertEqual($w->getStatus(), '1');
  }


  public function test_RequestIsValidAndItIsDeclined() {

  	$w = $this->getTestObjectInstance();
  	parse_str($this->webhookMessage('failed'), $testWebhookMessage);

    $reflection = new \ReflectionClass('OnePEY\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);
    
    $property2 = $reflection->getProperty('_response');
    $property2->setAccessible(true);
    $property2->setValue($w,json_decode(json_encode($testWebhookMessage)));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isDeclined());
    $this->assertNotNull($w->getUid());
    $this->assertEqual($w->getStatus(), '2');

  }


  protected function getTestObjectInstance() {
    self::authorizeFromEnv();

    return new Webhook();
  }

  private function webhookMessage($status = 'successful', $pSign = null ) {
  	
    if ($status == 'successful') {
      $responseCode = '1';
      $reasonCode = '1';
    } else {
      $responseCode = '2';
      $reasonCode = '2';
    }
    
     $ipnParams = array(
        'responseCode' => ''.$responseCode,
        'reasonCode' => ''.$reasonCode,
        'transactionID' => date('Ymd').'-015F95668EF6280D447E-37AD3938DC130CB20206',
        'amount' => '10.00',
        'currency' => 'USD',
        'orderID' => '118_44664e5619fc51c0f45992309992589b',
        'executed' => date('Y-m-d H:i:s')
     );

    if ($pSign == null)
        $pSign = hash(Settings::$pSignAlgorithm, Settings::$passCode.Settings::$merchantId.implode('',array_values($ipnParams)));
    
    $ipnParams = array_merge($ipnParams, ['pSign' => $pSign]);
    
    echo 'WebHookTest.php -> ' . print_r($ipnParams, true);
    return http_build_query($ipnParams);
  }
}
?>
