<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $params['title'] ?></title>
  <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/build/main.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="https://github.com/yahve89/anonymous-mvc" target="_blank">Ivan Alexandrov to GitHub</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link" href="/">Главная</a>
        <a class="nav-item nav-link" href="/task/create">Создать задачу</a>
        <a class="nav-item nav-link" href="/default/faq">Что использовал</a>
        <?php if (\App\Models\User::isGuest()): ?>
          <a class="nav-item nav-link" href="/login">Вход</a>
        <?php else: ?>
          <a class="nav-item nav-link" href="/logout">Выход</a>
        <?php endif ?>
      </div>
    </div>
  </nav>
  <?php \App\Basic\Controller::renderPartal('widgets/alert'); ?>
  <?php \App\Basic\Controller::renderPartal($view, $params); ?>
  <script src="/assets/js/jquery-3.4.1.slim.min.js"></script>
  <script src="/assets/js/popper.min.js"></script>
  <script src="/assets/js/bootstrap.min.js"></script>
  <script src="/build/main.js"></script>
</body>
</html>
