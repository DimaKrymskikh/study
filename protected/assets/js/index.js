
(function($) {
    // Добавление фильма в аккаунт пользователя.
    // Изменяется иконка, контент не переписывается
    $('#films-table').on('click', addingFilm);
    function addingFilm(e) {
        let $tag;
        if ($(e.target).hasClass('adding-film')) {
            $tag = $(e.target);
        } else {
            return;
        }
        
        $tag.closest('td').find('.spinner-border').removeClass('visually-hidden');
        $tag.addClass('visually-hidden');

        $.ajax({
            type: 'post',
            url: '/film/addingFilm',
            data: {
                filmId: $tag.data('film_id')
            },
            success: function() {
                $tag.removeClass('adding-film');
                $tag.closest('td').find('.spinner-border').addClass('visually-hidden');
                $tag.prop('src', '/protected/assets/svg/check-circle.svg').removeClass('visually-hidden');
            }
        });
    }
    
    // В модальное окно подтверждения на удаление фильма добавляютя данные о фильме
    $('#personal-films-table').on('click', showRemovalFilm);
    function showRemovalFilm(e) {
        let $tag;
        if ($(e.target).hasClass('removal-film')) {
            $tag = $(e.target);
        } else {
            return;
        }
        
        $('#film-delete').prop('disabled', true);
        $('#film-deletion-modal').find('.spinner-border').removeClass('visually-hidden');
        $('#deletedFilmName').html('');
        $('#deletedFilmYes').html('');
        
        $.ajax({
            type: 'post',
            url: '/film/getFilm',
            data: {
                filmId: $tag.data('film_id')
            },
            success: function(data) {
                $('#film-deletion-modal').find('.spinner-border').addClass('visually-hidden');
                let film = JSON.parse(data);
                $('#deletedFilmName').html(film.title);
                $('#film-delete').data('film-id', film.filmId);
                $('#deletedFilmYes').html('Да');
                $('#film-delete').prop('disabled', false);
            }
        });
    }

    // Удаление фильма из списка пользователя
    $('#film-delete').on('click', filmDelete);
    function filmDelete() {
        let $this = $(this);
        
        $('.table-responsive').find('.spinner-border').removeClass('visually-hidden');
        
        $.ajax({
            type: 'post',
            url: '/user/filmDelete',
            data: {
                filmId: $this.data('film-id'),
                filmsNumberOnPage: $this.data('films-number-on-page')
            },
            success: function(text) {
                $('#content-container').html(text);
                $('#personal-films-table').on('click', showRemovalFilm);
                $('#film-delete').on('click', filmDelete);
            }
        });
    }
    
})(jQuery);
