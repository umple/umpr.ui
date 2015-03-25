<div class="container mtb">
  <div class="row">
    <!-- LEFT PANE -->
    <div class="col-lg-12">

      <table class="table table-condensed table-bordered table-condensed umplify-summary">
        <tr>
          <th>Repository</th>
          <th>Diagram Type</th>
          <th>Data Type</th>
          <th>Name</th>
          <th>Successful</th>
          <th>Umple Online</th>

        </tr>

        <?php
        $jsonData = file_get_contents("data/meta.json");

        $data = json_decode($jsonData, true);

//        echo "<pre>$jsonData</pre>";

        $umpleOnlineUrl = "http://cruise.eecs.uottawa.ca/umpleonline/?filename=";

        function unicodeString($str, $encoding=null) {
          if (is_null($encoding)) $encoding = ini_get('mbstring.internal_encoding');
          return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), '.var_export($encoding, true).', "UTF-16BE");'), $str);
        }

        ?>

        <?php foreach ($data["repositories"] as $repo) { ?>
          <?php foreach ($repo["files"] as $file) {
            $filePath = "./data/" . $repo["name"] . "/" . $file["path"];

            $idTag = str_replace(".", "-", $file["path"]);

            ?>
            <tr>
              <td><?php echo $repo["name"]; ?></td>
              <td><?php echo $repo["diagramType"]; ?></td>
              <td><?php echo $file["type"]; ?></td>
              <td>
                <?php
                  if (filesize($filePath) > 0) { ?>
                    <a href="<?php echo $filePath ?>">
                      <?php echo $file["path"] ?>
                    </a>
                  <?php } else { ?>
                    <span style="color:darkred" title="Unable to import umple model">
                      <?php echo $file["path"] ?>
                    </span>
                  <?php } ?>
              </td>
              <td>
                <?php if ($file["successful"]) { ?>
                  <span class="label label-success">
                    <span class="glyphicon glyphicon-ok-circle" ></span> Success
                  </span>
                <?php } else { ?>
                  <a class="btn btn-danger accordion-toggle"
                     data-toggle="collapse"
                     href="#message-row-<?php echo $idTag ?>"
                     aria-expanded="false"
                     aria-controls="collapseExample">
                    <span class="glyphicon glyphicon-remove-circle" ></span> Failed
                  </a>
                <?php } ?>
              </td>

              <td>
                <a href="<?php echo $umpleOnlineUrl . $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/data/".$repo["path"]."/".$file["path"] ?>">
                  Link
                </a>
              </td>
            </tr>

            <?php // write the extra row:
            if (!$file["successful"]) { ?>
                <tr>
                  <td colspan="6" style="padding: 0 !important;">
                    <div class="accordian-body collapse" id="message-row-<?php echo $idTag ?>">
                      <?php echo $file["message"] ?>
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
