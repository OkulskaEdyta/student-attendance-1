<form action="{{route('login.store')}}" method="POST" novalidate>
    @csrf
    <div class="login-box__fields">
        <x-input-with-label type="email" id="email" name="email" value=""
            placeholder="{{ ucfirst(__('form-placeholders.student_email')) }}" required>
            {{ ucfirst(__('form-labels.email')) }}
        </x-input-with-label>

        <x-input-with-label id="password" name="password" type="password" value="" required>
            {{ ucfirst(__('form-labels.password')) }}
        </x-input-with-label>
    </div>

    <button type="submit" class="btn btn--primary btn--full">
        Se connecter
    </button>

</form>
