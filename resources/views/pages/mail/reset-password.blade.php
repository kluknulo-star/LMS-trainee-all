@component('components.header')
@endcomponent

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">To reset your password, please, click the link below</div>

                <div class="card-body">
                    <a href="{{ route('password.reset', ['id' => $user->getKey(), 'token' => $token]) }}">Reset my password</a>
                </div>
            </div>
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent

