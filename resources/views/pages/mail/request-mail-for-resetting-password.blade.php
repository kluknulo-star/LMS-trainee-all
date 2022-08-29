@component('components.header')
@endcomponent

<div class="welcome">
    <div class="welcome__container">
        <div class="welcome__logo logo h2">
            CourseZone
        </div>
        <div class="welcome__title h3" style="width:500px; text-align: center;">
            Enter the email where the password reset mail will be sent
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

        <form method="post" action="{{ route('send.password.reset.mail') }}" class="welcome__form form">
            @csrf
            <input name="email" type="email" placeholder="{{ __('main.email') }}" class="welcome__input input" required>
            <p class="welcome__text" style="text-align: center;">{{ 'Already registered?' }}&nbsp<a href="{{ route('login') }}">Login</a></p>
            <button type="submit" class="welcome__button rounded-red-button button w100p">Send mail</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
