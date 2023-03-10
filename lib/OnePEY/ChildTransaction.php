<?php
namespace OnePEY;

abstract class ChildTransaction extends ApiAbstract {
  protected $_parent_uid;
  public $money;

  public function __construct() {
    $this->money = new Money();
  }

  public function setParentUid($uid) {
    $this->_parent_uid = $uid;
  }

  public function getParentUid() {
    return $this->_parent_uid;
  }

  protected function _buildRequestMessage() {
    $request = array(
        'amount' => $this->money->getAmount(),
        'transactionID' => $this->getParentUid(),
    );

    Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

    return $request;
  }
}
?>
