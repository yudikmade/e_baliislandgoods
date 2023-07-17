    <footer>
      
    </footer>
</main>

<script>
    $(window).on("scroll", function() {
        if($(window).scrollTop() > 50) {
            $(".menu").addClass("bg-white");
            $(".menu").removeClass("bg-transparent");
        } else {
            $(".menu").removeClass("bg-white");
            $(".menu").addClass("bg-transparent");
        }
    });
    $(document).ready(function () {
        $('.first-button').on('click', function () {
            $('.animated-icon1').toggleClass('open');
        });

        $('.second-button').on('click', function () {
            $('.animated-icon2').toggleClass('open');
        });

        $('.third-button').on('click', function () {
            $('.animated-icon3').toggleClass('open');
        });

        $('#gotoTop').on('click', function () {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    });
</script>
<script src="{{asset(env('URL_ASSETS').'jquery.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'frontend/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://kit.fontawesome.com/6ade19a7ec.js" crossorigin="anonymous"></script>
<script src="{{asset(env('URL_ASSETS').'validation/jquery.validate.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'nprogress/nprogress.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'toastr/toastr.min.js')}}"></script>
<script type="text/javascript">
    NProgress.start();
	$(document).ready(function(e){
		NProgress.done();
	});

	toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    toastr.options.showMethod = 'slideDown';
</script>
<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
