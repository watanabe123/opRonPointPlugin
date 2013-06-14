<?php

/**
 * opRonPointPluginConfiguration
 *
 * @package    opRonPointPlugin
 * @subpackage config
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opRonPointPluginConfiguration extends sfPluginConfiguration
{
  protected static
    $pointConfig = null;

 /**
  * get point config
  *
  * @return array
  */
  public static function getPointConfig()
  {
    return self::$pointConfig;
  }

 /**
  * initialize plugin
  *
  */
  public function initialize()
  {
    if ($this->configuration instanceof sfApplicationConfiguration)
    {
      $file = 'config/op_point.yml';
      $this->configuration->getConfigCache()->registerConfigHandler($file, 'sfSimpleYamlConfigHandler');
      self::$pointConfig = include($this->configuration->getConfigCache()->checkConfig($file));
      foreach (self::$pointConfig as $key => $value)
      {
        if (isset($value['event']))
        {
          $listener = isset($value['listener']) ? $value['listener'] : array($this, 'listenTo'.sfInflector::camelize($key));
          
          if (is_array($value['event']))
          {
            foreach ($value['event'] as $eventName)
            {
              $this->dispatcher->connect($eventName, $listener);
            }
          }
          else
          {
            $this->dispatcher->connect($value['event'], $listener);
          }
        }
        else
        {
          throw new LogicException('op_point.yml is wrong');
        }
      }
    }
  }

 /**
  * point up action
  * 
  * @param string $name
  */
  protected function pointUp($name, $memberId = null, $options = array())
  {
    $point = (int)Doctrine::getTable('SnsConfig')->get('op_point_'.$name, 0);
    $reason = isset(self::$pointConfig[$name]['caption']) ? self::$pointConfig[$name]['caption'] : $name;
    opPointUtil::addPoint($point, $memberId, $reason, $options);
  }

 /**
  * listen to accept post
  *
  * @param sfEvent $event
  * @param string  $name
  */
  protected function listenToAcceptPost(sfEvent $event, $name)
  {
    if ('mobile_mail_frontend' === $this->configuration->getApplication())
    {
      if (($event['actionInstance']->getRoute()->getMember() instanceof Member) &&
        sfView::NONE === $event['result']
      )
      {
        $this->pointUp($name, $event['actionInstance']->getRoute()->getMember()->getId());
      }
    }
    else
    {
      if (($event->getSubject() instanceof opFrontWebController) &&
        sfView::SUCCESS === $event['result']
      )
      {
        $options = array();

        if (isset(self::$pointConfig[$name]['limit']))
        {
          $options['limit'] = true;
          $options['event'] = $name;
        }
        $this->pointUp($name, null, $options);
      }
    }
  }

 /**
  * magic method to use listen to some event
  *
  * @param string $methodName
  * @param array  $args
  */
  public function __call($methodName, $args)
  {
    if (0 === strpos($methodName, 'listenTo'))
    {
      $name = sfInflector::underscore(substr($methodName, 8));
      if (!in_array($name, array_keys(self::getPointConfig())))
      {
        throw new BadMethodCallException();
      }
      if (!(1 === count($args) && $args[0] instanceof sfEvent))
      {
        throw new InvalidArgumentException();
      }
      $event = $args[0];

      if (0 === strpos($event->getName(), 'op_action.post_execute'))
      {
        $options = array();

        if (isset(self::$pointConfig[$name]['limit']))
        {
          $options['limit'] = true;
          $options['event'] = $name;
        }

        if (isset(self::$pointConfig[$name]['check']))
        {
          $check = self::$pointConfig[$name]['check'];
          if (('redirect' === $check &&
            ($event->getSubject() instanceof opFrontWebController) &&
            sfView::SUCCESS === $event['result']) ||
            $event['result'] === $check
          )
          {
            $this->pointUp($name, null, $options);
          }
          return;
        }
        $this->pointUp($name, null, $options);
      }

      return;
    }

    throw new BadMethodCallException();
  }

  public function listenToMemberFriend(sfEvent $event)
  {
    $request = $event['actionInstance']->getRequest();
    if ('friend_confirm' === $request['category'] && isset($request['accept']))
    {
      $memberId = $request['id'];
      $this->pointUp('member_friend', $memberId);
    }
  }

  public function listenToMemberInvite(sfEvent $event)
  {
    $member = sfContext::getInstance()->getUser()->getMember();
    $memberId = $member->getInviteMemberId();
    
    $this->pointUp('member_invite', $memberId);
  }

  public function listenToMemberLogin(sfEvent $event)
  {
    if (sfContext::getInstance()->getUser()->isAuthenticated() 
         && ('mobile_frontend' === $this->configuration->getApplication() || 'pc_frontend' === $this->configuration->getApplication()) 
         && !('member' === $event['actionInstance']->getModuleName() && 'registerInput' === $event['actionInstance']->getActionName())
         && 0 !== sfContext::getInstance()->getUser()->getMemberId()
      )
    {
      $this->memberId = sfContext::getInstance()->getUser()->getMemberId();
      $this->pointUp('member_login', $this->memberId, array('limit' => true, 'event' => 'member_login'));
    }
  }

  public function listenToMemberIntrofriend(sfEvent $event)
  {
    if (sfRequest::POST == $event['actionInstance']->getRequest()->getMethod() && sfView::SUCCESS == $event['result'])
    {
      $this->pointUp('member_introfriend', sfContext::getInstance()->getUser()->getMemberId(), array('limit' => true, 'event' => 'member_introfriend'));
    }
  }

 /**
  * listen to post diary
  *
  * @param sfEvent $event
  */
  public function listenToPostDiary(sfEvent $event)
  {
    $this->listenToAcceptPost($event, 'post_diary');
  }

 /**
  * listen to post diary comment
  *
  * @param sfEvent $event
  */
  public function listenToPostDiaryComment(sfEvent $event)
  {
    $this->listenToAcceptPost($event, 'post_diary_comment');
  }
}
