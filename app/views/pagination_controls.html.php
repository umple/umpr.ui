

<?php
/**
 * Creates pagination controls.
 */

$chunks = array( 10, 25, 50, 100);
?>

<div class="umpr-pgn">

<?php if (l('bottom')) { ?>
  <div class="pgn-chunk">
    <span class="label chunk-label">Items per page:</span>
    <ul class="chunk-list list-inline">
      <?php
      $first = true;
      foreach ($chunks as $chunk) { ?>
        <li>
          <a href="#" class="chunk-select <?= $first ? "active" : "" ?>" data-chunk="<?= $chunk ?>"> <?= $chunk ?> </a>
        </li>
      <?php
        $first = false;
      } ?>
    </ul>
  </div>
<?php } ?>

  <nav class="pull-right">
    <ul class="pagination pgn set">
      <li class="pgn direct-enter">
        <span class="">
          <input class="form-control first collapse width" type="number" min="1" max="">
        </span><!-- /input-group -->
      </li>
      <li class="pgn direct-btn">
        <a href="#"
           class="first"
           data-toggle="collapse"
           aria-expanded="false"
           aria-label="Show page index entry">
          <span class="fa fa-angle-left expand-arrow"></span>
          <span class="fa fa-search"></span>
        </a>
      </li>

      <li class="pgn arrow first">
        <a href="#" aria-label="First">
          <span class="fa fa-angle-double-left"></span>
        </a>
      </li>
      <li class="pgn arrow prev">
        <a href="#" aria-label="Previous">
          <span class="fa fa-angle-left"></span>
        </a>
      </li>

      <?php for ($i = 1; $i < 6; ++$i) { ?>
        <li class="pgn item" data-page-idx="<?= $i ?>"><a href="#"><?= $i ?></a></li>
      <?php } ?>

      <li class="pgn arrow next">
        <a href="#" aria-label="Next">
          <span class="fa fa-angle-right"></span>
        </a>
      </li>
      <li class="pgn arrow last">
        <a href="#" aria-label="Last">
          <span class="fa fa-angle-double-right"></span>
        </a>
      </li>
    </ul>
  </nav>
</div>
