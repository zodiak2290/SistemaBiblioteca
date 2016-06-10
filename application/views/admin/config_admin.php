<section style="font-size:13px;">
 <div id="setting" hidden>
	<div class="container" style="margin-top:6%"  >
		<div class="row">
			<div class="col-lg-2 ">
				<div class="panel panel-default" >
				  	<div class="panel-body" >
						 <ul id="listamenu" class="list-group"  >
				          <li class="list-group-item"><a href="<?php echo base_url(); ?>home/ajustes/datos" >Mis datos</a></li>
				          <?php if($rol==8){ ?>
				          	<li class="list-group-item"><a href="<?php echo base_url(); ?>home/ajustes/biblioteca" >Biblioteca</a></li>
				         	<li class="list-group-item"><a href="<?php echo base_url(); ?>home/ajustes/permisos" >	Permisos</a></li>	
				         	<li class="list-group-item"><a href="<?php echo base_url(); ?>home/ajustes/logos" >	Logos</a></li>	
				          	<li class="list-group-item"><a href="<?php echo base_url(); ?>home/ajustes/respaldo" > Respaldar base de datos</a></li>
				         <?php } ?>
				         <!-- <li class="list-group-item"><a href="<?php echo base_url(); ?>home/ajustes/seguridad">Seguridad</a></li>
				          <hr>
				          <li class="list-group-item"><a href="<?php echo base_url(); ?>home/ajustes/bloquearusuarios">Bloquear Usuarios</a></li>
				          <li class="list-group-item"><a href="#">Desactivar mi cuenta</a></li>-->
				        </ul>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<?php $this->load->view('admin/'.$contenido) ?> 
			</div>
		</div>
	</div>
</div>
</section>
<style type="text/css">
	li{
		list-style: none;;
	}
	#listamenu li a:link{
		text-decoration: none;
	}
	#listamenu li a{
		color: black;
	}
		 #imgbio{
	 	
	 background-attachment: fixed;
	 	border: 30px;
	 	border: #c79a2d 4px solid;
	 	box-shadow: -20px 24px 30px rgba(112,76,35,3); 
	 	margin-bottom: 20px;
		background-repeat: no-repeat;

 	}
</style>
<script type="text/javascript">
	 $( document ).ready(function() {
            	$('#setting').fadeIn(400);
        });
</script>