<?php slot('submenu') ?>
<?php include_partial('menu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('ポイント景品編集') ?>
<?php end_slot() ?>

<?php echo $form->renderFormTag(url_for('@op_point_edit_item?id='.$pointItem->getId())) ?>
<table>
<?php echo $form ?>
<td colspan="2"><input type="submit" /></td>
</table>
</form>

