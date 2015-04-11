<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 10/04/15
 * Time: 10:14 PM
 */

?>

<div class="container">
  <h3>Repositories</h3>

  <div class="panel">
    <ul>
      <?php foreach ($repoNames as $name) { ?>
        <li><?= $name ?></li>
      <?php } ?>
    </ul>
  </div>

</div>