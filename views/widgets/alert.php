<?php if (!empty($flash = \App\Basic\Controller::getFlash())): ?>
  <div class="container mt-3"> 
    <?php foreach ($flash as $class => $msg): ?>
    <div class="alert alert-<?= $class ?> alert-dismissible fade show" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <p class="my-0"><?= $msg ?></p>
    </div>
    <?php endforeach ?>
  </div>
<?php endif ?>