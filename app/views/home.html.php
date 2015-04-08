
<?php

function unicodeString($str, $encoding=null) {
  if (is_null($encoding)) $encoding = ini_get('mbstring.internal_encoding');
  return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), '.var_export($encoding, true).', "UTF-16BE");'), $str);
}

function umpleOnlineUrl($name) {
  return sprintf($GLOBALS["umpleOnlineUrl"], $name);
}

$IMPORT_STATES = array(
    "Fetch"    => 0,
    "Import"   => 1,
    "Model"    => 2,
    "Complete" => 3
);

$jsonData = file_get_contents($GLOBALS['umprRepo']['dir'] . '/meta.json');
$data = json_decode($jsonData, true);

$repoNames = array();
$fileTypes = array();
$diagramTypes = array();

foreach ($data["repositories"] as $repo) {
  array_push($repoNames, $repo["name"]);
  array_push($diagramTypes, $repo["diagramType"]);

  foreach ($repo["files"] as $file) {
    array_push($fileTypes, $file["type"]);
  }
}

$repoNames = array_unique($repoNames, SORT_STRING);
$fileTypes = array_unique($fileTypes, SORT_STRING);
$diagramTypes = array_unique($diagramTypes, SORT_STRING);

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
            <?php foreach ($repoNames as $name) { ?>
              <option><?= $name ?></option>
            <?php } ?>
          </select>
        </div>


        <div class="col-md-4">
          <label for="filter-diagram-type">Diagram Type &nbsp;</label>
          <select class="input-control" id="filter-diagram-type">
            <option value="null"></option>
            <?php foreach ($diagramTypes as $dtype) { ?>
              <option value="<?= $dtype ?>"><?= ucfirst($dtype) ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-4">
          <label for="filter-input-type">Input Type &nbsp;</label>
          <select class="input-control" id="filter-input-type">
            <option value="null"></option>
            <?php foreach ($fileTypes as $type) { ?>
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
            <?php foreach ($IMPORT_STATES as $state => $val) { ?>
              <option value="<?= $state ?>"><?= $state ?></option>
            <?php } ?>
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

        <?php foreach ($data["repositories"] as $repo) { ?>
          <?php foreach ($repo["files"] as $file) {
            $folder = g('umpr-repos') . '/' . $repo["name"] . "/";

            $idTag = preg_replace("/\\./", "-", $file["path"]);

            ?>
            <tr class="info-import" id="row-<?= $idTag ?>"
                data-repository="<?= $repo["name"] ?>"
                data-diagram-type="<?= $repo["diagramType"] ?>"
                data-input-type="<?= $file["type"] ?>"
                data-name="<?= $file["path"] ?>"
                data-last-state="<?= $file["lastState"] ?>">
              <td class="col-repo"><?= $repo["name"]; ?></td>
              <td class="col-diagram-type"><?= ucfirst($repo["diagramType"]) ?></td>
              <td class="col-input-type"><?= $file["type"]; ?></td>
              <td class="col-name">
                <?= $file["path"] ?>

                &nbsp;

                <div style="float: right">

                <?php if ($file["successful"] || $IMPORT_STATES[$file["lastState"]] > $IMPORT_STATES["Fetch"]) { ?>
                  <a href="<?= $folder . $file["path"] ?>">(Source)</a>
                <?php } else { ?>
                  <span class="text-warning" title="Unable to fetch source">(Source)</span>
                <?php } ?>

                &nbsp;

                <?php if ($file["successful"] || $IMPORT_STATES[$file["lastState"]] >= $IMPORT_STATES["Model"] ) { ?>
                  <a href="<?= $folder . $file["path"] . ".ump" ?>">(Model)</a>
                <?php } else { ?>
                  <span class="text-warning" title="Unable to import umple model">(Model)</span>
                <?php } ?>

                </div>

              </td>
              <td class="col-state-info">
                <?php if ($file["successful"]) { ?>
                  <span class="status-badge status-badge-success text-center">
                    <span class="glyphicon glyphicon-ok-circle"></span>Success
                  </span>
                <?php } else { ?>
                  <button class="btn btn-danger text-center status-badge status-badge-failed"
                          type="button"
                          data-toggle="collapse"
                          data-target="#message-row-<?php echo $idTag ?>"
                          aria-expanded="false"
                          aria-controls="message-row-<?php echo $idTag ?>">
                    <span class="glyphicon glyphicon-remove-circle"></span><?php echo $file["lastState"] ?>
                    <span class="glyphicon collapse-direction" ></span>
                  </button>
                <?php } ?>
              </td>

              <td class="col-umple-online">
                <?php if ($file["successful"] || $IMPORT_STATES[$file["lastState"]] >= $IMPORT_STATES["Model"] ) { ?>
                  <a target="_blank"
                     href="<?= umpleOnlineUrl($_SERVER["SERVER_NAME"] . g('umpr-repos') ."/".$repo["path"]."/".$file["path"].'.ump') ?>">
                    Link
                  </a>
                <?php } else { ?>
                  <span class="text-warning" title="Unable to import umple model">Link</span>
                <?php } ?>

              </td>
            </tr>

            <?php // write the extra row:
            if (!$file["successful"]) { ?>
                <tr class="info-error">
                  <td colspan="6" style="padding: 0 !important;">
                    <div class="accordian-body collapse" id="message-row-<?php echo $idTag ?>">
                      <pre><?= $file["message"] ?></pre>
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
