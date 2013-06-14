<?php

/**
 * PluginPointItemTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginPointItemTable extends Doctrine_Table
{
  public function getAll()
  {
    return $this->createQuery('i')->addWhere('i.is_active = ?', true)->orderBy('i.points')->execute();
  }

  public function getItemPager($size, $page = 1)
  {
    $query = $this->createQuery('i')->addWhere('i.is_active = ?', true)->orderBy('i.id DESC');
    return $this->generatePager($query, $size, $page);
  }

  protected function generatePager(Doctrine_Query $query, $size, $page)
  {
    $pager = new sfDoctrinePager('PointItem', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();
    
    return $pager;
  }
}
