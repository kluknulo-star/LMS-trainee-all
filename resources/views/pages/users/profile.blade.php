@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="profile">
        <div class="profile__container classic-box flex flex-just-start">

            <div class="profile__column mb30">
                <div class="profile__row mb20">
                    <img src="{{ URL::asset('img/default-avatar.png') }}" alt="" class="profile__img">
                    <div class="profile__name-email-col">
                        <div class="profile__name h2 mb15">{{ $user->surname }} {{ $user->name }}</div>
                        <div class="text">{{ $user->email }}</div>
                    </div>
                </div>
                <a href="{{ route('users.edit', ['id' => $user->user_id]) }}" class="profile__edit-button button mb20">Edit profile</a>
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
                            <a href="{{ route('courses.assignments') }}" class="profile__more button">More...</a>
                        @empty
                            Assigned courses not found
                        @endforelse

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

                        <a href="{{ route('courses.own') }}" class="profile__more button">More...</a>

                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
