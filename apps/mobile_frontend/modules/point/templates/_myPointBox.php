<?php slot('body') ?>
<table width="100%">
<tr>
<td>
現在 <b><?php echo $point ?>ポイント</b>
</td>
</tr>
</table>

<?php
$list = array();
$list[] = link_to('ポイント履歴を見る', '@point_history');
$list[] = link_to('景品と交換する', '@point_exchange');
op_include_list('pointLinkList', $list);
?>
<?php end_slot() ?>

<?php
$options = array();
$options['title'] = '現在ポイント';
op_include_box('myPointBox', get_slot('body'), $options);
