<!DOCTYPE html>
<html>
    

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="/favicon.ico">

        <title>Juntas</title>

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" />

        <script src="{{ asset('js/modernizr.min.js') }}"></script>
        
    </head>
    <body>

    	<div class="account-pages"></div>
		<div class="clearfix"></div>
		
        <div class="wrapper-page">
            <div class="ex-page-content text-center">
                <div class="text-error"><span class="text-primary">5</span><i class="ti-face-sad text-pink"></i><i class="ti-face-sad text-info"></i></div>
                <h2>Internal Server Error.</h2><br>
                <p class="text-muted">Why not try refreshing your page? or you can contact <a href="#">support</a></p>
                <br>
                <a class="btn btn-default waves-effect waves-light" href="{{ route('app.board') }}"> Board</a>
            </div>
        </div>


        <!-- jQuery  -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script><!-- Popper for Bootstrap -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/waves.js') }}"></script>
        <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('js/jquery.core.js') }}"></script>
        <script src="{{ asset('js/jquery.app.js') }}"></script>
	
	</body>


</html>