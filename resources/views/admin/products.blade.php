@extends('layouts.dash')

@push('js')
    <script>
        $(document).on('submit', '.del_product_form', function() {
            if (confirm('Are you sure you want to delete the product?')) {
                return true;
            }
            return false;
        });

        $(document).on('click', '.add-product-btn', function() {
            var _Form = $('#product-form');

            $('#id').val('');
            $('#category_id').val('');
            $('#product_name').val('');
            $('#price').val('');
            $('#image_url').val('');
            $('#is_featured').val('');

            // set the add url
            var action = '/admin/products';
            //console.log(action);
            _Form .attr('action', action);
            $('#product-modal').modal('show');
        });

        // $(document).on('click', '.edit-product-btn', function() {
        //     var _Btn = $(this);
        //     var _id = _Btn.attr('item-id'),
        //         _Form = $('#product-form');

        //     if (_id !== '') {
        //         $.ajax({
        //             url: _Btn.attr('source'),
        //             type: 'get',
        //             dataType: 'json',

        //             success: function(data) {
        //                 console.log(data);
        //                 // populate the modal fields using data from the server
        //                 //$('#category_id').val(data['category_id']).trigger('change');;
        //                 // $('#category_id').val(data['category_id']).trigger('change');
        //                 // $('#product_name').val(data['product_name']);
        //                 // $('#id').val(data['id']);
        //                 // $('#price').val(data['price']);
        //                 // $('#image_url').val(data['image_url']);
        //                 // // $('#featured').val(data['featured']);
        //                 // if (data['is_featured'] === 1){
        //                 //     $('#is_featured').prop('checked', true);
        //                 // }else if(data['is_featured'] == 0) {
        //                 //     $('#is_featured').prop('checked', false);
        //                 // }

        //                 // // set the update url
        //                 // var action = '/admin/product/update';
        //                 // //console.log(action);
        //                 // _Form .attr('action', action);

        //                 // // open the modal
        //                 // $('#product-modal').modal('show');
        //                 $('#category_name').val(data['category_name']).trigger('change');
        //                 $('#product_name').val(data['product_name']);
        //                 $('#price').val(data['price']);

        //                 if (data['is_featured'] === 1){
        //                     $('#is_featured').prop('checked', true);
        //                 }else {
        //                     $('#is_featured').prop('checked', false);
        //                 }
        //                 $('#id').val(data['id']);

        //                 // set the update url
        //                 var action = '/admin/product/update';
        //                 //console.log(action);
        //                 _Form .attr('action', action);

        //                 // open the modal
        //                 $('#product-modal').modal('show');
        //             }
        //         });
        //     }
        // });

        $(document).on('click', '.edit-product-btn', function() {
            var _Btn = $(this);
            var _id = _Btn.attr('item-id'),
                _Form = $('#product-form');

            if (_id !== '') {
                $.ajax({
                    url: _Btn.attr('source'),
                    type: 'get',
                    dataType: 'json',

                    success: function(data) {
                        console.log(data);
                        // populate the modal fields using data from the server
                        $('#category_id').val(data['category_id']).trigger('change');
                        $('#product_name').val(data['product_name']);
                        $('#price').val(data['price']);

                        if (data['is_featured'] === 1){
                            $('#is_featured').prop('checked', true);
                        }else {
                            $('#is_featured').prop('checked', false);
                        }
                        $('#id').val(data['id']);

                        // set the update url
                        var action = '/admin/product/update';
                        //console.log(action);
                        _Form .attr('action', action);

                        // open the modal
                        $('#product-modal').modal('show');
                    }
                });
            }
        });

    </script>
@endpush

@section('content')

  <div class="content">
      <div class="container-fluid">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-md-12">
                      <div class="card">
                          <div class="card-header card-header-icon card-header-rose">
                              <div class="card-icon">
                                  <i class="material-icons">assignment</i>
                              </div>
                              <h4 class="card-title ">Products</h4>
                              <button class="btn btn-success btn-sm add-product-btn">

                                  Add Product
                              </button>
                          </div>
                          <div class="card-body">

                              @include('layouts.common.error')
                              @include('layouts.common.info')
                              @include('layouts.common.success')
                              @include('layouts.common.warnings')
                              @include('layouts.common.warning')

                              <div class="table-responsive">
                                  <table class="table">
                                      <thead class=" text-primary">
                                          <th>ID</th>
                                          <th>Category Id</th>
                                          <th>Name</th>
                                          <th>Price</th>
                                          <th>Image</th>
                                          <th>Featured</th>
                                          <th>Updated At</th>
                                          <th>Action</th>

                                      </thead>
                                      <tbody>
                                      @foreach($products as $product)
                                      <tr>
                                          <td>{{$product->id}} </td>
                                          <td>{{$product->category_id}}</td>
                                          <td>{{$product->product_name}} </td>
                                          <td>{{$product->price}} </td>
                                          <td><img width="40" height="40" src="{{\Illuminate\Support\Facades\Storage::url($product->image_url)}}" alt="Product image here">  </td>
                                          <td>{{$product->is_featured ? 'Yes':'No'}}
                                          <td>{{$product->updated_at}} </td>
                                          <td>
                                              <button class="btn btn-info btn-sm edit-product-btn"
                                                      source="{{route('edit-product' ,  $product->id)}}"
                                                      item-id="{{$product->id}}">Edit</button>

                                             <form action="{{url('admin/products/delete')}}" style="display: inline;" method="POST"
                                                   class="del_product_form">
                                                 @csrf
                                                 <input type="hidden" name="id" value="{{$product->id}}">
                                                 <button class="btn btn-danger btn-sm">Delete</button>
                                             </form>
                                          </td>
                                      </tr>
                                      @endforeach
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>



<div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Product Details</h4>
            </div>
            <div class="modal-body" >
                <form action="{{ url('admin/products') }}" method="POST" id="product-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category Name</label>

                                  {{-- <form> --}}
                                    <select name="category_id" id="category_id">

                                        @foreach ($categories as $category)
                                          <option value="{{$category->id}}" >{{$category->category_name}}</option>
                                        @endforeach
                                    </select>
                                  {{-- </form> --}}

                          </div>
                        <div class="col-md-6">
                          <label for="product_name" class="form-label">Product Name</label>
                        {{--class="bmd-label-floating" --}}
                         <input type="text" class="form-control" id="product_name" name="product_name">
                        </div>
                        <div class="col-md-6">
                          <label for="price" class="form-label">Price</label>
                          <input type="number" class="form-control" name="price" id="price">
                        </div>
                        {{-- <div class="col-md-6">
                          <label for="image_url" class="form-label">Image Url</label>
                          <input type="text" class="form-control" name="image_url" id="image_url">
                        </div> --}}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="">
                                    <label for="image">Select Image:</label>
                                    <input type="file" id="image" class="form-control" name="image" >
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="togglebutton">
                                <label>
                                    <input type="checkbox" name="is_featured" id="is_featured">
                                    <span class="toggle"></span>
                                    Is Featured
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--hidden fields--}}
                    <input type="hidden" name="id" id="id"/>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons">close</i> Close</button>
                        <button class="btn btn-success" type="submit" id="save-brand"><i class="material-icons">save</i> Save</button>
                    </div>
                </form>

            </div>


        </div>
    </div>
</div>

@endsection
