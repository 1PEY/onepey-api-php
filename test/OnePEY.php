<?php
echo "Running the 1PEY PHP bindings test suite.\n".
     "If you're trying to use the PHP bindings you'll probably want ".
     "to require('lib/OnePEY.php'); instead of this file\n\n" .
     "Setup the env variable LOG_LEVEL=DEBUG for more verbose output\n" ;

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok) {
  echo "MISSING DEPENDENCY: The 1PEY API test cases depend on SimpleTest. ".
       "Download it at <http://www.simpletest.org/>, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}

require_once(dirname(__FILE__) . '/../lib/OnePEY.php');
// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline) {
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/OnePEY.php');


$log_level = getenv('LOG_LEVEL');

if ($log_level == 'DEBUG') {
  \OnePEY\Logger::getInstance()->setLogLevel(\OnePEY\Logger::DEBUG);
} else {
  \OnePEY\Logger::getInstance()->setLogLevel(\OnePEY\Logger::INFO);
}

require_once(dirname(__FILE__) . '/OnePEY/TestCase.php');
require_once(dirname(__FILE__) . '/OnePEY/MoneyTest.php');
require_once(dirname(__FILE__) . '/OnePEY/CustomerTest.php');
require_once(dirname(__FILE__) . '/OnePEY/AuthorizationOperationTest.php');
require_once(dirname(__FILE__) . '/OnePEY/PaymentOperationTest.php');
require_once(dirname(__FILE__) . '/OnePEY/CaptureOperationTest.php');
require_once(dirname(__FILE__) . '/OnePEY/VoidOperationTest.php');
require_once(dirname(__FILE__) . '/OnePEY/RefundOperationTest.php');
require_once(dirname(__FILE__) . '/OnePEY/QueryByUidTest.php');
require_once(dirname(__FILE__) . '/OnePEY/WebhookTest.php');
require_once(dirname(__FILE__) . '/OnePEY/GatewayExceptionTest.php');
require_once(dirname(__FILE__) . '/OnePEY/PaymentMethod/CreditCardTest.php');
?>
