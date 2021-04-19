@extends('layouts.app')

@section('content')
    <div class="container w-25">
        <div class="card">
            <div class="card-header text-center">
                <h5 class = "card-title">{{$product->name}}</h5>
            </div>
            <div class="card-body">
                <img class="img-thumbnail" src = "{{$signedUrl}}"/></p>

                <p class="card-text"> <strong> Description:</strong> {{$product->description}}</p>
                <p class="card-text"> <strong> Price:</strong> $ {{$product->price}}</p>
                <p class="card-text"> <strong> Amount in stock:</strong>

                    @if($product->amount == 0)
                    <span style = "color:red"> {{$product->amount}} </span>
                    @endif
                    @if($product->amount > 1000)
                    <span style = "color:green"> {{$product->amount}} </span>
                    @endif
                    @if($product->amount < 1000 && $product->amount > 0)
                    <span style = "color:rgb(253, 186, 0)"> {{$product->amount}} </span>
                    @endif
                </p>

            </div>
        </div>
    </div>

@endsection
