<?php slot('balance'); ?>
  <table>
    <th><?php echo __('Point balance'); ?></th>
    <td><?php echo (int)$balance; ?>P</td>
  </table>
<?php end_slot(); ?>
<?php op_include_box('pointBalance', get_slot('balance'), array('title'=>__('Your current point balance'), 'class'=>'form')); ?>

<?php slot('itemList'); ?>
  <table width="95%">
    <tr>
      <th></th>
      <th>しょうひんめい</th>
      <th>せつめい</th>
      <th>ポイント</th>
    </tr>
    <?php foreach($itemList as $item): ?>
    <tr>
      <td class="image"><?php echo link_to(op_image_tag_sf_image($item->getImageFileName(), array('size' => '76x76', 'format' => 'jpg')), '@pointExchange_form?id='.$item->getId()) ?></td>
      <td>
        <strong><?php echo link_to($item->getName(), '@pointExchange_form?id='.$item->getId()); ?></strong>
      </td>
      <td>
        <p><?php echo op_decoration(nl2br($item->getDescription())) ?></p>
        <p>(せつめいこうしん日: <?php echo $item->getUpdatedAt() ?></p>
      </td>
      <td>
        <?php echo $item->getPoints() ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

<?php end_slot(); ?>

<?php op_include_box('pointExchangeItems', get_slot('itemList'), array('title' => __('Items to exchange your points'))); ?>
