<div class="dropdown">
    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        Число фильмов на странице
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li>
            <a class="dropdown-item" href="<?= "{$actionUrl}?page=1&quantity=10" ?>">10</a>
        </li>
        <li>
            <a class="dropdown-item" href="<?= "{$actionUrl}?page=1&quantity=20" ?>">20</a>
        </li>
        <li>
            <a class="dropdown-item" href="<?= "{$actionUrl}?page=1&quantity=50" ?>">50</a>
        </li>
        <li>
            <a class="dropdown-item" href="<?= "{$actionUrl}?page=1&quantity=100" ?>">100</a>
        </li>
    </ul>
</div>
