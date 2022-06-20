<nav class="pagination-container">
    <ul class="pagination justify-content-center">
        <li class="page-item
                <?php 
                    if ($this->activePage === 1) {
                        echo 'disabled';
                    }
                ?>
            "
            data-page="<?= 1 ?>"
        >
            <a class="page-link" href="<?= "{$url}?page=1&quantity={$this->itemsNumberOnPage}" ?>">&laquo;</a>
        </li>
        <?php
            for ($i = $this->firstButton; $i <= $this->lastButton; $i++) {
        ?>
            <li class="page-item 
                    <?php 
                        if ($this->activePage === $i) {
                            echo 'active';
                        }
                    ?>
                "
            >
                <a class="page-link" 
                    <?php 
                        if ($this->activePage !== $i) {
                            echo "href='{$url}?page={$i}&quantity={$this->itemsNumberOnPage}'";
                        }
                    ?>
                >
                    <?= $i ?>
                </a>
            </li>
        <?php
            }
        ?>
        <li class="page-item
                <?php 
                    if ($this->activePage === $this->pagesNumber) {
                        echo 'disabled';
                    }
                ?>
            " 
            data-page="<?= $this->pagesNumber ?>"
        >
            <a class="page-link" href="<?= "{$url}?page={$this->pagesNumber}&quantity={$this->itemsNumberOnPage}" ?>">&raquo;</a>
        </li>
    </ul>
</nav>
