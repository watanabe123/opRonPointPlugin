<?php

/**
 * PluginPointExchange form.
 *
 * @package    opRonPointPlugin
 * @subpackage form
 * @author     Shouta Kashiwagi <kashiwagi@openpne.jp>
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginPointExchangeForm extends BasePointExchangeForm
{
  protected $pointItem;
  
  public function setup()
  {
    parent::setup();
    if (!($this->getOption('pointItem') instanceof PointItem))
    {   
      throw new LogicException('PointExchangeForm needs PointItem object by option'); 
    }   

    
    unset($this['id']);
    if ($this->getOption('pointItem')->getIsInputAddress())
    {
      $this->useFields(array('pref', 'address', 'tel', 'real_name'));
    }
    else
    {
      $this->useFields(array('real_name'));
    }
    
    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'validatePointBalance'))));
    
  }
  
  public function setPointItem(PointItem $item)
  {
    $this->pointItem = $item;
  }
  
  public function validatePointBalance($validator, $values)
  {
    if ($this->getObject()->getMember() && $this->pointItem)
    {
      $balancePoints = opPointUtil::getPoint($this->getObject()->getMember()->getId());
      $orderPoints = $this->pointItem->getPoints();
      if($orderPoints > $balancePoints)
      {
        throw new sfValidatorError($validator, 'You do not have enough points to get the item.');
      }
    }
    return $values;
  }

  /**
   * for freeze feature
   */
  public $isFreeze = false;
  protected static $valuesBeforeFreeze = array();
  protected static $widgetSchemaBeforeFreeze = null;
  public function setHiddenAll()
  {
    self::$valuesBeforeFreeze = $this->getValues();
    self::$widgetSchemaBeforeFreeze = clone $this->getWidgetSchema();
    foreach ($this->getWidgetSchema()->getFields() as $id => $v) {
      // CSRF token is set by automate, so except !
      if ($this->getCSRFFieldName() == $id) continue;
      $this->widgetSchema[$id] = new sfWidgetFormInputHidden(array(), array('value' => $this->getValue($id)));
    }
  }
  public function unfreeze()
  {
    $this->setwidgetSchema(self::$widgetSchemaBeforeFreeze);
    $this->setDefaults(self::$valuesBeforeFreeze);
    $this->isFreeze = false;
  }
  public function freeze()
  {
    $this->setHiddenAll();
    $this->isFreeze = true;
   }  

  public function save($conn = null)
  {
    $conn = $this->getObject()->getTable()->getConnection();
    $conn->beginTransaction();
    try
    {
      $this->getObject()->setPointItemName($this->pointItem->getName());
      $this->getObject()->setPoints($this->pointItem->getPoints());
      $exchange = parent::save($conn);

      $points = $exchange->getPoints();
      $usePoint = new Point();
      $usePoint->setMember($exchange->getMember());
      $usePoint->setPoints(-1 * $points);
      $usePoint->setForeignTable('PointExchange');
      $usePoint->setForeignId($exchange->getId());
      $usePoint->setMemo($this->pointItem->getName());
      $usePoint->save();
      
      $conn->commit();
    }
    catch(Exception $e)
    {
      $conn->rollback();
      throw $e;
    }
    
    return $exchange;
  }
}
