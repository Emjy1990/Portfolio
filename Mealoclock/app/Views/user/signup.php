<?php $this->layout('layout', ['title' => 'Inscription']) ?>

<div class="col-md-6 offset-md-3 my-3">
    <h1>Inscription</h1>

    <?php if (!empty($errorList)) : ?>
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        <?php foreach ($errorList as $currentError) : ?>
            <?= $currentError ?><br>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
      <div class="form-group">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Mot de passe</label>
        <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword2">Confirmation</label>
        <input type="password" class="form-control" name="passwordConfirm" id="exampleInputPassword2" placeholder="Confirm Password">
      </div>
      <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>
