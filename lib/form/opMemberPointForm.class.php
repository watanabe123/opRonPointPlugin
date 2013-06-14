<?php

class opMemberPointForm extends BaseForm
{
  public function setup()
  {
    if (!($this->getOption('member') instanceof Member))
    {
      throw new LogicException('opMemberPointForm needs member object by option'); 
    }

    $this->setWidget('point', new sfWidgetFormInput());
    $this->setValidator('point', new sfValidatorInteger());
    $this->setWidget('reason', new sfWidgetFormInput());
    $this->setValidator('reason', new sfValidatorString());
    $this->setDefault('point', opPointUtil::getPoint($this->getOption('member')->getId()));
    $this->widgetSchema->setNameFormat('member[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    opPointUtil::addPoint($this->getValue('point'), $this->getOption('member')->getId(), $this->getValue('reason'));
  }

  public function bindAndSave($taintedValues)
  {
    $this->bind($taintedValues);
    if ($this->isValid())
    {
      $this->save();

      return true;
    }
    return false;
  }
}
