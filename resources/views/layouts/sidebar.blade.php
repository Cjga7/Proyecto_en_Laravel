<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu bg-soft-success">

    <!-- LOGO -->
    <div class="navbar-brand-box bg-soft-success">
        <a href="{{ route('panel') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo2_lanago.png') }}" alt="" height="58">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo2_lanago.png') }}" alt="" height="70">
            </span>
        </a>

        <a href="{{ route('panel') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo2_lanago.png') }}" alt="" height="58">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo2_lanago.png') }}" alt="" height="70">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <!-- Inicio -->
                <li class="menu-title">Inicio</li>
                <li>
                    <a href="{{ route('panel') }}" class="waves-effect">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Panel</span>
                    </a>
                </li>

                <!-- Modulos -->
                <li class="menu-title">Módulos</li>

                <!-- Compras -->
                @can('ver-compra')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fa-solid fa-store"></i>
                            <span>Compras</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('compras.index') }}">Ver</a></li>
                            <li><a href="{{ route('compras.create') }}">Crear</a></li>
                        </ul>
                    </li>
                @endcan

                <!-- Ventas -->
                @can('ver-venta')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Ventas</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('ventas.index') }}">Ver</a></li>
                            <li><a href="{{ route('ventas.create') }}">Crear</a></li>
                        </ul>
                    </li>
                @endcan

                <!--Gestion Productos -->
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa-solid fa-list-check"></i>
                        <span>Gestion de Productos</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <!-- Categorías -->
                        @can('ver-categoria')
                            <li>
                                <a href="{{ route('categorias.index') }}" class="waves-effect">
                                    <i class="fa-solid fa-tag"></i>
                                    <span>Categorías</span>
                                </a>
                            </li>
                        @endcan
                        <!-- Registro sanitario -->
                        @can('ver-registrosanitario')
                            <li>
                                <a href="{{ route('registrosanitarios.index') }}" class="waves-effect">
                                    <i class="fa-solid fa-kit-medical"></i>
                                    <span>Registro sanitario</span>
                                </a>
                            </li>
                        @endcan
                        <!-- Presentaciones -->
                        @can('ver-presentacione')
                            <li>
                                <a href="{{ route('presentaciones.index') }}" class="waves-effect">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                    <span>Presentaciones</span>
                                </a>
                            </li>
                        @endcan
                        <!-- Productos -->
                        @can('ver-producto')
                            <li>
                                <a href="{{ route('productos.index') }}" class="waves-effect">
                                    <i class="fa-sharp fa-thin fa-prescription-bottle-medical"></i>
                                    <span>Productos</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <!-- Clientes -->
                @can('ver-cliente')
                    <li>
                        <a href="{{ route('clientes.index') }}" class="waves-effect">
                            <i class="fa-solid fa-users"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                @endcan

                <!-- Proveedores -->
                @can('ver-proveedore')
                    <li>
                        <a href="{{ route('proveedores.index') }}" class="waves-effect">
                            <i class="fa-solid fa-users"></i>
                            <span>Proveedores</span>
                        </a>
                    </li>
                @endcan

                <!-- Otros -->
                @can('ver-user')
                    <li class="menu-title">OTROS</li>
                    <li>
                        <a href="{{ route('users.index') }}" class="waves-effect">
                            <i class="fa-solid fa-users"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                @endcan

                @can('ver-role')
                    <li>
                        <a href="{{ route('roles.index') }}" class="waves-effect">
                            <i class="fa-solid fa-person-circle-plus"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                @endcan
                <!--div class="sidebar-footer">
                    <div class="small">Bienvenid@:</div>
                    {{ auth()->user()->name }}
                </!--div-->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
