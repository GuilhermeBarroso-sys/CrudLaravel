@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="col-md-12">
            <a href="/products/create" class="btn btn-success">Criar</a>
            <br>
            <br>
            <h1>Products</h1>
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <th scope="row">{{$product->id}}</th>
                            <td>{{$product->name}}</td>
                            <td>{{$product->price}}</td>
                            <td>{{$product->amount}}</td>
                            <td class = "w-25 text-center">
                                <a href="{{ route('products.show', ['product'=>$product->id])}}" class="btn btn-secondary">Ver</a>
                                <a href="" class="btn btn-info">Edit</a>
                                <a href="" class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>


        </div>
    </div>

@endsection
