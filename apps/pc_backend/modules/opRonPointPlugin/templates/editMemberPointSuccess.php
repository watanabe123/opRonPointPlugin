<?php slot('submenu') ?>
<?php include_partial('menu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Change Member Point') ?>
<?php end_slot() ?>

<?php echo $form->renderFormTag(url_for('@op_point_update_member_point?id='.$member->getId())) ?>
<table>
<tr><th>ID</td><td><?php echo $member->getId() ?></td></tr>
<tr><th><?php echo __('Nickname') ?></th><td><?php echo $member->getName() ?></td></tr>
<?php echo $form ?>
<td colspan="2"><input type="submit" /></td>
</table>
</form>

<br />

<h3><?php echo __('Recent point history of this member') ?></h3>
<?php if($pager->getNbResults() > 0): ?>
  <table>
    <?php foreach($pager->getResults() as $point): ?>
      <tr>
        <td><?php echo op_format_date($point->getCreatedAt(), 'XDateTimeJa'); ?></td>
        <td><?php echo $point->getPoints(); ?></td>
        <td><?php echo $point->getMemo(); ?><?php if ($point->getForeignTable()): ?> (<?php echo __('from %table%', array('%table%' => $point->getForeignTable())); ?>) <?php endif ?></td>
        <td><?php echo $point->getExpiresAt() ? op_format_date($point->getExpiresAt(), 'XDateTimeJa') : ''; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p><?php echo __('No point history.'); ?></p>
<?php endif; ?>

