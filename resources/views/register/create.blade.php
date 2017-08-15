@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        {!! Form::open(['route' => 'register', 'method' => 'POST']) !!}

                            {!! Field::email('email') !!}

                            {!! Field::text('username') !!}

                            {!! Field::text('first_name') !!}

                            {!! Field::text('last_name') !!}

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button class="btn btn-primary">
                                        Regístrate
                                    </button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
