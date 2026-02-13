<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - Mentorship Wiki</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #1e1e2e;
            --bg-secondary: #252538;
            --bg-tertiary: #181825;
            --text-primary: #cdd6f4;
            --text-secondary: #a6adc8;
            --text-muted: #6c7086;
            --accent: #89b4fa;
            --accent-hover: #b4befe;
            --border: #313244;
            --success: #a6e3a1;
            --warning: #f9e2af;
            --error: #f38ba8;
            --code-bg: #11111b;
        }

        [data-theme="light"] {
            --bg-primary: #eff1f5;
            --bg-secondary: #e6e9ef;
            --bg-tertiary: #dce0e8;
            --text-primary: #4c4f69;
            --text-secondary: #5c5f77;
            --text-muted: #6c6f85;
            --accent: #1e66f5;
            --accent-hover: #7287fd;
            --border: #ccd0da;
            --code-bg: #e6e9ef;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 270px;
            background-color: var(--bg-primary);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 20px 16px;
            position: fixed;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-primary);
        }
        .logo-icon { font-size: 1.8rem; }
        .sidebar-logo h1 { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); }

        .sidebar-nav { display: flex; flex-direction: column; gap: 6px; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background-color: var(--bg-secondary);
            color: var(--accent);
        }
        .nav-icon { width: 20px; text-align: center; }

        .divider { height: 1px; background: var(--border); margin: 12px 0; }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-right: 270px;
            padding: 30px 40px;
            max-width: 1200px;
            width: 100%;
        }

        .page-header {
            display: flex;
            flex-direction: column; /* Admin often needs header stacking */
            gap: 10px;
            margin-bottom: 24px;
        }
        .page-header h2 { font-size: 1.8rem; color: var(--text-primary); }

        .card {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        /* Tables */
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td {
            text-align: right;
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }
        .table th { color: var(--text-muted); font-size: 0.85rem; }
        .table tr:last-child td { border-bottom: none; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-secondary { background: var(--bg-secondary); color: var(--text-primary); }
        .btn-danger { background: var(--error); color: #fff; }
        .btn-sm { padding: 4px 10px; font-size: 0.8rem; }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: inherit;
            margin-bottom: 12px;
        }
        .form-control:focus { outline: 2px solid var(--accent); border-color: transparent; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-icon">🛡️</div>
                <h1>لوحة التحكم</h1>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> الرئيسية
            </a>
            <a href="{{ route('admin.domains.index') }}" class="{{ request()->routeIs('admin.domains.*') ? 'active' : '' }}">
                <span class="nav-icon">🌐</span> الدومينات
            </a>
            <a href="{{ route('admin.tags.index') }}" class="{{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span> التاقات
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">📂</span> التصنيفات
            </a>
            
            <div class="divider"></div>

            <a href="{{ route('topics.index') }}">
                <span class="nav-icon">🏠</span> العودة للموقع
            </a>
        </nav>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div style="background: var(--success); color: #1e1e2e; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
                ✅ {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </main>

</body>
</html>
