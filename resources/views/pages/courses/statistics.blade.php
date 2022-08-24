@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="classic-box">
        <marquee class="h1 mb20" behavior="" direction="Right">Стата курса {{ $courseId }}</marquee>

        <p class="mb15">{{ __('main.course') }} Launched: {{ $count['CourseLaunched'].'/'.$count['CourseAssigned']}}</p>
        <p class="mb15">{{ __('main.course') }} Passed: {{ $count['CoursePassed'] }}</p>
        <p class="mb15">{{ __('main.course') }} Launched with Section: {{ $count['SectionLaunched'] }}</p>
        <a href="{{ route('courses.edit', ['id' => $courseId]) }}" class="back-button"><i class="fas fa-arrow-left"></i> {{ __('main.back') }}</a>
    </div>
</div>

@component('components.footer')
@endcomponent
