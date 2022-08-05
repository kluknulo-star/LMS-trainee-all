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

        <form method="post" action="{{ route('users') }}" class="welcome__form form">
            @csrf
            <input value="{{ old('surname') }}" name="surname" type="text" placeholder="Surname" class="welcome__input input @error('surname') is-invalid @enderror">
            <input value="{{ old('name') }}" name="name" type="text" placeholder="Name" class="welcome__input input">
            <input value="{{ old('patronymic') }}" name="patronymic" type="text" placeholder="Patronymic (optional)" class="welcome__input input">
            <input value="{{ old('email') }}" name="email" type="email" placeholder="E-mail" class="welcome__input input">
            <input name="password" type="password" placeholder="Password" class="welcome__input input">
            <input name="password_confirmation" type="password" placeholder="Confirm password" class="welcome__input input">
            <p class="welcome__text">Registered?&nbsp<a href="{{ route('login') }}">Sign in</a></p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <button type="submit" class="welcome__button rounded-red-button button">Register</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
