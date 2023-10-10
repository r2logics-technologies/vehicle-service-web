        <header class="header-style1 menu_area-light scrollHeader">
            <div class="navbar-default border-bottom border-color-light-white">

                <div class="container-fluid px-sm-2-9">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-12">
                            <div class="menu_area alt-font">
                                <nav class="navbar navbar-expand-lg navbar-light p-0">

                                    <div class="navbar-header">
                                        <!-- start logo -->
                                        <a href="/" class="navbar-brand"><img id="logo"
                                                src="/front_assets/img/logos/logo.png" alt="logo"></a>
                                        <!-- end logo -->
                                    </div>

                                    <div class="navbar-toggler rounded"></div>

                                    <!-- menu area -->
                                    <ul class="navbar-nav align-items-lg-center ms-auto" id="nav"
                                        style="display: none;">
                                        <li class="{{ Request::is('/*') ? 'active' : '' }}"><a
                                                href="/">Home</a></li>
                                        <li class="{{ Request::is('about*') ? 'active' : '' }}">
                                            <a href="/about">About Us</a>
                                        </li>

                                        <li
                                            class="{{ Request::is('bike-repair*') || Request::is('car-wash*') ? 'active' : '' }}">
                                            <a>Services</a>
                                            <ul>
                                                <li><a href="/bike-repair">Bike Repair</a></li>
                                                <li><a href="/car-wash">Car Wash</a></li>

                                            </ul>
                                        </li>

                                        <li class="{{ Request::is('packages*') ? 'active' : '' }}"><a
                                                href="/packages">Packages</a></li>


                                        <li class="{{ Request::is('contact*') ? 'active' : '' }}"><a
                                                href="/contact">Contact</a></li>
                                    </ul>
                                    <!-- end menu area -->

                                    <!-- start attribute navigation -->
                                    <div class="attr-nav align-items-lg-center ms-lg-auto">
                                        <ul>
                                            <li class="d-none d-xl-inline-block"><a
                                                    class="butn primary md butn-white">Get Start</a></li>
                                        </ul>
                                    </div>
                                    <!-- end attribute navigation -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </header>
