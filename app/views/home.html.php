
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

  <div class="form-inline">
    <div class="col-md-3">
      <div class="input-group">
        <label for="filter-repository">Repository &nbsp;</label>
        <select class="form-control" id="filter-repository">
          <option></option>
          <?php foreach ($repoNames as $name) { ?>
            <option><?php echo $name ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    &nbsp;

    <div class="col-md-3">
      <div class="input-group">
        <label for="filter-diagram-type">Diagram Type &nbsp;</label>
        <select class="form-control" id="filter-diagram-type">
          <option></option>
          <?php foreach ($diagramTypes as $dtype) { ?>
            <option><?php echo $dtype ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    &nbsp;

    <div class="col-md-3">
      <div class="input-group">
        <label for="filter-name">Name &nbsp;</label>
        <input type="email" class="form-control" id="filter-name" placeholder="Name..">
      </div>
    </div>

    &nbsp;

    <div class="col-md-3">
      <div class="input-group">
        <label for="filter-last-state">Failure State &nbsp;</label>
        <select class="form-control" id="filter-last-state">
          <option></option>
          <?php foreach (array_keys($ImportStates) as $state) { ?>
            <option><?php echo $state ?></option>
          <?php } ?>
        </select>
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

            $idTag = str_replace(".", "-", $file["path"]);

            ?>
            <tr>
              <td class="col-repo-name"><?php echo $repo["name"]; ?></td>
              <td class="col-diagram-type"><?php echo $repo["diagramType"]; ?></td>
              <td class="col-data-type"><?php echo $file["type"]; ?></td>
              <td class="col-name">
                <?php echo $file["path"] ?>

                &nbsp;

                <div style="float: right">

                <?php if ($file["successful"] || $ImportStates[$file["lastState"]] > $ImportStates["Fetch"]) { ?>
                  <a href="<?php echo $folder . $file["path"] ?>">(Source)</a>
                <?php } else { ?>
                  <span class="text-warning" title="Unable to fetch source">(Source)</span>
                <?php } ?>

                &nbsp;

                <?php if ($file["successful"] || $ImportStates[$file["lastState"]] >= $ImportStates["Model"] ) { ?>
                  <a href="<?php echo $folder . $file["path"] . ".ump" ?>">(Model)</a>
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
                <tr class="error-info">
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
