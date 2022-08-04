@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="profile">
        <div class="profile__container classic-box mrauto">

            <div class="profile__column mb30">
                <div class="profile__row mb20">
                    <img src="{{ URL::asset('img/default-avatar.png') }}" alt="" class="profile__img">
                    <div class="profile__name-email-col">
                        <div class="profile__name h2 mb15">Surname Name</div>
                        <div class="text">mail@email.com</div>
                    </div>
                </div>
                <button class="profile__edit-button button">Edit profile</button>
            </div>

            <div class="profile__column mb30">
                <div class="profile__courses">
                    <div class="text h3 mb15">Assigned courses:</div>
                    @for ($i = 1; $i < 5; $i++)
                        <div class="profile__course">
                            <div class="text profile__course-title">Course {{ $i }}</div>
                            <div class="text profile__course-status">Active</div>
                            <button class="text profile__course-button">Go <i class="fas fa-arrow-right"></i></button>
                        </div>
                    @endfor
                    <a href="{{ url('/courses') }}" class="profile__more button">More...</a>
                </div>
            </div>

            <div class="profile__column">
                <div class="profile__courses">
                    <div class="text h3 mb15">Own courses:</div>
                    @for ($i = 1; $i < 5; $i++)
                        <div class="profile__course">
                            <div class="text profile__course-title">Course {{ $i }}</div>
                            <div class="text profile__course-status">Published</div>
                            <button class="text profile__course-button">Edit <i class="fas fa-pen"></i></button>
                        </div>
                    @endfor
                    <a href="{{ url('/courses/my') }}" class="profile__more button">More...</a>
                </div>
            </div>

        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
