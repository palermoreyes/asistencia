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
        <title>Asistencia
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

           <!-- Data Tables -->
    <link rel="stylesheet" type="text/css" href="../../backend/css/datatable.css">
    <link rel="stylesheet" type="text/css" href="../../backend/css/buttonsdataTables.css">
    <link rel="stylesheet" type="text/css" href="../../backend/css/font.css">
    <link rel="stylesheet" type="text/css" href="../../backend/css/tab.css">

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
                
                        
                    </ul>
                </li>
                
                <li class="dropdown" class="">
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="material-icons">group</i><span>Empleado</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu2">
                        <li class=""> 
                            <a href="../empleado/mostrar.php">Mostrar</a>
                        </li>
                        <li>
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
                                    <h4 class="card-title">Asistencia</h4>
                                    <p class="category">Mostrar asistencias de los empleados</p>
                                    <?php
 require '../../backend/bd/ctconex.php'; 
 $id = $_GET['id'];
 $sentencia = $connect->prepare("SELECT * FROM asistencia  WHERE asistencia.idasi= '$id';");
 $sentencia->execute();

$data =  array();
if($sentencia){
  while($r = $sentencia->fetchObject()){
    $data[] = $r;
  }
}
   ?>
   <?php if(count($data)>0):?>
        <?php foreach($data as $f):?>

            <span class="badge badge-success"><?php echo  $f->nomas; ?></span>


<?php endforeach; ?>
    <?php else:?> 
<div class="alert alert-warning" style="position: relative;
    margin-top: 14px;
    margin-bottom: 0px;">
            <strong>No hay datos!</strong>
        </div>
    <?php endif; ?>
                                </div>
                               <br>



<div class="card-content table-responsive">
  <div class="tab">
    <button class="tablinks" onclick="openCity(event, 'London')">Mostrar</button>
    <button class="tablinks" onclick="openCity(event, 'Paris')">Nuevo</button>
   
  </div>

  <div id="London" class="tabcontent">
    <br>
 <?php 
 $sentencia = $connect->prepare("SELECT asis_empl.idasem, asistencia.idasi, asistencia.nomas, empleado.idemp, empleado.dniem, empleado.nomem, empleado.apeem, empleado.naci, empleado.celu, asis_empl.fere, asis_empl.estado FROM asis_empl INNER JOIN asistencia ON asis_empl.idasi = asistencia.idasi INNER JOIN empleado ON asis_empl.idemp = empleado.idemp WHERE asistencia.idasi= '$id' order BY asis_empl.idasem DESC;");
 $sentencia->execute();

$data =  array();
if($sentencia){
  while($r = $sentencia->fetchObject()){
    $data[] = $r;
  }
}
   ?>
   <?php if(count($data)>0):?>

    <table class="table table-hover" id="example">
                                        <thead class="text-primary">
                                            <tr><th>#</th>
                                            <th>Empleados</th>
                                            <th>Asistencia</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                           
                                        </tr></thead>
                                        <tbody>
                                            <?php foreach($data as $g):?>
                                            <tr>
                                                
                                                <td><?php echo  $g->idasem; ?></td>
                                                
                                                
                                                <div class="row">
                                                  <td><?php echo  $g->nomem; ?>&nbsp;<?php echo  $g->apeem; ?>
                                                      
                                                  </td>
                                                 
                                                </div>
                                                <td><?php echo  $g->nomas; ?></td>
                                                <td><?php echo  $g->fere; ?></td>
                                                <td><?php echo  $g->estado; ?></td>
                                                
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <?php else:?>
<div class="alert alert-warning" style="position: relative;
    margin-top: 14px;
    margin-bottom: 0px;">
            <strong>¡No se encontro ningún resultado!</strong>
        </div>
    <?php endif; ?>
</div>

<div id="Paris" class="tabcontent">
    <br>
  <div class="alert alert-warning">
  <strong>¡Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
</div>

<form enctype="multipart/form-data" method="POST"  autocomplete="off">
    <div class="row">
    
   <div class="col-md-6 col-lg-6">
    <div class="form-group">
    <label for="email">Empleados<span class="text-danger">*</span></label>
    <select class="form-control" required name="txtemp">
        <option value="">----------Seleccione------------</option>  

        <?php
       
            $stmt = $connect->prepare("SELECT * FROM empleado where estado='Activo'");
            $stmt->execute();
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    ?>
            <option value="<?php echo $idemp; ?>"><?php echo $nomem; ?>&nbsp;<?php echo $apeem; ?></option>
                    <?php
                }
        ?>
            ?>  
                                
    </select>
  
</div>  
  </div>

    <div class="col-md-6 col-lg-6">
    <div class="form-group">
    <label for="email">Asistencia<span class="text-danger">*</span></label>
    <select class="form-control" required name="txtasis" readonly>
       
             <?php
            $id = $_GET['id'];
            $stmt = $connect->prepare("SELECT * FROM asistencia where idasi= '$id'");
            $stmt->execute();
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    ?>
            <option value="<?php echo $idasi; ?>"><?php echo $nomas; ?></option>
                    <?php
                }
        ?>
            ?>                 
    </select>
  
</div>  
  </div>


    </div>

    <div class="row">
    <div class="col-md-6 col-lg-6">
    <div class="form-group">
        <label for="fechad">Fecha del Evento<span class="text-danger">*</span></label>
        <input type="datetime-local" id="fechad" class="form-control" name="txtfec" required value="<?php echo date('Y-m-d\TH:i'); ?>" readonly>
    </div>

    </div>

    <div class="col-md-6 col-lg-6">
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
            <button name='staddasem' class="btn btn-success text-white">Guardar</button>                       
            <a class="btn btn-danger text-white" href="../cargo/mostrar.php">Cancelar</a>
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
    </div>

     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="../../backend/js/jquery-3.3.1.slim.min.js"></script>
   <script src="../../backend/js/popper.min.js"></script>
   <script src="../../backend/js/bootstrap.min.js"></script>
   <script src="../../backend/js/jquery-3.3.1.min.js"></script>

   <script src="../../backend/js/sweetalert.js"></script>
   <?php
    include_once '../../backend/php/st_addasistemp.php'
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

<!-- Data Tables -->
    <script type="text/javascript" src="../../backend/js/datatable.js"></script>
    <script type="text/javascript" src="../../backend/js/datatablebuttons.js"></script>
    <script type="text/javascript" src="../../backend/js/jszip.js"></script>
    <script type="text/javascript" src="../../backend/js/pdfmake.js"></script>
    <script type="text/javascript" src="../../backend/js/vfs_fonts.js"></script>
    <script type="text/javascript" src="../../backend/js/buttonshtml5.js"></script>
    <script type="text/javascript" src="../../backend/js/buttonsprint.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
    </script>


<script type="text/javascript" src="../../backend/js/tab.js"></script>
 <script type="text/javascript">
var today = new Date();
  var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
  document.getElementById("fechad").value = date;


          </script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date();
        var year = today.getFullYear();
        var month = ('0' + (today.getMonth() + 1)).slice(-2);
        var day = ('0' + today.getDate()).slice(-2);
        var hours = ('0' + today.getHours()).slice(-2);
        var minutes = ('0' + today.getMinutes()).slice(-2);

        var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
        document.getElementById("fechad").value = formattedDate;
    });
</script>



  </body>
  </html>





<?php }else{ 
    header('Location: ../erro404.php');
 } ?>
 <?php ob_end_flush(); ?>     