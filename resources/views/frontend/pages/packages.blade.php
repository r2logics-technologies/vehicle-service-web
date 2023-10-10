@extends('frontend.layouts.app')


@section('content')
<section class="page-title-section top-position1 bg-img cover-background" data-overlay-dark="7" data-background="/front_assets/img/bg/bg-06.jpg" style="background-image: url(&quot;/front_assets/img/bg/bg-06.jpg&quot;);">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h1>Packages</h1>
            </div>
            <div class="col-md-12">
                <div class="breadcrumb">
                    <span class="left-dot"></span>
                    <span class="right-dot"></span>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/packages">Packages</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section bg-light">
<div class="container">
    <div class="row justify-content-center pb-5 mb-3">
  <div class="col-md-7 heading-section text-center ftco-animate fadeInUp ftco-animated">
    <span class="subheading">Package &amp; Plans</span>
    <h2>Packages</h2>
  </div>
</div>
    <div class="row">
        <div class="col-md-6 col-lg-4 ftco-animate fadeInUp ftco-animated">
      <div class="block-7">
        <div class="text-center">
        <span class="excerpt d-block">LUNAZMOTO</span>
        <span class="price"><span class="number">BASIC</span></span>
        
        <div class="pricing-text">
          <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
        </div>

        <a href="#" class="btn btn-secondary d-block px-2 py-3">Get Started</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4 ftco-animate fadeInUp ftco-animated">
      <div class="block-7">
        <div class="text-center">
        <span class="excerpt d-block">LUNAZMOTO</span>
        <span class="price"><span class="number">GOLD</span></span>
        
        <div class="pricing-text">
          <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
        </div>

        <a href="#" class="btn btn-secondary d-block px-2 py-3">Get Started</a>
        </div>
      </div>
    </div>
  
    <div class="col-md-6 col-lg-4 ftco-animate fadeInUp ftco-animated">
      <div class="block-7">
        <div class="text-center">
        <span class="excerpt d-block">LUNAZMOTO</span>
        <span class="price"><span class="number">PREMIUM</span></span>
        
        <div class="pricing-text">
          <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
        </div>

        <a href="#" class="btn btn-secondary d-block px-2 py-3">Get Started</a>
        </div>
      </div>
    </div>
  </div>
</div>
</section>


@endsection