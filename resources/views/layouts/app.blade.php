<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Órdenes de Trabajo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 250px;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            padding-top: 20px;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            display: block;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }
        
        .sidebar-menu i {
            width: 20px;
            margin-right: 10px;
        }
        
        .sidebar-submenu {
            list-style: none;
            padding-left: 2.5rem;
            margin-top: 0.5rem;
            display: none;
        }
        
        .sidebar-submenu.show {
            display: block;
        }
        
        .sidebar-submenu a {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-right: var(--sidebar-width);
            padding: 20px;
            transition: margin-right 0.3s;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(var(--sidebar-width));
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
            
            .sidebar-toggle {
                display: block !important;
            }
        }
        
        .sidebar-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1001;
            display: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <!-- Botón para toggle sidebar en móvil -->
    <button class="btn btn-primary sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0">Sistema de Órdenes</h5>
            <small class="opacity-75">Panel de Navegación</small>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('ordenes_trabajo.index') }}" class="{{ request()->routeIs('ordenes_trabajo.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Órdenes de Trabajo
                </a>
            </li>
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                <li>
                    <a href="{{ route('propietarios.index') }}" class="{{ request()->routeIs('propietarios.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Propietarios
                    </a>
                </li>
                <li>
                    <a href="{{ route('empleados.index') }}" class="{{ request()->routeIs('empleados.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i> Empleados
                    </a>
                </li>
                <li>
                    <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i> Servicios
                    </a>
                </li>
            @endif
            <!-- Opciones solo para administradores -->
            @if(auth()->user()->hasRole('admin'))
                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i> Gestión de Usuarios
                    </a>
                </li>
                <li>
                    <a href="#reportes" data-bs-toggle="collapse">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </a>
                    <ul class="sidebar-submenu collapse" id="reportes">
                        <li><a href="#">Reporte Diario</a></li>
                        <li><a href="#">Reporte Mensual</a></li>
                        <li><a href="#">Estadísticas</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#configuracion" data-bs-toggle="collapse">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                    <ul class="sidebar-submenu collapse" id="configuracion">
                        <li><a href="#">Ajustes Generales</a></li>
                        <li><a href="#">Backup</a></li>
                    </ul>
                </li>
            @endif

            <li>
                <a href="#">
                    <i class="fas fa-question-circle"></i> Ayuda
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    @endauth

    <!-- Main Content -->
    <div class="main-content" id="mainContent" style="@auth margin-right: 250px; @else margin-right: 0; @endauth">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            // Toggle sidebar en móvil
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
            
            // Cerrar sidebar al hacer clic fuera en móvil
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992 && 
                    !sidebar.contains(event.target) && 
                    !sidebarToggle.contains(event.target) &&
                    sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
            
            // Actualizar clases activas basado en la ruta actual
            function updateActiveMenu() {
                const currentPath = window.location.pathname;
                const menuItems = document.querySelectorAll('.sidebar-menu a');
                
                menuItems.forEach(item => {
                    if (item.getAttribute('href') === currentPath) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
            
            updateActiveMenu();
            @endauth
        });
    </script>
    @stack('scripts')
</body>
</html>