<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная страница</a></li>
        <li class="breadcrumb-item active" aria-current="page">Каталог</li>
    </ol>
</nav>

<h1>Список фильмов</h1> 

<?php
    $actionUrl = "/film/films";
    require_once __DIR__ . '/../block/filmsNumber.php';
?>

<div class="table-responsive">
    <table id="films-table" class="table table-striped table-hover  caption-top table-bordered">
        <caption>Показано <?= $actualNumberOfFilmsOnPage ?> фильмов из <?= $filmsList->filmsNumberTotal ?></caption>
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">Описание</th>
                <th scope="col">Язык</th>
                <?php if($isLogin) { ?>
                    <th scope="col"></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($filmsList->films as $film) {
            ?>
                <tr>
                    <th scope="row"><?= $film->n ?></th>
                    <td><?= $film->title ?></td>
                    <td><?= $film->description ?></td>
                    <td><?= $film->name ?></td>
                    <?php if($isLogin) { ?>
                        <td>
                            <img
                                <?php if (!$film->isAvailable) { 
                                    echo 'class="adding-film"';
                                } ?>
                                data-film_id="<?= $film->filmId ?>"
                                <?php if ($film->isAvailable) { ?>
                                    src="/protected/assets/svg/check-circle.svg" 
                                <?php } else { ?>
                                    src="/protected/assets/svg/plus-circle.svg" 
                                <?php } ?>
                                alt="Добавление фильма">
                            <span class="spinner-border spinner-border-sm visually-hidden" role="status" aria-hidden="true"></span>
                        </td>
                    <?php } ?>
                </tr>
            <?php 
                }
            ?>
        </tbody>
    </table>
</div>

<?= $pagination ?>
