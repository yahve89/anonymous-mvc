<div class="container">
  <h1 class="text-center my-5">Приложение-задачник</h1>
  <?php if (!empty($params['models'])): ?>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">
              <a href="<?= \App\Helpers\Main::sortChange('user_name') ?>">Имя&nbsp;пользователя</a>
            </th>
            <th scope="col">
              <a href="<?= \App\Helpers\Main::sortChange('email') ?>">Email</a>
            </th>
            <th scope="col">Текст задачи</th>
            <th scope="col">
              <a href="<?= \App\Helpers\Main::sortChange('type') ?>">Статус</a>
            </th>
            <?php if (\App\Models\User::getRole() == 'root'): ?>
              <th scope="col">&nbsp;</th>
            <?php endif ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($params['models'] as $item): ?>
            <tr>
              <td><p><?= $item->user_name ?></p></td>
              <td><p><?= $item->email ?></p></td>
              <td><p><?= $item->text ?></p></td>
              <td>
                <?php foreach ($item->getStatuses() as $status): ?>
                  <p><?= $status->getTypeName() ?></p>
                <?php endforeach ?>
                <p></p>
              </td>
              <?php if (\App\Models\User::IsAdmin()): ?>
                <td>
                  <a class="btn p-0" href="/task/update/<?= $item->id ?>">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                  </a>
                  <button class="btn ml-1 p-0" onclick="deleteItem(<?= $item->id ?>);">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                  </button>
                </td>
              <?php endif ?>
            </tr>
           <?php endforeach ?>
        </tbody>
      </table>  
    </div>
    <?php \App\Basic\Controller::renderPartal('widgets/pagination', $params); ?>
  <?php endif ?>
</div>

<?php if (\App\Models\User::IsAdmin()): ?>
  <script type="text/javascript">
    function deleteItem(id) {
      if (confirm("Вы подтверждаете удаление?")) {
        let xhr = new XMLHttpRequest()
        xhr.open("POST", '/task/delete/' + id, true)
        xhr.onreadystatechange = function () {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                location ='/'
            }
        }
        
        xhr.send()
      }
    }
</script>
<?php endif ?>
