<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – SMA Al-Azhar Jakarta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #022448;
            background-image: radial-gradient(rgba(255,255,255,.055) 1px, transparent 1px);
            background-size: 32px 32px;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .msym {
            font-family: 'Material Symbols Outlined';
            font-weight: normal; font-style: normal; line-height: 1;
            letter-spacing: normal; text-transform: none;
            display: inline-flex; white-space: nowrap;
            font-variation-settings: 'FILL' 0,'wght' 300,'GRAD' 0,'opsz' 24;
            vertical-align: middle; user-select: none;
        }
        /* Card */
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px 32px;
            width: 100%; max-width: 420px;
            box-shadow: 0 24px 80px rgba(0,0,0,.28);
            position: relative; overflow: hidden;
        }
        /* Decorative blobs */
        .card::before, .card::after {
            content: ''; position: absolute; border-radius: 50%;
            pointer-events: none;
        }
        .card::before {
            width: 180px; height: 180px;
            background: #022448; opacity: .04;
            top: -60px; right: -60px;
        }
        .card::after {
            width: 120px; height: 120px;
            background: #FFB21D; opacity: .06;
            bottom: -40px; left: -40px;
        }
        /* Header */
        .card-hd {
            text-align: center; margin-bottom: 32px;
        }
        .logo-wrap {
            width: 68px; height: 68px; border-radius: 50%;
            background: #F0F4FF;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            box-shadow: 0 4px 16px rgba(2,36,72,.15);
            overflow: hidden;
        }
        .logo-wrap img {
            width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
        }
        .logo-wrap .msym { font-size: 32px; color: #022448; }
        .school-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 800; font-size: 18px;
            color: #022448; line-height: 1.2;
        }
        .school-sub {
            font-size: 12.5px; color: #74777F; margin-top: 4px;
        }
        /* Error alert */
        .err-alert {
            display: flex; align-items: center; gap: 8px;
            background: #FEF2F2; border: 1px solid #FECACA;
            color: #DC2626; border-radius: 8px;
            padding: 10px 12px; font-size: 13px; margin-bottom: 20px;
        }
        .err-alert .msym { font-size: 18px; font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }
        /* Input group */
        .field { margin-bottom: 22px; }
        .field label {
            display: block; font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .09em;
            color: #74777F; margin-bottom: 8px;
        }
        .input-wrap {
            display: flex; align-items: center;
            border-bottom: 2px solid #E0E3E6;
            transition: border-color .2s; position: relative;
        }
        .input-wrap:focus-within { border-bottom-color: #022448; }
        .input-wrap .msym {
            font-size: 20px; color: #74777F;
            margin-right: 8px; flex-shrink: 0;
        }
        .input-wrap input {
            flex: 1; border: none; outline: none;
            background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: #191C1E;
            padding: 8px 0;
        }
        .input-wrap input::placeholder { color: #B0B4BA; }
        .toggle-pw {
            background: none; border: none; cursor: pointer;
            color: #74777F; display: flex; align-items: center;
            padding: 4px; flex-shrink: 0;
        }
        .toggle-pw:hover { color: #022448; }
        .toggle-pw .msym { font-size: 20px; }
        /* Submit button */
        .btn-submit {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 14px;
            background: #022448; color: #FFB21D;
            border: none; border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 700;
            cursor: pointer; margin-top: 28px;
            transition: filter .2s, transform .1s;
        }
        .btn-submit:hover { filter: brightness(1.18); }
        .btn-submit:active { transform: scale(.98); }
        .btn-submit .msym { font-size: 20px; color: #FFB21D; }
        /* Footer */
        .card-ft {
            text-align: center; margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #F0F2F5;
            font-size: 12px; color: #74777F;
        }
        /* Responsive */
        @media (max-width: 480px) {
            .card { padding: 30px 22px 24px; }
            .school-name { font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-hd">
            <div class="logo-wrap">
                <img src="{{ asset('images/logo.png') }}"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     alt="Logo">
                <span class="msym" style="display:none;">school</span>
            </div>
            <div class="school-name">SMA Al-Azhar Jakarta</div>
            <div class="school-sub">Sistem Aspirasi Sarana Sekolah</div>
        </div>

        @if($errors->has('identifier'))
            <div class="err-alert">
                <span class="msym">error</span>
                {{ $errors->first('identifier') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label>NIS / Username</label>
                <div class="input-wrap">
                    <span class="msym">person</span>
                    <input type="text" name="identifier"
                           placeholder="Masukkan NIS atau username"
                           value="{{ old('identifier') }}"
                           autofocus required>
                </div>
            </div>

            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <span class="msym">lock</span>
                    <input type="password" id="pwInput"
                           name="password"
                           placeholder="Masukkan password" required>
                    <button type="button" class="toggle-pw" id="togglePw" tabindex="-1">
                        <span class="msym" id="eyeIcon">visibility</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Masuk <span class="msym">login</span>
            </button>
        </form>

        <div class="card-ft">
            Hubungi admin jika lupa password
        </div>
    </div>

    <script>
        document.getElementById('togglePw').addEventListener('click', function () {
            const inp  = document.getElementById('pwInput');
            const icon = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                inp.type = 'password';
                icon.textContent = 'visibility';
            }
        });
    </script>
</body>
</html>
