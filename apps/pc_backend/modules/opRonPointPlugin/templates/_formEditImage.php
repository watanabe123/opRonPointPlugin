<?php $imageTag = image_tag_sf_image($image->getImageFileName(), array('size' => '120x120')); ?>
<?php if ($image->getFileId()): ?>
<a href="<?php echo sf_image_path($image->getImageFileName()) ?>" target="_blank"><?php echo $imageTag ?></a><br />
%input%<br />
%delete% %delete_label%
<?php else: ?>
<?php echo $imageTag; ?><br />
%input%<br />
<?php endif; ?>
