<?php

namespace base;

class Cookie 
{
    const DEFAULT_INT = 0; 
    
    public static function set(int $userId): void {
        setcookie("userId", $userId, 0, '/');
    }
    
    public static function clear(): void {
        setcookie("userId", '', time() - 1, '/');
    }
    
    /**
     * Авторизован пользователь или нет
     * @return bool
     */
    public static function isLogin(): bool
    {
        return (bool) self::validateInt('userId', self::DEFAULT_INT);
    }
    
    /**
     * Проверяет авторизован пользователь или нет
     * В акшенах, где пользователь должен быть авторизован,
     * перенапраляет не авторизованного пользователя на специальную страницу
     * @return void
     */
    public static function checkLogin(): void
    {
        if(!self::isLogin()) {
            header('Location: /user/refreshSession');
        }
    }
    
    /**
     * Берёт из куки id пользователя
     * Если пользователь не авторизован, будет возвращён 0
     * @return int
     */
    public static function getId(): int 
    {
        return self::validateInt('userId', self::DEFAULT_INT);
    }
    
    /**
     * Проверяет, что в куки величина с ключом $name имеет целый тип
     * При отсутствии в куки ключа $name возвращается $defaultValue
     * @param string $name
     * @param int $defaultValue
     * @return int
     */
    private static function validateInt(string $name, int $defaultValue): int
    {
        return filter_input(INPUT_COOKIE, $name, FILTER_VALIDATE_INT) ? : $defaultValue;
    }
}
