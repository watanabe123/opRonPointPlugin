<?php

class opPointUtil
{
  const CONFIG_NAME = 'op_point';

 /**
  * add point
  *
  * @param integer $p the point
  * @param integer $memberId the member id
  * @param string  $reason
  * @return integer
  */
  public static function addPoint($p, $memberId = null, $reason = '', $options = array())
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $value = $p;

    if (0 === $p)
    {
      return $value;
    }

    if (isset($options['limit']) && isset($options['event']))
    {
      $pointLimit = Doctrine::getTable('Point')
        ->createQuery('pl')
        ->where('pl.member_id = ?', $memberId)
        ->andWhere('pl.event = ?', $options['event'])
        ->andWhere('DATE_FORMAT(pl.updated_at, \'%Y-%m-%d\') = ?', date('Y-m-d'))
        ->execute();

      if ($pointLimit[0]->getId())
      {
        return;
      }
    }

    $point = new Point();
    $point->setMemberId($memberId);
    $point->setPoints($value);
    $point->setMemo($reason);
    if (isset($options['event']))
    {
      $point->setEvent($options['event']);
    }
    $point->save();

    $params = array(
      'member_id' => $memberId,
      'addPoint' => $p,
      'resultPoint' => $value,
      'reason' => $reason
    );

    $event = new sfEvent(null, 'op_point.add_point', $params);
    sfContext::getInstance()->getEventDispatcher()->notifyUntil($event);

    return $value;
  }

 /**
  * get point
  * 
  * @param integer $memberId
  * @return integer
  */
  public static function getPoint($memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $o = Doctrine::getTable('Point')->createQuery('p')->select('SUM(p.points) as psum')->where('p.member_id = ?', $memberId)->fetchOne();
    return $o->getPsum();
  }

 /**
  * get member list pager order by point
  *
  * @param integer $page
  * @param integer $size
  * @param boolean $desc
  * @return sfDoctrinePager
  */
  public static function getMemberListPagerOrderByPoint($page = 1, $size = 20, $desc = true)
  {
    $query = Doctrine::getTable('Member')->createQuery('m')
      ->leftJoin("m.Point p ON m.id = p.member_id")
      ->orderBy('m.id');

    $pager = new sfDoctrinePager('Member', 20);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
