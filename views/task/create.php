<div class="container mt-5">
  <div class="row justify-content-md-center">
    <div class="col-md-6">
        <div class="jumbotron py-3">
          <h2>Создать задачу</h2>
          <hr class="mb-2">
          <?php \App\Basic\Controller::renderPartal('task/_form', $params); ?>
        </div>
    </div>
  </div>
</div>