@extends('frontend.layouts.app')


@section('content')
<section class="page-title-section top-position1 bg-img cover-background" data-overlay-dark="7" data-background="/front_assets/img/bg/bg-06.jpg" style="background-image: url(&quot;/front_assets/img/bg/bg-06.jpg&quot;);">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h1>Contact Us</h1>
            </div>
            <div class="col-md-12">
                <div class="breadcrumb">
                    <span class="left-dot"></span>
                    <span class="right-dot"></span>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-form">
    <div class="container">
        <div class="section-heading wow fadeIn" data-wow-delay="100ms" style="visibility: visible; animation-delay: 100ms; animation-name: fadeIn;">
            <h2>Need Any Services For Cars?</h2>
            <div class="heading-separator"></div>
            <p class="alt-font">Our car rental services, in the travel industry and business industry, stand apart for their quality and great taste.</p>
        </div>
        <div class="row mt-n1-9">
            <div class="col-md-6 col-lg-4 mt-1-9 wow fadeInUp" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fadeInUp;">
                <div class="py-6 text-center bg-img cover-background rounded" data-overlay-dark="7" data-background="/front_assets/img/content/contact-img-01.jpg" style="background-image: url(&quot;/front_assets/img/content/contact-img-01.jpg&quot;);">
                    <div class="position-relative z-index-1">
                        <i class="ti-location-pin fs-1 text-white d-block mb-4"></i>
                        <h4 class="text-white">Hullas Bhawan Near SBI ATM</h4>
                        <span class="text-white">1st C road Sardarpura, Jodhpur</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mt-1-9 wow fadeInUp" data-wow-delay="400ms" style="visibility: visible; animation-delay: 400ms; animation-name: fadeInUp;">
                <div class="py-6 text-center bg-img cover-background rounded" data-overlay-dark="7" data-background="/front_assets/img/content/contact-img-02.jpg" style="background-image: url(&quot;/front_assets/img/content/contact-img-02.jpg&quot;);">
                    <div class="position-relative z-index-1">
                        <i class="ti-mobile fs-1 text-white d-block mb-4"></i>
                        <h4 class="text-white">(+91) 9672321008 , 9636937717</h4>
                        <span class="text-white">Mon-Sat 9:00am - 5:00pm</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mt-1-9 wow fadeInUp" data-wow-delay="600ms" style="visibility: visible; animation-delay: 600ms; animation-name: fadeInUp;">
                <div class="py-6 text-center bg-img cover-background rounded" data-overlay-dark="7" data-background="/front_assets/img/content/contact-img-03.jpg" style="background-image: url(&quot;/front_assets/img/content/contact-img-03.jpg&quot;);">
                    <div class="position-relative z-index-1">
                        <i class="ti-email fs-1 text-white d-block mb-4"></i>
                        <h4 class="text-white">info@lunazmoto.com</h4>
                        <span class="text-white">24 X 7 online support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-0">
    <!-- <div class="container-fluid"> -->
    <div class="row g-0">
        <div class="col-lg-6 order-2 order-lg-1 wow fadeIn" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fadeIn;">
            <div class="bg-very-light-gray p-2-5 p-sm-6 p-xl-8 h-100">
                <h2 class="mb-3">Get In Touch&nbsp;!</h2>
                <p class="mb-4">If you're searching out advice, please fill out this form. We will discover you and get in touch.</p>
                <form action="contact"  >
                  @csrf
                    <div class="quform-elements">
                        <div class="row">

                            <!-- Begin Text input element -->
                            <div class="col-md-6">
                                <div class="quform-element form-group">
                                    <label for="name">Your Name <span class="quform-required">*</span></label>
                                    <div class="quform-input">
                                        <input class="form-control" id="name" type="text" name="name" placeh\older="Your name here">
                                    </div>
                                </div>
                            </div>
                            <!-- End Text input element -->

                            <!-- Begin Text input element -->
                            <div class="col-md-6">
                                <div class="quform-element form-group">
                                    <label for="email">Your Email <span class="quform-required">*</span></label>
                                    <div class="quform-input">
                                        <input class="form-control" id="email" type="text" name="email" placeholder="Your email here">
                                    </div>
                                </div>
                            </div>
                            <!-- End Text input element -->

                            <!-- Begin Text input element -->
                            <div class="col-md-6">
                                <div class="quform-element form-group quform-select-replaced">
                                    <label for="subject">Your Subject <span class="quform-required">*</span></label>
                                    <div class="quform-input">
                                        <input class="form-control" id="subject" type="text" name="subject" placeholder="Your subject here">
                                    </div>
                                </div>
                            </div>
                            <!-- End Text input element -->

                            <!-- Begin Text input element -->
                            <div class="col-md-6">
                                <div class="quform-element form-group">
                                    <label for="phone">Contact Number</label>
                                    <div class="quform-input">
                                        <input class="form-control" id="phone" type="text" name="phone" placeholder="Your phone here">
                                    </div>
                                </div>
                            </div>
                            <!-- End Text input element -->

                            <!-- Begin Textarea element -->
                            <div class="col-md-12">
                                <div class="quform-element form-group">
                                    <label for="message">Message <span class="quform-required">*</span></label>
                                    <div class="quform-input">
                                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tell us a few words"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- End Textarea element -->

                            <!-- Begin Captcha element -->
                            <div class="col-md-12">
                                <div class="quform-element">
                                    <div class="form-group">
                                        <div class="quform-input">
                                            <input class="form-control" id="type_the_word" type="text" name="type_the_word" placeholder="Type the below word">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="quform-captcha">
                                            <div class="quform-captcha-inner">
                                                <img src="/front_assets/quform/images/captcha/courier-new-light.png" alt="...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Captcha element -->

                            <!-- Begin Submit button -->
                            <div class="col-md-12">
                                <div class="quform-submit-inner">
                                   <input class="butn butn-style1 lg" type="submit" value="submit">
                                </div>
                                <div class="quform-loading-wrap text-start"><span class="quform-loading"></span></div>
                            </div>
                            <!-- End Submit button -->

                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 wow fadeIn" data-wow-delay="400ms" style="visibility: visible; animation-delay: 400ms; animation-name: fadeIn;">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12033.342248786836!2d73.01049983022301!3d26.276781712845523!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39418b5148ddc2a1%3A0x3fc0807602655630!2sLunazMoto%20-%20Bike%20Service%20%26%20Car%20Wash!5e0!3m2!1sen!2sin!4v1683802779472!5m2!1sen!2sin" width="600" height="760" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
    </div>
    <!-- </div> -->
</section>


@endsection