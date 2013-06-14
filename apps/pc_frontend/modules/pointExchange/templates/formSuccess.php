<?php slot('firstRow'); ?>
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
<?php end_slot(); ?>


<?php

$op = array();
$op['title'] = __('Exchange your points to the item');
$op['url'] = url_for('@pointExchange_form?id='.$pointItem->getId());
$op['firstRow'] = get_slot('firstRow');

op_include_form('pointExchangeForm', $form, $op);

