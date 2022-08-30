@component('components.header')
@endcomponent

<div class="container">
    <h1>Hello, {{ $user->email }}!</h1>
    <h2>Course Zone in touch!</h2>

    <p>You have been subscribed to the course "{{ $course->title }}" by  {{ $course->author->email }}</p>
    <p>To authenticate in the Course Zone, please, click the link below</p>
    <p>Your temporary password: {{ $password }} <br> Do not forget to change it!</p>
    <a href="{{ route('login') }}">Click me</a>

</div>

@component('components.footer')
@endcomponent

