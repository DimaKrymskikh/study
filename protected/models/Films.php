<?php

namespace models;

use base\App;
use base\Cookie;
use base\Pagination;

/**
 * Извлекает данные, связанные с таблицей film
 */
class Films
{
    const DEFAULT_FILM_ID = 0;

    /**
     * Извлекает список фильмов для заданной страницы пагинации
     * @param int $activePage - заданная страница пагинации
     * @param int $filmsNumberOnPage - предполагаемое число фильмов на странице
     * @param bool $isPersonal - если true, извлекаются фильмы залогиненного пользователя
     * @return object
     */
    public function getFilmsList(int $activePage, int $filmsNumberOnPage, bool $isPersonal = false): object
    {
        $condition = $isPersonal ? 'WHERE fu.user_id = :userId' : '';
        
        $films = App::$db->selectObjects(<<<SQL
                WITH _ AS (
                    SELECT
                        f.film_id,
                        f.title,
                        f.description,
                        f.language_id,
                        row_number () OVER (ORDER BY f.title) AS n,
                        count (*) OVER () AS count,
                        coalesce (fu.user_id::bool, false) AS "isAvailable"
                    FROM film f
                    LEFT JOIN info.film_users fu ON fu.film_id = f.film_id AND fu.user_id = :userId
                    $condition
                )
                SELECT
                    _.film_id AS "filmId",
                    _.n,
                    _.count,
                    _.title,
                    _.description,
                    l.name,
                    _."isAvailable"
                FROM _
                JOIN language l ON l.language_id = _.language_id
                WHERE _.n >= :from AND _.n <= :to
                ORDER BY _.n
            SQL, [
                'from' => Pagination::from($activePage, $filmsNumberOnPage),
                'to' => Pagination::to($activePage, $filmsNumberOnPage),
                'userId' => Cookie::getId()
            ]);
        
        $filmsList = (object) [];
        $filmsList->films = $films;
        $filmsList->filmsNumberTotal = isset($films[0]) ? $films[0]->count : 0;
        
        return $filmsList;
    }
    
    /**
     * Добавление фильма в список пользователя
     * @param int $filmId - id добавляемого фильма
     * @return void
     */
    public function addingFilm(int $filmId): void
    {
        App::$db->execute(<<<SQL
                INSERT INTO info.film_users (user_id, film_id)
                VALUES (:userId, :filmId)
            SQL, [
                'userId' => Cookie::getId(),
                'filmId' => $filmId,
            ]);
    }
    
    /**
     * Извлекает данные фильма
     * @param int $filmId - id фильма
     * @return object
     */
    public function getFilm(int $filmId): object
    {
        return App::$db->selectObject(<<<SQL
                SELECT 
                    film_id AS "filmId", 
                    title 
                FROM film 
                WHERE film_id = ?
            SQL, [$filmId]);
    }
    
    /**
     * Извлекает потробные данные о фильме
     * @param int $filmId - id фильма
     * @return object
     */
    public function getFilmCard(int $filmId): object
    {
        $film = App::$db->selectObject(<<<SQL
                    SELECT 
                        f.film_id AS "filmId",
                        f.title,
                        f.description,
                        f.release_year AS "releaseYear",
                        string_agg(trim(concat(a.first_name, ' ', a.last_name)), ',' ORDER BY a.first_name, a.last_name) AS "actorNames",
                        l.name AS language
                    FROM film f 
                    JOIN film_actor fa USING (film_id) 
                    JOIN actor a USING (actor_id)
                    JOIN language l USING (language_id)
                    WHERE f.film_id = :filmId
                    GROUP BY f.film_id, l.name
                SQL, [
                    'filmId' => $filmId,
                ]);
        
        $film->actorNames = explode(',', $film->actorNames);
        
        return $film;
    }
}
