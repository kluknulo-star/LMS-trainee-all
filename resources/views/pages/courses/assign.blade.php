@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent
<div class="container">
    <div class="users w1200">
        <div class="users__title h1">
            <div class="users__after-title-links">
                <a href="{{ route('courses.edit.assignments', ['assign' => 'already', 'id' => $courseId]) }}" class="users__title-link">
                   Already assigned students
                </a>
                <a href="{{ route('courses.edit.assignments', ['assign' => 'all', 'id' => $courseId]) }}" class="users__title-link">
                   All students
                </a>
            </div>
            <form action="{{ route('users') }}" method="get" class="users__form-search">
                <input name="search" type="text" placeholder="Search" class="users__input-search">
                <button type="submit" class="users__button-search"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <table class="users__table classic-box">
            <thead>
            <tr class="users__tr users__tr_head">
                <th class="users__td users__td-img">Ava</th>
                <th class="users__td">ID</th>
                <th class="users__td">E-mail</th>
                <th class="users__td">Surname</th>
                <th class="users__td">Name</th>
                <th class="users__td">Patronymic</th>
            </tr>
            </thead>
            <tbody>

            @if ($users->isEmpty())
                Пользователей пока нету ;(
            @else

                @foreach ($users as $key => $user)
                    <tr class="users__tr">
                        <th class="users__td users__td-img"><img src="{{ URL::asset('img/default-avatar.png') }}" alt="">{{ $user->avatar_filename }}</th>
                        <th class="users__td">{{ $user->user_id }}</th>
                        <th class="users__td">{{ $user->email }}</th>
                        <th class="users__td">{{ $user->surname }}</th>
                        <th class="users__td">{{ $user->name }}</th>
                        <th class="users__td">{{ $user->patronymic }}</th>
                    </tr>
                @endforeach

                {{ $users->withQueryString()->links() }}

            @endif

            </tbody>
        </table>
    </div>
</div>

@component('components.footer')
@endcomponent
