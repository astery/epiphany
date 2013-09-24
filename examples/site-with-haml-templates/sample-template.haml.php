<h1><?php echo htmlspecialchars($heading,ENT_QUOTES,'UTF-8'); ?></h1>
<ul>
  <?php foreach($friends as $name) { ?>
    <li>Hello <?php echo htmlspecialchars($name,ENT_QUOTES,'UTF-8'); ?>!</li>
  <?php } ?>
</ul>
