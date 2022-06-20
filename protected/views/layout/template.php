<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="/protected/assets/js/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="/protected/assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-mystyle">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/protected/assets/svg/house.svg" alt="Домашняя страница">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/film/films">Каталог</a>
                    </li>
                    <li class="nav-item">
                        <?php if($isLogin) { ?>
                            <a class="nav-link" href="/user/personalArea">ЛК</a>
                        <?php } ?>
                        <?php if(!$isLogin) { ?>
                            <a class="nav-link" href="/user/loginForm">Вход</a>
                        <?php } ?>
                    </li>
                    <li class="nav-item">
                        <?php if($isLogin) { ?>
                            <a class="nav-link" href="/user/exit">Выход</a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
    
    <div class="container">
        <?php if(!$isLogin) { ?>
            <div class="alert alert-light" role="alert">
                Авторизуйтесь, и у Вас будет больше возможностей
            </div>
        <?php } ?>
        
        <div id="content-container">
            <?= $content ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
    <script src="/protected/assets/js/index.js"></script>
</body>
</html>
