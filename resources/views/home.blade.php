@extends('layouts.admin')

@section('content')
    <style>
        a.link, a.link:hover, a.link:active {
            text-decoration: none;
            font-size: 15px;
            color: black;
        }
    </style>
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <p><a class="link" href="/add">Добавление таблицы</a></p>
            <p><a class="link" href="/json">Просмотр таблиц</a></p>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection
