
@extends('layouts.app')
@section('content')

    <div class="container">

        <div class="col-md-12">
            <a href="/products/create" class="btn btn-success">Create</a>
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
                            <td>$ {{$product->price}}</td>
                            <td>
                                @if($product->amount == 0)
                                    <span style = "color:red"> {{$product->amount}} </span>
                                    @endif
                                    @if($product->amount > 1000)
                                    <span style = "color:green"> {{$product->amount}} </span>
                                    @endif
                                    @if($product->amount < 1000 && $product->amount > 0)
                                    <span style = "color:rgb(253, 186, 0)"> {{$product->amount}} </span>
                                @endif
                            </td>

                            <td class = "w-25 text-center">
                                <div class="container">
                                    <div class="col-sm-12">
                                        <div class="row">

                                                <a style = "margin-right:5px;" href="{{ route('products.show', ['product'=>$product->id])}}" class="btn btn-secondary">View</a>

                                                <a style = "margin-right:5px;" href="{{ route('products.edit', ['product'=>$product->id])}}" class="btn btn-info text-white">Edit</a>

                                                <form action="{{ route('products.destroy', ['product'=>$product->id])}}" onsubmit = "deleteConfirm(event,this)" method = "POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>






                            </td>

                        </tr>
                    @endforeach
                </tbody>


        </div>
    </div>


<script>
    function deleteConfirm(event, form) {
    event.preventDefault();
    const decision = confirm("This Product will be deleted, are you sure?");
    if(decision) {
        form.submit();
    }

}


</script>

@endsection
