<div class="container-fluid maxwidth navbar-bg" style="background-color:#523086 !important;">
  <div id="content" class="wrapper" >  
    <nav class="navbar navbar-expand-md">
    <!-- Brand -->
    <a class="navbar-brand" href="#"><img src="images/logo.png" alt="" height="50"></a>

    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" style="outline:none;">
      <span class="" style="color:white; font-size:1.75rem;">MENÚ</span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav"  >
      <?php  if(  $_SESSION["type"] =="guest" ){ ?>
              <li><a id="casos" href="casos.php" ><i class="fa fa-archive"></i> Casos </a></li>
              <li><a href="dashboard.php"  ><i class="fa fa-bar-chart" aria-hidden="true"></i>Estadisticas</a></li>
        <?php }else{?>
              <li><a id="casos" href="casos.php" ><i class="fa fa-archive"></i> Casos </a></li>
              <li><a href="dashboard.php"  ><i class="fa fa-bar-chart" aria-hidden="true"></i>Estadisticas</a></li>
              <li><a id="contactos" href="contactos.php" ><i class="fa fa-address-book"  ></i> Contacto </a></li>
              <li><a id="calendario" href="calendar.php" ><i class="fa fa-calendar"  ></i> Calendario </a></li>
              <li><a id="ususarios" href="usuarios.php" ><i class="fa fa-users"></i> Usuario </a></li>
              <li><a href="bitacora.php" ><i class="fa fa-list-alt"></i> Bitacora</a></li>
          <?php  if(  $_SESSION["type"] =="admin" ){ ?>
           
            <li><a id="loggin" href="log.php" ><i class="fa fa-list"></i> Log </a></li>
            <li><a id="set-up" href="setup.php"><i class="fa fa-cogs"></i> Setup </a></li>
		  	
          <?php } ?>
              <li><a id="account" href="account.php" ><i class="fa fa-user-circle"></i> Account </a></li>
          <?php  if(  $_SESSION["auth_id"] ==2 ){ ?>
              <li><a id="cuentas" href="crear_cuentas.php" ><i class="fa fa-users"></i>Cuentas </a></li>
          <?php } } ?>
              <li><a href="index.php?logout=true" ><i class="fa fa-sign-out"></i> Exit</a></li>
      </ul>
    </div> 
    </nav>
    </div> 
</div>