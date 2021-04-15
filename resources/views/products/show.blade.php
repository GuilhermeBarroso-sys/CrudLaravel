@extends('layouts.app')

@section('content')
    <div class="container w-25">
        <div class="card">
            <div class="card-header text-center">
                <h5 class = "card-title">{{$product->name}}</h5>
            </div>
        </div>
    </div>

@endsection
