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
  font-family: 'Font Awesome 5 Free','FontAwesome', 'verdana'!important;
}
</style>

<div id="mywidgetframe">
    <div id="mywidget">
         <h2 class="widget-title ">Istrumentos Rapidos:</h2>
        <div class="auyama_contact_info p-3">
            <?php
              
                if(basename($_SERVER['PHP_SELF'])=="view_caso.php"){
            ?>
            <h3 class="mt-3">Campo personalizado</h3>
            <hr />
            <div class="form-group">

                <div class="input-group mt-3">
                  <div class="input-group-prepend ">
                      <div class="input-group-text input-group"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i></div>
                  </div>
                    <select class="fa form-control custom-select FontAwesome" id="formato" name="formato">
                        <option value="" disabled selected><span>Selecciona tipo  </span></option>
                        <option value="1"> &#xf162; <span>Formato numerico  </span></option>
                        <option value="2"> &#xf272;  <span>Formato fecha </span></option>
                        <option value="3"> &#xf0d6;  <span>Importo </span></option>
                        <option value="4"> &#xf14b;  <span>Informaciòn generica </span></option>
                        <option value="5"> &#xf2bb;  <span>Ubicacciòn </span></option>
                        <option value="6"> &#xf19c;  <span>Inmueble </span></option>
                        <option value="7"> &#xf091;  <span>Objetivo </span></option>
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
                      <div class="input-group-text input-group"><i class="fa fa-thumb-tack" aria-hidden="true"></i></div>
                  </div>
                  <input type="text" class="form-control" id="valor" name="valor" value="" placeholder="Valor">
                </div>
                <div class="input-group mt-3">
                    <button type="button" id="save_options" name="save_options" class="btn btn-primary btn-block mt-2"><i class="fa fa-floppy-o" aria-hidden="true"></i> Crea campo</button>
                </div>


            </div>
            <?php
                }else{
            ?>
                    <a href="add_casos.php"><button type="button" class="btn btn-secondary btn-block"><i class="fa fa-archive" aria-hidden="true"></i> Crea caso</button></a>
                    <a href="view_contact.php"><button type="button" class="btn btn-primary btn-block mt-2"><i class="fa fa-address-book" aria-hidden="true"></i> Crea contacto</button></a>
            <?php
                }
            ?>
        </div>
    </div>
    <div id="opener">
        <i class="fa fa-arrow-circle-right empty" aria-hidden="true"></i>
    </div>
</div>

<div id="back-top" style="display: block;">
	 <span>
        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
	 </span>
</div>
 