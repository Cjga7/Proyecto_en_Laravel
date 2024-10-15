<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VentasTotalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use App\Models\Categoria;
use App\Models\Registrosanitario;
use App\Models\Presentacione;
use App\Models\TipoProducto;

class ReporteController extends Controller
{
    function __construct()
{
    // Aplicar el middleware solo para la acción 'index'
    $this->middleware('permission:ver-reporte', ['only' => ['index', 'indexVentas', 'ventasTotales', 'ventasPorProducto', 'ventasPorCliente', 'ventasPorUsuario'
    ,'indexProductos', 'inventarioActual']]);
}
    public function indexVentas()
    {
        // Cálculo real de los totales
        $ventasTotales = Venta::sum('total');
        $ventasPorProducto = Venta::with('productos')->count(); // ejemplo simple
        $ventasPorCliente = Venta::groupBy('cliente_id')->count();
        $ventasPorUsuario = Venta::groupBy('user_id')->count();

        return view('reportes.ventas.index', compact('ventasTotales', 'ventasPorProducto', 'ventasPorCliente', 'ventasPorUsuario'));
    }

    public function ventasTotales(Request $request)
    {
        $anio = $request->input('anio');
        $mesSeleccionado = $request->input('mes');
        $ventasDelMesSeleccionado = collect();
        $totalesPorMes = collect();
        $labels = [];
        $datosVentas = [];
        $colores = [];

        // 1. Filtrar las ventas solo del mes seleccionado
        $ventasDelMesSeleccionado = Venta::select(
            DB::raw('SUM(total) as total'),
            DB::raw('DAY(fecha_hora) as dia')
        )
            ->whereYear('fecha_hora', $anio)
            ->whereMonth('fecha_hora', $mesSeleccionado)
            ->groupBy('dia')
            ->get();

        // 2. Obtener el total de ventas por cada mes del año seleccionado
        $totalesPorMes = Venta::select(
            DB::raw('SUM(total) as total'),
            DB::raw('MONTH(fecha_hora) as mes')
        )
            ->whereYear('fecha_hora', $anio)
            ->groupBy('mes')
            ->get();

        // Preparar los datos para la gráfica de barras (totales por mes)
        for ($i = 1; $i <= 12; $i++) {
            $ventaMes = $totalesPorMes->firstWhere('mes', $i);
            $labels[] = \Carbon\Carbon::create()->month($i)->translatedFormat('F'); // Etiquetas con nombres de los meses
            $datosVentas[] = $ventaMes ? $ventaMes->total : 0;

            // Resaltar el mes seleccionado en la gráfica con un color diferente
            if ($i == $mesSeleccionado) {
                $colores[] = 'rgba(255, 99, 132, 0.6)'; // Color rojo para el mes seleccionado
            } else {
                $colores[] = 'rgba(54, 162, 235, 0.6)'; // Color azul para los demás meses
            }
        }

        // Verificar si la petición es para generar el PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_totales_pdf', compact('ventasDelMesSeleccionado', 'anio', 'mesSeleccionado', 'labels', 'datosVentas', 'colores'));

            // Cambiar de download() a stream() para que se previsualice el PDF
            return $pdf->stream('reporte_ventas_' . $mesSeleccionado . '_' . $anio . '.pdf');
        }
        // Verificar si la petición es para generar un archivo Excel
        if ($request->input('excel') == '1') {
            return Excel::download(new VentasTotalesExport($ventasDelMesSeleccionado, $anio, $mesSeleccionado), 'reporte_ventas_' . $mesSeleccionado . '_' . $anio . '.xlsx');

        }

        return view('reportes.ventas.ventas_totales', compact('ventasDelMesSeleccionado', 'anio', 'mesSeleccionado', 'labels', 'datosVentas', 'colores'));
    }




    public function ventasPorProducto(Request $request)
    {
        // Obtener los filtros de mes y año
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $query = DB::table('producto_venta')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
            ->select(
                'productos.nombre as producto',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as total_ingresos')
            )
            ->groupBy('productos.nombre');

        // Aplicar los filtros si se han seleccionado
        if (!empty($mes) && !empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio)
                ->whereMonth('ventas.fecha_hora', $mes);
        } elseif (!empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio);
        }

        $ventas = $query->get();

        return view('reportes.ventas.ventas_producto', compact('ventas'));
    }



    public function ventasPorCliente(Request $request)
    {



        // Obtener los filtros de mes y año del request
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Construir la consulta para obtener ventas por cliente
        $query = DB::table('ventas')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->join('personas', 'clientes.persona_id', '=', 'personas.id') // Relación cliente -> persona
            ->join('producto_venta', 'ventas.id', '=', 'producto_venta.venta_id')
            ->select(
                'personas.razon_social as cliente', // Usar razon_social en lugar de nombre
                DB::raw('SUM(producto_venta.cantidad) as total_comprado'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as total_ingresos')
            )
            ->groupBy('personas.razon_social'); // Agrupar por razon_social

        // Aplicar los filtros de mes y año si están presentes
        if (!empty($mes) && !empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio)
                ->whereMonth('ventas.fecha_hora', $mes);
        } elseif (!empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio);
        }

        // Obtener los resultados de la consulta
        $ventas = $query->get();

        // Retornar la vista con los datos de las ventas por cliente
        return view('reportes.ventas.ventas_cliente', compact('ventas', 'mes', 'anio'));
    }


    public function ventasPorUsuario(Request $request)
    {


        // Obtener los filtros de mes y año del request
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Construir la consulta para obtener ventas por usuario
        $query = DB::table('ventas')
            ->join('users', 'ventas.user_id', '=', 'users.id')
            ->join('producto_venta', 'ventas.id', '=', 'producto_venta.venta_id')
            ->select(
                'users.name as usuario', // Nombre del usuario
                DB::raw('SUM(producto_venta.cantidad) as total_productos_vendidos'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as total_ingresos')
            )
            ->groupBy('users.name'); // Agrupar por el nombre del usuario

        // Aplicar los filtros de mes y año si están presentes
        if (!empty($mes) && !empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio)
                ->whereMonth('ventas.fecha_hora', $mes);
        } elseif (!empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio);
        }

        // Obtener los resultados de la consulta
        $ventas = $query->get();

        // Retornar la vista con los datos de las ventas por usuario
        return view('reportes.ventas.ventas_usuario', compact('ventas', 'mes', 'anio'));
    }


    public function indexProductos()
    {
        $productos = Producto::with(['categorias.caracteristica', 'registrosanitario.caracteristica', 'presentacione.caracteristica', 'tipoProducto'])->latest()->get();
        return view('reportes.productos.index', compact('productos'));
    }




    public function inventarioActual()
    {
        $productos = Producto::with(['registrosanitario', 'presentacione', 'categorias'])
            ->when(request()->input('search'), function ($query) {
                $query->where('nombre', 'like', '%' . request()->input('search') . '%');
            })
            ->when(request()->input('tipo') == 'terminado', function ($query) {
                $query->where('tipo_producto_id', 1);  // Asume que 1 es el ID de productos terminados
            })
            ->when(request()->input('tipo') == 'materia', function ($query) {
                $query->where('tipo_producto_id', 2);  // Asume que 2 es el ID de materia prima
            })
            ->paginate(10);

        return view('reportes.productos.inventario', compact('productos'));
    }




    public function productosMasVendidos()
    {
        // Obtener el año y el mes de los parámetros de la solicitud, o establecer valores predeterminados
        $anio = request()->input('anio', date('Y'));
        $mes = request()->input('mes', date('m'));

        $productos = Producto::select(
            'productos.id',
            'productos.nombre',
            DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
            DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as ingresos')
        )
            ->join('producto_venta', 'productos.id', '=', 'producto_venta.producto_id')
            ->where('productos.tipo_producto_id', 1) // Solo productos terminados
            ->whereYear('producto_venta.created_at', $anio) // Filtrar por año usando la columna correcta
            ->whereMonth('producto_venta.created_at', $mes) // Filtrar por mes usando la columna correcta
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->take(10)
            ->get();

        // Obtener los datos para la gráfica
        $nombresProductos = $productos->pluck('nombre');
        $cantidadesVendidas = $productos->pluck('total_vendido');

        return view('reportes.productos.productos_mas_vendidos', compact('productos', 'anio', 'mes', 'nombresProductos', 'cantidadesVendidas'));
    }








    public function bajoStock()
    {
        $productos = Producto::where('stock', '<', 5)->get();
        return view('reportes.productos.bajo_stock', compact('productos'));
    }

    public function historialVentas(Request $request, $productoId)
    {
        // Obtén las fechas de filtrado, si están presentes en la solicitud
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Verifica si se está solicitando el historial de todos los productos
        if ($productoId === 'all') {
            // Obtener todas las ventas sin filtrar por producto, aplicando filtro de fechas si existen
            $historial = Venta::with('productos')
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
                })
                ->get();
        } else {
            // Consulta el historial de ventas del producto específico, aplicando filtro de fechas si existen
            $historial = Venta::whereHas('productos', function ($query) use ($productoId) {
                    $query->where('producto_id', $productoId);
                })
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
                })
                ->with(['productos' => function ($query) use ($productoId) {
                    // Filtra solo el producto específico en la relación de productos
                    $query->where('producto_id', $productoId);
                }])
                ->get();
        }

        // Verifica si hay ventas registradas
        if ($historial->isEmpty()) {
            return redirect()->back()->with('message', 'No hay historial de ventas para este producto en el rango de fechas seleccionado.');
        }

        // Obtiene información del producto (si no es "all")
        $producto = ($productoId !== 'all') ? Producto::find($productoId) : null;

        // Devuelve la vista con el historial, el ID del producto y la información del producto
        return view('reportes.productos.historial_ventas', compact('historial', 'productoId', 'producto'));
    }


}
