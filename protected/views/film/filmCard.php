<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная страница</a></li>
        <li class="breadcrumb-item active"><a href="/user/personalArea">Личный кабинет</a></li>
        <li class="breadcrumb-item active" aria-current="page">Карточка фильма</li>
    </ol>
</nav>

<h1><?= $film->title ?></h1>

<div class="row">
    <div class="col">
        <h4>Основная информация</h4>
    </div>
    <div class="col">
        <h4>Описание</h4>
    </div>
    <div class="col">
        <h4>Актёры</h4>
    </div>
</div>

<div class="row">
    <div class="col">
        <div>Фильм вышел в <?= $film->releaseYear ?> году</div>
        <div>Язык фильма: <?= $film->language ?></div>
    </div>
    <div class="col">
        <div><?= $film->description ?></div>
    </div>
    <div class="col">
        <?php 
            foreach ($film->actorNames as $actorName) {
        ?>
                <div><?= $actorName ?></div>
        <?php 
            }
        ?>
    </div>
</div>
