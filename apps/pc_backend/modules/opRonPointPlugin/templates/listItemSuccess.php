<?php slot('submenu') ?>
<?php include_partial('menu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('ポイント景品一覧') ?>
<?php end_slot() ?>

<?php if($pager->getNbResults() > 0): ?>
  <table width="95%">
    <tr>
      <th>ID</th>
      <th>画像</th>
      <th>名前</th>
      <th>説明</th>
      <th>ポイント</th>
      <th>更新日</th>
      <th colspan="2">操作</th>
    </tr>
    <?php foreach($pager->getResults() as $item): ?>
    <tr>
      <td><?php echo $item->getId() ?></td>
      <td class="image"><?php echo op_image_tag_sf_image($item->getImageFileName(), array('size' => '76x76', 'format' => 'jpg')) ?></td>
      <td>
        <strong><?php echo $item->getName() ?><?php // echo link_to($item->getName(), '@pointExchange_form?id='.$item->getId()); ?></strong>
      </td>
      <td>
        <p><?php echo op_decoration(nl2br($item->getDescription())) ?></p>
      </td>
      <td>
        <?php echo $item->getPoints() ?>
      </td>
      <td>
        <?php echo $item->getUpdatedAt() ?>
      </td>
      <td>
        <?php echo link_to('更新', '@op_point_edit_item?id='.$item->getId()) ?>
      </td>
      <td>
        <?php echo link_to('削除', '@op_point_delete_confirm_item?id='.$item->getId()) ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p><?php echo __('No Item.'); ?></p>
<?php endif; ?>
