<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//PRUEBA DE FACEBOOK
$route['logoutrestore'] = 'admin/logout';

//ruta inicial
$route['default_controller'] = 'welcome';
//actividades del mes y paginacion de actividades
$route['actividades'] = 'welcome/actividades';
$route['actividades/(:num)'] = 'welcome/actividades/$1';
//actividades de un dia especifico
$route['actividad/(:num)'] = 'welcome/actividad/$1';
//apartado biblioteca controlador welcome parametro conocenos
$route['biblioteca']='welcome/index/conocenos';
//cualquier entrada sera verificado en el welcome
$route['(:any)'] = 'welcome/index/$1';
$route['404_override'] = 'home/error_home';
$route['translate_uri_dashes'] = FALSE;
//para restaura database
$route['restaurador']='restaurador/index';
$route['conexion/error']='restaurador/error';
$route['restaurador/restore']='restaurador/restore';
//busqueda desde pagina principal , paginagion de resultados
$route['buscar']='welcome/buscar';
$route['buscar/(:any)/(:num)/(:num)']='welcome/buscar/$1/$2/$3';
$route['buscar/(:any)/(:num)']='welcome/buscar/$1/$2';

//informacion de ejemplar 
$route['ejemplar/(:num)']='libro/ejemplar/$1';

//controladores de acceso al sitema desde web y movil
$route['verifylogin'] = 'verifylogin';
$route['loginandroid']='verifylogin/loginandroid';

//rutas de restablecimiento de contraseña
$route['validaremail']='validaremail';
$route['validaremail/enviar']='validaremail/enviar';
$route['pass/restablecer/idusuario/(:any)/(:any)']="validaremail/restablecer/$1/$2";

/*home necesario loguearse  
  home/pdf generar pdf,
  home/logo/cambiar  cambiar logo desde modulo de director
  home/ejemplares/barcode/ generar pdf de codigo de barras
  home/ejemplar/barcode   agregar codigo de barras , necesario enviar datos desde la vista
  home/ejemplares/codes vista de codigo 
  home/ejemplares/barcodes/vaciar vaciar lista de codigos de barra
  home/ejemplares/barcodes/eliminar/(:num) eliminar codigo especifico
  home/ejemplar/bloquear bloquear o desbloquear un ejemplar
  */ 
$route['home'] = 'home';
$route['home/pdf'] = 'Pdfs/generar';
$route['home/pdfs/reportar']='Pdfs/reportar';
$route['home/logo/cambiar']='admin/logo';
$route['home/ejemplares/barcode']="Pdfs/generar_bar_codes";
$route['home/ejemplar/barcode']="libro/addbarcode";
$route['home/ejemplares/codes']='home/barcode';
$route['home/ejemplares/barcodes/vaciar']='home/vaciarcb';
$route['home/ejemplares/barcodes/eliminar/(:num)']='home/vaciarcb/$1';
$route['home/ejemplar/bloquear']='libro/bloquedesbloqueo';

//agregar actividad
$route['home/evento'] = 'home/eventonuevo';
//lista de actividades any=año
$route['home/eventos/json/(:any)']='actividad/jsoneventos/$1';
//agregar actividad,necesario utilizar desde vista
$route['home/actividad'] = 'actividad/nueva';
//calendario de actividades
$route['home/actividades'] = 'home/actividades';
//vista agregar fechas a actividad
$route['home/actividad/(:any)'] = 'home/agregarfechas/$1';
//registro de actividades
$route['home/actividad/registro'] = 'actividad/registrar';
//agregar fechas a actividad con angular desde vista agregarfechas
$route['home/actividad/addgafechas'] = 'actividad/addfechas';
//ver datos de actividad mediante su id
$route['home/actividad/ver/(:any)'] = 'home/findevbyid/$1';
//eliminar activiad,necesario ser usuario de difusion
$route['home/evento/borrar/(:any)']='actividad/delete_actividad/$1';
//editar acticidad desde vista ver actividad angular
$route['home/actividad/edit']='actividad/editar';
//eliminar fecha de atividad angular
$route['home/actividaddia/eliminar']='actividad/eliminardia';
//vista de novedades
$route['home/novedades']="home/novedades";
//devuelve la lista de ultimas adquisiciones
$route['home/lista/novedades']="novedades/lista";
//agregar noveded ajax
$route['home/agregar/novedad']="novedades/nueva";
//lista de novedades para mostrar en pagina principal max 15
$route['home/difusion/ennovedades']='novedades/novedades';
//editar datos novedad angular
$route['home/novedad/editar']='novedades/editar';
//eliminar novedad angular
$route['home/novedad/eliminar']='novedades/eliminar';
$route['home/editimg/novedad']='novedades/editarimg';
//vista actividades sin fecha
$route['home/actvidadessinfecha']='home/actsf';
//lista de actividades sin fechas
$route['home/actividadessfecha']='actividad/actividadessf';
//sugerir clasificacion
$route['home/clasificacion/sugerir']='libro/sugerirclasificacion';
//sugerencias de autor y materia en fotmato json
$route['home/autor/sugerir']='libro/sugerirautor';
$route['home/materia/sugerir']='libro/sugerirmateria';
$route['home/materia/agregar']='ficha/aggregarmateria';
//editar clasificacion
$route['home/clasificacion/editar']='ficha/agregarclasificacion';
//contar materias por libro
$route['home/materia/contar_por_materia']='ficha/contar_by_materia';
//agregar autor
$route['home/autor/agregar']='ficha/agregar_autor';
//quitar relacion autorescribio ->eliminar autor de un libro 
$route['home/autor/eliminar']='ficha/quitarautor';
//editar autor
$route['home/autor/editar']='ficha/editarautor';
//obtener total de autores por autor
$route['home/autor/contar_por_autor']='ficha/contar_by_autor';
//baja de autor
$route['home/autor/baja']='ficha/bajaautor';
//baja materia
$route['home/materia/baja']='ficha/bajamateria';
//editar materia
$route['home/materia/editar']='ficha/editarmateria';
//quitar materia
$route['home/materia/eliminar']='ficha/quitarmateria';
$route['home/clasificaciones']='home/clasificaciones';
//rutas para graficas prestamos
$route['home/biblioteca/prestamos']='admin/graficas';
$route['home/estadisticasvisitas/(:any)']='home/estadisticas/$1';
//estadisticas procesos
$route['home/biblioteca/clasificacionlibros']='libro/chart';
$route['home/biblioteca/prestamosporcategoria']='libro/prestamospor_categoria';
//estaditica por numero de adquisicion
$route['home/prestamo/nadqui']='home/estadisticasprestamo_por_nadqui';
//vista de configuracion y contenido (:any)
$route['home/ajustes']='home/configurar';
$route['home/ajustes/(:any)']='home/configurar/$1';
//lista de libros json 
$route['home/libros/json'] = 'libro/json';
//agregar ejemplar
$route['home/libros/guardar'] = 'libro/agregar';
//editar datos libro
$route['home/libros/editar']='libro/editar';
//nadquisicion max
$route['home/libros/id_max'] = 'libro/id_max';
//reservar ejemplar
$route['home/ejemplar/reservar']='libro/reservar';
//vista de ejemplares procesos tecnicos
$route['home/ejemplares']= 'home/paginaejemplares';
//eliminar ejemplar angular
$route['home/libros/baja']='libro/baja';
//cambiar estado de ejemplar:reparacion,disponible
$route['home/ejemplar/estado']='libro/estado';
//vista para agregar libro
$route['home/fichas']='home/paginafichas';
//vista agregar etiquetas
$route['home/etiquetas']='home/paginaetiquetas';
//agregar ejempalr 
$route['home/libros']='home/mostrarlib';
//verifica si existe ficha
$route['home/libro/ficha']='libro/buscaridficha';
//agregar libro des de vista fichas 
$route['home/agregar/libros']='ficha/recibedatoslibros';
//vista grupos agregar grupo
$route['home/grupos']='home/grupos';
$route['home/grupos/agregar/json']='grupo/agregar';
//rutas de prestamo 
$route['home/prestamo/agregar/json']='prestamo/json';
$route['home/registro/agregar/json']='users/agregarvisita';
$route['home/prestamos/devolucion']='home/devolucion';
$route['home/prestamo/renovar']='prestamo/renovar';
$route['home/prestados']='home/enprestamos';
$route['home/ejemplares/bajas']='home/descartados';
//saldar multa
$route['home/devolucion/saldarmulta']='prestamo/saldarmulta';
//obtener monto y multa en vista de usuario prestamos
$route['home/multasmonto']='Users/get_monto_multa';
//prestamos internos
$route['home/autorizarreserva']='reservar/realizarprestamo';
$route['home/prestamo/']='home/prestamo';
$route['home/prestamos/vencidos']='home/pvencidos';
$route['home/prestamos']='prestamo/get_prestamos';
$route['home/prestamosv']='prestamo/get_vencidos';
$route['home/ejemplares/nadqui']='libro/buscar_ejemplar';
$route['home/realizarprestamo']='libro/realizarprestamo';

$route['home/descartados']='libro/get_bajas';

$route['home/respaldar']='Backup_Database/restore';
$route['home/devolucion']='prestamo/devolver';
$route['home/mirespaldo']='Backup_Database/descargar_database';
//route usuarios
$route['home/usuarios'] = 'home/mostrarusuarios';
$route['home/usuario/(:any)']='home/mostarunusuario/$1';
$route['home/usuario/reporte']='pdfs/generarreporte';
$route['home/usuarios/(:num)'] = 'home/mostrarusuarios/$1';
$route['home/usuario'] = 'home/agregauser';
$route['home/usuario/ver/(:any)']='home/findembyid/$1';
$route['home/usuario/desbloqueo/(:any)']='users/desbloquear/$1';
$route['home/usuario/bloqueo']="users/bloquear";
$route['home/usuario/borrar/(:any)']='users/delete/$1';
$route['home/usuario/renovar/(:any)']='users/renovar/$1';

$route['home/prestamo/usuario']='prestamo/find_user_by_id_in_prestamo';
$route['home/prestamointerno/usuario']='prestamo/fin_user_prestamo_inter';

$route['home/empleado/desbloqueo/(:any)']='users/desbloquear/$1';
$route['home/empleados'] = 'home/mostrarusuarios';
$route['home/empleado'] = 'home/agregauser';
$route['home/empleado/ver/(:any)']='home/findembyid/$1';
$route['home/empleado/agregar'] = 'home/agregaemple';
$route['home/empleados/(:num)'] = 'home/mostrarusuarios/$1';

///vista usuario
$route['home/usuario/recomendaciones']='users/get_recomendaciones';
$route['home/usuario/prestamosactuales']='users/get_datos_prestamos_by_usuario';

$route['home/reservas']='home/reserva';
$route['home/reserva/delete']='reservar/delete';
$route['home/get_reservas']='prestamo/get_reservas';

$route['home/empleados/email/json'] = 'users/buscaemail';
$route['home/empleados/cuenta/json'] = 'users/buscacuenta';
$route['home/empleado/editar']='users/editar';

$route['home/escuelas']='home/escuelas';
$route['home/escuela/editar']='escuela/editar';
$route['home/escuela/eliminar']='escuela/eliminar';
$route['home/escuela/agregar']='escuela/agregar';

$route['home/reservar/(:num)']='home/reserva/$1';


$route['home/admin/editar'] = 'admin/editar';
$route['home/admin/editarpas'] = 'admin/editpas';


$route['home/empleados/(:any)'] = 'home/empleados/$1';
$route['home/empleados/(:any)/(:num)'] = 'home/empleados/$1/$2';
$route['home/empleado/edit']='empleado/editar';
$route['home/empleados/(:any)/ver/(:any)']='home/ver/$1/$2';
$route['home/empleados/(:any)/ver/(:any)/editar']='empleado/editaremp/$1/$2';
$route['home/empleados/(:any)/ver/(:any)/editarpas']='empleado/editpass/$1/$2';
$route['home/empleado/borrar/(:any)']='empleado/delete/$1';
$route['home/empleados/(:any)/borrar/(:any)']='empleado/delete/$1/$2';
//preferencias de usaurio
$route['home/usuario/preferencias/(:any)/(:any)']='home/preferencias/$1/$2';
$route['home/buscaretiqueta/access']='libro/access';


$route['home/cargar_prome']="home/cargar_archivo";