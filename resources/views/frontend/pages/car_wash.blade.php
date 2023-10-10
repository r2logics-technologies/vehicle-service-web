@extends('frontend.layouts.app')


@section('content')
<section class="page-title-section top-position1 bg-img cover-background" data-overlay-dark="7" data-background="/front_assets/img/bg/bg-06.jpg" style="background-image: url(&quot;/front_assets/img/bg/bg-06.jpg&quot;);">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h1>Car Wash</h1>
            </div>
            <div class="col-md-12">
                <div class="breadcrumb">
                    <span class="left-dot"></span>
                    <span class="right-dot"></span>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/car-wash">Car Wash</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <!-- service left -->
            <div class="col-lg-4 order-2 order-lg-1">
                <div class="service-details-sidebar pe-lg-1-6 pe-xl-1-9">
                    <aside class="widget widget-nav-menu wow fadeIn" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fadeIn;">
                        <h4 class="widget-title">Car Wash Services</h4>
                        
                        
                    </aside>
                    <aside class="widget widget-address wow fadeIn" data-wow-delay="300ms" style="visibility: hidden; animation-delay: 300ms; animation-name: none;">
                        <h4 class="widget-title">Contact Info</h4>
                         <ul class="address-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                 <a href="#!">Hullas Bhawan Near SBI ATM</a>
                                 <a href="#!" style="padding-left: 40px;">1st C road Sardarpura ,Jodhpur</a>
                            </li>
                           <li><i class="fas fa-mobile-alt"></i>
                                <a href="#!">(+91) 9672321008</a>
                                <a href="#!" style="padding-left: 50px;">(+91) 9636937717</a>
                            </li>
                            <li>
                                <i class="far fa-envelope"></i>
                                <a href="#!">info@lunazmoto.com </a>
                            </li>
                            <li>
                                <i class="far fa-clock"></i>
                                <a href="#!">Mon-Sat 9:00am - 5:00pm</a>
                              <a href="#!" style="padding-left: 50px;">24 X 7 online support</a>
                            </li>
                        </ul>
                    </aside>
                
                </div>
            </div>
            <!-- end service left -->

            <!-- service right -->
            <div class="col-lg-8 order-1 order-lg-2 mb-2-2 mb-lg-0">
                <img class="mb-2-2 rounded wow fadeIn" data-wow-delay="200ms" src="/front_assets/img/content/service-detail-020.jpg" style="border: 2px solid #cc1212;" alt="..." style="visibility: visible; animation-delay: 200ms; animation-name: fadeIn;">
                <div class="row mb-2-2 wow fadeIn" data-wow-delay="300ms" style="visibility: hidden; animation-delay: 300ms; animation-name: none;">
                    <div class="col-lg-12">
                        <h3 class="h4 mb-3">Engine Diagnostic</h3>
                        <p class="w-95">
                            Engine diagnostic is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                        </p>
                        <p class="mb-0 w-95">
                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                        </p>
                    </div>
                </div>
                <div class="row mb-2-2 mt-n1-9">
                    <div class="col-md-6 mt-1-9 wow fadeIn" data-wow-delay="400ms" style="visibility: hidden; animation-delay: 400ms; animation-name: none;">
                        <img src="/front_assets/img/content/service-detail-021.png" style="border: 2px solid #cc1212;" alt="...">
                    </div>
                    <div class="col-md-6 mt-1-9 wow fadeIn" data-wow-delay="500ms" style="visibility: hidden; animation-delay: 500ms; animation-name: none;">
                        <img src="/front_assets/img/content/service-detail-022.png"style="border: 2px solid #cc1212;" alt="...">
                    </div>
                </div>
   
             
                <div class="row mb-1-9 align-items-center wow fadeIn" data-wow-delay="800ms" style="visibility: hidden; animation-delay: 800ms; animation-name: none;">
                    <div class="col-xl-6 mb-4 mb-xl-0">
                        <img src="/front_assets/img/content/service-detail-023.png" style="border: 2px solid #cc1212;" class="rounded" alt="...">
                    </div>
                    <div class="col-xl-6 ps-xl-1-9">
                        <p class="mb-3 w-95">Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</p>
                        <ul class="list-style1 mb-0">
                            <li>24 month nationwide warranty</li>
                            <li>Customer rewards program</li>
                            <li>Certified technicians</li>
                        </ul>
                    </div>
                </div>
                <div class="row wow fadeIn" data-wow-delay="900ms" style="visibility: hidden; animation-delay: 900ms; animation-name: none;">
                    <div class="col-lg-12">
                        <p class="mb-0 w-95">
                            If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                        </p>
                    </div>
                </div>
            </div>
            <!-- end service right -->
        </div>
    </div>
</section>



@endsection