<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator sign in</title>
    @vite(['resources/css/app.css'])
</head>
<body class="admin-page bg-[#f4f6f6] text-[#202327] antialiased">
    <main class="admin-shell">
        <a class="admin-back" href="{{ url('/') }}">← Back to portfolio</a>
        <section class="admin-card">
            <p class="admin-kicker">Restricted area</p>
            <h1>Administrator sign in</h1>
            <p class="admin-copy">Use your administrator credentials to continue.</p>
            <form method="POST" action="{{ route('admin.login.store') }}" class="admin-form">
                @csrf
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email')<p class="admin-error">{{ $message }}</p>@enderror
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                <button class="admin-submit" type="submit">Sign in</button>
            </form>
        </section>
    </main>
</body>
</html>
