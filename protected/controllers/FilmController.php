<?php
namespace controllers;

use base\BaseController;
use base\Pagination;
use base\Cookie;
use base\UtilsRequest;
use base\PrintException;
use models\User;
use models\Films;

class FilmController extends BaseController 
{
    protected const TEMPLATE = 'layout/template.php';
    protected const BASE_URL = __DIR__ . '/../views/';
    protected const FILE_FOLDER = 'film/';
    
    public function filmsAction(): string
    {
        $activePage = UtilsRequest::validateInt('page', Pagination::DEFAULT_ACTIVE_PAGE);
        $filmsNumberOnPage = UtilsRequest::validateInt('quantity', Pagination::DEFAULT_ITEMS_NUMBER_ON_PAGE);
        
        $films = (new Films)->getFilms($activePage, $filmsNumberOnPage);
        $filmsNumberTotal = isset($films[0]) ? $films[0]->count : 0;
                
        $this->title = 'Главная страница';
        
        return $this->conditionalRender('films.php', [
            'films' => $films,
            'filmsNumberOnPage' => Pagination::getElementsNumberOnActivePage($activePage, $filmsNumberOnPage, $filmsNumberTotal),
            'filmsNumberTotal' => $filmsNumberTotal,
            'pagination' => (new Pagination($activePage, $filmsNumberOnPage, $filmsNumberTotal))->render('/film/films'),
            'isLogin' => Cookie::isLogin()
        ]);
    }
    
    public function addingFilmAction(): void
    {
        if (!$filmId = UtilsRequest::validateInt('filmId', User::DEFAULT_USER_ID)) {
            throw new PrintException('Нет возможности распознать фильм');
        }
        (new Films)->addingFilm($filmId);
    }
    
    public function getFilmAction(): string
    {
        return json_encode((new Films)->getFilm(UtilsRequest::validateInt('filmId', Films::DEFAULT_FILM_ID)));
    }
    
    public function filmCardAction(): string
    {
        Cookie::checkLogin();
        
        $filmId = UtilsRequest::validateInt('filmId', Films::DEFAULT_FILM_ID);
        
        return $this->conditionalRender('filmCard.php', [
            'film' => (new Films)->getFilmCard($filmId)
        ]);
    }
}
