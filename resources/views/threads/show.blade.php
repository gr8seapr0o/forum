@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> опубликовал:
                                {{ $thread->title }}
                            </span>

                            @can ('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <button type="submit" class="btn btn-link">Удалить тему</button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                @foreach ($replies as $reply)
                    @include ('threads.reply')
                @endforeach

                {{ $replies->links() }}

                @if (auth()->check())
                    <form method="POST" action="{{ $thread->path() . '/replies' }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Есть что сообщить?"
                                      rows="5"></textarea>
                        </div>

                        <button type="submit" class="btn btn-default">Добавить</button>
                    </form>
                @else
                    <p class="text-center">Пожалуйста <a href="{{ route('login') }}">войдите</a> для того чтобы учавствовать в обсуждении.</p>
                @endif
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            Тема была опубликована {{ $thread->created_at->format('d-m-Y') }} в {{ $thread->created_at->format('H:i:s') }}
                            Опубликовал: <a href="#">{{ $thread->creator->name }}</a>.
                            Сообщений в теме: {{ $thread->replies_count }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
