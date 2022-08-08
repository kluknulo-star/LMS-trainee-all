@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent
<div class="container">
    <div class="users">
        <div class="users__title h1">
            Students
            <div class="users__after-title-links">
                <a href="{{ route('users.create') }}" class="users__title-link">
                    <i class="fas fa-plus"></i>
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
                    <th class="users__td">ID</th>
                    <th class="users__td">E-mail</th>
                    <th class="users__td">Surname</th>
                    <th class="users__td">Name</th>
                    <th class="users__td">Patronymic</th>
                    <th class="users__td"></th>
                </tr>
            </thead>
            <tbody>

            @if ($users->isEmpty())

            @else

                @foreach ($users as $key => $user)
                    <tr class="users__tr">
                        <th class="users__td">{{ $user->user_id }}</th>
                        <th class="users__td">{{ $user->email }}</th>
                        <th class="users__td">{{ $user->surname }}</th>
                        <th class="users__td">{{ $user->name }}</th>
                        <th class="users__td">{{ $user->patronymic }}</th>
                        <th class="users__td">
                            @if ($user->deleted_at !== NULL)
                                <a class="table-action-button table-restore-button" href="{{ route('users.restore', ['id' => $user->user_id]) }}"><i class="fa-solid fa-arrow-rotate-right"></i></a>
                            @else
                                <a class="table-action-button table-show-button" href="{{ route('users.show', ['id' => $user->user_id]) }}"><i class="fas fa-eye"></i></a>
                                <a class="table-action-button table-edit-button" href="{{ route('users.edit', ['id' => $user->user_id]) }}"><i class="fas fa-pen"></i></a>
                                <form class="table-action-form" action="{{ route('users.destroy', ['id' => $user->user_id]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input name="user_id" type="hidden" value="{{ $user->user_id }}">
                                    <button type="submit" class="table-action-button table-delete-button"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </th>
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
