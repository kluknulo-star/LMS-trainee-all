@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent
<div class="container">
    <div class="users w1200">
        <div class="users__title h1">
            {{ __('main.students') }}
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
                    <th class="users__td users__td-img">{{ __('main.avatar') }}</th>
                    <th class="users__td">ID</th>
                    <th class="users__td">{{ __('main.email') }}</th>
                    <th class="users__td">{{ __('main.surname') }}</th>
                    <th class="users__td">{{ __('main.name') }}</th>
                    <th class="users__td">{{ __('main.patronymic') }}</th>
                    <th class="users__td"></th>
                </tr>
            </thead>
            <tbody>

                @forelse ($users as $key => $user)
                    <tr class="users__tr">
                        @if(mb_substr($user->avatar_filename,0,4) == 'http')
                            <th class="users__td users__td-img"><img src="{{ URL::asset($user->avatar_filename) }}" alt="" class="profile__img"></th>
                        @elseif($user->avatar_filename && file_exists('images/avatars/'.$user->user_id."/".$user->avatar_filename))
                            <th class="users__td users__td-img"><img src="{{ URL::asset('images/avatars/'.$user->user_id."/".$user->avatar_filename) }}" alt="" class="profile__img"></th>
                        @else
                            <th class="users__td users__td-img"><img src="{{ URL::asset('images/default-avatar.png') }}" alt="" class="profile__img"></th>
                        @endif
                        <th class="users__td">{{ $user->user_id }}</th>
                        <th class="users__td">{{ $user->email }}</th>
                        <th class="users__td">{{ $user->surname }}</th>
                        <th class="users__td">{{ $user->name }}</th>
                        <th class="users__td">{{ $user->patronymic }}</th>
                        <th class="users__td">
                            @if ($user->deleted_at !== NULL)
                                <button class="table-action-button table-restore-button" onclick="document.getElementById('restore-modal-<?= $user->user_id ?>').style.display = 'flex'">
                                    <i class="fa-solid fa-arrow-rotate-right"></i>
                                </button>
                            @else
                                <a class="table-action-button table-show-button" href="{{ route('users.show', ['id' => $user->user_id]) }}"><i class="fas fa-eye"></i></a>

                                @can ('update', [$user])
                                <a class="table-action-button table-edit-button" href="{{ route('users.edit', ['id' => $user->user_id]) }}"><i class="fas fa-pen"></i></a>
                                @endcan

                                @can ('delete', [$user])
                                <button class="table-action-button table-delete-button" onclick="document.getElementById('delete-modal-<?= $user->user_id ?>').style.display = 'flex'">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan

                            @endif
                        </th>
                    </tr>

                    <div class="modal" id="delete-modal-{{ $user->user_id }}">
                        <div class="modal-box">
                            <p class="modal-text modal-text-delete mb20 mr20">You sure to <span>delete</span> user id{{ $user->user_id }}?</p>

                            <div class="modal-buttons">
                                <form class="table-action-form" action="{{ route('users.delete', ['id' => $user->user_id]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input name="user_id" type="hidden" value="{{ $user->user_id }}">
                                    <button type="submit" class="table-action-button confirm-button">Confirm</button>
                                </form>
                                <button onclick="document.getElementById('delete-modal-<?= $user->user_id ?>').style.display = 'none'" class="table-action-button cancel-button">Cancel</button>
                            </div>

                        </div>
                    </div>

                    <div class="modal" id="restore-modal-{{ $user->user_id }}">
                        <div class="modal-box">
                            <p class="modal-text modal-text-restore mb20 mr20">You sure to <span>restore</span> user id{{ $user->user_id }}?</p>

                            <div class="modal-buttons">
                                <form class="table-action-form" action="{{ route('users.restore', ['id' => $user->user_id]) }}" method="post">
                                    @csrf
                                    @method('post')
                                    <input name="user_id" type="hidden" value="{{ $user->user_id }}">
                                    <button type="submit" class="table-action-button confirm-button">Confirm</button>
                                </form>
                                <button onclick="document.getElementById('restore-modal-<?= $user->user_id ?>').style.display = 'none'" class="table-action-button cancel-button">Cancel</button>
                            </div>
                        </div>
                    </div>
                @empty
                    Пользователей пока нету ;(
                @endforelse

                {{ $users->withQueryString()->links() }}

            </tbody>
        </table>
    </div>
</div>

@component('components.footer')
@endcomponent
