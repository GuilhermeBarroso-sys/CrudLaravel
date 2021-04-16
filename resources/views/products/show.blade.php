@extends('layouts.app')

@section('content')
    <div class="container w-25">
        <div class="card">
            <div class="card-header text-center">
                <h5 class = "card-title">{{$product->name}}</h5>
            </div>
            <div class="card-body">
                <p class="card-text"> <strong> Description:</strong> {{$product->description}}</p>
                <p class="card-text"> <strong> Price:</strong> $ {{$product->price}}</p>
                <p class="card-text"> <strong> Amount (stock):</strong> {{$product->amount}}</p>
            </div>
        </div>
    </div>

@endsection
