<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?=$this->getMeta();?>
</head>
<body>
<h1>Шаблон Main</h1>

<?php if (isset($content)) echo $content;?>
<p>Имя: <?php if (isset($myname)) echo $myname;?></p>
<p>Возраст: <?php if (isset($age)) echo $age;?></p>

<?php if (isset($names)):?>
<?php foreach ($names as $name):?>
    <h5><?=$name->name;?></h5>
<?php endforeach;?>
<?php endif;?>

<?php
$logs = R::getDatabaseAdapter()
         ->getDatabase()
         ->getLogger();

debug( $logs->grep( 'SELECT' ) );

?>
</body>
</html>