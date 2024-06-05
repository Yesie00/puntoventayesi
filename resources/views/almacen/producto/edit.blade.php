@extends('layouts.admin')
@section('contenido')
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Editar Producto</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('producto.update', $producto->id_producto) }}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group"> 
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="{{ $producto->nombre}}" placeholder="Ingresa el nombre">
                            </div> 
                        </div>  
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select name="id_categoria" class="form-control" id="id_categoria">
                                    @foreach($categorias as $cat)
                                    @if($cat->id_categoria==$producto->id_categoria)
                                        <option value="{{ $cat->id_categoria}}">{{ $cat->categoria }}</option>
                                    @else
                                    <option value="{{$cat->id_categoria}}">{{ $cat->categoria }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control" name="codigo"  value="{{$producto->codigo}}"placeholder="Ingresa el código del producto">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="text" class="form-control" name="stock"  value="{{$producto->stock}}" placeholder="Ingresa el stock del producto">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="descripcion">Descripcion</label>
                                <input type="text" class="form-control" name="descripcion"  value="{{$producto->descripcion}}" placeholder="Ingresa la descripcion del producto">
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="imagen">Imagen</label>
                                <input type="file" class="form-control" name="imagen" id="imagen">
                                @if (($producto->imagen)!="")
                                <img src="{{asset('imagenes/productos/'.$producto->imagen)}}"  height="100px" width="100px">
                                @endif
                            </div>
                        </div>
                    
                
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success me-1 mb-1">Guardar</button>
                            <button type="button" class="btn btn-danger me-1 mb-1">Cancelar</button>
                        </div>
                  </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.row -->
@endsection
