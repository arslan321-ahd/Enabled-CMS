@php
    use App\Helpers\PermissionHelper;
@endphp
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

                    {{-- Dashboard (usually visible to all authenticated users) --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="iconoir-report-columns menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- Customers --}}
                    @if (auth()->user()->canAccess('tagging', 'view'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.forms.index') }}">
                                <i class="iconoir-community menu-icon"></i>
                                <span>Customers List</span>
                            </a>
                        </li>
                    @endif

                    {{-- Calendar (no permission applied yet) --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.calender') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Calendar</span>
                        </a>
                    </li>
                    @if (auth()->user()->canAccess('branches', 'view'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.branches') }}">
                                <i class="iconoir-hexagon-dice menu-icon"></i>
                                <span>Branches</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->canAccess('tagging', 'view'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.tagging.list') }}">
                                <i class="iconoir-label menu-icon"></i>
                                <span>Tagging</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.brands.index') }}">
                            <i class="iconoir-shop menu-icon"></i>
                            <span>Brands</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.use-cases.index') }}">
                            <i class="iconoir-light-bulb menu-icon"></i>
                            <span>Use Cases</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.export.index') }}" class="sidebar-link">
                            <i class="iconoir-download menu-icon"></i>
                            <span>Export</span>
                        </a>
                    </li>
                    @if (auth()->user()->canAccess('announcement', 'view'))
                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarAnnouncements" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarAnnouncements">
                                <i class="iconoir-bell menu-icon"></i>
                                <span>Announcements</span>
                            </a>

                            <div class="collapse" id="sidebarAnnouncements">
                                <ul class="nav flex-column">

                                    <li class="nav-item">
                                        <a href="{{ route('admin.announcements') }}" class="nav-link">
                                            Announcements List
                                        </a>
                                    </li>

                                    @if (auth()->user()->canAccess('announcement', 'create'))
                                        <li class="nav-item">
                                            <a href="{{ route('admin.announcements.create') }}" class="nav-link">
                                                Post Announcement
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="startbar-overlay d-print-none"></div>
