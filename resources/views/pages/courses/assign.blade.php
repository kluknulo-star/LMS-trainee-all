@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent
<div class="container">
    <div class="users w1200">
        <div class="users__title h1">
            <div class="users__after-title-links">
                <li class="assigned-users-navbar">
                    <ul class="assigned-users-navbar-elem">
                        <a href="{{ route('courses.edit.assignments', ['id' => $courseId, 'state' => 'already']) }}" class="aside__link button">
                            {{ __('main.assigned') }} {{ __('main.students') }}
                        </a>
                    </ul>
                    <ul class="assigned-users-navbar-elem">
                        <a href="{{ route('courses.edit.assignments', ['id' => $courseId, 'state' => 'all']) }}" class="aside__link button">
                            {{ __('main.all') }} {{ __('main.students') }}
                        </a>
                    </ul>
                    <ul class="assigned-users-navbar-elem" >
                        <a href="{{ route('courses.edit', ['id' => $courseId]) }}" class="aside__link button">
                            {{ __('main.back') }} {{ __('main.to') }} {{ __('main.edit') }} {{ __('main.course') }}
                        </a>
                    </ul>
                </li>
            </div>
            @if ($state == 'already')
                <form action="{{ route('courses.edit.assignments', ['id' => $courseId, 'state' => 'already']) }}" method="get" class="users__form-search">
                    <input name="search" type="text" placeholder="{{ __('main.search') }}" class="users__input-search">
                    <input name="assign" value="already" type="hidden">
                    <button type="submit" class="users__button-search"><i class="fas fa-search"></i></button>
                </form>
            @elseif ($state == 'all')
                <form action="{{ route('courses.edit.assignments', ['id' => $courseId, 'state' => 'all']) }}" method="get" class="users__form-search">
                    <input name="search" type="text" placeholder="{{ __('main.search') }}" class="users__input-search">
                    <input name="assign" value="all" type="hidden">
                    <button type="submit" class="users__button-search"><i class="fas fa-search"></i></button>
                </form>
            @endif
        </div>
        <div class="classic-box mb30">
            <p class="h2 mb30">{{ __('main.multiply') }} {{ __('main.adding') }}</p>
            <form method="post" action="{{ route('courses.course.assgin', ['id' => $courseId]) }}">
                @csrf
                @method('post')
                <textarea
                    placeholder="{{ __('main.multiplyAddingPlaceholder') }}"
                    class="edit__input col-input input h150" name="studentEmails" id="" cols="30" rows="10"></textarea>
                <button type="submit" class="rounded-black-button">{{ __('main.add') }}</button>
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
                <th class="users__td">{{ __('main.progress') }}</th>
                <th class="users__td"></th>
            </tr>
            </thead>
            <tbody>

            @forelse ($users as $key => $user)
                <tr class="users__tr">
                    @if($user->avatar_filename && file_exists('images/avatars/'.$user->user_id."/".$user->avatar_filename))
                        <th class="users__td users__td-img"><img src="{{ URL::asset('images/avatars/'.$user->user_id."/".$user->avatar_filename) }}" alt="" class="profile__img"></th>
                    @else
                        <th class="users__td users__td-img"><img src="{{ URL::asset('images/default-avatar.png') }}" alt="" class="profile__img"></th>
                    @endif
                    <th class="users__td">{{ $user->user_id }}</th>
                    <th class="users__td">{{ $user->email }}</th>
                    <th class="users__td">{{ $user->surname }}</th>
                    <th class="users__td">{{ $user->name }}</th>
                    <th class="users__td">{{ $user->patronymic }}</th>
                    <th class="users__td">{{ $studentsProgress[$user->user_id] ?? '0'}}%</th>
                    <th class="users__td">
                        @if ($state == 'already')
                            <button class="table-action-button table-delete-button" onclick="document.getElementById('deduct-modal-<?= $user->user_id ?>').style.display = 'flex'">
                                <i class="fas fa-trash"></i>
                            </button>
                        @elseif ($state == 'all')
                            <button class="table-action-button table-delete-button" onclick="document.getElementById('assign-modal-<?= $user->user_id ?>').style.display = 'flex'">
                                <i class="fas fa-plus"></i>
                            </button>
                        @endif
                    </th>
                </tr>
                <div class="modal" id="deduct-modal-{{ $user->user_id }}">
                    <div class="modal-box">
                        <p class="modal-text modal-text-delete mb20 mr20">{{ __('main.sureQuestion') }} {{ __('main.deduct') }} {{ $user->name }} {{ __('main.fromYourCourse') }}?</p>

                        <div class="modal-buttons">
                            <form class="table-action-form" action="{{ route('courses.course.deduct', ['id' => $courseId]) }}" method="post">
                                @csrf
                                @method('post')
                                <input name="user_id" type="hidden" value="{{ $user->user_id }}">
                                <input name="action" type="hidden" value="deduct">
                                <button type="submit" class="table-action-button confirm-button">{{ __('main.confirm') }}</button>
                            </form>
                            <button onclick="document.getElementById('deduct-modal-<?= $user->user_id ?>').style.display = 'none'" class="table-action-button cancel-button">{{ __('main.cancel') }}</button>
                        </div>

                    </div>
                </div>

                <div class="modal" id="assign-modal-{{ $user->user_id }}">
                    <div class="modal-box">
                        <p class="modal-text modal-text-restore mb20 mr20">{{ __('main.sureQuestion') }} {{ __('main.assign') }} {{ $user->name }} {{ __('main.toYourCourse') }}?</p>

                        <div class="modal-buttons">
                            <form class="table-action-form" action="{{ route('courses.course.assgin', ['id' => $courseId]) }}" method="post">
                                @csrf
                                @method('post')
                                <input name="user_id" type="hidden" value="{{ $user->user_id }}">
                                <input name="action" type="hidden" value="assign">
                                <button type="submit" class="table-action-button confirm-button">{{ __('main.confirm') }}</button>
                            </form>
                            <button onclick="document.getElementById('assign-modal-<?= $user->user_id ?>').style.display = 'none'" class="table-action-button cancel-button">{{ __('main.cancel') }}</button>
                        </div>
                    </div>
                </div>
            @empty
                There are no students
            @endforelse

            {{ $users->withQueryString()->links() }}

            </tbody>
        </table>
    </div>
</div>

@component('components.footer')
@endcomponent
