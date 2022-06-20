<h1>Регистрация</h1>

<form action="/user/registrationForm" method="post">
    <div class="mb-3">
        <label class="form-label">Логин: 
            <input type="text" class="form-control" name="login"/>
        </label>
    </div>
    <div class="mb-3">
        <label class="form-label">Пароль: 
            <input type="password" class="form-control" name="password"/>
        </label>
    </div>
    <div class="mb-3">
        <label class="form-label">Подтверждение пароля: 
            <input type="password" class="form-control" name="verification"/>
        </label>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </div>
</form>

<div class="list-group">
    <?php foreach ($errors as $error) { ?>
        <p class="list-group-item list-group-item-action list-group-item-danger"><?= $error ?></p>
    <?php } ?>
</div>
