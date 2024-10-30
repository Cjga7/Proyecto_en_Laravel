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
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $ventas = collect();
        $labels = [];
        $datosVentas = [];
        $colores = [];

        // Filtrar ventas en el rango de fechas seleccionado y estado igual a 1
        if ($fechaInicio && $fechaFin) {
            $ventas = Venta::select(
                DB::raw('SUM(total) as total'),
                DB::raw('DATE(fecha_hora) as fecha')
            )
            ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
            ->where('estado', 1) // Filtrar solo ventas con estado igual a 1
            ->groupBy('fecha')
            ->get();

            // Preparar etiquetas y datos para cada día en el rango de fechas
            $periodo = \Carbon\CarbonPeriod::create($fechaInicio, $fechaFin);
            foreach ($periodo as $fecha) {
                $ventaDia = $ventas->firstWhere('fecha', $fecha->toDateString());
                $labels[] = $fecha->format('d/m/Y'); // Formato de la etiqueta
                $datosVentas[] = $ventaDia ? $ventaDia->total : 0;
                $colores[] = 'rgba(54, 162, 235, 0.6)';
            }
        }

        // Verificar si la petición es para generar el PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_totales_pdf', compact('ventas', 'fechaInicio', 'fechaFin', 'labels', 'datosVentas', 'colores'));
            return $pdf->stream('reporte_ventas_rango_' . $fechaInicio . '_al_' . $fechaFin . '.pdf');
        }

        return view('reportes.ventas.ventas_totales', compact('ventas', 'fechaInicio', 'fechaFin', 'labels', 'datosVentas', 'colores'));
    }





    public function ventasPorProducto(Request $request)
    {
        // Obtener los filtros de rango de fechas
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Consulta para obtener las ventas por producto, incluyendo solo las ventas con estado igual a 1
        $query = DB::table('producto_venta')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
            ->select(
                'productos.nombre as producto',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as total_ingresos')
            )
            ->where('ventas.estado', 1) // Filtrar solo ventas con estado igual a 1
            ->groupBy('productos.nombre');

        // Aplicar el filtro por rango de fechas si se han proporcionado
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('ventas.fecha_hora', [$fechaInicio, $fechaFin]);
        }

        // Obtener los resultados de la consulta
        $ventas = $query->get();

        // Verificar si se está solicitando un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_producto_pdf', compact('ventas', 'fechaInicio', 'fechaFin'));
            return $pdf->stream('reporte_ventas_producto_' . $fechaInicio . '_al_' . $fechaFin . '.pdf');
        }

        return view('reportes.ventas.ventas_producto', compact('ventas', 'fechaInicio', 'fechaFin'));
    }




    public function ventasPorCliente(Request $request)
    {
        // Obtener los filtros de fechas del request
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        // Subconsulta para obtener las razones sociales agrupadas por cliente
        $razonesSocialesSubquery = DB::table('razon_social')
            ->select('persona_id', DB::raw("GROUP_CONCAT(DISTINCT razon_social SEPARATOR ', ') as razones_sociales"))
            ->groupBy('persona_id');

        // Consulta principal para obtener ventas por cliente, incluyendo solo las ventas con estado igual a 1
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
            ->where('ventas.estado', 1) // Filtrar solo ventas con estado igual a 1
            ->groupBy('personas.id', 'personas.nombre', 'personas.primer_apellido', 'personas.segundo_apellido', 'rs.razones_sociales');

        // Aplicar filtros de rango de fechas si están presentes
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $query->whereBetween('ventas.fecha_hora', [$fecha_inicio, $fecha_fin]);
        }

        // Obtener los resultados de la consulta
        $ventas = $query->get();

        // Verificar si se solicita un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_cliente_pdf', compact('ventas', 'fecha_inicio', 'fecha_fin'));
            return $pdf->stream('reporte_ventas_cliente_' . date('Ymd') . '.pdf');
        }

        return view('reportes.ventas.ventas_cliente', compact('ventas', 'fecha_inicio', 'fecha_fin'));
    }





    public function ventasPorUsuario(Request $request)
    {
        // Obtener los filtros de fecha de inicio y fin del request
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        // Consulta para obtener ventas por usuario, incluyendo solo las ventas con estado igual a 1
        $query = DB::table('ventas')
            ->join('users', 'ventas.user_id', '=', 'users.id')
            ->join('producto_venta', 'ventas.id', '=', 'producto_venta.venta_id')
            ->select(
                'users.name as usuario', // Nombre del usuario
                DB::raw('SUM(producto_venta.cantidad) as total_productos_vendidos'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as total_ingresos')
            )
            ->where('ventas.estado', 1) // Filtrar solo ventas con estado igual a 1
            ->groupBy('users.name'); // Agrupar por el nombre del usuario

        // Aplicar el filtro de rango de fechas si están presentes
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $query->whereBetween('ventas.fecha_hora', [$fecha_inicio, $fecha_fin]);
        } elseif (!empty($fecha_inicio)) {
            $query->where('ventas.fecha_hora', '>=', $fecha_inicio);
        } elseif (!empty($fecha_fin)) {
            $query->where('ventas.fecha_hora', '<=', $fecha_fin);
        }

        // Obtener los resultados de la consulta
        $ventas = $query->get();

        // Verificar si se está solicitando un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.ventas.ventas_usuario_pdf', compact('ventas', 'fecha_inicio', 'fecha_fin'));
            return $pdf->stream('reporte_ventas_usuario_' . date('Ymd') . '.pdf');
        }

        return view('reportes.ventas.ventas_usuario', compact('ventas', 'fecha_inicio', 'fecha_fin'));
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
        // Obtener las fechas de inicio y fin del request
        $fechaInicio = request()->input('fecha_inicio');
        $fechaFin = request()->input('fecha_fin');

        // Consulta para obtener los productos más vendidos
        $productos = Producto::select(
                'productos.id',
                'productos.nombre',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as ingresos')
            )
            ->join('producto_venta', 'productos.id', '=', 'producto_venta.producto_id')
            ->where('productos.tipo_producto_id', 1);

        // Aplicar filtros de fecha si están presentes
        if ($fechaInicio && $fechaFin) {
            $productos->whereBetween('producto_venta.created_at', [$fechaInicio, $fechaFin]);
        }

        // Agrupar, ordenar y limitar los resultados
        $productos = $productos->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->take(10)
            ->get();

        $nombresProductos = $productos->pluck('nombre');
        $cantidadesVendidas = $productos->pluck('total_vendido');

        // Verifica si se solicita previsualización o descarga del PDF
        if (request()->input('pdf') == 'preview' || request()->input('pdf') == 'download') {
            $pdf = Pdf::loadView('reportes.productos.productos_mas_vendidos_pdf', compact('productos', 'fechaInicio', 'fechaFin'));

            if (request()->input('pdf') == 'download') {
                return $pdf->download('productos_mas_vendidos.pdf');
            } else {
                return $pdf->stream('productos_mas_vendidos.pdf');  // Previsualización
            }
        }

        return view('reportes.productos.productos_mas_vendidos', compact('productos', 'fechaInicio', 'fechaFin', 'nombresProductos', 'cantidadesVendidas'));
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
        // Obtener las fechas de inicio y fin del request
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = Venta::query();

        // Filtrar por producto específico si no es 'all'
        if ($productoId !== 'all') {
            $query->whereHas('productos', function ($q) use ($productoId) {
                $q->where('producto_id', $productoId);
            });
        }

        // Aplicar filtros de fecha si están presentes
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
        }

        // Obtener el historial de ventas
        $historial = $query->with('productos')->get();
        $producto = ($productoId !== 'all') ? Producto::find($productoId) : null;

        // Verificar si se solicita la vista en PDF
        if ($request->input('pdf') == 'preview' || $request->input('pdf') == 'download') {
            $pdf = PDF::loadView('reportes.productos.historial_ventas_pdf', compact('historial', 'productoId', 'producto', 'fechaInicio', 'fechaFin'));

            if ($request->input('pdf') == 'download') {
                return $pdf->download('historial_ventas_producto_' . $productoId . '.pdf');
            } else {
                return $pdf->stream('historial_ventas_producto_' . $productoId . '.pdf', ['Attachment' => false]);  // Previsualización sin descarga
            }
        }

        return view('reportes.productos.historial_ventas', compact('historial', 'productoId', 'producto', 'fechaInicio', 'fechaFin'));
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
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $compras = collect();
        $labels = [];
        $datosCompras = [];
        $colores = [];

        // Filtrar compras en el rango de fechas seleccionado
        if ($fechaInicio && $fechaFin) {
            $compras = DB::table('compras')
                ->select(
                    DB::raw('SUM(total) as total'),
                    DB::raw('DATE(fecha_hora) as fecha')
                )
                ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
                ->groupBy('fecha')
                ->get();

            // Preparar etiquetas y datos para cada día en el rango de fechas
            $periodo = \Carbon\CarbonPeriod::create($fechaInicio, $fechaFin);
            foreach ($periodo as $fecha) {
                $compraDia = $compras->firstWhere('fecha', $fecha->toDateString());
                $labels[] = $fecha->format('d/m/Y'); // Formato de la etiqueta
                $datosCompras[] = $compraDia ? $compraDia->total : 0;
                $colores[] = 'rgba(75, 192, 192, 0.6)'; // Color para el gráfico
            }
        }

        // Verificar si la petición es para generar el PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.compras.compras_totales_pdf', compact('compras', 'fechaInicio', 'fechaFin', 'labels', 'datosCompras', 'colores'));
            return $pdf->stream('compras_totales_' . $fechaInicio . '_a_' . $fechaFin . '.pdf');
        }

        return view('reportes.compras.compras_totales', compact('compras', 'fechaInicio', 'fechaFin', 'labels', 'datosCompras', 'colores'));
    }





    public function comprasPorProducto(Request $request)
    {
        // Filtros de rango de fechas
        $fechaInicio = $request->input('fecha_inicio'); // Fecha de inicio
        $fechaFin = $request->input('fecha_fin'); // Fecha de fin

        // Consulta para obtener las compras por producto
        $query = DB::table('compra_producto')
            ->join('productos', 'compra_producto.producto_id', '=', 'productos.id')
            ->join('compras', 'compra_producto.compra_id', '=', 'compras.id')
            ->select(
                'productos.nombre as producto',
                DB::raw('SUM(compra_producto.cantidad) as total_comprado'),
                DB::raw('SUM(compra_producto.cantidad * compra_producto.precio_compra) as total_gasto')
            )
            ->where('compras.estado', '=', 1); // Solo compras activas

        // Aplicar filtros de rango de fechas
        if (!empty($fechaInicio)) {
            $query->where('compras.fecha_hora', '>=', $fechaInicio);
        }
        if (!empty($fechaFin)) {
            $query->where('compras.fecha_hora', '<=', $fechaFin);
        }

        $compras = $query->groupBy('productos.nombre')->get();

        // Datos para el gráfico
        $labels = $compras->pluck('producto')->toArray();
        $datosCompras = $compras->pluck('total_comprado')->toArray();
        $datosGastos = $compras->pluck('total_gasto')->toArray();

        // Generar el PDF si es solicitado
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.compras.compras_producto_pdf', compact('compras', 'fechaInicio', 'fechaFin', 'labels', 'datosCompras', 'datosGastos'));
            return $pdf->stream('compras_por_producto_' . date('Y-m-d') . '.pdf');
        }

        // Pasar variables a la vista
        return view('reportes.compras.compras_producto', compact('compras', 'fechaInicio', 'fechaFin', 'labels', 'datosCompras', 'datosGastos'));
    }





    public function comprasPorProveedor(Request $request)
    {
        // Filtros de fecha
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

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
        if (!empty($fechaInicio)) {
            $query->whereDate('compras.fecha_hora', '>=', $fechaInicio);
        }
        if (!empty($fechaFin)) {
            $query->whereDate('compras.fecha_hora', '<=', $fechaFin);
        }

        // Obtener los resultados
        $compras = $query->get();

        // Verificar si se solicita un PDF
        if ($request->input('pdf') == '1') {
            $pdf = PDF::loadView('reportes.compras.compras_proveedor_pdf', compact('compras', 'fechaInicio', 'fechaFin'));
            return $pdf->stream('compras_por_proveedor_' . ($fechaInicio ?? 'todos') . '_a_' . ($fechaFin ?? 'todos') . '.pdf');
        }

        // Retornar la vista con los datos de compras por proveedor
        return view('reportes.compras.compras_proveedor', compact('compras', 'fechaInicio', 'fechaFin'));
    }

}
