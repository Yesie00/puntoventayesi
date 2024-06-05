<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\ProductoFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $texto = trim($request->get('texto'));
        $productos = DB::table('producto as a')
            ->join('categoria as c', 'a.id_categoria', '=', 'c.id_categoria')
            ->select('a.id_producto',  'a.nombre', 'a.codigo','a.stock','c.categoria','a.descripcion', 'a.imagen', 'a.estado')
            ->where(function ($query) use ($texto) {
                $query->where('a.nombre', 'LIKE', '%' . $texto . '%')
                    ->orWhere('a.codigo', 'LIKE', '%' . $texto . '%');
            })
            ->where('a.estado', '=', 'Activo') // Filtrar por estado activo
            ->orderBy('id_producto', 'desc')
            ->paginate(10);

        return view('almacen.producto.index', compact('productos', 'texto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categorias=DB::table('categoria')->where('estatus','=','1')->get();
        return view("almacen.producto.create",["categorias"=>$categorias]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoFormRequest $request)
    {

        // Crear un nuevo objeto Producto con los datos del formulario
        $producto = new Producto;
        $producto->id_categoria = $request->input('id_categoria');
        $producto->codigo = $request->input('codigo');
        $producto->nombre = $request->input('nombre');
        $producto->stock = $request->input('stock');
        $producto->descripcion = $request->input('descripcion');
        $producto->estado = 'Activo';
    
        if($request->hasFile("imagen")){
            $imagen=$request->file("imagen");
            $nombreimagen= Str::slug($request->nombre).".".$imagen->guessExtension();
            $ruta= public_path("/imagenes/productos/");

            copy($imagen->getRealPath(),$ruta.$nombreimagen);
            
            $producto->imagen=$nombreimagen;
        }

        $producto->save();

        // Redireccionar a la página de índice de productos con un mensaje de éxito
        return redirect()->route('producto.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return view("almacen.producto.show",["producto"=>Producto::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        $categorias =DB:: table ('categoria')->where('estatus','=','1')->get(); 
        return view('almacen.producto.edit', ["producto"=>$producto,"categorias"=>$categorias]);
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoFormRequest $request, $id)
    {
        // Validar los datos del formulario
       
        // Obtener el producto a actualizar
        $producto = Producto::findOrFail($id);
        $producto->id_categoria = $request->input('id_categoria');
        $producto->codigo = $request->input('codigo');
        $producto->nombre = $request->input('nombre');
        $producto->stock = $request->input('stock');
        $producto->descripcion = $request->input('descripcion');

        //scrip par subir la imagen
        if($request->hasFile("imagen")){
        $imagen=$request->file("imagen");
        $nombreimagen= Str::slug($request->nombre).".".$imagen->guessExtension();
        $ruta= public_path("/imagenes/productos/");
        copy($imagen->getRealPath(),$ruta.$nombreimagen);
        $producto->imagen=$nombreimagen;
        }
        // Guardar los cambios en la base de datos
        $producto->update();

        // Redirigir al usuario a la página de detalles del producto actualizado
        return redirect()->route('producto.index');
    }

    /**
     * Remove the specified resource from storage.
     *   @param int  $id
     *  @return\Illuminate\Http\Response
     */
 
    public function destroy(string $id)
    {
        // Buscar el producto por su ID
        $producto = Producto::findOrFail($id);

        // Actualizar el estado del producto a "Inactivo"
        $producto->estado = 'Inactivo';

        // Guardar los cambios en la base de datos
        $producto->save();

        // Redirigir al usuario a la página de listado de productos
        return redirect()->route('producto.index')->with('success', 'Producto eliminado correctamente');
    }
}