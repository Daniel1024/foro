@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>

    {!! $post->safe_html_content !!}

    <p>{{ $post->user->name }}</p>

    @if(Auth::check())
        @if(Auth::user()->isSubscribedTo($post))
            {!! Form::open(['route' => ['posts.unsubscribe', $post], 'method' => 'DELETE']) !!}
                <button>Cancelar suscripción</button>
            {!! Form::close() !!}
        @else
            {!! Form::open(['route' => ['posts.subscribe', $post], 'method' => 'POST']) !!}
                <button>Suscribirse al post</button>
            {!! Form::close() !!}
        @endif
    @endif

    <h4>Comentarios</h4>

    {!! Form::open(['route' => ['comments.store', $post], 'method' => 'POST']) !!}

        {!! Field::textarea('comment') !!}

        <button>
            Publicar comentario
        </button>

    {!! Form::close() !!}

    @foreach($post->latestComments as $comment)
        <article class="{{ $comment->answer ? 'answer' : '' }}">
            {{ $comment->comment }}
            <!--@-can('accept', $comment)-->
            @if(Gate::allows('accept', $comment) && !$comment->answer)
                {!! Form::open(['route' => ['comments.accept', $comment], 'method' => 'POST']) !!}
                    <button>
                        Aceptar respuesta
                    </button>
                {!! Form::close() !!}
            @endif
        </article>
    @endforeach
@endsection
