<?php

require_once 'home.html.php';

function umple_online_url($name, $type) {
  $DIAGRAM_TYPES = array(
      "diagramtypeclass" => "GvClass",
      "diagramtypestate" => "GvState"
  );

  if (!str_ends_with($name, '.ump')) {
    $name = $name . '.ump';
  }

  return sprintf(g('umple-online-url'), srv("SERVER_NAME") . g('umpr-repos') . "/" .$name, $DIAGRAM_TYPES[strtolower
  ($type)]);
}

$data = ImportRepositorySet::fromFile($GLOBALS['umprRepo']['dir'] . '/meta.json');

?>

<div class="container">
  <div class="filter-group panel">
    <h4>Filters</h4>
    <div class="well form-inline">
      <div class="row">
        <div class="col-md-4">
          <label for="filter-repository">Repository &nbsp;</label>
          <select class="input-control" id="filter-repository">
            <option value="null"></option>
            <?php foreach ($data->getRepositoryNames() as $name) { ?>
              <option><?= $name ?></option>
            <?php } ?>
          </select>
        </div>


        <div class="col-md-4">
          <label for="filter-diagram-type">Diagram Type &nbsp;</label>
          <select class="input-control" id="filter-diagram-type">
            <option value="null"></option>
            <?php foreach ($data->getDiagramTypes() as $dtype) { ?>
              <option value="<?= $dtype ?>"><?= ucfirst($dtype) ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-4">
          <label for="filter-input-type">Input Type &nbsp;</label>
          <select class="input-control" id="filter-input-type">
            <option value="null"></option>
            <?php foreach ($data->getFileTypes() as $type) { ?>
              <option><?= $type ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <label for="filter-name">Name &nbsp;</label>
          <input type="text" class="input-control" id="filter-name" placeholder="Name..">
        </div>

        <div class="col-md-4">
          <label for="filter-last-state">Failure State &nbsp;</label>
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

        <div class="col-md-4">
          <div class="filter-reset">
            <button id="filter-reset-btn"
                    class="btn btn-sm btn-danger">
              Reset
            </button>
          </div>
        </div>
      </div>
    </div> <!-- filter box -->
  </div>
</div>

<div class="container">

  <div class="row panel">
    <!-- LEFT PANE -->
    <div class="col-lg-12 panel-body">

      <table class="table table-condensed table-bordered table-condensed umpr-summary">
        <thead>
          <th>Repository</th>
          <th>Diagram Type</th>
          <th>Data Type</th>
          <th>Name</th>
          <th>Successful</th>
          <th>Umple Online</th>

        </thead>

        <?php foreach ($data->getRepositories() as $repo) { ?>
          <?php foreach ($repo->getFiles() as $file) {
            $folder = g('umpr-repos') . '/' . $repo->getName() . "/";

            $idTag = preg_replace("/\\./", "-", $file->getPath());

            ?>
            <tr class="info-import" id="row-<?= $idTag ?>"
                data-repository="<?= $repo->getName() ?>"
                data-diagram-type="<?= $repo->getDiagramType() ?>"
                data-input-type="<?= $file->getImportType() ?>"
                data-name="<?= $file->getPath() ?>"
                data-last-state="<?= $file->getState() ?>">
              <td class="col-repo">
                <?php if ($repo->getRemoteLoc() != null) { ?>
                  <a target="_blank"
                     href="<?= $repo->getRemoteLoc()?>">
                <?php } ?>
                    <?= $repo->getName(); ?>
                <?php if ($repo->getRemoteLoc() != null) { ?>
                  </a>
                <?php } ?>
              </td>
              <td class="col-diagram-type"><?= ucfirst($repo->getDiagramType()) ?></td>
              <td class="col-input-type"><?= $file->getImportType(); ?></td>
              <td class="col-name">
                <?= $file->getPath() ?>

                &nbsp;

                <div style="float: right">

                <?php if ($file->isSuccessful() || g('IMPORT_STATES')[$file->getState()] > g('IMPORT_STATES')["Fetch"]) { ?>
                  <a target="_blank"
                     href="<?php if ($file->getAttrib() != null) {
                              echo $file->getAttrib()->getRemoteLoc();
                            } else {
                              echo $folder . $file->getPath();
                            } ?>">
                      (Source)
                  </a>
                <?php } else { ?>
                  <span class="text-warning" title="Unable to fetch source">(Source)</span>
                <?php } ?>

                &nbsp;

                <?php if ($file->isSuccessful() || g('IMPORT_STATES')[$file->getState()] >= g('IMPORT_STATES')["Model"] ) { ?>
                  <a href="<?= $folder . $file->getPath() . ".ump" ?>">(Model)</a>
                <?php } else { ?>
                  <span class="text-danger" title="Unable to import umple model">(Model)</span>
                <?php } ?>

                </div>

              </td>
              <td class="col-state-info">
                <?php if ($file->isSuccessful()) { ?>
                  <span class="status-badge status-badge-success text-center">
                    <span class="glyphicon glyphicon-ok-circle"></span>Success
                  </span>
                <?php } else { ?>
                  <button class="btn btn-danger text-center status-badge status-badge-failed"
                          type="button"
                          data-toggle="collapse"
                          data-target="#message-row-<?= $idTag ?>"
                          aria-expanded="false"
                          aria-controls="message-row-<?= $idTag ?>">
                    <span class="glyphicon glyphicon-remove-circle"></span>
                      <?= substr_replace($file->getState(), "", 0, 5) ?>
                    <span class="glyphicon collapse-direction" ></span>
                  </button>
                <?php } ?>
              </td>

              <td class="col-umple-online">
                <?php if ($file->isSuccessful() || g('IMPORT_STATES')[$file->getState()] >= g('IMPORT_STATES')["Model"] ) { ?>
                  <a target="_blank"
                     href="<?= umple_online_url($repo->getPath()."/".$file->getPath(), $repo->getDiagramType()) ?>">
                    Link
                    <?php if (!$file->isSuccessful()) { ?>
                      &nbsp; <span class="fa fa-exclamation-triangle text-warning" title="Model is invalid." ></span>
                    <?php } ?>
                  </a>
                <?php } else { ?>
                  <span class="text-danger" title="Unable to import umple model">Link</span>
                <?php } ?>

              </td>
            </tr>

            <?php // write the extra row:
            if (!$file->isSuccessful()) { ?>
                <tr class="info-error">
                  <td colspan="6" style="padding: 0 !important;">
                    <div class="accordian-body collapse" id="message-row-<?= $idTag ?>">
                      <pre><?= $file->getMessage() ?></pre>
                    </div>
                  </td>
                </tr>
            <?php } ?>
        <?php
          }
        }
        ?>
      </table>

    </div>

  </div>
</div>
