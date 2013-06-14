<?php slot('submenu') ?>
<?php include_partial('menu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('ポイント景品削除確認画面') ?>
<?php end_slot() ?>

<div class="block">
<p>本当にこの景品を削除してもよろしいですか？</p>
  <table width="95%">
    <tr>
      <th>ID</th>
      <td><?php echo $item->getId() ?></td>
    </tr>
    <tr>
      <th>画像</th>
      <td class="image"><?php echo op_image_tag_sf_image($item->getImageFileName(), array('size' => '76x76', 'format' => 'jpg')) ?></td>
    </tr>
    <tr>
      <th>名前</th>
      <td><strong><?php echo $item->getName() ?></strong></td>
    </tr>
    <tr>
      <th>説明</th>
      <td><?php echo op_decoration(nl2br($item->getDescription())) ?></td>
    </tr>
    <tr>
      <th>ポイント</th>
      <td><?php echo $item->getPoints() ?> P</td>
    </tr>
    <tr>
      <th>更新日</th>
      <td><?php echo $item->getUpdatedAt() ?></td>
    </tr>
  </table>

<form action="<?php echo url_for('@op_point_delete_item?id='.$item->getId()) ?>" method="post">
<div class="operation">
<ul class="moreInfo button">
<li>
<?php echo $form[$form->getCSRFFieldName()] ?>
<input class="input_submit" type="submit" value="<?php echo __('Delete') ?>" />
</li>
</ul>
</div>
</form>
</div>

