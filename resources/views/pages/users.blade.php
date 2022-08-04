@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="users">
        <div class="users__title h1">
            Users
            <a href="" class="action-button title-button"><i class="fas fa-plus"></i></a>
            <a href="" class="action-button title-button"><i class="fas fa-search"></i></a>
        </div>
        <table class="users__table">
            <thead>
                <tr class="users__tr users__tr_head">
                    <th class="users__td">ID</th>
                    <th class="users__td">email</th>
                    <th class="users__td">Surname</th>
                    <th class="users__td">Name</th>
                    <th class="users__td">Patronymic</th>
                    <th class="users__td"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                    <tr class="users__tr">
                        <th class="users__td">{{ $user['id'] }}</th>
                        <th class="users__td">{{ $user['email'] }}</th>
                        <th class="users__td">{{ $user['surname'] }}</th>
                        <th class="users__td">{{ $user['name'] }}</th>
                        <th class="users__td">{{ $user['patronymic'] }}</th>
                        <th class="users__td"></th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@component('components.footer')
@endcomponent
