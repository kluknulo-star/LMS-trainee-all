@component('components.header')
@endcomponent

<div class="welcome">
    <div class="welcome__container">
        <div class="welcome__logo logo h2">
            CourseZone
        </div>
        <div class="welcome__title h2">
            Register
        </div>

        <form method="post" action="{{ url('/users') }}" class="welcome__form form">
            <input name="surname" type="text" placeholder="Surname" class="welcome__input input">
            <input name="name" type="text" placeholder="Name" class="welcome__input input">
            <input name="patronymic" type="text" placeholder="Patronymic (optional)" class="welcome__input input">
            <input name="email" type="email" placeholder="E-mail" class="welcome__input input">
            <input name="password" type="password" placeholder="Password" class="welcome__input input">
            <input name="passwordRepeat" type="password" placeholder="Confirm password" class="welcome__input input">
            <p class="welcome__text">Registered?&nbsp<a href="{{ url('/login') }}">Sign in</a></p>
            <button type="submit" class="welcome__button rounded-red-button button">Register</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
