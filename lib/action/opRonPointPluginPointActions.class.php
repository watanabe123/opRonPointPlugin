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
class opRonPointPluginPointActions extends sfActions
{
  public function executeIndex($request)
  {
    $this->forward('point', 'history');
  }

  public function executeHistory($request)
  {
    $this->size = 20;
    $this->page = $request->getParameter('page', 1);
    if ($this->page < 1)
    {
      $this->page = 1;
    }

    $this->pager = Doctrine::getTable('Point')->getMemberHistoryPager($this->getUser()->getMemberId(), $this->size, $this->page);
    $this->balance = Doctrine::getTable('Point')->getBalance($this->getUser()->getMemberId());
    
    return sfView::SUCCESS;    
  }
}
