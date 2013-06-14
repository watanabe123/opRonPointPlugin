<?php slot('submenu') ?>
<?php include_partial('menu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('ポイント景品追加') ?>
<?php end_slot() ?>

<?php echo $form->renderFormTag(url_for('@op_point_add_item')) ?>
<table>
<?php echo $form ?>
<td colspan="2"><input type="submit" /></td>
</table>
</form>

