@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="profile">
        <div class="profile__container classic-box flex flex-just-start">

            <div class="profile__column mb30">
                <div class="profile__row mb20">
                    @if(mb_substr($user->avatar_filename,0,4) == 'http')
                        <img src="{{ URL::asset($user->avatar_filename) }}" alt="" class="profile__img">
                    @elseif($user->avatar_filename && file_exists('images/avatars/'.$user->user_id."/".$user->avatar_filename))
                        <img src="{{ URL::asset('images/avatars/'.$user->user_id."/".$user->avatar_filename) }}" alt="" class="profile__img">
                    @else
                        <img src="{{ URL::asset('images/default-avatar.png') }}" alt="" class="profile__img">
                    @endif
                    <div class="profile__name-email-col">
                        <div class="profile__name h2 mb15">{{ $user->surname }} {{ $user->name }}</div>
                        <div class="text">{{ $user->email }}</div>
                    </div>
                </div>
                @if (auth()->id() == $user->user_id)
                    <a href="{{ route('users.edit', ['id' => $user->user_id]) }}"
                       class="profile__edit-button button mb20">{{ __('main.edit') }} {{ __('main.profile') }}</a>
                    <a href="{{ route('users.edit.avatar', ['id' => $user->user_id]) }}"
                       class="profile__edit-button button mb20">{{ __('main.update') }} {{ __('main.avatar') }}</a>
                @elseif (auth()->user()->is_teacher == 1 && $user->is_teacher != 1)
                    <form method="post" action="{{ route('users.assign.teacher', ['id' => $user->user_id]) }}">
                        @csrf
                        @method('patch')
                        <button type="submit" class="rounded-black-button button mb15">{{ __('main.assign') }} {{ __('main.userVin') }} {{ __('main.teacherTvor') }}</button>
                    </form>
                @endif
                <div class="text">LMS {{ __('main.role') }}:
                    @if ($user->is_teacher == 1)
                        {{ __('main.teacher') }}
                    @else
                        {{ __('main.student') }}
                    @endif</div>
            </div>

{{-- Проверка на наличие экспортов --}}
            @if(auth()->id() == $user->user_id)
            <div class="profile__column_courses">
                <p class="h3 mb30">{{ __('main.export') }} {{ __('main.to') }} Excel</p>
                <div class="mb30">
                    <a class="rounded-black-button" href="{{ route('courses.export', ['type' => 'all']) }}">{{ __('main.exportVerb') }} {{ __('main.all') }} {{ __('main.courses') }}</a>
                    <a class="rounded-black-button" href="{{ route('courses.export', ['type' => 'own']) }}">{{ __('main.exportVerb') }} {{ __('main.own') }} {{ __('main.courses') }}</a>
                </div>
                @foreach($exports as $export)
                    <form class="mb15" method="post" action="{{ route('courses.export.download', ['id' => $export->export_id]) }}">
                        @csrf
                        <button class="button rounded-red-button">{{ __('main.download') }} {{ $export->export_file_path }}</button>
                    </form>
                @endforeach
            </div>
            @endif
{{--            @endif--}}

{{--            <div class="profile__column_courses">--}}

{{--                <div class="profile__column mb30">--}}
{{--                    <div class="profile__courses">--}}
{{--                        <div class="text h3 mb15">Assigned courses:</div>--}}

{{--                        @forelse ($assignedCourses as $key => $course)--}}
{{--                            <div class="profile__course">--}}
{{--                                <div class="text profile__course-title flex flex-alit-center">{{ $course->title }}</div>--}}

{{--                                @if(auth()->id() == $user->user_id)--}}
{{--                                    <a href="{{ route('courses.play', ['id' => $course->course_id]) }}"--}}
{{--                                       class="text profile__course-button flex flex-alit-center">--}}
{{--                                        <i class="fa-solid fa-play"></i>--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        @empty--}}

{{--                            Assigned courses not found--}}
{{--                        @endforelse--}}

{{--                        @if (!$assignedCourses->isEmpty() && auth()->user()->user_id == $user->user_id)--}}
{{--                            <a href="{{ route('courses.assignments') }}" class="profile__more button">More...</a>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                @if ($user->is_teacher == 1)--}}
{{--                    <div class="profile__column">--}}
{{--                        <div class="profile__courses">--}}
{{--                            <div class="text h3 mb15">Own courses:</div>--}}

{{--                            @forelse ($ownCourses as $key => $course)--}}
{{--                                <div class="profile__course">--}}
{{--                                    <div--}}
{{--                                        class="text profile__course-title flex flex-alit-center">{{ $course->title  }}</div>--}}
{{--                                    @if(auth()->id() == $user->user_id)--}}
{{--                                        <a href="{{ route('courses.edit', ['id' => $course->course_id]) }}"--}}
{{--                                           class="text profile__course-button flex flex-alit-center">--}}
{{--                                            <i class="fas fa-pen"></i>--}}
{{--                                        </a>--}}
{{--                                    @else--}}
{{--        !                                <a href="{{ route('courses.play', ['id' => $course->course_id]) }}"--}}
{{--        !                                   class="text profile__course-button flex flex-alit-center">--}}
{{--        !                                    <i class="fa-solid fa-play"></i>--}}
{{--        !                               </a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            @empty--}}
{{--                                Own courses not found--}}
{{--                            @endforelse--}}

{{--                            @if (!$ownCourses->isEmpty() && auth()->user()->user_id == $user->user_id)--}}
{{--                                <a href="{{ route('courses.own') }}" class="profile__more button">More...</a>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
