@component('components.header')
@endcomponent

<div class="welcome">
    <div class="welcome__container">
        <div class="welcome__logo logo h2">
            CourseZone
        </div>
        <div class="welcome__title h2">
            Вход
        </div>

        <form action="{{ url('/users') }}" class="welcome__form form">
            <input type="text" placeholder="Логин" class="welcome__input input">
            <input type="text" placeholder="Пароль" class="welcome__input input">
            <p class="welcome__text">Не зарегистрированы?&nbsp<a href="{{ url('/register') }}">Регистрация</a></p>
            <button type="submit" class="welcome__button button">Войти</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
