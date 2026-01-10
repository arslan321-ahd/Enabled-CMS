 <div class="topbar d-print-none">
     <div class="container-fluid">
         <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">
             <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                 <li>
                     <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                         <i class="iconoir-menu"></i>
                     </button>
                 </li>
                 <li class="mx-2 welcome-text">
                     <h5 class="mb-0 fw-semibold text-truncate">{{ $greeting }},
                         {{ auth()->user()->name ?? 'Guest' }}!</h5>
                     <!-- <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Here's your overview this week.</h6> -->
                 </li>
             </ul>
             <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                 <li class="hide-phone app-search">
                     <form role="search" action="#" method="get">
                         <input type="search" name="search" class="form-control top-search mb-0"
                             placeholder="Search here...">
                         <button type="submit"><i class="iconoir-search"></i></button>
                     </form>
                 </li>
                 {{-- <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false" data-bs-offset="0,19">
                        <img src="assets/images/flags/us_flag.jpg" alt="" class="thumb-sm rounded-circle">
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><img src="assets/images/flags/us_flag.jpg" alt="" height="15" class="me-2">English</a>
                            <a class="dropdown-item" href="#"><img src="assets/images/flags/spain_flag.jpg" alt="" height="15" class="me-2">Spanish</a>
                            <a class="dropdown-item" href="#"><img src="assets/images/flags/germany_flag.jpg" alt="" height="15" class="me-2">German</a>
                            <a class="dropdown-item" href="#"><img src="assets/images/flags/french_flag.jpg" alt="" height="15" class="me-2">French</a>
                        </div>
                    </li> --}}

                 <li class="topbar-item">
                     <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                         <i class="iconoir-half-moon dark-mode"></i>
                         <i class="iconoir-sun-light light-mode"></i>
                     </a>
                 </li>

                 <li class="dropdown topbar-item">
                     <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#"
                         role="button">
                         <i class="iconoir-bell"></i>
                         <span class="alert-badge">{{ $notificationCount }}</span>
                     </a>

                     <div class="dropdown-menu stop dropdown-menu-end dropdown-lg py-0">

                         <h5 class="dropdown-item-text m-0 py-3 d-flex justify-content-between align-items-center">
                             Notifications
                             <a href="#" class="badge text-body-tertiary badge-pill">
                                 <i class="iconoir-plus-circle fs-4"></i>
                             </a>
                         </h5>

                         <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-1" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link mx-0 active" data-bs-toggle="tab" href="#All">
                                     All
                                     <span class="badge bg-primary-subtle text-primary badge-pill ms-1">
                                         {{ $notificationCount }}
                                     </span>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a class="nav-link mx-0" data-bs-toggle="tab" href="#SystemLogs">
                                     System Logs
                                 </a>
                             </li>
                         </ul>

                         <div class="ms-0" style="max-height:230px;" data-simplebar>
                             <div class="tab-content">

                                 {{-- ================= ALL NOTIFICATIONS ================= --}}
                                 <div class="tab-pane fade show active" id="All">
                                     @forelse($allNotifications as $log)
                                         <a href="#" class="dropdown-item py-3">
                                             <small class="float-end text-muted ps-2">
                                                 {{ $log->created_at->diffForHumans() }}
                                             </small>

                                             <div class="d-flex align-items-center">
                                                 <div
                                                     class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                     <i class="iconoir-bell fs-4"></i>
                                                 </div>

                                                 <div class="flex-grow-1 ms-2 text-truncate">
                                                     <h6 class="my-0 fw-normal text-dark fs-13">
                                                         {{ $log->title }}
                                                     </h6>
                                                     <small class="text-muted mb-0">
                                                         {{ $log->description }}
                                                     </small>
                                                 </div>
                                             </div>
                                         </a>
                                     @empty
                                         <div class="text-center py-3 text-muted">
                                             No notifications found
                                         </div>
                                     @endforelse
                                 </div>

                                 {{-- ================= SYSTEM LOGS ONLY ================= --}}
                                 <div class="tab-pane fade" id="SystemLogs">
                                     @forelse($systemLogs as $log)
                                         <a href="#" class="dropdown-item py-3">
                                             <small class="float-end text-muted ps-2">
                                                 {{ $log->created_at->diffForHumans() }}
                                             </small>

                                             <div class="d-flex align-items-center">
                                                 <div
                                                     class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                                                     <i class="iconoir-user fs-4"></i>
                                                 </div>

                                                 <div class="flex-grow-1 ms-2 text-truncate">
                                                     <h6 class="my-0 fw-normal text-dark fs-13">
                                                         {{ $log->title }}
                                                     </h6>
                                                     <small class="text-muted mb-0">
                                                         {{ $log->description }}
                                                     </small>
                                                 </div>
                                             </div>
                                         </a>
                                     @empty
                                         <div class="text-center py-3 text-muted">
                                             No system logs found
                                         </div>
                                     @endforelse
                                 </div>

                             </div>
                         </div>

                         <a href="#" class="dropdown-item text-center text-dark fs-13 py-2">
                             View All <i class="fi-arrow-right"></i>
                         </a>
                     </div>
                 </li>


                 <li class="dropdown topbar-item">
                     <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#"
                         role="button" aria-haspopup="false" aria-expanded="false" data-bs-offset="0,19">
                         <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt=""
                             class="thumb-md rounded-circle">
                     </a>
                     <div class="dropdown-menu dropdown-menu-end py-0">
                         <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                             <div class="flex-shrink-0">
                                 <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt=""
                                     class="thumb-md rounded-circle">
                             </div>
                             <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                 <h6 class="my-0 fw-medium text-dark fs-13">{{ Auth::user()->name ?? 'Guest' }}</h6>
                                 <small class="text-muted mb-0">{{ Auth::user()->email ?? 'Guest' }}</small>
                             </div><!--end media-body-->
                         </div>
                         <div class="dropdown-divider mt-0"></div>
                         <small class="text-muted px-2 pb-1 d-block">Account</small>
                         <a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                 class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>

                         <div class="dropdown-divider mb-0"></div>
                         <form method="POST" action="{{ route('logout') }}">
                             @csrf
                             <a href="#" class="dropdown-item text-danger"
                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                 <i class="las la-power-off fs-18 me-1 align-text-bottom"></i>
                                 Logout
                             </a>
                         </form>
                     </div>
                 </li>
             </ul><!--end topbar-nav-->
         </nav>
         <!-- end navbar-->
     </div>
 </div>
