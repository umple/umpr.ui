<?php

function umple_online_url($name, $type) {
  $DIAGRAM_TYPES = array(
      "diagramtypeclass" => "GvClass",
      "diagramtypestate" => "GvState"
  );

  if (!str_ends_with($name, '.ump')) {
    $name = $name . '.ump';
  }

  return sprintf(g('umple-online-url'), srv("SERVER_NAME") . g('umpr-repos') . "/" .$name,
      $DIAGRAM_TYPES[strtolower($type)]);
}

$data = l("data");

?>

<script id="repo-information" type="application/javascript">
  var Meta = {};
  (function (root) {
    root.data = {
      <?php foreach ($data->getRepositories() as $repo) {
        echo '"' . $repo->getName() . '": {';
        echo '"license": "' . $repo->getLicense() . '",';
        echo '"description": "' . preg_replace('/\n/', '<br/>', htmlentities($repo->getDescription())) . '",';
        echo '"name": "' . $repo->getName() . '",';
        echo '"remote": "' . $repo->getRemoteLoc() . '"';
        echo '},';
      } ?>
    }
  })(Meta);
</script>


<?php require_view('pagination_controls', array('bottom' => false)) ?>

<table class="table table-condensed table-bordered table-condensed umpr-summary">
  <thead>
    <th class="col-idx">          No.</th>
    <th class="col-repo">         Repository</th>
    <th class="col-diagram-type"> Diagram Type</th>
    <th class="col-input-type">   Data Type</th>
    <th class="col-name">         Name</th>
    <th class="col-state-info">   Last State</th>
    <th class="col-umple-online"> Umple Online</th>

  </thead>


  <?php
  $idx = 1;
  foreach ($data->getRepositories() as $repo) {
    foreach ($repo->getFiles() as $file) {
      $folder = g('umpr-repos') . '/' . $repo->getName() . "/";

      $idTag = preg_replace("/\\./", "-", $file->getPath());

      ?>
      <tr class="info-import" id="row-<?= $idTag ?>"
          data-index="<?= $idx ?>"
          data-repository="<?= $repo->getName() ?>"
          data-diagram-type="<?= $repo->getDiagramType() ?>"
          data-input-type="<?= $file->getImportType() ?>"
          data-name="<?= $file->getPath() ?>"
          data-last-state="<?= $file->getState() ?>">
        <td class="col-idx"><?= $idx ?></td>
        <td class="col-repo">
          <?php if ($repo->getRemoteLoc() != null) { ?>
            <a href="#" data-toggle="popover">
          <?php } ?>
              <?= $repo->getName(); ?>
          <?php if ($repo->getRemoteLoc() != null) { ?>
            </a>
          <?php } ?>
        </td>
        <td class="col-diagram-type"><?= ucfirst(str_replace('DiagramType', '', $repo->getDiagramType())) ?></td>
        <td class="col-input-type"><?= $file->getImportType(); ?></td>
        <td class="col-name">
          <?= $file->getPath() ?>

          &nbsp;

          <div style="float: right">

          <?php
            $import_states = g('IMPORT_STATES');
            if ($file->isSuccessful() || $import_states[$file->getState()] > $import_states["Fetch"]) { ?>
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

          <?php if ($file->isSuccessful() || $import_states[$file->getState()] >= $import_states["Model"] ) { ?>
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
          <?php if ($file->isSuccessful() || $import_states[$file->getState()] >= $import_states["Model"] ) { ?>
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
          <td colspan="7" style="padding: 0 !important;">
            <div class="accordian-body collapse" id="message-row-<?= $idTag ?>">
              <p class="error-trace"><?php
                // replace all '\n' with '<br>' make the first line <strong>, replace tabs with two spaces
                $arr = preg_split('/\n/', preg_replace('/\t/', '&nbsp;&nbsp;', $file->getMessage()));
                $arr[0] = "<strong>" . $arr[0] . "</strong>";
                echo implode('<br/>', $arr);
              ?></p>
            </div>
          </td>
        </tr>
      <?php } ?>
  <?php
      $idx = $idx + 1;
    }
  }
  ?>
</table>

<?php require_view('pagination_controls', array('bottom' => true)) ?>
