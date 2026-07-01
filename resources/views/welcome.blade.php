<!DOCTYPE html>
<html lang="ru" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.auth.brand') }}</title>
    <style>
        *{ box-sizing: border-box; margin: 0; padding: 0; }
        body{
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: radial-gradient(circle at top, #1f2937, #0f172a); color: #f8fafc;
        }
        .card{ text-align: center; padding: 3rem; max-width: 32rem; }
        h1{ font-size: 2.25rem; margin-bottom: .75rem; }
        p{ color: #cbd5e1; margin-bottom: 2rem; line-height: 1.6; }
        .actions{ display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        a.btn{
            display: inline-block; padding: .75rem 1.75rem; border-radius: .625rem;
            font-weight: 600; text-decoration: none; transition: opacity .15s;
        }
        a.btn:hover{ opacity: .85; }
        .primary{ background: #f59e0b; color: #1f2937; }
        .secondary{ background: rgba(255,255,255,.1); color: #f8fafc; }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ __('messages.welcome.heading') }}</h1>
        <p>{{ __('messages.welcome.tagline') }}</p>
        <div class="actions">
            <a class="btn primary" href="{{ url('/admin/register') }}">{{ __('messages.welcome.register') }}</a>
            <a class="btn secondary" href="{{ url('/admin/login') }}">{{ __('messages.welcome.login') }}</a>
        </div>
    </div>
</body>
</html>
