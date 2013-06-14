<?php slot('balance'); ?>
  <table>
    <th><?php echo __('Point balance'); ?></th>
    <td><?php echo (int)$balance; ?>P</td>
  </table>
<?php end_slot(); ?>
<?php op_include_box('pointBalance', get_slot('balance'), array('title'=>__('Your current point balance'), 'class'=>'form')); ?>

<?php slot('itemList'); ?>
<?php
$list = array();
foreach ($itemList as $item)
{
  $list[] = sprintf("[%s] %s<br>%s",
              $item->getPoints().'P',
              link_to($item->getName(), '@pointExchange_form?id='.$item->getId()),
              substr($item->getDescription(), 0, 15).'...'
            );
}
op_include_list('itemList', $list);

?>

<?php end_slot(); ?>

<?php op_include_box('pointExchangeItems', get_slot('itemList'), array('title' => __('Items to exchange your points'))); ?>
