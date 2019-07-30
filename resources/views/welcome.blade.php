<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GRID</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            #cupos-wrapper {
                margin-top: 30px;
                margin-left: -137px;
            }
        </style>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('js/toastr/build/toastr.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="flex-center position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="row" style="margin-bottom: 30px; margin-top:10px;">
                <h3 class="text-center" >Inscripción</h3>
                <small id="cupos-wrapper" style="display: none;">Quedan <span id="numero-cupos"></span> cupos</small>

                <div class="col-md-12" style="width: 500px;margin-top: 10px;">
                    <form name="incripcion" id="form" method="POST" action="#">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <div class="form-group row" style="width: 100%">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="form-group row" style="width: 100%">
                            <label for="edad">Edad</label>
                            <input type="number" name="edad" id="edad" class="form-control" required>
                        </div>
                        <div class="form-group row" style="width: 100%; display: none;" id="fecha-nacimiento-wrapper">
                            <label for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control">
                        </div>
                        <div class="form-group row" style="width: 100%">
                            <label for="rut">Rut</label>
                            <input type="text" name="rut" id="rut" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="ml-auto" style="margin-right:31px; margin-top: 20px;">
                                <input type="submit" class="btn btn-info float-right" id="btn-guardar" value="Guardar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery.rut-master/jquery.rut.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/toastr/toastr.js') }}"></script>
        <script type="text/javascript">
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '<Pre',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié;', 'Juv', 'Vie', 'Sáb'],
                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);

            $("#rut").rut().on('rutInvalido', function () {
                toastr.error('El rut ingresado no es válido');
                $('#btn-guardar').prop('disabled', true);
            }).on('rutValido', function () {
                $('#btn-guardar').prop('disabled', false);
            });
            
            $('#fecha_nacimiento').datepicker({ changeYear: true });

            $('#edad').on('change', function () {
                let edad = $(this).val();

                if (edad >= 30) {
                    $('#fecha-nacimiento-wrapper').slideDown();
                    $('#fecha_nacimiento').prop('required', true);
                } else {
                    $('#fecha-nacimiento-wrapper').slideUp();
                    $('#fecha_nacimiento').prop('required', false);
                }
            });

            $('#form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('registrarInscripcion') }}",
                    method: "POST",
                    dataType: "json",
                    data: {
                        nombre: $('#nombre').val(),
                        edad: $('#edad').val(),
                        fecha_nacimiento: $('#fecha_nacimiento').val(),
                        rut: $('#rut').val(),
                        _token: $('#csrf-token').val()
                    },
                    success: function (data) {
                        if (data.respuesta) {
                            toastr.success(data.mensaje);
                        } else {
                            toastr.error(data.mensaje);
                        }
                    },
                    error: function () {
                        toastr.error('Ha ocurrido un error inesperado');
                    }
                })
            });

            $(document).ready(function() {
                $.ajax({
                    url: "{{ url('contarInscripciones') }}",
                    method: "GET",
                    dataType: "json",
                    data: {
                        _token: $('#csrf-token').val()
                    },
                    success: function (data) {
                        let cupos = 5 - data.inscripciones;

                        cupos = cupos < 0 ? 0 : cupos;

                        $('#numero-cupos').html(cupos);
                        $('#cupos-wrapper').show();

                        if (data.inscripciones >= 5) {
                            toastr.info('El periodo de inscripciones ha terminado');
                            $('#cupos-wrapper').html('Inscripciones finalizadas');
                        }
                    }
                })
            });
        </script>
    </body>
</html>
