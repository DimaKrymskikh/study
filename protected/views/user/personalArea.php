<div id="personalArea">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Главная страница</a></li>
            <li class="breadcrumb-item active" aria-current="page">Личный кабинет</li>
        </ol>
    </nav>
    
    <h1><?= $userLogin ?>. Личный кабинет</h1>
    
    <h2>Список доступных фильмов</h2> 
    <?php if ($filmsNumberTotal) { ?>

    <?php
        $actionUrl = "/user/personalArea";
        require_once __DIR__ . '/../block/filmsNumber.php';
    ?>
    
    <div class="table-responsive">
        <table id="personal-films-table" class="table table-striped table-hover  caption-top table-bordered">
            <div class="text-center">
                <span 
                    class="spinner-border visually-hidden" 
                    role="status" aria-hidden="true" 
                    style="position: fixed; width: 100px; height: 100px"
                >
                    
                </span>
            </div>
            <caption>Показано <?= $filmsNumberOnPage ?> фильмов из <?= $filmsNumberTotal ?></caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Язык</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($films as $film) {
                ?>
                    <tr>
                        <th scope="row"><?= $film->n ?></th>
                        <td><?= $film->title ?></td>
                        <td><?= $film->description ?></td>
                        <td><?= $film->name ?></td>
                        <td>
                            <a href="/film/filmCard?filmId=<?= $film->filmId ?>">
                                <img class="film-card"
                                    src="/protected/assets/svg/eye.svg"
                                    alt="Карточка фильма"
                                    title="Карточка фильма">
                            </a>
                        </td>
                        <td>
                            <img class="removal-film"
                                data-film_id="<?= $film->filmId ?>"
                                src="/protected/assets/svg/trash.svg"
                                alt="Удаление фильма"
                                data-bs-toggle="modal"
                                data-bs-target="#film-deletion-modal"
                                title="Удаление фильма">
                        </td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?php } else { ?>
        <div class="alert alert-info" role="alert">
            У Вас ещё нет выбранных фильмов!
        </div>
    <?php } ?>
    
    <?php 
        if ($filmsNumberTotal) { 
            echo $pagination;
        }
    ?>

    <p>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#accountDeletionModal" title="Удалить аккаунт">
            Удалить
        </button>
    </p>

    <!-- Модальное окно для удаления аккаунта -->
    <div class="modal fade" id="accountDeletionModal" tabindex="-1" aria-labelledby="modal-user-delete" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-user-delete">Подтверждение удаления аккаунта</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы действительно хотите удалить свой аккаунт?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет</button>
                    <a id="user-delete" class="btn btn-danger" href="/user/delete">Да</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для удаления фильма -->
    <div class="modal fade" id="film-deletion-modal" tabindex="-1" aria-labelledby="modal-user-delete" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-user-delete">Подтверждение удаления фильма</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы действительно хотите удалить фильм 
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span id="deletedFilmName"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет</button>
                    <button 
                        type="button" id="film-delete" class="btn btn-danger" 
                        data-bs-dismiss="modal" 
                        data-films-number-on-page="<?= $filmsNumberOnPage ?>"
                    >
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span id="deletedFilmYes"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>