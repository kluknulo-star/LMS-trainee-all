@component('components.header')
@endcomponent

<div class="welcome">
    <div class="welcome__container">
        <div class="welcome__logo logo h2">
            CourseZone
        </div>
        <div class="welcome__title h2">
            Регистрация
        </div>

        <form action="{{ url('/users') }}" class="welcome__form form">
            <input type="text" placeholder="Фамилия" class="welcome__input input">
            <input type="text" placeholder="Имя" class="welcome__input input">
            <input type="text" placeholder="Отчество (необязательно)" class="welcome__input input">
            <input type="email" placeholder="Почта" class="welcome__input input">
            <input type="password" placeholder="Пароль" class="welcome__input input">
            <input type="password" placeholder="Подтвердите пароль" class="welcome__input input">
            <p class="welcome__text">Зарегистрированы?&nbsp<a href="{{ url('/login') }}">Войти</a></p>
            <button type="submit" class="welcome__button button">Зарегистрироваться</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
