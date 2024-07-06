<?php
ob_start();
     session_start();
    
    if(!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 1){
    header('location: ../erro404.php');
  }
?>
<?php if(isset($_SESSION['id'])) { ?>

<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Panel de control de asistencia
        </title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../backend/css/bootstrap.min.css">
        <!----css3---->
        <link rel="stylesheet" href="../../backend/css/custom.css">
        <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!--google material icon-->
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons"
      rel="stylesheet">
      <link rel="shortcut icon" href="../../backend/img/ico.png" />

      
  </head>
  <body>
  
<div class="wrapper">
<div class="body-overlay"></div>
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><img src="../../backend/img/ico.png" class="img-fluid"/><span>Asistencia</span></h3>
            </div>
            <ul class="list-unstyled components">
            <li  class="active">
                    <a href="../administrador/escritorio.php" class="dashboard"><i class="material-icons">dashboard</i><span>Panel</span></a>
                </li>
                
                <li class="dropdown">
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="material-icons">group</i><span>Empleados</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu2">
                        <li>
                            <a href="../empleado/mostrar.php">Mostrar</a>
                        </li>
                        <li>
                            <a href="../empleado/nuevo.php">Nuevo</a>
                        </li>
                        
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="material-icons">business_center</i><span>Cargos</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu3">
                        <li>
                            <a href="../cargo/mostrar.php">Mostrar</a>
                        </li>
                        <li>
                            <a href="../cargo/nuevo.php">Nuevo</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="material-icons">equalizer</i><span>Reportes</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu4">
                        <li>
                            <a href="../reporte/asistencia.php">Asistencia</a>
                        </li>
                        <li>
                            <a href="../reporte/empleados.php">Empleados</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        
        <!-- Page Content  -->
        <div id="content">
        
        <div class="top-navbar">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="d-xl-block d-lg-block d-md-mone d-none">
                        <span class="material-icons">arrow_back_ios</span>
                    </button>
                    
                    <a class="navbar-brand" href="#"> Panel de control </a>
                    
                    <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="material-icons">more_vert</span>
                    </button>

                    <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">   
                            <li class="dropdown nav-item active">
                                <a href="#" class="nav-link" data-toggle="dropdown">
                                   <span class="material-icons">settings</span>
                                   
                               </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="../configuracion/perfil.php">Mi perfil</a>
                                    </li>
                                    <li>
                                        <a href="../configuracion/configuracion.php">Configuracion</a>
                                    </li>
                                    
                                  
                                </ul>
                            </li>
                            
                           
                            <li class="nav-item">
                                <a class="nav-link" href="../configuracion/salir.php">
                                <span class="material-icons">power_settings_new</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
            
            
            <div class="main-content">
            
            <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header">
                                    <div class="icon icon-warning">
                                       <span class="material-icons">group</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <p class="category"><strong>Empleados</strong></p>
                                    <?php
                              require '../../backend/bd/ctconex.php'; 
        $sql = "SELECT COUNT(*) total FROM empleado";
        $result = $connect->query($sql); //$pdo sería el objeto conexión
        $total = $result->fetchColumn();
         ?>
                                    <h3 class="card-title"><?php echo  $total; ?></h3>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <i class="material-icons text-info">info</i>
                                        <a href="../empleado/mostrar.php">Ver informe detallado</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header">
                                    <div class="icon icon-rose">
                                       <span class="material-icons">business_center</span>

                                    </div>
                                </div>
                                <div class="card-content">
                                    <p class="category"><strong>Cargos</strong></p>
                                    <?php
                             
        $sql = "SELECT COUNT(*) total FROM cargo";
        $result = $connect->query($sql); //$pdo sería el objeto conexión
        $total = $result->fetchColumn();
         ?>
                                    <h3 class="card-title"><?php echo  $total; ?></h3>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <i class="material-icons text-info">info</i>
                                        <a href="../cargo/mostrar.php">Ver informe detallado</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header">
                                    <div class="icon icon-success">
                                        <span class="material-icons">
                                            event
                                        </span>

                                    </div>
                                </div>
                                <div class="card-content">
                                    <p class="category"><strong>Entrada</strong></p>

                                    <?php
                             
        $sql = "SELECT COUNT(*) total FROM asis_empl WHERE estado= 'Activo' ";
        $result = $connect->query($sql); //$pdo sería el objeto conexión
        $total = $result->fetchColumn();
         ?>
                                    <h3 class="card-title"><?php echo  $total; ?></h3>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <i class="material-icons text-info">info</i>
                                        <a href="../reporte/asistencia.php">Ver informe detallado</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header">
                                    <div class="icon icon-info">
                                        <span class="material-icons">
                                            event
                                        </span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <p class="category"><strong>Salida</strong></p>
                                    <?php
                             
        $sql = "SELECT COUNT(*) total FROM asis_empl WHERE salida is not null";
        $result = $connect->query($sql); //$pdo sería el objeto conexión
        $total = $result->fetchColumn();
         ?>
                                    <h3 class="card-title"><?php echo  $total; ?></h3>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <i class="material-icons text-info">info</i>
                                        <a href="../reporte/asistencia.php">Ver informe detallado</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="row ">
                        <div class="col-lg-12 col-md-12">
                            <div class="card" style="min-height: 485px">
                                <div class="card-header card-header-text">
                                    <h4 class="card-title">Actividad Reciente</h4>
                                    <p class="category">Nuevos registros de Asistencia</p>
                                </div>
                                <div class="card-content table-responsive">
                                    <?php
                              
 $sentencia = $connect->prepare("SELECT asis_empl.idasem, empleado.idemp, empleado.dniem, empleado.nomem, empleado.apeem, empleado.programa, empleado.coord, asis_empl.ingreso, asis_empl.inbreak, asis_empl.fnbreak, asis_empl.salida, asis_empl.estado FROM asis_empl INNER JOIN empleado ON asis_empl.idemp = empleado.idemp  where asis_empl.estado = 'Activo' order BY asis_empl.idasem DESC limit 15;");
 $sentencia->execute();

$data =  array();
if($sentencia){
  while($r = $sentencia->fetchObject()){
    $data[] = $r;
  }
}
   ?>
   <?php if(count($data)>0):?>
                                    <table class="table table-hover">
                                        <thead class="text-primary">
                                        <th>DNI</th>
                                        <th>Nombres</th>
                                        <th>Programa</th>
                                        <th>Coordinador</th>
                                        <th>Ingreso</th>
                                        <th>Inicio Break</th>
                                        <th>Fin Break</th>
                                        <th>Salida</th>
                                                                </tr></thead>
                                        <tbody>
                                          <?php foreach($data as $g):?>  
                                            <tr>
                                                <td><?php echo  $g->dniem; ?></td>
                                                <td><?php echo  $g->nomem; ?> <?php echo  $g->apeem; ?></td>
                                                <td><?php echo  $g->programa; ?></td>
                                                <td><?php echo  $g->coord; ?></td>
                                                <td><?php echo  $g->ingreso; ?></td>
                                                <td><?php echo  $g->inbreak; ?></td>
                                                <td><?php echo  $g->fnbreak; ?></td>
                                                <td><?php echo  $g->salida; ?></td>

                                            </tr>
                                           <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                   <?php else:?>
<div class="alert alert-warning" style="position: relative;
    margin-top: 14px;
    margin-bottom: 0px;">
            <strong>No se encontro ningun resultado!</strong>
        </div>
    <?php endif; ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                  
                    
                    </div>
                    
        </div>
    </div>


     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="../../backend/js/jquery-3.3.1.slim.min.js"></script>
   <script src="../../backend/js/popper.min.js"></script>
   <script src="../../backend/js/bootstrap.min.js"></script>
   <script src="../../backend/js/jquery-3.3.1.min.js"></script>
  
  
  <script type="text/javascript">
  $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
            
             $('.more-button,.body-overlay').on('click', function () {
                $('#sidebar,.body-overlay').toggleClass('show-nav');
            });
            
        });

</script>


  </body>
  </html>


<?php }else{ 
    header('Location: ../erro404.php');
 } ?>
 <?php ob_end_flush(); ?>     