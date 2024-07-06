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
        <title>Empleados
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
            <li  class="">
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
                
                
                <li class="dropdown" class="active">
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="material-icons">group</i><span>Empleados</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu2">
                        <li class=""> 
                            <a href="../empleado/mostrar.php">Mostrar</a>
                        </li>
                        <li  class="active">
                            <a href="../empleado/nuevo.php">Nuevo</a>
                        </li>
                        
                    </ul>
                </li>

                <li class="dropdown" class="">
                    <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="material-icons">business_center</i><span>Cargos</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu3">
                        <li class="">
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
                                    <h4 class="card-title">Nuevos empleados</h4>
                                    <p class="category">Añadiendo nuevos empleados recientes</p>
                                </div>


                             <div class="card-content table-responsive">
                                  <div class="alert alert-warning">
  <strong>Estimado usuario!</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
</div> 

<form enctype="multipart/form-data" method="POST"  autocomplete="off">
    
    <div class="row">
    <div class="col-md-6 col-lg-6">
     <div class="form-group">
    <label for="email">DNI<span class="text-danger">*</span></label>
    <input type="text" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="14" class="form-control"  name="txtnume" required placeholder="ejm: 7887878">
</div>    
    </div>


    <div class="col-md-6 col-lg-6">
     <div class="form-group">
    <label for="email">Nombres<span class="text-danger">*</span></label>
    <input type="text" onkeypress="return soloLetras(event)" class="form-control"  name="txtnome" required placeholder="ejm: Juan">
</div>    
    </div> 
    </div>


    <div class="row">
    <div class="col-md-6 col-lg-6">
     <div class="form-group">
    <label for="email">Apellidos<span class="text-danger">*</span></label>
    <input type="text" onkeypress="return soloLetras(event)" class="form-control"  name="txtapell" required placeholder="ejm: Perez">
</div>    
    </div>

    <div class="col-md-6 col-lg-6">
     <div class="form-group">
    <label for="email">Programa<span class="text-danger">*</span></label>
    <input type="text" onkeypress="return soloLetras(event)" class="form-control"  name="txtprog" required placeholder="ejm: Triadas">
</div>    
    </div> 
    </div>

    <div class="row">
    <div class="col-md-6 col-lg-6">
     <div class="form-group">
     <label for="email">Coordinador<span class="text-danger">*</span></label>
    <input type="text" onkeypress="return soloLetras(event)" class="form-control"  name="txtcoord" required placeholder="ejm: Pamela Rios">
</div>    
    </div>

    <div class="col-md-6 col-lg-6">
    <div class="form-group">
    <label for="email">Cargo<span class="text-danger">*</span></label>
    <select class="form-control" required name="txtcar">
        <option value="" >----------Seleccione------------</option>
        <?php
        require '../../backend/bd/ctconex.php';  
            $stmt = $connect->prepare("SELECT * FROM cargo where estado='Activo'");
            $stmt->execute();
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    ?>
            <option value="<?php echo $idcar; ?>"><?php echo $nomcar; ?></option>
                    <?php
                }
        ?>
            ?>
                                
    </select>
  
</div>  
  </div> 
    </div>

    <div class="row">
       <div class="col-md-12 col-lg-12">
    <div class="form-group">
    <label for="email">Estado<span class="text-danger">*</span></label>
    <select class="form-control" required name="txtest" readonly>
        <option value="Activo">Activo</option>    
                                
    </select>
  
</div>  
  </div> 
    </div>

      <br>
    <hr>
<div class="form-group">
        <div class="col-sm-12">
            <button name='staddempl' class="btn btn-success text-white">Guardar</button>                       
            <a class="btn btn-danger text-white" href="../empleado/mostrar.php">Cancelar</a>
        </div>
</div>
</form>
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
    include_once '../../backend/php/st_stempl.php'
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

    <script type="text/javascript" src="../../backend/js/letra.js"></script>
  </body>
  </html>





<?php }else{ 
    header('Location: ../erro404.php');
 } ?>
 <?php ob_end_flush(); ?>     