@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="courses">
    <div class="container">

        <div class="courses__title flex flex-just-spbtw flex-alit-center h1 w1200">
            My courses

            <div class="courses__after-title-links">
                <a href="{{ route('courses.create') }}" class="users__title-link">
                    <i class="fas fa-plus"></i>
                </a>
            </div>

            <form action="{{ route('courses.own') }}" method="get" class="courses__form-search">
                <input name="search" type="text" placeholder="Search course" class="courses__input-search">
                <button type="submit" class="courses__button-search"><i class="fas fa-search"></i></button>
            </form>

        </div>
        {{ $courses->withQueryString()->links() }}

        <div class="courses__row">
            @foreach ($courses as $key => $course)
                <div class="courses__item">
                    <img src="" alt="" class="courses__img">
                    <div class="courses__course-title h3 mb20">{{ $course->title }}</div>
                    <div
                        class="courses__course-description mb30">{{ Str::limit($course->description, 200, '...') }}</div>
                    <div class="courses__course-author">
                        Author: <a
                            href="{{ route('users.show', ['id' => auth()->id()]) }}">{{ auth()->user()->email }}</a>
                    </div>
                    <div class="courses__course-assign-count"><i
                            class="fa-solid fa-user"></i> {{ $course->assignedUsers()->count() }}</div>
                    @if (!$course->deleted_at)
                        <a href="{{ route('courses.edit', ['id' => $course->course_id]) }}"
                           class="courses__course-edit"><i class="fas fa-pen"></i></a>
                        <a href="" class="courses__course-play"><i class="fas fa-play"></i></a>
                    @else
                        <a class="courses__course-edit"
                           onclick="document.getElementById('restore-modal-<?= $course->course_id  ?>').style.display = 'flex'">
                            <i class="fa-solid fa-arrow-rotate-right"></i>
                        </a>
                        {{--                        <a href="" class="courses__course-edit"><i class="fa-solid fa-arrow-rotate-right"></i></a>--}}
                    @endif
                </div>

                <div class="modal" id="restore-modal-{{ $course->course_id }}">
                    <div class="modal-box">
                        <p class="modal-text modal-text-restore mb20 mr20">You sure to <span>restore</span> course:
                            "{{ $course->title }}" ?</p>

                        <div class="modal-buttons">
                            <form class="table-action-form"
                                  action="{{ route('courses.restore', ['id' => $course->course_id]) }}" method="post">
                                @csrf
                                @method('post')
                                <input name="user_id" type="hidden" value="{{ $course->course_id }}">
                                <button type="submit" class="table-action-button confirm-button">Confirm</button>
                            </form>
                            <button
                                onclick="document.getElementById('restore-modal-<?= $course->course_id ?>').style.display = 'none'"
                                class="table-action-button cancel-button">Cancel
                            </button>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
    {{--  Роут для play курса  --}}
    {{--    {{ route('courses.play', ['id' => $course->course_id]) }}--}}
</div>








@component('components.footer')
@endcomponent
