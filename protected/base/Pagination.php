<?php

namespace base;

/**
 * Класс реализующий пагинацию
 * @property-read int $activePage - номер активной страницы документа
 * @property-read int $pagesNumber - число страниц документа
 * @property-read int $firstButton - первая кнопка пагинации
 * @property-read int $lastButton - последняя кнопка пагинации
 */
class Pagination 
{
    // Дефолтное значение номера активной страницы
    const DEFAULT_ACTIVE_PAGE = 1;
    // Дефолтное значение числа эдементов на страницы
    const DEFAULT_ITEMS_NUMBER_ON_PAGE = 20;
    // Дефолтное значение числа кнопок пагинации
    const DEFAULT_BUTTONS_NUMBER = 5;
    
    // Номер активной страницы документа
    public readonly int $activePage;
    // Число эдементов на странице
    public readonly int $itemsNumberOnPage;
    // Число страниц документа
    public readonly int $pagesNumber;
    // Первая кнопка пагинации
    public readonly int $firstButton;
    // Последняя кнопка пагинации
    public readonly int $lastButton;

    /**
     * Задаются свойства пагинации
     * @param int $activePage - номер активной страницы документа
     * @param int $itemsNumberOnPage - число эдементов на странице
     * @param int $itemsNumberTotal - число эдементов во всём документе
     * @param int $buttonsNumber - число кнопок пагинации
     * @throws PrintException - выбрасывается при чётном числе кнопок пагинации
     */
    public function __construct(int $activePage, int $itemsNumberOnPage, int $itemsNumberTotal, int $buttonsNumber = self::DEFAULT_BUTTONS_NUMBER) 
    {
        if ($buttonsNumber % 2 === 0) {
            throw new PrintException("Кнопок в пагинации должо быть нечётное число");
        }
        // Задаём номер активной страницы документа
        $this->activePage = $itemsNumberTotal ? $activePage : 0;
        // Число эдементов на странице
        $this->itemsNumberOnPage = $itemsNumberOnPage;
        // Находим число страниц документа
        $this->pagesNumber = intdiv($itemsNumberTotal, $itemsNumberOnPage) + ($itemsNumberTotal % $itemsNumberOnPage ? 1 : 0);
        // Находим первую кнопку пагинации
        $this->firstButton = $itemsNumberTotal ? max(1, $activePage - intdiv($buttonsNumber, 2)) : 0;
        // Находим последнюю кнопку пагинации
        $this->lastButton = $itemsNumberTotal ? min($this->pagesNumber, $activePage + intdiv($buttonsNumber, 2)) : 0;
    }
    
    /**
     * Отрисовывает пагинацию
     * @param string $url - путь к экшену, обновляющему страницу при изменении активной страницы
     * @return string
     */
    public function render(string $url): string
    {
        ob_start();
        require_once 'html/pagination.php';
        return ob_get_clean();
    }
    
    /**
     * Возвращает номер первого элемента активной страницы документа
     * @param int $activePage - номер активной страницы
     * @param int $itemsNumberOnPage - число элементов на странице
     * @return int
     */
    public static function from(int $activePage, int $itemsNumberOnPage): int
    {
        return ($activePage - 1) * $itemsNumberOnPage + 1;
    }
    
    /**
     * Возвращает номер последнего элемента активной страницы документа
     * (Номер последнего элемента активной страницы может быть больше обшего числа элементов)
     * @param int $activePage - номер активной страницы
     * @param int $itemsNumberOnPage - число элементов на странице
     * @return int
     */
    public static function to(int $activePage, int $itemsNumberOnPage): int
    {
        return $activePage * $itemsNumberOnPage;
    }
    
    public static function getElementsNumberOnActivePage(int $activePage, int $itemsNumberOnPage, int $itemsNumberTotal): int
    {
        return min($itemsNumberOnPage, $itemsNumberTotal - ($activePage - 1) * $itemsNumberOnPage);
    }
}
