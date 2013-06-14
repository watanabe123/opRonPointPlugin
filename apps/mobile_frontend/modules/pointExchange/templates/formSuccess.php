<?php op_mobile_page_title(__('Exchange your points to the item')) ?>
<table>
<tr>
  <th><?php echo __('Exchange item'); ?></th>
  <td><?php echo $pointItem->getName(); ?></td>
</tr>
<tr>
  <th>景品画像</th>
  <td><?php echo op_image_tag_sf_image($pointItem->getImageFileName(), array('size' => '76x76', 'format' => 'jpg')) ?></td>
</tr>
<tr>
  <th>説明</th>
  <td><?php echo op_decoration(nl2br($pointItem->getDescription())) ?></td>
</tr>
<tr>
  <th>使用ポイント</th>
  <td><?php echo $pointItem->getPoints() ?> P</td>
</tr>
<tr>
  <th>交換後残りポイント</th>
  <td><b><?php echo($balance - $pointItem->getPoints()) ?> P</b></td>
</tr>
</table>
<?php
$op = array();
$op['url'] = url_for('@pointExchange_form?id='.$pointItem->getId());

op_include_form('pointExchangeForm', $form, $op);
?>
