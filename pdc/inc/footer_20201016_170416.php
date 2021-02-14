<div id="footerWrap" class="wrap">
    <footer class="container">
        <nav id="tools">
            <!-- heare some footer stuff -->
        </nav>
    </footer>
</div>

<style>
    .FontAwesome,
    .FontAwesome option {
        font-family: 'Font Awesome 5 Free', 'FontAwesome', 'verdana', serif !important;
    }
</style>

<div id="mywidgetframe">


    <div id="mywidget" class="tab-content">
    <?php
              
              if(basename($_SERVER['PHP_SELF'])=="view_caso.php"){
          ?>
        <ul class="nav nav-tabs" role="tablist">
            <li class="active" ><a href="#opciones" role="tab" data-toggle="tab">Opciones </a></li>
            <li style="margin-left:10px;"><a href="#pagos" role="tab" data-toggle="tab"> Pagos</a></li>
        </ul>
        <?php
              
              }
          ?>
        <h2 class="widget-title ">Instrumentos Rapidos:</h2>
        <div class="tab-pane active auyama_contact_info p-3" id="opciones">
            <?php
              
                if(basename($_SERVER['PHP_SELF'])=="view_caso.php"){
            ?>
            <h3 class="mt-3">Campo personalizado</h3>
            <hr />
            <div class="form-group">

                <div class="input-group mt-3">
                    <div class="input-group-prepend ">
                        <div class="input-group-text input-group"><i class="fa fa-sort-amount-asc"
                                aria-hidden="true"></i></div>
                    </div>
                    <select class="selectpicker" id="formato" name="formato">
                        <option value="" disabled selected><span>Selecciona tipo </span></option>
                        <option value="1" data-icon="fas fa-sort-numeric-down"><span>Formato numerico </span></option>
                        <option value="2" data-icon="far fa-calendar-minus"><span>Formato fecha </span></option>
                        <option value="3" data-icon="fas fa-money-bill"><span>Importo </span></option>
                        <option value="4" data-icon="fas fa-pen-square"><span>Información generica </span></option>
                        <option value="5" data-icon="fas fa-address-card"><span>Ubicacción </span></option>
                        <option value="6" data-icon="fas fa-university"><span>Inmueble </span></option>
                        <option value="7" data-icon="fas fa-trophy"><span>Objetivo </span></option>

                    </select>
                </div>
                <div class="input-group mt-3">
                    <div class="input-group-prepend ">
                        <div class="input-group-text input-group"><i class="fa fa-tags" aria-hidden="true"></i></div>
                    </div>
                    <input type="text" class="form-control" id="tags" name="tags" value="" placeholder="Etiqueta">
                </div>
                <div class="input-group mt-3">
                    <div class="input-group-prepend ">
                        <div class="input-group-text input-group"><i class="fa fa-thumb-tack" aria-hidden="true"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control" id="valor" name="valor" value="" placeholder="Valor">
                </div>
                <div class="input-group mt-3">
                    <button type="button" id="save_options" name="save_options"
                        class="btn btn-primary btn-block mt-2"><i class="fa fa-floppy-o" aria-hidden="true"></i> Crea
                        campo</button>
                </div>


            </div>
            <?php
                }else{
            ?>
            <a href="add_casos.php"><button type="button" class="btn btn-secondary btn-block"><i class="fa fa-archive"
                        aria-hidden="true"></i> Crea caso</button></a>
            <a href="view_contact.php"><button type="button" class="btn btn-primary btn-block mt-2"><i
                        class="fa fa-address-book" aria-hidden="true"></i> Crea contacto</button></a>
            <?php
                }
            ?>
        </div>


        <div class="tab-pane auyama_contact_info p-3" id="pagos">
            <?php
              
                if(basename($_SERVER['PHP_SELF'])=="view_caso.php"){
            ?>
            <h3 class="mt-3">Pago</h3>
            <hr />
            <div class="form-group">

                <div class="input-group mt-3">
                    <div class="input-group-prepend ">
                        <div class="input-group-text input-group"><i class="fa fa-sort-amount-asc"
                                aria-hidden="true"></i></div>
                    </div>
                    <select class="selectpicker fa form-control custom-select FontAwesome" id="tipo_pago" name="tipoPago">
                        <option value="" disabled selected><span>Selecciona tipo </span></option>
                        <option value="pagoCapital"  data-icon="fas fa-credit-card"> <span>    Pago Capital </span> </option>  
                        <option value="otrosPagos"  data-icon="fas fa-money">  <span>   Otros Pagos </span> </option>
                    </select>
                </div>
                <div class="input-group mt-3">
                    <div class="input-group-prepend ">
                        <div class="input-group-text input-group"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                    </div>
                    <input type="date" class="form-control" id="Fecha" name="Fecha" value="">
                </div>
                <div class="input-group mt-3">
                    <div class="input-group-prepend ">
                        <div class="input-group-text input-group"><i class="fa fa-thumb-tack" aria-hidden="true"></i>
                        </div>
                    </div>
                    <input data-type="currency" type="text" class="form-control" id="valorPago" name="valorPago" value=""
                        placeholder="Valor">
                </div>
                <div class="input-group mt-3">
                    <div class="input-group-prepend ">
                        <div class="input-group-text input-group"><i class="fa fa-clipboard" aria-hidden="true"></i></div>
                    </div>
                    <input type="text" class="form-control" id="description" name="description" value="" maxlength="50" placeholder="Descripción">
                </div>
                <div class="input-group mt-3">
                    <button type="button" id="save_pago" name="save_pago"
                        class="btn btn-primary btn-block mt-2"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Agregar Pago</button>
                </div>


            </div>
            <?php
                }else{
            ?>
            <a href="add_casos.php"><button type="button" class="btn btn-secondary btn-block"><i class="fa fa-archive"
                        aria-hidden="true"></i> Crea caso</button></a>
            <a href="view_contact.php"><button type="button" class="btn btn-primary btn-block mt-2"><i
                        class="fa fa-address-book" aria-hidden="true"></i> Crea contacto</button></a>
            <?php
                }
            ?>
        </div>



    </div>
    <div id="opener">
        <i class="fa pulse fa-arrow-circle-right empty pulse" aria-hidden="true"></i>
    </div>
</div>
<div id="back-top" style="display: block;">
    <span>
        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
    </span>
</div>
