<?php $formAction = isset($params['model']->id)? '/task/update/' .$params['model']->id: '/task/create'?>
<form action="<?= $formAction ?>" method="POST">
  <div class="form-group">
    <label for="user_name">Имя пользователя:</label>
    <input type="text" class="form-control <?= isset($params['validate']['user_name'])? 'is-invalid' :'' ?>" id="user_name" name="task[user_name]" value="<?= $params['model']->user_name ?>">
    <?php if (isset($params['validate']['user_name'])): ?>
      <div class="invalid-feedback">Имя пользователя не может быть пустым</div>
    <?php endif ?>
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control <?= isset($params['validate']['email'])? 'is-invalid' :'' ?>" id="email" name="task[email]" value="<?= $params['model']->email ?>">
    <?php if (isset($params['validate']['email'])): ?>
      <div class="invalid-feedback">Email не валиден</div>
    <?php endif ?>
  </div>
  <div class="form-group">
    <label for="text">Текст задачи:</label>
    <textarea class="form-control <?= isset($params['validate']['text'])? 'is-invalid' :'' ?>" name="task[text]" id="text" cols="30" rows="10"><?= $params['model']->text ?></textarea>
    <?php if (isset($params['validate']['text'])): ?>
      <div class="invalid-feedback">Текст задачи не может быть пустым</div>
    <?php endif ?>
  </div>
  <?php if (\App\Models\User::isAdmin() and !empty($params['model']->id)): ?>
    <div class="form-group">
      <label for="staus">Статус задачи:</label>
      <select onchange="updateItem('<?= $formAction ?>');" class="form-control" name="task[status]" id="staus">
        <option value="0" <?= ($params['model']->getStatus()->type == 0)? 'selected' :'' ?>>Не выполнено</option>
        <option value="1" <?= ($params['model']->getStatus()->type == 1)? 'selected' :'' ?>>выполнено</option>
      </select>
    </div>
  <?php endif ?>
  <button type="submit" class="btn btn-primary">
    <?= isset($params['model']->id)? 'Обновить': 'Сохранить' ?>    
  </button>
</form>
