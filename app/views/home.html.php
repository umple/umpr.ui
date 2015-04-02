
<?php
$jsonData = file_get_contents("data/meta.json");

$data = json_decode($jsonData, true);

//        echo "<pre>$jsonData</pre>";

$umpleOnlineUrl = "http://cruise.eecs.uottawa.ca/umpleonline/?filename=";

function unicodeString($str, $encoding=null) {
  if (is_null($encoding)) $encoding = ini_get('mbstring.internal_encoding');
  return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), '.var_export($encoding, true).', "UTF-16BE");'), $str);
}

$ImportStates = array(
    "Fetch"    => 0,
    "Import"   => 1,
    "Model"    => 2,
    "Complete" => 3
);

$repoNames = [];
$fileTypes = [];
$diagramTypes = [];

foreach ($data["repositories"] as $repo) {
  array_push($repoNames, $repo["name"]);
  array_push($diagramTypes, $repo["diagramType"]);

  foreach ($repo["files"] as $file) {
    array_push($fileTypes, $file["type"]);
  }
}

array_unique($repoNames);
array_unique($fileTypes);
array_unique($diagramTypes);

?>

<div class="container mtb">

  <div class="filter-group">
    <div class="form-inline">
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
      </div>

      <div class="row">
        <div class="col-md-4">
          <label for="filter-file-type">Input Type &nbsp;</label>
          <select class="input-control" id="filter-file-type">
            <option value="null"></option>
            <?php foreach ($fileTypes as $type) { ?>
              <option><?= $type ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-4">
          <div class="input-group">
            <label for="filter-name">Name &nbsp;</label>
            <input type="text" class="input-control" id="filter-name" placeholder="Name..">
          </div>
        </div>

        <div class="col-md-4">
          <div class="input-group">
            <label for="filter-last-state">Failure State &nbsp;</label>
            <select class="input-control" id="filter-last-state">
              <option value="null"></option>
              <?php foreach (array_keys($ImportStates) as $state) { ?>
                <option><?= $state ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>

    </div>

  </div>

  <div class="row">
    <!-- LEFT PANE -->
    <div class="col-lg-12">

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
            $folder = "./data/" . $repo["name"] . "/";

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

                <?php if ($file["successful"] || $ImportStates[$file["lastState"]] > $ImportStates["Fetch"]) { ?>
                  <a href="<?= $folder . $file["path"] ?>">(Source)</a>
                <?php } else { ?>
                  <span class="text-warning" title="Unable to fetch source">(Source)</span>
                <?php } ?>

                &nbsp;

                <?php if ($file["successful"] || $ImportStates[$file["lastState"]] >= $ImportStates["Model"] ) { ?>
                  <a href="<?= $folder . $file["path"] . ".ump" ?>">(Model)</a>
                <?php } else { ?>
                  <span class="text-warning" title="Unable to import umple model">(Model)</span>
                <?php } ?>

                </div>

              </td>
              <td class="col-state-info">
                <?php if ($file["successful"]) { ?>
                  <span class="status-badge status-badge-success">
                    <span class="glyphicon glyphicon-ok-circle"></span>Success
                  </span>
                <?php } else { ?>
                  <button class="btn btn-danger status-badge status-badge-failed"
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
                <a href="<?php echo $umpleOnlineUrl . $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/data/".$repo["path"]."/".$file["path"] ?>">
                  Link
                </a>
              </td>
            </tr>

            <?php // write the extra row:
            if (!$file["successful"]) { ?>
                <tr class="info-error">
                  <td colspan="6" style="padding: 0 !important;">
                    <div class="accordian-body collapse" id="message-row-<?php echo $idTag ?>">
                      <pre><?php echo $file["message"] ?></pre>
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
