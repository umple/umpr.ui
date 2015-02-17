<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_view('layout/html_head'); ?>
  </head>

  <body>
    <?php require_view('layout/navbar'); ?>
    <?php require_view($GLOBALS["viewables"]['view']); ?>
    <?php require_view('layout/footer'); ?>
    <?php require_view('layout/post_scripts'); ?>
  </body>
</html>
