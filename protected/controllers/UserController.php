<?php
namespace controllers;

use base\Cookie;
use base\BaseController;
use base\Pagination;
use base\UtilsRequest;
use models\User;
use models\Films;

class UserController extends BaseController {
    protected const TEMPLATE = 'layout/template.php';
    protected const BASE_URL = __DIR__ . '/../views/';
    protected const FILE_FOLDER = 'user/';
    
    public function registrationFormAction(): string
    {
        $errors = [];
        
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $errors = (new User)->processRegistrationForm();
        }
        
        return $this->render('registrationForm.php', [
            'errors' => $errors
        ]);
    }
    
    public function loginFormAction(): string 
    {
        $errors = [];
        
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $errors = (new User)->processLoginForm();
        }
        
        return $this->render('loginForm.php', [
            'errors' => $errors
        ]);
    }
    
    public function exitAction(): void 
    {
        Cookie::clear();
        header('Location: /');
    }
    
    public function personalAreaAction(): string 
    {
        Cookie::checkLogin();
        
        $userId = Cookie::getId();
        $userLogin = User::getLogin($userId);
        
        $activePage = UtilsRequest::validateInt('page', Pagination::DEFAULT_ACTIVE_PAGE);
        $filmsNumberOnPage = UtilsRequest::validateInt('quantity', Pagination::DEFAULT_ITEMS_NUMBER_ON_PAGE);
        
        return $this->renderPersonalArea($activePage, $filmsNumberOnPage, $userId, $userLogin);
    }
    
    public function refreshSessionAction(): string 
    {
        $this->title = 'Сессия истекла';
        return $this->render('refreshSession.php', []);
    }
    
    /**
     * Удаляет запись пользователя в info.users
     * @return void
     */
    public function deleteAction(): void 
    {
        // Удаляется запись
        User::delete(Cookie::getId());
        // Очищаются куки
        Cookie::clear();
        // Переходим на главную страницу
        header('Location: /');
    }
    
    public function filmDeleteAction()
    {
        Cookie::checkLogin();
        
        $userId = Cookie::getId();
        $filmId = UtilsRequest::validateInt('filmId', Films::DEFAULT_FILM_ID);
        $filmsNumberOnPage = UtilsRequest::validateInt('filmsNumberOnPage', Pagination::DEFAULT_ITEMS_NUMBER_ON_PAGE);
        
        User::deleteFilm($userId, $filmId);
        
        return $this->renderPersonalArea(Pagination::DEFAULT_ACTIVE_PAGE, $filmsNumberOnPage, $userId, User::getLogin($userId));
    }
    
    private function renderPersonalArea(int $activePage, int $filmsNumberOnPage, int $userId, string $userLogin): string
    {
        $this->title = "Личный кабинет $userLogin";
        
        $films = (new Films)->getFilms($activePage, $filmsNumberOnPage, $userId);
        $filmsNumberTotal = isset($films[0]) ? $films[0]->count : 0;
        
        return $this->conditionalRender('personalArea.php', [
            'userLogin' => htmlspecialchars($userLogin),
            'userId' => $userId,
            'films' => $films,
            'filmsNumberOnPage' => Pagination::getElementsNumberOnActivePage($activePage, $filmsNumberOnPage, $filmsNumberTotal),
            'filmsNumberTotal' => $filmsNumberTotal,
            'pagination' => (new Pagination($activePage, $filmsNumberOnPage, $filmsNumberTotal))->render('/user/personalArea'),
        ]);
    }
}
