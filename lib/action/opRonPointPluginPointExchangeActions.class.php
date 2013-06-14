<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opRonPointPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage point
 * @author     Shouta Kashiwagi <kashiwagi@openpne.jp>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class opRonPointPluginPointExchangeActions extends sfActions
{
  public function preExecute()
  {
    if($this->getRoute() instanceOf sfDoctrineRoute)
    {
      $this->pointItem = $this->getRoute()->getObject();
      $balance = Doctrine::getTable('Point')->getBalance($this->getUser()->getMemberId());
      $this->forward404Unless($this->pointItem->getIsActive());
      if ($this->pointItem->getPoints() > $balance)
      {
        $this->setTemplate('lack');

        return sfView::SUCCESS;
      }
    }
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('pointExchange', 'itemList');
  }
  
  public function executeItemList(sfWebRequest $request)
  {
    $this->itemList = Doctrine::getTable('PointItem')->getAll();
    $this->balance = opPointUtil::getPoint();
  }
  
  public function executeForm(sfWebRequest $request)
  {
    $this->balance = opPointUtil::getPoint();
    $this->form = new PointExchangeForm(array(), array('pointItem' => $this->pointItem));
    $this->form->setPointItem($this->pointItem);
    $this->form->getObject()->setMember($this->getUser()->getMember());
    
    if (0 == $this->pointItem->getIsInputAddress())
    {
      $this->form->freeze();
    }
    if($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if($this->form->isValid())
      {
        $this->getUser()->setAttribute('pointExchange', $this->form->getValues());
        $this->csrfForm = new BaseForm();
        
        return 'Confirm';
      }
    }
  }

  
  public function executeDo(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $this->form = new PointExchangeForm(array(), array('pointItem' => $this->pointItem), false);
    $this->form->setPointItem($this->pointItem);
    $this->form->getObject()->setMember($this->getUser()->getMember());
    
    $this->form->bind($this->getUser()->getAttribute('pointExchange', array()));
    if($this->form->isValid())
    {
      $exchange = $this->form->save();
      $params = array(
        'member_name' => $this->getUser()->getMember()->getName(),
        'member_id' => $this->getUser()->getMemberId(),
        'item_id' => $this->pointItem->getId(),
        'item_name' => $this->pointItem->getName(),
        'address' => $this->form->getValue('pref').' '.$this->form->getValue('address').' '.$this->form->getValue('real_name').' '.$this->form->getValue('tel'),
      );
      opMailSend::sendTemplateMail('notityMemberExchange', opConfig::get('admin_mail_address'), $this->getUser()->getMember()->getEmailAddress(), $params);
      $this->getUser()->setFlash('notice', sfContext::getInstance()->getI18N()->__('Completed a application of point exchange')); 
    }
 
    $this->getUser()->setAttribute('pointExchange', null);
    $this->redirect('point/history');
  }
}
