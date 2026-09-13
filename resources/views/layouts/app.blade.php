<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senja Inventory - Stock & PO Management System</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-light: #E3FDFD;
            --surface-light: #CBF1F5;
            --accent-soft: #A6E3E9;
            --primary-teal: #71C9CE;
            --primary-dark: #2A7B88;
            --text-dark: #0F2C59;
            --text-muted: #4A6572;
            --card-shadow: 0 10px 25px -5px rgba(113, 201, 206, 0.2), 0 8px 10px -6px rgba(113, 201, 206, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* Navbar Styling - Mekari Jurnal Style */
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1.5px solid var(--accent-soft);
            box-shadow: 0 4px 20px rgba(113, 201, 206, 0.12);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--text-dark) !important;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand-icon {
            background: linear-gradient(135deg, var(--primary-teal), var(--primary-dark));
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(113, 201, 206, 0.4);
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-muted) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-dark) !important;
            background-color: var(--surface-light);
        }

        .nav-link.btn-adjust {
            background: linear-gradient(135deg, var(--primary-teal), var(--primary-dark));
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(113, 201, 206, 0.35);
        }

        .nav-link.btn-adjust:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        /* Card Customization */
        .card {
            border: 1px solid var(--accent-soft);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--accent-soft);
            border-radius: 16px 16px 0 0 !important;
            padding: 1.2rem 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Metric Cards */
        .metric-card {
            border-radius: 18px;
            padding: 1.5rem;
            color: var(--text-dark);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--accent-soft);
            box-shadow: var(--card-shadow);
        }

        .metric-card.style-1 {
            background: linear-gradient(135deg, #ffffff 0%, var(--surface-light) 100%);
        }

        .metric-card.style-2 {
            background: linear-gradient(135deg, #ffffff 0%, var(--bg-light) 100%);
        }

        .metric-card.style-3 {
            background: linear-gradient(135deg, #ffffff 0%, #FFF9E6 100%);
            border-color: #FFEAA7;
        }

        .metric-card.style-4 {
            background: linear-gradient(135deg, #ffffff 0%, #FFEEEE 100%);
            border-color: #FFCDD2;
        }

        .metric-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .metric-icon.style-1 { background-color: var(--primary-teal); color: white; }
        .metric-icon.style-2 { background-color: var(--accent-soft); color: var(--primary-dark); }
        .metric-icon.style-3 { background-color: #F39C12; color: white; }
        .metric-icon.style-4 { background-color: #E74C3C; color: white; }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-teal), var(--primary-dark));
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.6rem 1.4rem;
            box-shadow: 0 4px 12px rgba(113, 201, 206, 0.35);
            transition: all 0.2s ease;
        }

        .btn-primary:hover, .btn-primary:focus {
            opacity: 0.92;
            transform: translateY(-1px);
            color: white;
        }

        .btn-secondary {
            background-color: var(--surface-light);
            border: 1px solid var(--accent-soft);
            color: var(--text-dark);
            font-weight: 600;
            border-radius: 10px;
            padding: 0.6rem 1.4rem;
        }

        .btn-secondary:hover {
            background-color: var(--accent-soft);
            color: var(--text-dark);
        }

        .btn-warning {
            background-color: #F39C12;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-warning:hover {
            background-color: #D68910;
            color: white;
        }

        .btn-success {
            background-color: #2ECC71;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-success:hover {
            background-color: #27AE60;
            color: white;
        }

        /* Table Styling */
        .table {
            color: var(--text-dark);
            border-color: var(--accent-soft);
        }

        .table th {
            background-color: var(--surface-light);
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-bottom: 1.5px solid var(--accent-soft);
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Badges */
        .badge {
            padding: 0.55em 0.85em;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-teal {
            background-color: var(--surface-light);
            color: var(--primary-dark);
            border: 1px solid var(--accent-soft);
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 1.5px solid var(--accent-soft);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.25rem rgba(113, 201, 206, 0.25);
            background-color: #ffffff;
        }

        /* Alerts */
        .alert-success {
            background-color: var(--surface-light);
            border-color: var(--accent-soft);
            color: var(--primary-dark);
            border-radius: 12px;
            font-weight: 600;
        }

        /* Custom Modern Pagination Styling - Matching Reference Image */
        .pagination-custom {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .page-link-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 8px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .page-link-custom:hover {
            background-color: var(--surface-light);
            color: var(--primary-dark);
        }

        .page-link-custom.active-page {
            background-color: var(--primary-teal);
            color: #ffffff !important;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(113, 201, 206, 0.4);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <div class="navbar-brand-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <span>Senja <span style="color: var(--primary-teal)">Inventory</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                            <i class="bi bi-truck"></i> Suppliers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                            <i class="bi bi-file-earmark-text"></i> Purchase Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('inventory.history') ? 'active' : '' }}" href="{{ route('inventory.history') }}">
                            <i class="bi bi-clock-history"></i> Stock History
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a class="nav-link btn-adjust" href="{{ route('inventory.adjust') }}">
                        <i class="bi bi-sliders"></i> Manual Adjustment
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Attention:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
