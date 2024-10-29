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
        $this->middleware('permission:ver-reporte', ['only' => [
            'index',
            'indexVentas',
            'ventasTotales',
            'ventasPorProducto',
            'ventasPorCliente',
            'ventasPorUsuario',
            'indexProductos',
            'inventarioActual',
            'indexCompras'
        ]]);
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


        return view('reportes.ventas.ventas_totales', compact('ventasDelMesSeleccionado', 'anio', 'mesSeleccionado', 'labels', 'datosVentas', 'colores'));
    }



    public function ventasPorProducto(Request $request)
    {
        // Obtener los filtros de mes y año
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Consulta para obtener las ventas por producto
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

        // Obtener los resultados de la consulta
        $ventas = $query->get();

        // Verificar si se está solicitando un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_producto_pdf', compact('ventas', 'mes', 'anio'));
            return $pdf->stream('reporte_ventas_producto_' . $mes . '_' . $anio . '.pdf');
        }

        return view('reportes.ventas.ventas_producto', compact('ventas', 'mes', 'anio'));
    }



    public function ventasPorCliente(Request $request)
    {
        // Obtener los filtros de mes y año del request
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Subconsulta para obtener las razones sociales agrupadas por cliente
        $razonesSocialesSubquery = DB::table('razon_social')
            ->select('persona_id', DB::raw("GROUP_CONCAT(DISTINCT razon_social SEPARATOR ', ') as razones_sociales"))
            ->groupBy('persona_id');

        // Consulta principal para obtener ventas por cliente
        $query = DB::table('ventas')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->join('personas', 'clientes.persona_id', '=', 'personas.id') // Relación cliente -> persona
            ->join('producto_venta', 'ventas.id', '=', 'producto_venta.venta_id')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id') // Unión con productos para obtener nombres
            ->leftJoinSub($razonesSocialesSubquery, 'rs', function ($join) {
                $join->on('rs.persona_id', '=', 'personas.id');
            })
            ->select(
                DB::raw("CONCAT_WS(' ', personas.nombre, personas.primer_apellido, personas.segundo_apellido) as cliente"),
                'rs.razones_sociales', // Obtener razones sociales ya agrupadas y sin duplicados
                DB::raw('SUM(producto_venta.cantidad) as total_comprado'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as total_ingresos'),
                DB::raw("GROUP_CONCAT(CONCAT(productos.nombre, ' (', producto_venta.cantidad, ')') SEPARATOR ', ') as productos_vendidos") // Lista de productos y cantidades
            )
            ->groupBy('personas.id', 'personas.nombre', 'personas.primer_apellido', 'personas.segundo_apellido', 'rs.razones_sociales');

        // Aplicar filtros de mes y año si están presentes
        if (!empty($mes) && !empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio)
                ->whereMonth('ventas.fecha_hora', $mes);
        } elseif (!empty($anio)) {
            $query->whereYear('ventas.fecha_hora', $anio);
        }

        // Obtener los resultados de la consulta
        $ventas = $query->get();

        // Verificar si se solicita un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_cliente_pdf', compact('ventas', 'mes', 'anio'));
            return $pdf->stream('reporte_ventas_cliente_' . $mes . '_' . $anio . '.pdf');
        }

        return view('reportes.ventas.ventas_cliente', compact('ventas', 'mes', 'anio'));
    }



    public function ventasPorUsuario(Request $request)
    {
        // Obtener los filtros de mes y año del request
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Consulta para obtener ventas por usuario
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

        // Verificar si se está solicitando un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_usuario_pdf', compact('ventas', 'mes', 'anio'));
            return $pdf->stream('reporte_ventas_usuario_' . $mes . '_' . $anio . '.pdf');
        }


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
                $query->where('tipo_producto_id', 1);  // ID de productos terminados
            })
            ->when(request()->input('tipo') == 'materia', function ($query) {
                $query->where('tipo_producto_id', 2);  // ID de materia prima
            })
            ->paginate(10);

        // Verifica si el usuario quiere previsualizar el reporte en PDF
        if (request()->input('preview') == 'pdf') {
            $pdf = Pdf::loadView('reportes.productos.inventario_pdf', compact('productos'));
            return $pdf->stream('inventario_actual.pdf');
        }

        return view('reportes.productos.inventario', compact('productos'));
    }




    public function productosMasVendidos()
    {
        $anio = request()->input('anio', date('Y'));
        $mes = request()->input('mes', date('m'));

        $productos = Producto::select(
            'productos.id',
            'productos.nombre',
            DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
            DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as ingresos')
        )
            ->join('producto_venta', 'productos.id', '=', 'producto_venta.producto_id')
            ->where('productos.tipo_producto_id', 1)
            ->whereYear('producto_venta.created_at', $anio)
            ->whereMonth('producto_venta.created_at', $mes)
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->take(10)
            ->get();

        $nombresProductos = $productos->pluck('nombre');
        $cantidadesVendidas = $productos->pluck('total_vendido');

        // Verifica si se solicita previsualización o descarga del PDF
        if (request()->input('pdf') == 'preview' || request()->input('pdf') == 'download') {
            $pdf = Pdf::loadView('reportes.productos.productos_mas_vendidos_pdf', compact('productos', 'anio', 'mes'));

            if (request()->input('pdf') == 'download') {
                return $pdf->download('productos_mas_vendidos.pdf');
            } else {
                return $pdf->stream('productos_mas_vendidos.pdf');  // Previsualización
            }
        }

        return view('reportes.productos.productos_mas_vendidos', compact('productos', 'anio', 'mes', 'nombresProductos', 'cantidadesVendidas'));
    }


    public function bajoStock()
    {
        $productos = Producto::where('stock', '<', 5)->get();

        // Verifica si el usuario quiere descargar o previsualizar el reporte en PDF
        if (request()->input('download') == 'pdf') {
            $pdf = Pdf::loadView('reportes.productos.bajo_stock_pdf', compact('productos'));
            return $pdf->download('productos_bajo_stock.pdf');
        }

        // Verifica si el usuario quiere previsualizar el reporte en PDF
        if (request()->input('view') == 'pdf') {
            $pdf = Pdf::loadView('reportes.productos.bajo_stock_pdf', compact('productos'));
            return $pdf->stream('productos_bajo_stock.pdf');
        }

        return view('reportes.productos.bajo_stock', compact('productos'));
    }

    public function historialVentas(Request $request, $productoId)
    {
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $query = Venta::query();

        if ($productoId !== 'all') {
            $query->whereHas('productos', function ($q) use ($productoId) {
                $q->where('producto_id', $productoId);
            });
        }

        if ($anio) {
            $query->whereYear('fecha_hora', $anio);
        }
        if ($mes) {
            $query->whereMonth('fecha_hora', $mes);
        }

        $historial = $query->with('productos')->get();
        $producto = ($productoId !== 'all') ? Producto::find($productoId) : null;

        // Verificar si se solicita la vista en PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.productos.historial_ventas_pdf', compact('historial', 'productoId', 'producto', 'mes', 'anio'));

            return $pdf->stream('historial_ventas_producto_' . $productoId . '.pdf');
        }

        return view('reportes.productos.historial_ventas', compact('historial', 'productoId', 'producto', 'mes', 'anio'));
    }




    public function indexCompras()
    {
        // Cálculo de compras totales
        $comprasTotales = DB::table('compras')->sum('total');

        // Obtener los totales de compras por producto
        $comprasPorProducto = DB::table('compra_producto')
            ->join('productos', 'compra_producto.producto_id', '=', 'productos.id')
            ->select('productos.nombre as producto', DB::raw('SUM(compra_producto.cantidad) as total_comprado'))
            ->groupBy('productos.nombre')
            ->get();

        // Subconsulta para obtener las razones sociales agrupadas por proveedor
        $razonesSocialesSubquery = DB::table('razon_social')
            ->select('persona_id', DB::raw("GROUP_CONCAT(DISTINCT razon_social SEPARATOR ', ') as razones_sociales")) // Ajusta aquí el nombre del campo
            ->groupBy('persona_id');

        // Obtener compras por proveedor
        $comprasPorProveedor = DB::table('compras')
            ->join('proveedores', 'compras.proveedore_id', '=', 'proveedores.id')
            ->join('personas', 'proveedores.persona_id', '=', 'personas.id')
            ->leftJoinSub($razonesSocialesSubquery, 'rs', function ($join) {
                $join->on('rs.persona_id', '=', 'personas.id');
            })
            ->select(
                DB::raw("COALESCE(rs.razones_sociales, 'Sin razón social') as proveedor"), // Usamos razones_sociales de la subconsulta
                DB::raw('SUM(compras.total) as total_compras')
            )
            ->groupBy('proveedores.id', 'rs.razones_sociales') // Agrupar por ID de proveedor y razones sociales para evitar duplicados
            ->get();

        return view('reportes.compras.index', compact('comprasTotales', 'comprasPorProducto', 'comprasPorProveedor'));
    }



    public function comprasTotales(Request $request)
    {
        // Filtros de año y mes
        $anioSeleccionado = $request->input('anio', date('Y')); // Usar el año actual como predeterminado
        $mesSeleccionado = $request->input('mes', date('m'));  // Usar el mes actual como predeterminado

        // Obtener el total de compras por día del mes seleccionado
        $query = DB::table('compras')
            ->select(
                DB::raw('SUM(total) as total'),
                DB::raw('DAY(fecha_hora) as dia')
            )
            ->groupBy('dia');

        // Aplicar filtros si están presentes
        if (!empty($anioSeleccionado)) {
            $query->whereYear('fecha_hora', $anioSeleccionado);
        }
        if (!empty($mesSeleccionado)) {
            $query->whereMonth('fecha_hora', $mesSeleccionado);
        }

        $comprasDelMesSeleccionado = $query->get();

        // Datos para el gráfico
        $labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $datosCompras = array_fill(0, 12, 0); // Inicializar un arreglo con 12 meses
        $totalesPorMes = DB::table('compras')
            ->select(
                DB::raw('MONTH(fecha_hora) as mes'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('fecha_hora', $anioSeleccionado)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // Rellenar el arreglo de datos con los totales de compras para cada mes
        foreach ($totalesPorMes as $mes => $total) {
            $datosCompras[$mes - 1] = $total; // El índice del arreglo es el mes - 1
        }

        // Colores para resaltar el mes seleccionado en el gráfico
        $colores = array_fill(0, 12, 'rgba(75, 192, 192, 0.2)');
        $colores[$mesSeleccionado - 1] = 'rgba(255, 99, 132, 0.2)'; // Resaltar el mes seleccionado

        // Generar PDF si es solicitado
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.compras.compras_totales_pdf', compact('comprasDelMesSeleccionado', 'anioSeleccionado', 'mesSeleccionado', 'labels'));
            return $pdf->stream('compras_totales_' . $mesSeleccionado . '_' . $anioSeleccionado . '.pdf');
        }


        // Pasar las variables a la vista
        return view('reportes.compras.compras_totales', compact('comprasDelMesSeleccionado', 'anioSeleccionado', 'mesSeleccionado', 'labels', 'datosCompras', 'colores'));
    }




    public function comprasPorProducto(Request $request)
    {
        // Filtros de mes y año
        $mes = $request->input('mes', date('m')); // Mes actual predeterminado
        $anio = $request->input('anio', date('Y')); // Año actual predeterminado

        // Consulta para obtener las compras por producto
        $query = DB::table('compra_producto')
            ->join('productos', 'compra_producto.producto_id', '=', 'productos.id')
            ->join('compras', 'compra_producto.compra_id', '=', 'compras.id')
            ->select(
                'productos.nombre as producto',
                DB::raw('SUM(compra_producto.cantidad) as total_comprado'),
                DB::raw('SUM(compra_producto.cantidad * compra_producto.precio_compra) as total_gasto')
            )
            ->where('compras.estado', '=', 1) // Solo compras activas
            ->groupBy('productos.nombre');

        // Aplicar filtros de año y mes
        if (!empty($anio)) {
            $query->whereYear('compras.fecha_hora', $anio);
        }
        if (!empty($mes)) {
            $query->whereMonth('compras.fecha_hora', $mes);
        }

        $compras = $query->get();

        // Datos para el gráfico
        $labels = $compras->pluck('producto')->toArray();
        $datosCompras = $compras->pluck('total_comprado')->toArray();
        $datosGastos = $compras->pluck('total_gasto')->toArray();

        // Generar el PDF si es solicitado
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.compras.compras_producto_pdf', compact('compras', 'mes', 'anio', 'labels', 'datosCompras', 'datosGastos'));
            return $pdf->stream('compras_por_producto_' . $mes . '_' . $anio . '.pdf');
        }

        // Pasar variables a la vista
        return view('reportes.compras.compras_producto', compact('compras', 'mes', 'anio', 'labels', 'datosCompras', 'datosGastos'));
    }




    public function comprasPorProveedor(Request $request)
    {
        // Filtros de mes y año
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Subconsulta para obtener las razones sociales agrupadas por proveedor
        $razonesSocialesSubquery = DB::table('razon_social')
            ->select('persona_id', DB::raw("GROUP_CONCAT(DISTINCT razon_social SEPARATOR ', ') as razones_sociales"))
            ->groupBy('persona_id');

        // Consulta principal con joins a las tablas correspondientes
        $query = DB::table('compras')
            ->join('proveedores', 'compras.proveedore_id', '=', 'proveedores.id')
            ->join('personas', 'proveedores.persona_id', '=', 'personas.id')
            ->join('compra_producto', 'compras.id', '=', 'compra_producto.compra_id')
            ->join('productos', 'compra_producto.producto_id', '=', 'productos.id')
            ->leftJoinSub($razonesSocialesSubquery, 'rs', function ($join) {
                $join->on('rs.persona_id', '=', 'personas.id');
            })
            ->select(
                DB::raw("CONCAT_WS(' ', personas.nombre, personas.primer_apellido, personas.segundo_apellido, COALESCE(rs.razones_sociales, 'Sin razón social')) as proveedor"),
                DB::raw('SUM(compras.total) as total_compras'),
                DB::raw('SUM(compra_producto.cantidad) as total_productos_comprados'),
                DB::raw("GROUP_CONCAT(DISTINCT productos.nombre SEPARATOR ', ') as productos")
            )
            ->groupBy('personas.nombre', 'personas.primer_apellido', 'personas.segundo_apellido', 'rs.razones_sociales');

        // Aplicar filtros de fecha
        if (!empty($anio)) {
            $query->whereYear('compras.fecha_hora', $anio);
        }
        if (!empty($mes)) {
            $query->whereMonth('compras.fecha_hora', $mes);
        }

        // Obtener los resultados
        $compras = $query->get();

        // Verificar si se solicita un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.compras.compras_proveedor_pdf', compact('compras', 'mes', 'anio'));
            return $pdf->stream('compras_por_proveedor_' . ($mes ?? 'todos') . '_' . ($anio ?? 'todos') . '.pdf');
        }

        // Retornar la vista con los datos de compras por proveedor
        return view('reportes.compras.compras_proveedor', compact('compras', 'mes', 'anio'));
    }
}
