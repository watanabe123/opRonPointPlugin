<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opRonPointPluign actions.
 *
 * @package    OpenPNE
 * @subpackage opRonPointPluign
 * @author     Shouta Kashiwagi <kashiwagi@openpne.jp>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class opRonPointPluginActions extends sfActions
{
  public function executePointConfigure(sfWebRequest $request)
  {
    $this->form = new opPointConfigureForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bindAndSave($request->getParameter('op_point_configure'));
    }
  }

  public function executeMemberPoint(sfWebRequest $request)
  {
    $this->pager = opPointUtil::getMemberListPagerOrderByPoint($request->getParameter('page', 1));
  }

  public function executeEditMemberPoint(sfWebRequest $request)
  {
    $this->member = $this->getRoute()->getObject();
    $this->form = new opMemberPointForm(array(), array('member' => $this->member));
    $this->pager = Doctrine::getTable('Point')->getMemberHistoryPager($this->member->getId(), 20, 1);
  }

  public function executeUpdateMemberPoint(sfWebRequest $request)
  {
    $this->member = $this->getRoute()->getObject();
    $this->form = new opMemberPointForm(array(), array('member' => $this->member));
    $this->pager = Doctrine::getTable('Point')->getMemberHistoryPager($this->member->getId(), 20, 1);

    if ($this->form->bindAndSave($request->getParameter('member')))
    {
      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirect('@op_point_edit_member_point?id='.$this->member->getId());
    }

    $this->setTemplate('editMemberPoint');
  }

  public function executeAddItem(sfWebRequest $request)
  {
    $this->form = new PointItemForm();
    $this->form->setPhoto();
    if ($request->isMethod('post'))
    {
      $this->processForm($request, $this->form);
    }
  }

  public function executeListItem(sfWebRequest $request)
  {
    $this->size = 20;
    $this->page = $request->getParameter('page', 1);
    if ($this->page < 1)
    {
      $this->page = 1;
    }

    $this->pager = Doctrine::getTable('PointItem')->getItemPager($this->size, $this->page);

    return sfView::SUCCESS;
  }

  public function executeEditItem(sfWebRequest $request)
  {
    $this->pointItem = $this->getRoute()->getObject();
    $this->form = new PointItemForm($this->pointItem);
    $this->form->setPhoto(); 
    if ($request->isMethod('put'))
    {
      $this->processForm($request, $this->form);
    }
  }

  public function executeDeleteConfirmItem(sfWebRequest $request)
  {
    $this->item = $this->getRoute()->getObject();
    $this->form = new sfForm();
  }

  public function executeDeleteItem(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->pointItem = $this->getRoute()->getObject();
    $this->pointItem->delete();
    $this->getUser()->setFlash('notice', '正常に削除しました');    
    $this->redirect('@op_point_list_item');    
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );
    if ($form->isValid())
    {
      $pointItem = $form->save();
      $this->redirect('@op_point_list_item');
    }
  }
}
