<?php

namespace models;

use base\Cookie;
use base\App;

class User 
{
    const DEFAULT_USER_ID = 0;
    public ?int $id;
    public ?string $login;
    public ?string $password;
    
    /**
     * Выполняет регистрацию пользователя.
     * Возвращает массив ошибок. Пустой массив, если ошибок нет
     * @return array
     */
    public function processRegistrationForm(): array
    {
        $errors = [];
        
        $login = filter_input(INPUT_POST, 'login');
        $password = filter_input(INPUT_POST, 'password');
        $verification = filter_input(INPUT_POST, 'verification');
        
        if ($this->isLogin($login)) {
            $errors[] = "Данный логин уже существует. Для регистрации нужно задать другой логин";
        }
        
        if($password !== $verification) {
            $errors[] = "Введённый пароль не совпадает с подверждённым";
        }
        
        if (count($errors) === 0) {
            Cookie::set($this->insert($login, $password));
            header('Location: /');
        }
        
        return $errors;
    }
    
    /**
     * Выполняет вход пользователя.
     * Возвращает массив ошибок. Пустой массив, если ошибок нет
     * @return array
     */
    public function processLoginForm(): array
    {
        $errors = [];
        $login = filter_input(INPUT_POST, 'login');
        $password = filter_input(INPUT_POST, 'password');

        $this->getRecord($login);

        if ($this->password && password_verify($password, $this->password)) {
            Cookie::set($this->id);
            header('Location: /');
        } else {
            $errors[] = "Неправильный логин или пароль";
        }
        
        return $errors;
    }
    
    /**
     * По id возвращает логин пользователя
     * @param string $userId
     * @return string
     */
    public static function getLogin(int $userId): string
    {
        return App::$db->selectValue(<<<SQL
                SELECT login FROM info.users WHERE id = :userId
            SQL, ['userId' => $userId]);
    }
    
    /**
     * По логину извлекает из базы и задаёт id, login, password пользователя
     * @param string $userLogin 
     * @return void
     */
    private function getRecord(string $userLogin): void 
    {
        $row = App::$db->selectObject(<<<SQL
                SELECT id, login, password FROM info.users WHERE login = :userLogin
            SQL, ['userLogin' => $userLogin]);

        if ($row) {
            $this->id = $row->id;
            $this->login = $row->login;
            $this->password = $row->password;
        } else {
            $this->id = null;
            $this->login = null;
            $this->password = null;
        }
    }
    
    public static function delete(int $userId): void 
    {
        App::$db->execute(<<<SQL
                DELETE FROM info.users WHERE id = :userId
            SQL, ['userId' => $userId]);
    }
    
    public static function deleteFilm(int $userId, int $filmId) {
        App::$db->execute(<<<SQL
                DELETE FROM info.film_users WHERE user_id = :userId AND film_id = :filmId
            SQL, [
                'userId' => $userId,
                'filmId' => $filmId
            ]);
    }


    /**
     * Проверяет существование логина
     * @param string $login
     * @return bool
     */
    private function isLogin(string $login): bool
    {
        return App::$db->selectValue(<<<SQL
                SELECT EXISTS(SELECT FROM info.users WHERE login = :login)
            SQL, ['login' => $login]);
    }
    
    /**
     * Создаёт новую запись в info.users
     * Возвращает id созданного пользователя
     * @param string $login
     * @param string $password
     * @return int
     */
    public static function insert(string $login, string $password): int 
    {
        return App::$db->selectValue(<<<SQL
                    INSERT INTO info.users (login, password) VALUES (:login, :password)
                    RETURNING id
                SQL, [
                    'login' => $login,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
    }
}
