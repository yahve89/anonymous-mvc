<div class="container mt-5">
  <div class="row justify-content-md-center">
    <div class="col-md-6">
      <div class="jumbotron py-3">
        <h2>Вход</h2>
        <hr class="mb-2">
        <form action="/login" method="POST">
          <div class="form-group">
            <label for="username">Логин:</label>
            <input type="text" class="form-control <?= isset($params['validate']['username'])? 'is-invalid' :'' ?>" placeholder="Введите Логин" id="username" name="auth[username]" value="<?=$params['loginForm']->username?>">
            <?php if (isset($params['validate']['username'])): ?>
              <div class="invalid-feedback">Введите логин</div>
            <?php endif ?>
          </div>
          <div class="form-group">
            <label for="pwd">Пароль:</label>
            <input type="password" class="form-control <?= isset($params['validate']['password'])? 'is-invalid' :'' ?>" placeholder="Введите пароль" id="pwd" name="auth[password]" value="<?=$params['loginForm']->password?>">
            <?php if (isset($params['validate']['password'])): ?>
              <div class="invalid-feedback">Введите пароль</div>
            <?php endif ?>
          </div>
          <?php if ($params['validate'] === false): ?>
            <div class="alert alert-danger" role="alert">Введенные данные не верные</div>
          <?php endif ?>
          <button type="submit" class="btn btn-primary">Вход</button>
        </form>
        <p class="mt-3">
          <span>Доступ админа логин: <b>admin</b> пароль: <b>123</b></span>
        </p>
      </div>
    </div>
  </div>
</div>
