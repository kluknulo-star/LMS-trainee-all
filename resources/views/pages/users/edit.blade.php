@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="edit">
        <div class="edit__container classic-box mrauto">
            <div class="edit__title h2 mb30">Edit Account</div>
            <form method="post" action="{{ url('/users/'.$user->user_id.'/update') }}" class="edit__form form">
                <input name="surname" value="{{ $user->surname }}" type="text" placeholder="Surname" class="edit__input col-input input">
                <input name="name" value="{{ $user->name }}" type="text" placeholder="Name" class="edit__input col-input input">
                <input name="patronymic" value="{{ $user->patronymic }}" type="text" placeholder="Patronymic (optional)" class="edit__input col-input input">
                <input name="email" value="{{ $user->email }}" type="email" placeholder="E-mail" class="edit__input col-input input">
                <input name="password" type="password" placeholder="Password" class="edit__input col-input input">
                <input name="passwordRepeat" type="password" placeholder="Confirm password" class="edit__input input mb30">
                <button type="submit" class="edit__button rounded-red-button button">Save changes</button>
            </form>
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
