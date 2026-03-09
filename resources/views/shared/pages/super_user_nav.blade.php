<aside class="main-sidebar sidebar-dark-primary elevation-4" style="height: 100vh">

    <!-- System title and logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('public/images/pricon_logo2.png') }}" alt="" class="brand-image img-circle elevation-3"
            style="opacity: .8">

        <span class="brand-text font-weight-light font-size">
            <h5>CSR</h5>
        </span>
    </a> <!-- System title and logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{ url('../RapidX') }}" class="nav-link">
                        <i class="nav-icon fas fa-arrow-left"></i>
                        <p>Return to RapidX</p>
                    </a>
                </li>

                <li class="nav-item has-treeview" id="dashboard" >
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="fa-solid fa-square-poll-vertical fa-lg nav-icon"></i>
                        <p> Dashboard</p>
                    </a>
                </li>

                {{-- Allowed positions 0-ISS, 2-QC Supervisor, 3-Section Head --}}
                @if ( $globalUser && in_array( $globalUser->position, [0,2,3]))
                    <li class="nav-header font-weight-bold">Data Management</li>

                    <li class="nav-item has-treeview">
                        <a href="{{ route('users') }}" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Users</p>
                        </a>
                    </li>

                    {{-- <i class="fas fa-exclamation-triangle nav-icon"></i> --}}
                    <li class="nav-item has-treeview">
                        <a href="{{ route('customers') }}" class="nav-link">
                        <i class="fas fa-list-ol nav-icon"></i>
                        <p>Customer</p>
                        </a>
                    </li>
                @endif

                <li class="nav-header font-weight-bold">Records</li>

                <li class="nav-item has-treeview">
                    <a href="{{ route('csr_documents') }}" class="nav-link">
                      <i class="fas fa-microchip nav-icon"></i>
                      <p>CSR Documents</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div><!-- Sidebar -->
</aside>

