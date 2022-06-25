<?php

namespace base;

/**
 * Класс для работы с куки пользователя
 */
class Cookie 
{
    // Используется для замены null при работе с id пользователя
    const DEFAULT_INT = 0; 
    
    /**
     * Сохраняет id пользователя в куки до тех пор, пока открыта страница в браузере
     * @param int $userId - id пользователя
     * @return void
     */
    public static function set(int $userId): void
    {
        setcookie("userId", $userId, 0, '/');
    }
    
    /**
     * Очищает куки
     * @return void
     */
    public static function clear(): void
    {
        setcookie("userId", '', time() - 1, '/');
    }
    
    /**
     * Проверяет авторизацию пользователя
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
