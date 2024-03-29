<script>
    document.addEventListener('DOMContentLoaded', function() {
        let errors = [];
        console.log()
        const fields = [{
                id: 'name',
                msg: 'Inserire solo caratteri testuali e massimo 255 caratteri'
            },
            {
                id: 'last_name',
                msg: 'Inserire solo caratteri testuali e massimo 255 caratteri'
            },
            {
                id: 'address',
                msg: 'Inserire un indirizzo'
            },
            {
                id: 'email',
                msg: 'Inserire un indirizzo email valido'
            },
            {
                id: 'password',
                msg: 'Inserire una password valida (almeno 8 caratteri)'
            },
            {
                id: 'password-confirm',
                msg: 'La password risulta diversa o è vuota'
            }
        ];

        fields.forEach(field => {
            const input = document.getElementById(field.id);
            input.addEventListener('blur', () => validateField(input, field.msg));
        });

        const specialtiesCheckbox = document.querySelectorAll('input[type="checkbox"][name="specialties[]"]');
        specialtiesCheckbox.forEach(checkbox => {
            checkbox.addEventListener('change', () => validateSpecialties());
        });

        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight) {
                validateSpecialties();
            }
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const inputsVal = fields.map(field => {
                const input = document.getElementById(field.id);
                return input.value.trim();
            });
            const emptyFields = inputsVal.filter(val => val === '').length;
            if (emptyFields === 0) {
                if (errors.length === 0) {
                    this.submit();
                } else {
                    handleValidationErrors(
                        'Il modulo contiene errori di validazione. Correggi prima di inviare.');
                }
            } else {
                handleValidationErrors('Vi sono dei campi non compilati');
            }
        });

        function handleValidationErrors(errorMessage) {
            const input = document.getElementById('submit-register');
            const errorMsgId = input.id + '-msg';
            const parentDiv = input.parentElement;
            const errorDiv = document.getElementById(errorMsgId);
            if (errorDiv) {
                errorDiv.remove();
            }
            const newDiv = createErrorDiv(errorMsgId, errorMessage);
            newDiv.classList.remove('invalid-feedback');
            newDiv.classList.add('text-red');
            parentDiv.appendChild(newDiv);
        }

        function validateField(input, message) {
            const value = input.value.trim();
            const errorMsgId = input.id + '-msg';
            const errorDiv = document.getElementById(errorMsgId);
            let isValid = true;
            switch (input.id) {
                case 'email':
                    isValid = value !== '' && isValidEmail(value);
                    break;
                case 'name':
                case 'last_name':
                    isValid = value !== '' && containsOnlyLetters(input.value);
                    break;
                case 'password':
                    isValid = value !== '' && value.length >= 8;
                    break;
                case 'password-confirm':
                    isValid = value !== '' && confirmPas(value);
                    break;
                case 'address':
                    isValid = value !== '' && value.length <= 255;
                    break;
                default:
                    isValid = value !== '';
            }
            if (!isValid) {
                input.classList.add('is-invalid');
                if (!errorDiv) {
                    const parentDiv = input.parentElement;
                    const newDiv = createErrorDiv(errorMsgId, message);
                    parentDiv.appendChild(newDiv);
                    errors.push(message);
                }
            } else {
                input.classList.remove('is-invalid');
                if (errorDiv) {
                    errorDiv.remove();
                    errors.splice(errors.indexOf(message), 1);
                }
            }
        }


        function validateSpecialties() {
            const selectedSpecialties = document.querySelectorAll(
                'input[type="checkbox"][name="specialties[]"]:checked');
            const errorMsgId = 'specialties-msg';
            const input = document.getElementById('specialties-div');
            const errorDiv = document.getElementById(errorMsgId);
            if (selectedSpecialties.length === 0) {
                input.classList.add('is-invalid');
                if (!errorDiv) {
                    const parentDiv = input.parentElement;
                    const newDiv = createErrorDiv(errorMsgId, 'Selezionare una o più specializzazioni');
                    parentDiv.appendChild(newDiv);
                    errors.push('Selezionare una o più specializzazioni');
                }
            } else {
                input.classList.remove('is-invalid');
                if (errorDiv) {
                    errorDiv.remove();
                    errors.splice(errors.indexOf('Selezionare una o più specializzazioni'), 1);
                }
            }
        }

        function isValidEmail(email) {
            const indexCh = email.indexOf('@');
            if (indexCh === -1 || indexCh === email.length - 1) {
                return false;
            }
            const emailSplit = email.substring(indexCh);
            return email.includes('@') && emailSplit.includes('.');
        }

        function createErrorDiv(id, message) {
            const newDiv = document.createElement('div');
            newDiv.classList.add('invalid-feedback');
            newDiv.textContent = message;
            newDiv.setAttribute('id', id);
            return newDiv;
        }

        function containsOnlyLetters(str) {
            return /^[a-zA-Z\s]+$/.test(str) && str.length <= 255;
        }

        function confirmPas(pas) {
            const originalPas = document.getElementById('password');
            return pas === originalPas.value
        }

    });
</script>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Registrazione') }}</div>

                    <div class="card-body">
                        <form id="register-form" method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-4 row">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Nome*') }}</label>

                                <div id="name-div" class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="last_name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Cognome*') }}</label>

                                <div id="last_name-div" class="col-md-6">
                                    <input id="last_name" type="text"
                                        class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                        value="{{ old('last_name') }}" autocomplete="last_name" autofocus>

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="address"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Indirizzo*') }}</label>

                                <div id="address-div" class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('address') is-invalid @enderror" name="address"
                                        value="{{ old('address') }}" autocomplete="address" autofocus>

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="specialties"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Specializzazione*') }}</label>

                                <div id="specialties-div"
                                    class="col-md-6 form-control @error('specialties') is-invalid @enderror">
                                    @foreach ($specialties as $specialty)
                                        <div class="form-check">
                                            <input type="checkbox" value="{{ $specialty->id }}"
                                                {{ in_array($specialty->id, old('specialties', [])) ? 'checked' : '' }}
                                                id="specialties{{ $specialty->id }}" name="specialties[]">
                                            <label class="form-check-label">{{ $specialty->name }}</label>
                                        </div>
                                    @endforeach

                                    @error('specialties')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail*') }}</label>

                                <div id="email-div" class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password*') }}</label>

                                <div id="password-div" class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Conferma Password*') }}</label>

                                <div id="password-confirm-div" class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <div class="col-md-6 offset-md-4 d-flex">
                                    <button id="submit-register" type="submit" class="btn btn-primary">
                                        {{ __('Registrati') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
