<!DOCTYPE html>
<html lang="en">
  <head profile="http://www.w3.org/2005/10/profile">
    <?php require_view('layout/html_head'); ?>
  </head>

  <body>
    <?php require_view('layout/navbar'); ?>
    <?php require_view(g('view')); ?>
    <?php require_view('layout/footer'); ?>
    <?php require_view('layout/post_scripts'); ?>
  </body>
</html>
