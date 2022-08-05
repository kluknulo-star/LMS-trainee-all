@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent


<div class="container">
    <div class="create">
        <div class="create__container classic-box mrauto">
            <div class="create__title h2 mb30">Create Account</div>

            <form method="post" action="{{ url('/users') }}" class="create__form form">
                @csrf
                <input name="surname" value="{{ old('surname') }}" type="text" placeholder="Surname" class="create__input col-input input">
                <input name="name" value="{{ old('name') }}" type="text" placeholder="Name" class="create__input col-input input">
                <input name="patronymic" value="{{ old('patronymic') }}" type="text" placeholder="Patronymic (optional)" class="create__input col-input input">
                <input name="email" value="{{ old('email') }}" type="email" placeholder="E-mail" class="create__input col-input input">
                <input name="password" type="password" placeholder="Password" class="create__input col-input input">
                <input name="password_confirmation" type="password" placeholder="Confirm password" class="create__input col-input input">
                <button type="submit" class="create__button rounded-red-button button">Create</button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>

        </div>
    </div>
</div>
@component('components.footer')
@endcomponent
