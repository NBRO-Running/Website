<?php
/**
 * @file
 * Shortcode's theme implementation to display a collpase.
 */
?>

<div class="panel-group" id="<?php echo $tagid;?>">
  <?php foreach ($accordions as $aid => $accord): ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $aid;?>">
          <?php echo $accord['attrs']['title']; ?>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $aid;?>" class="panel-collapse collapse">
      <div class="panel-body">
        <?php echo $accord['text']; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
