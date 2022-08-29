@component('components.header')
@endcomponent

<div class="welcome">
    <div class="welcome__container">
        <div class="welcome__logo logo h2">
            CourseZone
        </div>
        <div class="welcome__title h3" style="width: 500px; text-align: center">
            Hello, {{$user->email}}
            <br>
            Enter your new password
        </div>
        <form method="post" action="{{ route('password.reset', ['id' => $user->getKey(), 'token' => $token]) }}" class="welcome__form form">
            @csrf

            @if ($errors->has('password'))
                <div class="alert alert-danger">
                    <ul>@foreach($errors->get('password') as $message)<li>{{$message}}</li>@endforeach</ul>
                </div>
            @endif

            <input name="password" type="password" placeholder="{{ __('main.password') }}" class="welcome__input input" required>
            <input name="password_confirmation" type="password" placeholder="{{ __('main.passwordConfirmation') }}" class="welcome__input input" required>
            <button type="submit" class="welcome__button rounded-red-button button w100p">Reset</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
