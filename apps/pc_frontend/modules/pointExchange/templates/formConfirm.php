<?php slot('firstRow'); ?>
<p><?php echo __('Are you sure to get this item for your %order_points% points?', array('%order_points%'=>$pointItem->getPoints())); ?></p>
<table>
<tr>
  <th><?php echo __('Exchange item'); ?></th>
  <td><?php echo $pointItem->getName(); ?></td>
</tr>
<tr>
  <th><?php echo __('Image') ?></th>
  <td><?php echo op_image_tag_sf_image($pointItem->getImageFileName(), array('size' => '76x76', 'format' => 'jpg')) ?></td>
</tr>
<tr>
  <th><?php echo __('Item description') ?></th>
  <td><?php echo op_decoration(nl2br($pointItem->getDescription())) ?></td>
</tr>
<tr>
  <th><?php echo __('Use points') ?></th>
  <td><?php echo $pointItem->getPoints() ?> P</td>
</tr>
<tr>
  <th><?php echo __('After your point balance') ?></th>
  <td><b><?php echo($balance - $pointItem->getPoints()) ?> P</b></td>
</tr>
<?php if ($pointItem->getIsInputAddress()): ?>
<tr>
  <th><?php echo __('Address to ship'); ?></th>
  <td>
    <p><?php echo $form->getValue('pref'); ?> <?php echo $form->getValue('address'); ?></p>
    <p><?php echo $form->getValue('real_name'); ?></p>
    <p><?php echo $form->getValue('tel'); ?></p>
  </td>
</tr>
<?php endif ?>
</table>
<?php end_slot(); ?>


<?php

$op = array();
$op['title'] = __('Exchange your points to the item');
$op['yes_url'] = url_for('@pointExchange_do?id='.$pointItem->getId());
$op['no_url'] = url_for('pointExchange/itemList');
$op['no_method'] = 'get';

$op['body'] = get_slot('firstRow');
$op['class'] = 'form';

op_include_yesno('pointExchangeForm', $csrfForm, $csrfForm, $op);
