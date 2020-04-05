<?php if ($params['pagination']['totalPages'] > 1): ?>
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
      <?php if ($params['pagination']['prevPage'] > 0): ?>
        <li class="page-item">
          <a class="page-link" href="<?= \App\Helpers\Main::filterURL('page='.$params['pagination']['prevPage']) ?>" >Назад</a>
        </li>      
      <?php endif ?>
      <?php for ($i = 1; $i <= $params['pagination']['totalPages']; $i++): ?>
        <li class="page-item <?= ($params['pagination']['currentPage'] == $i)? 'active': '' ?>">
          <a class="page-link" href="<?= \App\Helpers\Main::filterURL('page='.$i) ?>"><?= $i ?></a>
        </li>
      <?php endfor ?>
      <?php if ($params['pagination']['currentPage'] < $params['pagination']['totalPages']): ?>
        <li class="page-item">
          <a class="page-link" href="<?= \App\Helpers\Main::filterURL('page='.$params['pagination']['nextPage']) ?>">Вперед</a>
        </li>
      <?php endif ?>
    </ul>
  </nav>
<?php endif ?>
