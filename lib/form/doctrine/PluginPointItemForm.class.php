<?php

/**
 * PluginPointItem form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginPointItemForm extends BasePointItemForm
{
  protected static $isActive = array(1 => '有効', 0 => '無効');

  public function setup()
  {
    parent::setup();

    unset($this['id']);
    unset($this['created_at']);
    unset($this['updated_at']);
    unset($this['file_id']);

    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['points'] = new sfWidgetFormInput();
    $this->widgetSchema['description'] = new opWidgetFormRichTextareaOpenPNE();

    $this->widgetSchema['is_active'] = new sfWidgetFormChoice(array(
      'choices'  => self::$isActive,
    ));

    $this->validatorSchema['name'] = new sfValidatorString(array('max_length' => 30));
    $this->validatorSchema['points'] = new sfValidatorInteger();
    $this->validatorSchema['description'] = new sfValidatorString(array('required' => false));

    $this->validatorSchema['is_active'] = new sfValidatorChoice(array(
      'choices' => array_keys(self::$isActive),
    ));
  }

  public function setPhoto()
  {
    $options = array(
        'file_src'     => '',
        'is_image'     => true,
        'with_delete'  => false,
        'label'        => 'CoverImage',
        'edit_mode'    => !$this->isNew(),
      );

    if (!$this->isNew())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
      $options['template'] = get_partial('opRonPointPlugin/formEditImage', array('image' => $this->getObject()));
      if ($this->getObject()->getFileId())
      {
        $options['with_delete'] = true;
        $this->setValidator('file_id_delete', new sfValidatorBoolean(array('required' => false)));
      }
    }

    $this->setWidget('file_id', new sfWidgetFormInputFileEditable($options, array('size' => 40)));
    $this->setValidator('file_id', new opValidatorImageFile(array('required' => false)));
  }

  public function updateObject($values = null)
  {
    parent::updateObject($values);

    if (is_null($values))
    {
      $values = $this->values;
    }

    $values = $this->processValues($values);

    if ($values['file_id'] instanceof sfValidatedFile)
    {
      if (!$this->isNew())
      {
        unset($this->getObject()->File);
      }

      $file = new File();
      $file->setFromValidatedFile($values['file_id']);

      $this->getObject()->setFile($file);
    }
    else
    {
      if (!$this->isNew() && !empty($values['file_id_delete']))
      {
        $old = $this->getObject()->getFile();
        $this->getObject()->setFile(null);
        $this->getObject()->save();

        $old->delete();
      }
    }

    return $this->getObject();
  }
}
