@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="profile">
        <div class="profile__container classic-box flex flex-just-start">

            <div class="profile__column mb30">
                <div class="profile__row mb20">
                    @if($user->avatar_filename && file_exists('images/avatars/'.$user->user_id."/".$user->avatar_filename))
                        <img src="{{ URL::asset('images/avatars/'.$user->user_id."/".$user->avatar_filename) }}" alt="" class="profile__img">
                    @else
                        <img src="{{ URL::asset('images/default-avatar.png') }}" alt="" class="profile__img">
                    @endif
                    <div class="profile__name-email-col">
                        <div class="profile__name h2 mb15">{{ $user->surname }} {{ $user->name }}</div>
                        <div class="text">{{ $user->email }}</div>
                    </div>
                </div>
                <a href="{{ route('users.edit', ['id' => $user->user_id]) }}" class="profile__edit-button button mb20">Edit profile</a>
                <a href="{{ route('users.edit.avatar', ['id' => $user->user_id]) }}" class="profile__edit-button button mb20">Update avatar</a>

                <div class="text">LMS role: @if ($user->is_teacher == 1) teacher @else student @endif</div>
            </div>

            <div class="profile__column_courses">

                <div class="profile__column mb30">
                    <div class="profile__courses">
                        <div class="text h3 mb15">Assigned courses:</div>

                        @forelse ($assignedCourses as $key => $course)
                            <div class="profile__course">
                                <div class="text profile__course-title flex flex-alit-center">{{ $course->title }}</div>
                                <a href="{{ route('courses.play', ['id' => $course->course_id]) }}" class="text profile__course-button flex flex-alit-center">
                                    <i class="fa-solid fa-play"></i>
                                </a>
                            </div>
                        @empty
                            Assigned courses not found
                        @endforelse

                        @if (!$assignedCourses->isEmpty())
                            <a href="{{ route('courses.assignments') }}" class="profile__more button">More...</a>
                        @endif
                    </div>
                </div>

                @if ($user->is_teacher == 1)
                <div class="profile__column">
                    <div class="profile__courses">
                        <div class="text h3 mb15">Own courses:</div>

                        @forelse ($ownCourses as $key => $course)
                                <div class="profile__course">
                                    <div class="text profile__course-title flex flex-alit-center">{{ $course->title  }}</div>
                                    <a href="{{ route('courses.edit', ['id' => $course->course_id]) }}" class="text profile__course-button flex flex-alit-center">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </div>
                        @empty
                            Own courses not found
                        @endforelse

                        @if (!$ownCourses->isEmpty())
                            <a href="{{ route('courses.own') }}" class="profile__more button">More...</a>
                        @endif
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
