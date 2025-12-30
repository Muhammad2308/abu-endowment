<header>
    <div class="header-area ">
        <div class="header-top_area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-6 col-md-12 col-lg-8">
                        <div class="short_contact_list">
                            <ul>
                                <li><a href="#"><i class="fa fa-phone"></i> +234 (454) 556-5656</a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i>abuendowment@gmail.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-lg-4">
                        <div class="social_media_links d-none d-lg-block">
                            <a href="#">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-pinterest-p"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-linkedin"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="sticky-header" class="main-header-area">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-3">
                        <div class="logo">
                            <a href="{{ url('/') }}" class="d-flex align-items-center" style="text-decoration: none;">
                                <img src="{{ asset('img/abu_logo_white.png') }}" alt="ABU Endowment" style="height: 50px; width: auto; margin-right: 12px;">
                                <div class="logo-text" style="display: flex; flex-direction: column; line-height: 1.2;">
                                    <span style="font-size: 18px; font-weight: 600; color: #fff;">ABU Endowment</span>
                                    <span style="font-size: 14px; font-weight: 400; color: #fff;">& Crowd Funding</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <div class="main-menu">
                            <nav>
                                <ul id="navigation">
                                    <li><a href="{{ url('/') }}">home</a></li>
                                    <li><a href="{{ route('about') }}">About</a></li>
                                    <!-- <li><a href="#">blog <i class="ti-angle-down"></i></a>
                                        <ul class="submenu">
                                            <li><a href="#">blog</a></li>
                                            <li><a href="#">single-blog</a></li>
                                        </ul>
                                    </li> -->
                                    <!-- <li><a href="#">pages <i class="ti-angle-down"></i></a>
                                        <ul class="submenu">
                                            <li><a href="#">elements</a></li>
                                            <li><a href="#">Cause</a></li>
                                        </ul>
                                    </li> -->
                                    <li><a href="#">Contact</a></li>
                                    <li class="d-lg-none"><a href="#make-donation">Make a Donation</a></li>
                                    
                                    <!-- Authentication Section -->
                                    <!-- Authentication Section -->
                                    <li id="authSection" class="d-lg-none">
                                        @if(!$isLoggedIn)
                                        <!-- Not Logged In (Mobile) -->
                                        <div>
                                            <a href="#" wire:click.prevent="$dispatch('openLoginModal')">Sign In</a>
                                        </div>
                                        @else
                                        <!-- Logged In (Mobile) -->
                                        <div>
                                            <a href="#"><i class="fa fa-user"></i> {{ $user['name'] ?? 'User' }} <i class="ti-angle-down"></i></a>
                                            <ul class="submenu">
                                                <li><a href="#" wire:click.prevent="$dispatch('openEditProfileModal')"><i class="fa fa-user-edit"></i> Edit Profile</a></li>
                                                <li><a href="#" wire:click.prevent="logout"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
                                            </ul>
                                        </div>
                                        @endif
                                    </li>
                                </ul>
                            </nav>
                            <div class="Appointment">
                                <!-- Desktop Authentication -->
                                <div class="d-none d-lg-flex align-items-center" style="gap: 10px;">
                                    @if(!$isLoggedIn)
                                    <!-- Not Logged In -->
                                    <div>
                                        <a href="#" class="boxed-btn3-white" 
                                           wire:click.prevent="$dispatch('openLoginModal')" 
                                           style="padding: 10px 25px; font-size: 14px; cursor: pointer;">
                                            <i class="fa fa-sign-in-alt"></i> Sign In
                                        </a>
                                    </div>
                                    @else
                                    <!-- Logged In -->
                                    <div class="dropdown" style="position: relative;">
                                        <a href="#" class="user-dropdown-toggle d-flex align-items-center" 
                                           id="userDropdown" 
                                           data-toggle="dropdown" 
                                           aria-haspopup="true" 
                                           aria-expanded="false"
                                           style="color: #fff; text-decoration: none; gap: 10px; padding: 5px 15px; background: rgba(255,255,255,0.1); border-radius: 50px; transition: all 0.3s;">
                                            
                                            @if(isset($user['avatar']) && $user['avatar'])
                                                <img src="{{ $user['avatar'] }}" alt="User" 
                                                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
                                            @else
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #fff; color: #3CC78F; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex flex-column" style="line-height: 1.2;">
                                                <span style="font-size: 14px; font-weight: 600;">{{ $user['name'] ?? 'User' }}</span>
                                                <i class="fa fa-chevron-down" style="font-size: 10px; opacity: 0.7;"></i>
                                            </div>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" 
                                             style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); min-width: 220px; padding: 10px; margin-top: 10px;">
                                            
                                            <div class="px-3 py-2 border-bottom mb-2">
                                                <small class="text-muted d-block">Signed in as</small>
                                                <strong class="text-dark text-truncate d-block" style="max-width: 180px;">{{ $user['email'] ?? '' }}</strong>
                                            </div>

                                            <a class="dropdown-item rounded-lg py-2" href="#" wire:click.prevent="$dispatch('openEditProfileModal')">
                                                <i class="fa fa-user-edit mr-2 text-primary"></i> Edit Profile
                                            </a>
                                            
                                            <div class="dropdown-divider"></div>
                                            
                                            <a class="dropdown-item rounded-lg py-2 text-danger" href="#" wire:click.prevent="logout">
                                                <i class="fa fa-sign-out-alt mr-2"></i> Logout
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="book_btn d-none d-lg-block" style="margin-left: 10px;">
                                    <a data-scroll-nav="1" href="#make-donation">Make a Donation</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
