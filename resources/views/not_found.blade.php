
<style>
    .error-page {
        padding: 20px 0px 50px;
    }

    .error-page p {
        margin-bottom: 15px !important;
    }

    .error-page a {
        text-transform: capitalize;
    }

    .error-img {
        height: 400px;
    }

    .error-img img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    @media only screen and (max-width:575px) {
        .error-img {
            height: 200px;
        }
    }
</style>

<body>
    <!-- =============== header ============== -->
    <x-front.advertise />

    <section class="error-page">
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <div class="error-img">
                        <img src="{{asset('assets/front/404.jpg')}}" alt="error-img">
                    </div>
                    <p>Opps! page not found.</p>
                    <div>
                        <a href="{{(Auth::guard('web')->check())?route('profile'):route('front.home')}}" class="cmn-btn">back home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== footer ======= -->
   