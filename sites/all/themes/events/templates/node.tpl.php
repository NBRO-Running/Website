<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php //print $user_picture; ?>

  <?php if ($events_media_field): ?>
    <?php print($events_media_field); ?>
  <?php endif; ?>

  <div class="node-content">

    <?php if ($display_submitted): ?>
      <div class="created-date">
        <span class="submitted">
          <?php
            print t('<i class="fa fa-user"></i> !username - ', array(
              '!username' => $name
              )
            );
          ?>
        </span>
        <?php
          print t('<span >@date</span>', array('@date' => $created_date));
        ?>
      </div>
    <?php endif; ?>

    <div class="content"<?php print $content_attributes; ?>>
      <?php
        // We hide the comments and links now so that we can render them later.
        hide($content['links']);
        print render($content);
      ?>
    </div>


    <?php print render($content['links']); ?>

    <?php print render($content['comments']); ?>
  </div>
</article>
