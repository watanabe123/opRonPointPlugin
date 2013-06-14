<div class="parts">
<div class="partsHeading"><h3><?php echo __('My Point') ?></h3></div>

<table width="100%">
<tr>
<td>
<b><?php echo __('Point: %point%', array('%point%' => $point)) ?></b>
</td>
</tr>
</table>

<div class="moreInfo">
<ul class="moreInfo">
<li><?php echo link_to(__('See detail history'), '@point_history') ?></li>
<li><?php echo link_to(__('Exchange some items'), '@point_exchange') ?></li>
</ul>
</div>
</div>
