<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('assets/images/e.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
                <img src="{{ asset('assets/images/blue.png') }}" alt="logo-large" class="logo-lg logo-dark">
                <img src="{{ asset('assets/images/white.png') }}" alt="logo-large" class="logo-lg logo-light">
            </span>
        </a>
    </div>
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label mt-2">
                        <span>Navigation</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="iconoir-report-columns menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.customers.list') }}">
                            <i class="iconoir-community menu-icon"></i>
                            <span>Customers List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.calender') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.branches') }}">
                            <i class="iconoir-hexagon-dice menu-icon"></i>
                            <span>Branches</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.tagging.list') }}">
                            <i class="iconoir-label menu-icon"></i>
                            <span>Tagging</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarAnalytics" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarAnalytics">
                            <i class="iconoir-bell menu-icon"></i>
                            <span>Announcements</span>
                            <span class="badge text-bg-warning ms-auto">08</span>
                        </a>
                        <div class="collapse " id="sidebarAnalytics">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.announcements') }}" class="nav-link ">Announcements
                                        List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.announcements.create') }}" class="nav-link ">Post
                                        Announcement</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="iconoir-log-out menu-icon"></i>
                                <span>Sign Out</span>
                            </a>
                        </form>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="startbar-overlay d-print-none"></div>
