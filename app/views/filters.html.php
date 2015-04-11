<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 11/04/15
 * Time: 10:49 AM
 */

$data = l('data');
?>

<div class="filter-group" data-spy="affix" data-offset-top="20" data-offset-bottom="60">
  <div class="form-horizontal">
    <div class="form-group">
      <h3 class="pull-left">Filters</h3>
      <div class="pull-right">
        <button id="filter-reset-btn" class="btn btn-sm btn-danger">
          Reset
        </button>
      </div>
      <div class="clearfix"></div>
    </div>

    <!-- Repositories -->
    <div class="form-group">
      <label for="filter-repository">Repository</label>
      <select class="input-control" id="filter-repository">
        <option value="null"></option>
        <?php foreach ($data->getRepositoryNames() as $name) { ?>
          <option><?= $name ?></option>
        <?php } ?>
      </select>
    </div>

    <!-- Diagram Type -->
    <div class="form-group">
      <label for="filter-diagram-type">Diagram Type</label>
      <select class="input-control" id="filter-diagram-type">
        <option value="null"></option>
        <?php foreach ($data->getDiagramTypes() as $dtype) { ?>
          <option value="<?= $dtype ?>"><?= ucfirst($dtype) ?></option>
        <?php } ?>
      </select>
    </div>

    <!-- Input Data Type -->
    <div class="form-group">
      <label for="filter-input-type">Input Type</label>
      <select class="input-control" id="filter-input-type">
        <option value="null"></option>
        <?php foreach ($data->getFileTypes() as $type) { ?>
          <option><?= $type ?></option>
        <?php } ?>
      </select>
    </div>

    <!-- Name -->
    <div class="form-group">
      <label for="filter-name">Name</label>
      <input type="text" class="input-control" id="filter-name" placeholder="Name..">
    </div>

    <!-- Last State -->
    <div class="form-group">
      <label for="filter-last-state">Last State</label>
      <select class="input-control" id="filter-last-state">
        <option value="null"></option>
        <?php foreach (g('IMPORT_STATES') as $state => $val) {
          if (!str_starts_with($state, "State")) { ?>
            <option value="<?= "State" . $state ?>"><?= $state ?></option>
          <?php
          }
        }
        ?>
      </select>
    </div>
  </div>
</div> <!-- filter box -->