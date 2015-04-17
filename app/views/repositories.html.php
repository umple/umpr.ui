<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 10/04/15
 * Time: 10:14 PM
 */

$data = l('data');

?>

<nav class="panel">
  <h3>Repositories</h3>

  <ul>
    <?php foreach ($data->getRepositoryNames() as $name) { ?>
      <li><?= $name ?></li>
    <?php } ?>
  </ul>
</nav>