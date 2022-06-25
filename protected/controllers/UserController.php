<?php
namespace controllers;

use base\Cookie;
use base\BaseController;
use base\Pagination;
use base\UtilsRequest;
use models\User;
use models\Films;

/**
 * Отрисовывает контент, связанный с пользователем
 */
class UserController extends BaseController {
    protected const TEMPLATE = 'layout/template.php';
    protected const BASE_URL = __DIR__ . '/../views/';
    protected const FILE_FOLDER = 'user/';
    
    /**
     * Отрисовывает форму регистрации 
     * или при успешной регистрации перенаправляет пользователя на главную страницу
     * @return string
     */
    public function registrationFormAction(): string
    {
        $errors = [];
        // Если были переданы данные формы, выполняется процесс регистрации
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $errors = (new User)->processRegistrationForm();
        }
        
        $this->title = 'Регистрация';

        return $this->render('registrationForm.php', [
            'errors' => $errors
        ]);
    }
    
    /**
     * Отрисовывает форму авторизации
     * или выполняет процесс авторизации
     * @return string
     */
    public function loginFormAction(): string 
    {
        $errors = [];
        // Если были переданы данные формы, выполняется процесс авторизации
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $errors = (new User)->processLoginForm();
        }
        
        $this->title = 'Авторизация';
        
        return $this->render('loginForm.php', [
            'errors' => $errors
        ]);
    }
    
    /**
     * Выход пользователя
     * @return void
     */
    public function exitAction(): void 
    {
        Cookie::clear();
        header('Location: /');
    }
    
    /**
     * Отрисовывает личный кабинет пользователя
     * @return string
     */
    public function personalAreaAction(): string 
    {
        // Проверяем, что пользователь залогинился
        Cookie::checkLogin();
        
        $activePage = UtilsRequest::validateInt('page', Pagination::DEFAULT_ACTIVE_PAGE);
        $filmsNumberOnPage = UtilsRequest::validateInt('quantity', Pagination::DEFAULT_ITEMS_NUMBER_ON_PAGE);
        
        return $this->renderPersonalArea($activePage, $filmsNumberOnPage);
    }
    
    /**
     * Отрисовывает страницу при истёкшей сессии
     * @return string
     */
    public function refreshSessionAction(): string 
    {
        $this->title = 'Сессия истекла';
        return $this->render('refreshSession.php', []);
    }
    
    /**
     * Удаляет запись пользователя в info.users
     * @return void
     */
    public function deleteAccountAction(): void 
    {
        Cookie::checkLogin();
        // Удаляется учётная запись
        User::deleteAccount();
        // Очищаются куки
        Cookie::clear();
        // Переходим на главную страницу
        header('Location: /');
    }
    
    /**
     * Удаляет фильм из списка пользователя 
     * и отрисовывает личный кабинет пользователя
     * @return string
     */
    public function filmDeleteAction(): string
    {
        Cookie::checkLogin();
        
        $filmId = UtilsRequest::validateInt('filmId', Films::DEFAULT_FILM_ID);
        $filmsNumberOnPage = UtilsRequest::validateInt('filmsNumberOnPage', Pagination::DEFAULT_ITEMS_NUMBER_ON_PAGE);
        
        User::deleteFilm($filmId);
        
        return $this->renderPersonalArea(Pagination::DEFAULT_ACTIVE_PAGE, $filmsNumberOnPage);
    }
    
    /**
     * Отрисовывает контент личного кабинета пользователя
     * @param int $activePage
     * @param int $filmsNumberOnPage
     * @return string
     */
    private function renderPersonalArea(int $activePage, int $filmsNumberOnPage): string
    {
        $userId = Cookie::getId();
        $userLogin = User::getLogin($userId);
        // Извлекаем список фильмов данного пользователя, а не весь список
        $filmsList = (new Films)->getFilmsList($activePage, $filmsNumberOnPage, true);
        
        $this->title = "Личный кабинет $userLogin";
        
        return $this->conditionalRender('personalArea.php', [
            'userLogin' => htmlspecialchars($userLogin),
            'userId' => $userId,
            'filmsList' => $filmsList,
            'filmsNumberOnPage' => $filmsNumberOnPage,
            'actualNumberOfFilmsOnPage' => Pagination::getElementsNumberOnActivePage($activePage, $filmsNumberOnPage, $filmsList->filmsNumberTotal),
            'pagination' => (new Pagination($activePage, $filmsNumberOnPage, $filmsList->filmsNumberTotal))->render('/user/personalArea'),
        ]);
    }
}
