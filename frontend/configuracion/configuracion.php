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
        <title>Perfil del administrador
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
        
            
            
                  <!--
                    <li class="dropdown">
                    <a href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="material-icons">event</i><span>Asistencia</span></a>
                    <ul class="collapse list-unstyled menu" id="homeSubmenu1">
                    <li>
                    <a href="../asistencia/mostrar.php">Mostrar</a>
                    </li>
                        
                        
                    </ul>
                </li>  -->
                
                
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
            
                    <div class="row ">
                        <div class="col-lg-12 col-md-12">
                            <div class="card" style="min-height: 485px">
                                <div class="card-header card-header-text">
                                    <h4 class="card-title">Cofiguracion del sistema</h4>
                                    <p class="category">configuracion</p>
                                </div>
                                <hr>
                              

                                <div class="card-content table-responsive">
<?php
 require '../../backend/bd/ctconex.php'; 
 $id = $_SESSION['id'];
 $sentencia = $connect->prepare("SELECT * FROM usuarios  WHERE usuarios.id= '$id';");
 $sentencia->execute();

$data =  array();
if($sentencia){
  while($r = $sentencia->fetchObject()){
    $data[] = $r;
  }
}
   ?>
   <?php if(count($data)>0):?>
        <?php foreach($data as $d):?> 
<form enctype="multipart/form-data" method="POST"  autocomplete="off">
    <div class="row">
    <div class="col-md-12 col-lg-12">
     <div class="form-group">
    <label for="email">Nueva contraseña<span class="text-danger">*</span></label>
    <input type="password"  class="form-control"  name="txtnew" required placeholder="">
    <input type="hidden" value="<?php echo  $d->id; ?>" name="txtidadm">
</div>    
    </div> 

  
    </div>


      <br>
    <hr>
<div class="form-group">
        <div class="col-sm-12">
            <button name='stupdprofpsd' class="btn btn-success text-white">Guardar</button>                       
            <a class="btn btn-danger text-white" href="../administrador/escritorio.php">Cancelar</a>
        </div>
</div>
</form>
<?php endforeach; ?>
  
    <?php else:?>
        
<div class="alert alert-warning" style="position: relative;
    margin-top: 14px;
    margin-bottom: 0px;">
            <strong>No hay datos!</strong>
        </div>
    <?php endif; ?>  
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
  
  
  <script src="../../backend/js/sweetalert.js"></script>
   <?php
    include_once '../../backend/php/st_updpropsd.php'
?>

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