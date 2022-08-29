@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <div class="classic-box">
        <p class="h1 mb30">Стата курса {{ $courseId }}</p>
        <p class="mb15">{{ __('main.course') }} Launched: {{ $count['CourseLaunched'].'/'.$count['CourseAssigned']}}</p>
        <p class="mb15">{{ __('main.course') }} Passed: {{ $count['CoursePassed'] }}</p>
        <p class="mb30">{{ __('main.course') }} launched, and at least one section passed: {{ $count['SectionPassed'] }}</p>
        <a href="{{ route('courses.edit', ['id' => $courseId]) }}" class="back-button"><i class="fas fa-arrow-left"></i> {{ __('main.back') }}</a>
    </div>
</div>

@component('components.footer')
@endcomponent
