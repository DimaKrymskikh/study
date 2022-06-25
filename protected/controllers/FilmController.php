<?php
namespace controllers;

use base\BaseController;
use base\Pagination;
use base\Cookie;
use base\UtilsRequest;
use base\PrintException;
use models\Films;

/**
 * Отрисовывает данные модели Films
 */
class FilmController extends BaseController 
{
    protected const TEMPLATE = 'layout/template.php';
    protected const BASE_URL = __DIR__ . '/../views/';
    protected const FILE_FOLDER = 'film/';
    
    /**
     * Отрисовывает вкладку "Каталог"
     * @return string
     */
    public function filmsAction(): string
    {
        $activePage = UtilsRequest::validateInt('page', Pagination::DEFAULT_ACTIVE_PAGE);
        $filmsNumberOnPage = UtilsRequest::validateInt('quantity', Pagination::DEFAULT_ITEMS_NUMBER_ON_PAGE);
        // Получаем список существующих фильмов для активной страницы
        $filmsList = (new Films)->getFilmsList($activePage, $filmsNumberOnPage);
                
        $this->title = 'Список фильмов';
        
        return $this->conditionalRender('films.php', [
            'filmsList' => $filmsList,
            'actualNumberOfFilmsOnPage' => Pagination::getElementsNumberOnActivePage($activePage, $filmsNumberOnPage, $filmsList->filmsNumberTotal),
            // Отрисовывает блок пагинации
            'pagination' => (new Pagination($activePage, $filmsNumberOnPage, $filmsList->filmsNumberTotal))->render('/film/films'),
            'isLogin' => Cookie::isLogin()
        ]);
    }
    
    /**
     * Добавляет фильм в каталог пользователя
     * @return void
     * @throws PrintException
     */
    public function addingFilmAction(): void
    {
        if (!$filmId = UtilsRequest::validateInt('filmId', Films::DEFAULT_FILM_ID)) {
            throw new PrintException('Нет возможности распознать фильм');
        }
        (new Films)->addingFilm($filmId);
    }
    
    /**
     * Отдаёт данные о фильме для модального окна с подтверждением на удаление фильма
     * @return string
     */
    public function getFilmAction(): string
    {
        return json_encode((new Films)->getFilm(UtilsRequest::validateInt('filmId', Films::DEFAULT_FILM_ID)));
    }
    
    /**
     * Отрисовывает карточку фильма
     * @return string
     */
    public function filmCardAction(): string
    {
        Cookie::checkLogin();
        $filmId = UtilsRequest::validateInt('filmId', Films::DEFAULT_FILM_ID);
                
        $this->title = 'Карточка фильма';
        
        return $this->conditionalRender('filmCard.php', [
            'film' => (new Films)->getFilmCard($filmId)
        ]);
    }
}
