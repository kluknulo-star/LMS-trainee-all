@component('components.header')
@endcomponent

<div class="welcome">
    <div class="welcome__container">
        <div class="welcome__logo logo h2">
            CourseZone
        </div>
        <div class="welcome__title h2">
            Sign In
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('authenticate') }}" class="welcome__form form">
            @csrf
            <input name="email" type="email" placeholder="E-mail" class="welcome__input input" required>
            <input name="password" type="password" placeholder="Password" class="welcome__input input" required>
            <p class="welcome__text">Not registered?&nbsp<a href="{{ route('register') }}">Register</a> Or <a href="{{ route('vk.auth') }}">VK.com</a> </p>
            <button type="submit" class="welcome__button rounded-red-button button">Sign in</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
