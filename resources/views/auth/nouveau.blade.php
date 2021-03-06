
<!DOCTYPE html>
<html lang="en">
<head>
	<title>S'inscrire - Colisade</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
<link rel="icon" type="image/png" sizes="16x16" href="{{url('/assets/images/favicon.png')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{url('/assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{url('/assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css"  href="{{url('/css/inscription/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{url('/css/inscription/css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
                <form class="login100-form validate-form" style="position:relative;top:-250px;" method="POST" action="{{ route('register') }}">
                    @csrf
					<span class="login100-form-title p-b-34">
				        Cr??ez votre espace
					</span>

					<div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="">
						<input id="name" class="input100  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus type="text" placeholder="Nom Complet">
                        <span class="focus-input100"></span>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
					</div>
					<div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="V??rifiez votre email">
                        <input id="storeName" class="input100" type="text" placeholder="Nom du store"  name="storeName" value="{{ old('storeName') }}" required>
						<span class="focus-input100"></span>
					</div>
                    <div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="" style="width: 100%;">
						<input class="input100 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" type="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
                    <div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="">
						<input id="cin" class="input100" type="text" placeholder="N?? CIN"  name="cin" value="{{ old('cin') }}">
						<span class="focus-input100"></span>
					</div>
                    <div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="">
						<input id="first-name" class="input100" value="{{ old('rib') }}" type="text" name="rib" placeholder="RIB">
						<span class="focus-input100"></span>
					</div>
					<div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="">
                                <input placeholder="Votre ville"  class="input100" list="brow" name="ville" value="{{ old('ville') }}" required>
                                <datalist  id="brow">
                                  <@foreach ($villes as $ville)
                                  <option value="{{$ville->name}}" class="rounded-circle">
                                      {{$ville->name}}
                                  </option>
                                  @endforeach
                                </datalist>
                        <span class="focus-input100"></span>
                        @error('ville')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
					</div>
                    <div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="">
						<input id="first-name" class="input100" type="tel"  name="telephone" value="{{ old('telephone') }}" placeholder="T??l??phone">
						<span class="focus-input100"></span>
					</div>
					<div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="" style="width: 100%;">
						<input id="description" class="input100" type="text" placeholder="N?? Registre de Commerce"  name="description" value="{{ old('description') }}">
						<span class="focus-input100"></span>
					</div>
					<div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="" style="width: 100%;">
						<input class="input100" type="text" name="adresse" value="{{ old('adresse') }}" placeholder="Adresse">
						<span class="focus-input100"></span>
					</div>
                    <div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="">
						<input id="first-name" class="input100 @error('password') is-invalid @enderror" type="password"  name="password" required autocomplete="new-password" placeholder="Mot de passe">
                        <span class="focus-input100"></span>
                        @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
					<div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="">
						<input id="password-confirm" class="input100" type="password"  name="password_confirmation" required autocomplete="new-password" placeholder="Confirmez votre Mot de passe">
						<span class="focus-input100"></span>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit"  class="login100-form-btn inscrire">
							S'inscrire
						</button>
					</div>


					<div class="w-full text-center p-t-27 ">
						<a href="/login" class="txt4" >
							Se connecter
						</a>
					</div>
				</form>

				<div class="login100-more" style="background-image: url('/assets/images/bg-01.jpg'); background-size: 126%;
    background-position: top;"></div>
			</div>
		</div>
	</div>



	<div id="dropDownSelect1"></div>

      <script src="{{ url('/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ url('/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/js/app-style-switcher.js') }}" ></script>
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});
	</script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>



</body>
</html>

