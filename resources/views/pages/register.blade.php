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

            @if ($errors->has('surname'))
                <div class="alert alert-danger">
                    <ul>@foreach($errors->get('surname') as $message)<li>{{$message}}</li>@endforeach</ul>
                </div>
            @endif
            <input value="{{ old('surname') }}" name="surname" type="text" placeholder="Surname" class="welcome__input input @error('surname') is-invalid @enderror" required>

            @if ($errors->has('name'))
                <div class="alert alert-danger">
                    <ul>@foreach($errors->get('name') as $message)<li>{{$message}}</li>@endforeach</ul>
                </div>
            @endif
            <input value="{{ old('name') }}" name="name" type="text" placeholder="Name" class="welcome__input input" required>

            @if ($errors->has('patronymic'))
                <div class="alert alert-danger">
                    <ul>@foreach($errors->get('patronymic') as $message)<li>{{$message}}</li>@endforeach</ul>
                </div>
            @endif
            <input value="{{ old('patronymic') }}" name="patronymic" type="text" placeholder="Patronymic (optional)" class="welcome__input input">

            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    <ul>@foreach($errors->get('email') as $message)<li>{{$message}}</li>@endforeach</ul>
                </div>
            @endif
            <input value="{{ old('email') }}" name="email" type="email" placeholder="E-mail" class="welcome__input input" required>

            @if ($errors->has('password'))
                <div class="alert alert-danger">
                    <ul>@foreach($errors->get('password') as $message)<li>{{$message}}</li>@endforeach</ul>
                </div>
            @endif
            <input name="password" type="password" placeholder="Password" class="welcome__input input" required>
            <input name="password_confirmation" type="password" placeholder="Confirm password" class="welcome__input input" required>
            <p class="welcome__text">Registered?&nbsp<a href="{{ route('login') }}">Sign in</a></p>
            <button type="submit" class="welcome__button rounded-red-button button w100p">Register</button>
        </form>

    </div>
</div>

@component('components.footer')
@endcomponent
