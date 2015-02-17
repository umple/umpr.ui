<div class="container mtb">
  <div class="row">
    <!-- LEFT PANE -->
    <div class="col-lg-12">

      <table class="table table-condensed table-bordered table-condensed umplify-summary">
        <tr>
          <th>Owner</th>
          <th>Project</th>
          <th>Version</th>
        </tr>

        <?php foreach(Project::listOwners() as $owner) { ?>
          <tr>
            <td><?php echo $owner; ?></td>
            <td></td>
            <td></td>
          </tr>
        <?php } ?>
      </table>

    </div>

  </div>
</div>
