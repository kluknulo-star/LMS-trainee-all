@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="edit">
        <div class="edit__container classic-box mrauto">
            <div class="edit__title h2 mb30">Edit Account</div>
            <form method="post" action="{{ route('users.update', ['id' => $user->user_id]) }}" class="edit__form form">
                @csrf
                @method('patch')
                <input name="user_id" value="{{ old('user_id') ?? $user->user_id }}" type="hidden" class="edit__input col-input input">
                <input name="surname" value="{{ old('surname') ?? $user->surname }}" type="text" placeholder="Surname" class="edit__input col-input input">
                <input name="name" value="{{  old('name') ?? $user->name }}" type="text" placeholder="Name" class="edit__input col-input input">
                <input name="patronymic" value="{{  old('patronymic') ?? $user->patronymic }}" type="text" placeholder="Patronymic (optional)" class="edit__input col-input input">
                <input name="email" value="{{  old('email') ?? $user->email }}" type="email" placeholder="E-mail" class="edit__input col-input input">
                <input name="password" type="password" placeholder="Password" class="edit__input col-input input">
                <input name="password_confirmation" type="password" placeholder="Confirm password" class="edit__input input mb30">
                <button type="submit" class="edit__button rounded-red-button button mb20">Save changes</button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <a href="{{ url()->previous() }}" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
            </form>
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
