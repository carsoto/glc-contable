
$('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
});

$('input[name="entrada[tipo_recibo]"]').on('ifChecked', function() {
	var tipo_seleccionado = $(this).val();
	if(tipo_seleccionado == "link"){
		document.getElementById('recibo_tipo_link').style.display = 'block';
		document.getElementById('recibo_tipo_archivo').style.display = 'none';
		document.getElementById('entrada-tipo-link').style.display = 'block';
		document.getElementById('entrada-tipo-archivo').style.display = 'none';

	}else{
		document.getElementById('recibo_tipo_link').style.display = 'none';
		document.getElementById('recibo_tipo_archivo').style.display = 'block';
		document.getElementById('entrada-tipo-link').style.display = 'none';
		document.getElementById('entrada-tipo-archivo').style.display = 'block';
	}
});

$('input[name="gasto[tipo_recibo]"]').on('ifChecked', function() {
	var tipo_seleccionado = $(this).val();
	if(tipo_seleccionado == "link"){
		document.getElementById('recibo_tipo_link').style.display = 'block';
		document.getElementById('recibo_tipo_archivo').style.display = 'none';
		document.getElementById('gasto-tipo-link').style.display = 'block';
		document.getElementById('gasto-tipo-archivo').style.display = 'none';
		document.getElementById('edit-gasto-tipo-link').style.display = 'block';
		document.getElementById('edit-gasto-tipo-archivo').style.display = 'none';
	}else{
		document.getElementById('recibo_tipo_link').style.display = 'none';
		document.getElementById('recibo_tipo_archivo').style.display = 'block';
		document.getElementById('gasto-tipo-link').style.display = 'none';
		document.getElementById('gasto-tipo-archivo').style.display = 'block';
		document.getElementById('edit-gasto-tipo-link').style.display = 'none';
		document.getElementById('edit-gasto-tipo-archivo').style.display = 'block';
	}
});

function bs_input_file(file_accepted) {
    $(".input-file").before(
        function() {
            if ( ! $(this).prev().hasClass('input-ghost') ) {
                var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0' accept='"+file_accepted+"'>");
                element.attr("name",$(this).attr("name"));
                element.change(function(){
                    element.next(element).find('input').val((element.val()).split('\\').pop());
                });
                $(this).find("button.btn-choose").click(function(){
                    element.click();
                });
                $(this).find("button.btn-reset").click(function(){
                    element.val(null);
                    $(this).parents(".input-file").find('input').val('');
                });
                $(this).find('input').css("cursor","pointer");
                $(this).find('input').mousedown(function() {
                    $(this).parents('.input-file').prev().click();
                    return false;
                });
                return element;
            }
        }
    );
}

bs_input_file('.pdf, .jpeg, .jpg, .png');

$('.datepicker').datepicker({
    language: "es",
    format: 'dd-mm-yyyy',
    orientation: "auto left",
    forceParse: false,
    autoclose: true,
    todayHighlight: true,
    toggleActive: true
});

$("#table-entradas").DataTable({
	"processing": true,
    //"serverSide": true,
    "ajax": "historial-entradas/"+$('#table-entradas').attr('data-charter-id'),
    "columns": [
		{data: 'user', name: 'user'},
		{data: 'monto', name: 'monto'},
		{data: 'comentario', name: 'comentario'},
		{data: 'fecha', name: 'fecha'},
		{data: 'created_at', name: 'created_at'},
        {data: 'action', name: 'action', orderable: false}
    ]
});

$("#tabla_comisiones").DataTable({
	"processing": true,
    ////"serverSide": true,
    "ajax": "comisiones/charters",
    "columns": [
    	{data: "cliente", name: "cliente"},
    	{data: "fecha_inicio", name: "fecha_inicio"},
    	{data: "fecha_fin", name: "fecha_fin"},
    	{data: "yacht", name: "yacht"},
    	{data: "precio_venta", name: "precio_venta"},
    	{data: "deluxe_total", name: "deluxe_total", class: "success"},
    	{data: "deluxe_gastos", name: "deluxe_gastos", class: "warning"},
    	{data: "deluxe_saldo", name: "deluxe_saldo", class: "danger"},
    	/*{data: "aryel_total", name: "aryel_total", class: "success"},
    	{data: "aryel_abono", name: "aryel_abono", class: "warning"},
    	{data: "aryel_saldo", name: "aryel_saldo", class: "danger"},
    	{data: "stephi_total", name: "stephi_total", class: "success"},
		{data: "stephi_abono", name: "stephi_abono", class: "warning"},
		{data: "stephi_saldo", name: "stephi_saldo", class: "danger"},*/
		{data: "global_total", name: "global_total", class: "success"},
		{data: "global_gastos", name: "global_gastos", class: "warning"},
		{data: "global_saldo", name: "global_saldo", class: "danger"},
        {data: 'action', name: 'action', orderable: false}
    ]
});

$("#table-charters-eliminados").DataTable({
	"processing": true,
    //"serverSide": true,
    "ajax": "charters-eliminados",
    "order": [[ 2, "desc" ]],
    "columns": [
    	{data: "usuario", name: "usuario"},
    	{data: "comentario", name: "comentario"},
    	{data: "fecha", name: "fecha"},
    ]
});

$("#table-historial-entradas").DataTable({

	"processing": true,
    //"serverSide": true,
    "ajax": {
	    "url": "historial/entradas",
	    "type": "GET",
	    "data": {charter_id: $('#table-historial-entradas').attr('data-charter-id'), item: $('#table-historial-entradas').attr('data-item')}
  	},
    "order": [[ 3, "DESC" ]],
    "columns": [
    	{data: "usuario", name: "usuario"},
    	{data: "accion", name: "accion"},
    	{data: "comentario", name: "comentario"},
    	{data: "fecha", name: "fecha"},
    ]
});

cargar_gastos('broker');
cargar_gastos('deluxe');
cargar_gastos('operador');
cargar_gastos('apa');
cargar_gastos('other');

/**$("#porcentaje_comision_broker").notify("Debe colocar el charter rate", { className: "danger", clickToHide: true, autoHide: true, autoHideDelay: 1000, });**/
function recalcular_totales(totales){

	$.each(totales, function(key, valores) {

		$.each(valores, function(key1, valores1) {
			
			if(key1 == 'total'){
				console.log('total_'+key + ' = ' + valores1);
				if(key != 'global'){
					document.getElementById('total_'+key).innerHTML = valores1;	
				}

				if(key != 'entradas'){
					document.getElementById('resumen_'+key+'_entrada').innerHTML = valores1;	
				}
			}
			if(key1 == 'gastos'){
				document.getElementById('resumen_'+key+'_salida').innerHTML = valores1;
			}
			if(key1 == 'saldo'){
				if(key != 'global'){
					document.getElementById('total_'+key+'_pendiente').innerHTML = valores1;
				}
				if(key != 'entradas'){
					document.getElementById('resumen_'+key+'_saldo').innerHTML = valores1;
				}
			}
		});
	});
}

function calcular_comision(tipo){
	var charter_rate = 0;
	var charter_neto = 0;
	var porcentaje_broker = 0;
	var comision_broker = 0;
	var costo_deluxe = 0;
	var comision_glc = 0;

	if(tipo == 'crear'){
		charter_rate = document.getElementById('charter_rate').value;
		charter_neto = document.getElementById('charter_neto').value;
		porcentaje_broker = document.getElementById('porcentaje_comision_broker').value;
		comision_broker = document.getElementById('comision_broker').value;
		costo_deluxe = document.getElementById('costo_deluxe').value;

	}else{
		charter_rate = document.getElementById('charter_precio_rate').value;
		charter_neto = document.getElementById('charter_precio_neto').value;
		porcentaje_broker = document.getElementById('charter_porcentaje_comision_broker').value;
		comision_broker = document.getElementById('charter_comision_broker').value;
		costo_deluxe = document.getElementById('charter_costo_deluxe').value;
	}

	comision_glc = charter_rate - charter_neto - comision_broker - costo_deluxe;
	comision_broker = Math.floor(charter_rate*porcentaje_broker)/100;
	
	document.getElementById('comision_broker').value = comision_broker;
	document.getElementById('comision_glc').value = comision_glc;

	document.getElementById('charter_comision_broker').value = comision_broker;
	document.getElementById('charter_comision_glc').value = comision_glc;
}

function agregar_abono_comision(id_comision){
	document.getElementById('id_comision').value = id_comision;
	$("#abonos-comision").modal("toggle");  
}

function agregar_entrada(id_charter){
	document.getElementById('id_charter').value = id_charter;
	$("#nueva-entrada").modal("toggle");  
}

function editar_entrada(id_entrada){
	document.getElementById('id_entrada').value = id_entrada;
	
	$.ajax({
	    url: 'editar-entrada/'+id_entrada,
	    type: 'GET',
        processData: false,
    	contentType: false,
        success: function(response){
        	document.getElementById('charters-id').value = response.entrada.charters_id;
            document.getElementById('entrada-fecha').value = response.entrada.fecha;
            document.getElementById('entrada-monto').value = response.entrada.monto;
            document.getElementById('entrada-comentario').value = response.entrada.comentario;
            document.getElementById('entrada-banco').value = response.entrada.banco;
            document.getElementById('entrada-referencia').value = response.entrada.referencia;
            $('#entrada-tipo-gasto option[value="'+ response.entrada.tipo_gasto_id +'"]').attr("selected", "selected");
            $('#entrada-tipo-r-'+response.entrada.tipo_recibo).iCheck('check');
            document.getElementById('input-entrada-tipo-'+response.entrada.tipo_recibo).value = response.entrada.link_papeleta_pago;
			//console.log(response.entrada.tipo_recibo);
			if(response.entrada.tipo_recibo == "link"){
				document.getElementById('entrada-tipo-link').style.display = 'block';
				document.getElementById('entrada-tipo-archivo').style.display = 'none';
			}else{
				document.getElementById('entrada-tipo-link').style.display = 'none';
				document.getElementById('entrada-tipo-archivo').style.display = 'block';
			}
			recalcular_totales(response.totales);
            $("#editar-entrada").modal("toggle");
        },

        error: function (xhr, ajaxOptions, thrownError) {
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
}

function editar_charter(id_charter){
	document.getElementById('id_charter').value = id_charter;
	var contrato = "";
	$.ajax({
	    url: 'actualizar-charter/'+id_charter,
	    type: 'GET',
        processData: false,
    	contentType: false,
        success: function(response){
			//console.log(response.charter.broker.id);
			document.getElementById('id_charter').value = response.id;
			document.getElementById('charter_yacht').value = response.charter.yacht;
			document.getElementById('charter_yacht_rack').value = response.charter.yacht_rack;
			//document.getElementById('charter_broker').value = response.charter.broker;
			document.getElementById('charter_broker').value = response.charter.broker.id;
			document.getElementById('charter_cliente').value = response.charter.cliente;
			document.getElementById('charter_f_inicio').value = response.charter.fecha_inicio;
			document.getElementById('charter_f_fin').value = response.charter.fecha_fin;
			document.getElementById('charter_precio_rate').value = response.charter.precio_venta;
			document.getElementById('charter_precio_neto').value = response.charter.neto;
			document.getElementById('charter_porcentaje_comision_broker').value = response.charter.porcentaje_comision_broker;
			document.getElementById('charter_comision_broker').value = response.charter.comision_broker;
			document.getElementById('charter_costo_deluxe').value = response.charter.costo_deluxe;
			document.getElementById('charter_comision_glc').value = response.charter.comision_glc;
			document.getElementById('charter_apa').value = response.charter.apa;
			document.getElementById('charter_contrato').value = response.charter.contrato;

			if(response.charter.contrato != "Sin contrato"){
				contrato += '<div class="col-lg-6 col-md-6 col-sm-12">';
	            contrato += '    <div class="form-group">';
	            contrato += '        <label style="font-size: 11px;">REVISAR CONTRATO</label>';
	            contrato += '        <br><a target="_blank" href="../images/charters/'+response.charter.codigo+'/contrato/'+response.charter.contrato+'"><i class="fa fa-paperclip"></i> '+ response.charter.contrato +'</a>';
	            contrato += '    </div>';
	            contrato += '</div>	';
			}
            
			document.getElementById('charter_contrato_view').innerHTML = contrato;
            $("#editarCharter").modal("toggle");
        },


        error: function (xhr, ajaxOptions, thrownError) {
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
}

function historial_abonos_comision(id_comision){

    $('#tabla_hist_abonos_comisiones').DataTable().destroy();

	$("#tabla_hist_abonos_comisiones").DataTable({
		"processing": true,
	    //"serverSide": true,
	    "ajax": "historial-abonos-comisiones/"+id_comision,
	    "columns": [
		   	{data: "user", name: "user"},
		   	{data: "monto", name: "monto"},
		   	{data: "fecha", name: "fecha"},
		   	{data: "comentario", name: "comentario"},
		   	{data: "created_at", name: "created_at"},
		   	{data: "action", name: "action"},
	    ]
	});

	$("#historial-abonos-comision").modal("toggle"); 
}

function agregar_gasto(id_charter, tipo){
	document.getElementById('categoria_gasto').value = tipo;

	if((tipo != 'apa') && (tipo != 'other')){
		document.getElementById('gasto_precio_cliente').style.display = 'none';
	}else{
		document.getElementById('gasto_precio_cliente').style.display = 'block';
	}
	
	$("#nuevo-gasto").modal("toggle");
}

function historial_gasto(id_charter, tipo){
	//console.log($("#table-historial-gastos"));
	$('#tabla_hist_gastos').DataTable().destroy();
	$("#tabla_hist_gastos").DataTable({
		"processing": true,
	    //"serverSide": true,
	    "ajax": "historial/gastos/"+tipo+"/"+id_charter,
	    "order": [[ 3, "DESC" ]],
	    "columns": [
	    	{data: "usuario", name: "usuario"},
	    	{data: "accion", name: "accion"},
	    	{data: "comentario", name: "comentario"},
	    	{data: "fecha", name: "fecha"},
	    ]
	});

	$("#historial-gastos").modal("toggle");
}

function cargar_gastos(tipo){
	$('#table-'+tipo).DataTable().destroy();
	
	if((tipo != 'apa') && (tipo != 'other')){
		$("#table-"+tipo).DataTable({
			"processing": true,
		    "serverSide": true,
		    "ajax": "gastos/"+tipo+"/"+$('#table-'+tipo).attr('data-charter-id'),
		    "order": [[ 3, "DESC" ]],
		    "columns": [
				{data: "registrado_por", name: "registrado_por"},
				{data: "monto", name: "monto"},
				{data: "comentario", name: "comentario"},
				{data: "fecha", name: "fecha"},
				{data: 'action', name: 'action', orderable: false}
		    ]
		});	
	}else{
		$("#table-"+tipo).DataTable({
			"processing": true,
		    "serverSide": true,
		    "ajax": "gastos/"+tipo+"/"+$('#table-'+tipo).attr('data-charter-id'),
		    "order": [[ 3, "DESC" ]],
		    "columns": [
				{data: "registrado_por", name: "registrado_por"}, 
				{data: "comentario", name: "comentario"}, 
				{data: "precio_cliente", name: "precio_cliente"}, 
				{data: "neto", name: "neto"}, 
				{data: "ganancia", name: "ganancia"}, 
				{data: "fecha", name: "fecha"}, 
				{data: 'action', name: 'action', orderable: false}
		    ]
		});
	}
	$('#table-'+tipo).DataTable().ajax.reload();
}

function tipoNumeros(e){
	var key = window.event ? e.which : e.keyCode
	return (key == 46 || key >= 48 && key <= 57);
}

function eliminar_charter(id_charter){
	swal({		        
		title: "¿Está seguro?",
		text: "Una vez eliminado, no podrá recuperar su información!",
		icon: "error",
	    showCancelButton: true,
	    confirmButtonColor: '#DD4B39',
	    cancelButtonColor: '#00C0EF',
	    buttons: ["Cancelar", true],
	    closeOnConfirm: false
	}).then(function(isConfirm) {
	    if (isConfirm) {
			$.ajax({
	           	url: 'eliminar-charter/'+id_charter,
	            dataType: "JSON",
	            type: 'GET',
	            success: function (response) {
	            	if(response.status == 'success'){
	            		swal("Hecho!", response.msg, response.status);
	        			$("#tabla_comisiones").DataTable().ajax.reload();
	        			$("#tabla_charters").DataTable().ajax.reload();
	        			$("#table-charters-eliminados").DataTable().ajax.reload();
	            	}else{
	            		swal("Ocurrió un error!", response.msg, "error");
	            	}
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	            }
	        });
	    }
	});
}

function eliminar_entrada(id_entrada){
	swal({		        
		title: "¿Está seguro?",
		text: "Una vez eliminada, no podrá recuperar su información!",
		icon: "error",
	    showCancelButton: true,
	    confirmButtonColor: '#DD4B39',
	    cancelButtonColor: '#00C0EF',
	    buttons: ["Cancelar", true],
	    closeOnConfirm: false
	}).then(function(isConfirm) {
	    if (isConfirm) {
			$.ajax({
	           	url: 'eliminar-entrada/'+id_entrada,
	            dataType: "JSON",
	            type: 'GET',
	            success: function (response) {
	            	if(response.status == 'success'){
	            		recalcular_totales(response.totales);
	            		swal("Hecho!", response.msg, response.status);
	            		$("#table-entradas").DataTable().ajax.reload();
	        			/*$("#tabla_comisiones").DataTable().ajax.reload();
	        			$("#table-entradas").DataTable().ajax.reload();*/
	            	}else{
	            		swal("Ocurrió un error!", response.msg, "error");
	            	}
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	            }
	        });
	    }
	});
}

function editar_gasto(gasto_id, tipo){
	document.getElementById('id_gasto').value = gasto_id;
	document.getElementById('categoria_gasto').value = tipo;
	
	if((tipo != 'apa') && (tipo != 'other')){
		document.getElementById('edit_gasto_precio_cliente').style.display = 'none';
	}else{
		document.getElementById('edit_gasto_precio_cliente').style.display = 'block';
	}

	$.ajax({
	    url: 'editar-gasto/'+gasto_id,
	    type: 'GET',
        processData: false,
    	contentType: false,
        success: function(response){

			document.getElementById("gasto_fecha").value = response.gasto.fecha;
			document.getElementById("gasto_razon_social").value = response.gasto.razon_social;
			document.getElementById("gasto_monto_precio_cliente").value = response.gasto.precio_cliente;
			document.getElementById("gasto_monto_neto").value = response.gasto.neto;
			document.getElementById("gasto_banco").value = response.gasto.banco;
			document.getElementById("gasto_referencia").value = response.gasto.referencia;
			document.getElementById("gasto_comentario").value = response.gasto.comentario;
			document.getElementById("tipo_gasto_id").value = response.gasto.tipo_gasto_id;
			$('#edit-gasto-tipo-r-'+response.gasto.tipo_recibo).iCheck('check');
			document.getElementById('edit-input-gasto-tipo-'+response.gasto.tipo_recibo).value = response.gasto.link_papeleta_pago;

			if(response.gasto.tipo_recibo == "link"){
				document.getElementById('edit-gasto-tipo-link').style.display = 'block';
				document.getElementById('edit-gasto-tipo-archivo').style.display = 'none';
			}else{
				document.getElementById('edit-gasto-tipo-link').style.display = 'none';
				document.getElementById('edit-gasto-tipo-archivo').style.display = 'block';
			}

			recalcular_totales(response.totales);

            $("#editar-gasto").modal("toggle");
        },


        error: function (xhr, ajaxOptions, thrownError) {
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
}

function eliminar_gasto(gasto_id, tipo){
	swal({		        
		title: "¿Está seguro?",
		text: "Una vez eliminada, no podrá recuperar su información!",
		icon: "error",
	    showCancelButton: true,
	    confirmButtonColor: '#DD4B39',
	    cancelButtonColor: '#00C0EF',
	    buttons: ["Cancelar", true],
	    closeOnConfirm: false
	}).then(function(isConfirm) {
	    if (isConfirm) {
			$.ajax({
	           	url: 'eliminar-gasto/'+gasto_id,
	            dataType: "JSON",
	            type: 'GET',
	            success: function (response) {
	            	if(response.status == 'success'){
	            		swal("Hecho!", response.msg, response.status);
	            		$("#table-"+tipo).DataTable().ajax.reload();
	        			recalcular_totales(response.totales);
	            	}else{
	            		swal("Ocurrió un error!", response.msg, "error");
	            	}
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	            }
	        });
	    }
	});
}

$("#nueva-entrada-form").on('submit', function(e){
	e.preventDefault();
    var monto = document.getElementById('monto_entrada').value;
    if(monto == ""){
    	$("#monto_entrada").notify("Debe colocar el monto de entrada", { className: "error", clickToHide: true, autoHide: true, autoHideDelay: 3000, });
    }else{
    	$.ajax({
		    url: 'crear-entrada-charter',
		    type: 'POST',
	        data: new FormData(this),
	        processData: false,
	    	contentType: false,

	        beforeSend: function(){
	            $('.submitBtn').attr("disabled","disabled");
	            $('#nueva-entrada-form').css("opacity",".5");
	        },

	        success: function(response){
	        	$('#nueva-entrada-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");

	            if(response.status == 'success'){
	            	$('#nueva-entrada-form')[0].reset();
	            	$('#nueva-entrada').modal('toggle');
	            	$('#table-entradas').DataTable().ajax.reload();
	            	$('#table-historial-entradas').DataTable().ajax.reload();
		            swal("Hecho!", response.msg, "success");

		            recalcular_totales(response.totales);
		        }else{
		            swal("Ocurrió un error!", response.msg, "error");
		        }
	        },

	        error: function (xhr, ajaxOptions, thrownError) {
	        	$('#nueva-entrada-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");
		        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
		    }
	    });
    }
});

$("#actualizar-entrada-form").on('submit', function(e){
	e.preventDefault();
    var monto = document.getElementById('entrada-monto').value;
    if(monto == ""){
    	$("#entrada-monto").notify("Debe colocar el monto de entrada", { className: "error", clickToHide: true, autoHide: true, autoHideDelay: 3000, });
    }else{
    	$.ajax({
		    url: 'actualizar-entrada-charter',
		    type: 'POST',
	        data: new FormData(this),
	        processData: false,
	    	contentType: false,

	        beforeSend: function(){
	            $('.submitBtn').attr("disabled","disabled");
	            $('#actualizar-entrada-form').css("opacity",".5");
	        },

	        success: function(response){
	            if(response.status == 'success'){
	            	$('#actualizar-entrada-form')[0].reset();
	            	$('#editar-entrada').modal('toggle');
	            	$('#table-entradas').DataTable().ajax.reload();
		            swal("Hecho!", response.msg, "success");
		        }else{
		        	$('#actualizar-entrada-form').css("opacity","");
	            	$(".submitBtn").removeAttr("disabled");
		            swal("Ocurrió un error!", response.msg, "error");
		        }

		        recalcular_totales(response.totales);

	            $('#actualizar-entrada-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");
	        },

	        error: function (xhr, ajaxOptions, thrownError) {
	        	$('#actualizar-entrada-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");
		        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
		    }
	    });
    }
});

$("#registrar-charter-form").on('submit', function(e){
    e.preventDefault();
    
    $.ajax({
	    url: 'crear-charter',
	    type: 'POST',
        data: new FormData(this),
        processData: false,
    	contentType: false,

        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#registrar-charter-form').css("opacity",".5");
        },

        success: function(response){

            if(response.status == 'success'){
            	$('#registrar-charter-form')[0].reset();
            	$('#registrarCharter').modal('toggle');
            	$('#tabla_comisiones').DataTable().ajax.reload();
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#registrar-charter-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

            $('#registrar-charter-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#registrar-charter-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

$("#actualizar-charter-form").on('submit', function(e){
    e.preventDefault();
    
    $.ajax({
	    url: 'actualizar-charter',
	    type: 'POST',
        data: new FormData(this),
        processData: false,
    	contentType: false,

        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#actualizar-charter-form').css("opacity",".5");
        },

        success: function(response){

            if(response.status == 'success'){
            	$('#actualizar-charter-form')[0].reset();
            	$('#editarCharter').modal('toggle');
            	$('#tabla_comisiones').DataTable().ajax.reload();
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#actualizar-charter-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

            $('#actualizar-charter-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#actualizar-charter-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

$("#nuevo-abono-comision-form").on('submit', function(e){
	e.preventDefault();
    
    $.ajax({
	    url: 'crear-abono-comision',
	    type: 'POST',
        data: new FormData(this),
        processData: false,
    	contentType: false,

        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#nuevo-abono-comision-form').css("opacity",".5");
        },

        success: function(response){

            if(response.status == 'success'){
            	$('#nuevo-abono-comision-form')[0].reset();
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#nuevo-abono-comision-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

            $('#nuevo-abono-comision-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");

			document.getElementById("abonado_"+response.id_comision).innerHTML = response.abonado;
			document.getElementById("saldo_"+response.id_comision).innerHTML = response.saldo;
			document.getElementById("fecha_ult_abono_"+response.id_comision).innerHTML = response.fecha_ult_abono;
			//document.getElementById("total_gastos").innerHTML = response.totales.global.gastos;

			document.getElementById("resumen_gastos_comision_"+response.id_comision).innerHTML = response.abonado;
			document.getElementById("resumen_saldo_comision_"+response.id_comision).innerHTML = response.saldo;

			/*$.each(response.totales.salidas, function(key, salida) {
				document.getElementById("resumen_gastos_"+key).innerHTML = salida.gastos;
				document.getElementById("resumen_saldo_"+key).innerHTML = salida.saldo;
			});

			document.getElementById("resumen_gastos_total").innerHTML = response.totales.global.gastos;
			document.getElementById("resumen_saldo_total").innerHTML = response.totales.global.saldo;*/
			
			recalcular_totales(response.totales);

			$("#abonos-comision").modal("toggle");
        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#nuevo-abono-comision-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

$("#nuevo-gasto-form").on('submit', function(e){
	e.preventDefault();
	var tipo = document.getElementById('categoria_gasto').value;
    $.ajax({
	    url: 'crear-gasto',
	    type: 'POST',
        data: new FormData(this),
        processData: false,
    	contentType: false,

        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#nuevo-gasto-form').css("opacity",".5");
        },

        success: function(response){

            if(response.status == 'success'){
            	$('#nuevo-gasto-form')[0].reset();
            	cargar_gastos(tipo);
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#nuevo-gasto-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

            $('#nuevo-gasto-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");

            /*document.getElementById("salidas_gasto_"+response.id_gasto).innerHTML = response.r_gastos;
			document.getElementById("salidas_saldo_"+response.id_gasto).innerHTML = response.r_saldo;
			document.getElementById("total_gastos").innerHTML = response.totales.global.gastos;

			$.each(response.totales.salidas, function(key, salida) {
				document.getElementById("resumen_gastos_"+key).innerHTML = salida.gastos;
				document.getElementById("resumen_saldo_"+key).innerHTML = salida.saldo;
			});

			document.getElementById("resumen_gastos_total").innerHTML = response.totales.global.gastos;
			document.getElementById("resumen_saldo_total").innerHTML = response.totales.global.saldo;***/
			recalcular_totales(response.totales);

			$("#nuevo-gasto").modal("toggle");

        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#nuevo-gasto-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

$("#editar-gasto-form").on('submit', function(e){
	e.preventDefault();
	var tipo = document.getElementById('categoria_gasto').value;

    $.ajax({
	    url: 'actualizar-gasto',
	    type: 'POST',
        data: new FormData(this),
        processData: false,
    	contentType: false,

        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#editar-gasto-form').css("opacity",".5");
        },

        success: function(response){

            if(response.status == 'success'){
            	$('#editar-gasto-form')[0].reset();
            	cargar_gastos(tipo);
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#editar-gasto-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

            $('#editar-gasto-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");

            /*document.getElementById("salidas_gasto_"+response.id_gasto).innerHTML = response.r_gastos;
			document.getElementById("salidas_saldo_"+response.id_gasto).innerHTML = response.r_saldo;
			document.getElementById("total_gastos").innerHTML = response.totales.global.gastos;

			$.each(response.totales.salidas, function(key, salida) {
				document.getElementById("resumen_gastos_"+key).innerHTML = salida.gastos;
				document.getElementById("resumen_saldo_"+key).innerHTML = salida.saldo;
			});

			document.getElementById("resumen_gastos_total").innerHTML = response.totales.global.gastos;
			document.getElementById("resumen_saldo_total").innerHTML = response.totales.global.saldo;***/
			recalcular_totales(response.totales);

			$("#editar-gasto").modal("toggle");

        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#editar-gasto-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

function eliminar_abono_comision(id_comision, id_abono){

	swal({		        
		title: "¿Está seguro?",
		text: "Una vez eliminado, no podrá recuperar su información!",
		icon: "error",
	    showCancelButton: true,
	    confirmButtonColor: '#DD4B39',
	    cancelButtonColor: '#00C0EF',
	    buttons: ["Cancelar", true],
	    closeOnConfirm: false
	}).then(function(isConfirm) {
	    if (isConfirm) {
			$.ajax({
	           	url: 'eliminar-abono/'+id_comision+'/'+id_abono,
	            dataType: "JSON",
	            type: 'GET',
	            success: function (response) {
	            	if(response.status == 'success'){
	            		swal("Hecho!", response.msg, response.status);
	        			$('#tabla_hist_abonos_comisiones').DataTable().ajax.reload();
						document.getElementById("abonado_"+id_comision).innerHTML = response.abonado;
						document.getElementById("saldo_"+id_comision).innerHTML = response.saldo;
						document.getElementById("fecha_ult_abono_"+id_comision).innerHTML = '-';

						document.getElementById("resumen_gastos_comision_"+id_comision).innerHTML = response.abonado;
						document.getElementById("resumen_saldo_comision_"+id_comision).innerHTML = response.saldo;

	            	}else{
	            		swal("Ocurrió un error!", response.msg, "error");
	            	}
	            	recalcular_totales(response.totales);
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	            }
	        });
	    }
	});
}

function historial_acciones_abonos(charter_id, item){
	$("#table-historial-comisiones").DataTable().destroy();

	$("#table-historial-comisiones").DataTable({

		"processing": true,
	    //"serverSide": true,
	    "ajax": {
		    "url": "historial/comisiones",
		    "type": "GET",
		    "data": {charter_id: charter_id, item: item}
	  	},
	    "order": [[ 3, "DESC" ]],
	    "columns": [
	    	{data: "usuario", name: "usuario"},
	    	{data: "accion", name: "accion"},
	    	{data: "comentario", name: "comentario"},
	    	{data: "fecha", name: "fecha"},
	    ]
	});

	$("#historial-comisiones").modal("toggle"); 
}

$("#tabla_pedidos").DataTable({
	"processing": true,
    //"serverSide": true,
    "ajax": {
	    "url": "pedidos/dashboard",
	    "type": "GET",
  	},
    "order": [[ 1, "ASC" ]],
    "columns": [
    	{data: "fecha", name: "fecha"},
		{data: "compania", name: "compania"},
		{data: "solicitante", name: "solicitante"},
		{data: "f_inicio", name: "f_inicio"},
		{data: "f_fin", name: "f_fin"},
		{data: "prox_seguimiento", name: "prox_seguimiento"},
		{data: "status", render: function ( data, type, row ) {
    		if(data.toUpperCase() == 'ACTIVO'){
    			return '<span style="font-size: 11px;" class="label label-success">'+ data +'</span>';
    		}
    		else if(data.toUpperCase() == 'VENDIDO'){
				return '<span style="font-size: 11px;" class="label label-primary">'+ data +'</span>';
			}else{
				return '<span style="font-size: 11px;" class="label label-danger">'+ data +'</span>';
			}
		}},
		{data: "action", name: "action", orderable: false},
    ]
});

function historial_status_pedidos(status){
	
	document.getElementById("hist-estatus-pedidos-title").innerHTML = "PEDIDOS "+status.toUpperCase();
	
	/*$("#tabla_pedidos_por_estatus").DataTable().destroy();
	
	$("#tabla_pedidos_por_estatus").DataTable({
		"processing": true,
	    //"serverSide": true,
	    "ajax": {
		    "url": "pedidos/dashboard/"+status.toUpperCase(),
		    "type": "GET",
	  	},
	    "order": [[ 5, "DESC" ]],
	    "columns": [
	    	{data: "fecha", name: "fecha"},
			{data: "compania", name: "compania"},
			{data: "solicitante", name: "solicitante"},
			{data: "f_inicio", name: "f_inicio"},
			{data: "f_fin", name: "f_fin"},
			{data: "prox_seguimiento", name: "prox_seguimiento"},
			{data: "status", render: function ( data, type, row ) {
				if(status.toUpperCase() == 'VENDIDO'){
					return '<span style="font-size: 11px;" class="label label-primary">'+ data +'</span>';
				}else{
					return '<span style="font-size: 11px;" class="label label-danger">'+ data +'</span>';
				}
	    		
			}},
			{data: "action", name: "action", orderable: false},
	    ]
	});

	$("#estatus-pedidos").modal("toggle");**/ 
}

function historial_pedidos(){

	$("#table-pedidos-eliminados").DataTable().destroy();

	$("#table-pedidos-eliminados").DataTable({
		"processing": true,
	    //"serverSide": true,
	    "ajax": "pedidos/eliminados",
	    "order": [[ 2, "desc" ]],
	    "columns": [
	    	{data: "usuario", name: "usuario"},
	    	{data: "comentario", name: "comentario"},
	    	{data: "fecha", name: "fecha"},
	    	{data: "hora", name: "hora"},
	    ]
	});

	$("#historial-pedidos").modal("toggle");
}

function eliminar_pedido(id_pedido){
	swal({		        
		title: "¿Está seguro?",
		text: "Una vez eliminado, no podrá recuperar su información!",
		icon: "error",
	    showCancelButton: true,
	    confirmButtonColor: '#DD4B39',
	    cancelButtonColor: '#00C0EF',
	    buttons: ["Cancelar", true],
	    closeOnConfirm: false
	}).then(function(isConfirm) {
	    if (isConfirm) {
			$.ajax({
	           	url: 'pedidos/eliminar-pedido/'+id_pedido,
	            dataType: "JSON",
	            type: 'GET',
	            success: function (response) {
	            	if(response.status == 'success'){
	            		swal("Hecho!", response.msg, response.status);
	        			$("#tabla_pedidos").DataTable().ajax.reload();
						//$("#tabla_pedidos_por_estatus").DataTable().ajax.reload();
	            	}else{
	            		swal("Ocurrió un error!", response.msg, "error");
	            	}
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	            }
	        });
	    }
	});
}

$("#registrar-pedido-form").on('submit', function(e){
	e.preventDefault();

    $.ajax({
	    url: 'pedidos/registrar-pedido',
	    type: 'POST',
        data: new FormData(this),
        processData: false,
    	contentType: false,

        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#registrar-pedido-form').css("opacity",".5");
        },

        success: function(response){

            if(response.status == 'success'){
            	$('#registrar-pedido-form')[0].reset();
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#registrar-pedido-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

	        $('#tabla_pedidos').DataTable().ajax.reload();
            $('#registrar-pedido-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");

			$("#registrarPedido").modal("toggle");
        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#registrar-pedido-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

/*onSelect: function(dateText) {
    console.log("Selected date: " + dateText + "; input's current value: " + this.value);
}*/

function diasEnUnMes(mes, anyo) {
	return new Date(anyo, mes, 0).getDate();
}

function next_follow(date, arg) {
	var d = date.split('-');
	var dias_mes = diasEnUnMes(parseInt(d[1]), parseInt(d[2]));
	if(d[0] == 30){
		if(dias_mes == 30){
			d[0] = "02";
			d[1] = parseInt(d[1]) + 1;
		}else{
			d[0] = "01";
			d[1] = parseInt(d[1]) + 1;	
		}
	}else if(d[0] == 31){
		d[0] = "02";
		d[1] = parseInt(d[1]) + 1;
	}else{
		d[0] = parseInt(d[0]) + arg;
	}
	var dd = d[0]+'-'+d[1]+'-'+d[2];
    $('#create_next_follow').datepicker('setDate', dd);
}

$('#create_pedido_fecha').on("change", function() {
    next_follow($(this).val(), 2);
});

function editar_pedido(id_pedido){

	$.ajax({
	    url: 'pedidos/editar-pedido/'+id_pedido,
	    type: 'GET',
        processData: false,
    	contentType: false,
        success: function(response){
			document.getElementById("pedido_id").value = response.id;
			document.getElementById("pedido_fecha").value = response.pedido.fecha;
			$('#pedido_contacto option[value="'+ response.pedido.tipo_contacto_id +'"]').attr("selected", "selected");
			document.getElementById("pedido_company").value = response.pedido.compania;
			document.getElementById("pedido_name").value = response.pedido.solicitante;
			document.getElementById("pedido_phone").value = response.pedido.telefono;
			document.getElementById("pedido_email").value = response.pedido.email;
			document.getElementById("pedido_date_start").value = response.pedido.f_inicio;
			document.getElementById("pedido_date_finish").value = response.pedido.f_fin;
			document.getElementById("pedido_details").innerHTML = response.pedido.detalles;
			$('#pedido_status option[value="'+ response.pedido.pedidos_status_id +'"]').attr("selected", "selected");

            $("#editarPedido").modal('toggle');
        },

        error: function (xhr, ajaxOptions, thrownError) {
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
}

$("#actualizar-pedido-form").on('submit', function(e){
	e.preventDefault();

    $.ajax({
	    url: 'pedidos/actualizar-pedido',
	    type: 'POST',
        data: new FormData(this),
        processData: false,
    	contentType: false,

        beforeSend: function(){
            $('.submitBtn').attr("disabled","disabled");
            $('#actualizar-pedido-form').css("opacity",".5");
        },

        success: function(response){
            
            if(response.status == 'success'){
            	$('#actualizar-pedido-form')[0].reset();
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#actualizar-pedido-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

	        $('#tabla_pedidos').DataTable().ajax.reload();
            $('#actualizar-pedido-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");

			$("#editarPedido").modal("toggle");
        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#actualizar-pedido-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

function seguimientos_pedido(id_pedido){

	document.getElementById('seguimiento_pedido_id').value = id_pedido;

	$("#tabla_seguimientos").DataTable().destroy();

	$("#tabla_seguimientos").DataTable({
		"processing": true,
	    //"serverSide": true,
	    "ajax": "pedidos/seguimientos/"+id_pedido,
	    "columns": [
	    	{data: "fecha", name: "fecha"},
	    	{data: "usuario", name: "usuario"},
	    	{data: "comentario", name: "comentario"},
	    	{data: "action", name: "action"},
	    ]
	});

	$("#seguimientosPedido").modal("toggle");
}

$("#seguimiento-pedido-form").on('submit', function(e){
	e.preventDefault();

    if($(".seguimientoBtn").hasClass("btn-success")){
	    $.ajax({
		    url: 'pedidos/registrar-seguimiento',
		    type: 'POST',
	        data: new FormData(this),
	        processData: false,
	    	contentType: false,

	        beforeSend: function(){
	            $('.submitBtn').attr("disabled","disabled");
	            $('#seguimiento-pedido-form').css("opacity",".5");
	        },

	        success: function(response){
	            
	            if(response.status == 'success'){
	            	$('#seguimiento-pedido-form')[0].reset();
		            swal("Hecho!", response.msg, "success");
		        }else{
		        	$('#seguimiento-pedido-form').css("opacity","");
	            	$(".submitBtn").removeAttr("disabled");
		            swal("Ocurrió un error!", response.msg, "error");
		        }

		        $("#tabla_seguimientos").DataTable().ajax.reload();
	            $('#seguimiento-pedido-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");

				//$("#seguimientosPedido").modal("toggle");
	        },

	        error: function (xhr, ajaxOptions, thrownError) {
	        	$('#seguimiento-pedido-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");
		        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
		    }
	    });	
    }else{
		$.ajax({
		    url: 'pedidos/actualizar-seguimiento',
		    type: 'POST',
	        data: new FormData(this),
	        processData: false,
	    	contentType: false,

	        beforeSend: function(){
	            $('.submitBtn').attr("disabled","disabled");
	            $('#seguimiento-pedido-form').css("opacity",".5");
	        },

	        success: function(response){
	            
	            if(response.status == 'success'){
	            	$('#seguimiento-pedido-form')[0].reset();
		            swal("Hecho!", response.msg, "success");
		        }else{
		        	$('#seguimiento-pedido-form').css("opacity","");
	            	$(".submitBtn").removeAttr("disabled");
		            swal("Ocurrió un error!", response.msg, "error");
		        }

		        $("#tabla_seguimientos").DataTable().ajax.reload();
	            $('#seguimiento-pedido-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");

				$(".seguimientoBtn").removeClass("btn-primary");
				$(".seguimientoBtn").addClass("btn-success");
				var items = document.getElementsByClassName("seguimientoBtn"), i, len;

				for (i = 0, len = items.length; i < len; i++) {
					items[i].innerHTML = "<i class='fa fa-plus'></i> ADD";
				}
				document.getElementById("details_seguimiento").innerHTML = "";
				document.getElementById('seguimiento_id').value = "";
	        },

	        error: function (xhr, ajaxOptions, thrownError) {
	        	$('#seguimiento-pedido-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");
		        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
		    }
	    });
  
    }
    
});

function editar_seguimiento(id_seguimiento){
	$.ajax({
	    url: 'pedidos/editar-seguimiento/'+id_seguimiento,
	    type: 'GET',
        processData: false,
    	contentType: false,
        success: function(response){
			document.getElementById("fecha_seguimiento").value = response.seguimiento.fecha;
			document.getElementById("details_seguimiento").innerHTML = response.seguimiento.comentario;
			document.getElementById('seguimiento_id').value = id_seguimiento;
			$(".seguimientoBtn").removeClass("btn-success");
			$(".seguimientoBtn").addClass("btn-primary");

			var items = document.getElementsByClassName("seguimientoBtn"), i, len;

			for (i = 0, len = items.length; i < len; i++) {
				items[i].innerHTML = "<i class='fa fa-pencil'></i> UPDATE";
			}
        },

        error: function (xhr, ajaxOptions, thrownError) {
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
}

$("#tabla_charters").DataTable({
	"processing": true,
	"ajax": "charters-dashboard",
	"columns": [
    	{data: "codigo", name: "codigo"},
    	{data: "broker", name: "broker"},
    	{data: "cliente", name: "cliente"},
    	{data: "yacht", name: "yacht"},
    	{data: "fecha_inicio", name: "fecha_inicio"},
    	{data: "fecha_fin", name: "fecha_fin"},
    	{data: "programa", name: "programa"},
    	{data: "status", render: function ( data, type, row ) {
    		if(data == 'EJECUTADO'){
				return '<span style="font-size: 11px;" class="label label-success">'+ data +'</span>';
    		}else if(data == 'ACTIVO'){
    			return '<span style="font-size: 11px;" class="label label-primary">'+ data +'</span>';
    		}else{
    			return '<span style="font-size: 11px;" class="label label-danger">'+ data +'</span>';
    		}
    		
		}},
        {data: 'action', name: 'action', orderable: false}
    ]
});

$('input[name="nuevo_intermediario[check]"]').on('ifChecked', function() {
	document.getElementById('nuevo_intermediario').style.display = 'block';
});

$('input[name="nuevo_intermediario[check]"]').on('ifUnchecked', function() {
	document.getElementById('nuevo_intermediario').style.display = 'none';
});

$('input[name="tipo_charter_selected"]').on('ifChecked', function() {
	document.getElementById('nuevo_intermediario').style.display = 'block';
});
/*$('input[name="tipo_charter_selected"]').on('ifChecked', function() {
	var tipo_seleccionado = $(this).val();
	alert(tipo_seleccionado);
	var block = "";
	var title = '<label style="font-size: 11px;">CHARTER TÁNDEM</label><br>';
	$.ajax({
	    url: 'variantes-patente/'+tipo_seleccionado,
	    type: 'GET',
        processData: false,
    	contentType: false,
        success: function(response){
        	//console.log(response.variantes);

			init_check(tipo_seleccionado, response.variantes);
        },

        error: function (xhr, ajaxOptions, thrownError) {
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }

	    
    });
});*/

$('#select_tipo_charter').change(function () { 
	var tipo_seleccionado = $(this).val();
	//alert(tipo_seleccionado);
	var block = "";
	//var title = '<label style="font-size: 11px;">CHARTER TÁNDEM</label><br>';
	if(tipo_seleccionado != 0){
		$.ajax({
		    url: 'variantes-patente/'+tipo_seleccionado,
		    type: 'GET',
	        processData: false,
	    	contentType: false,
	        success: function(response){
				//var block = "";
				var title = '<div class="col-lg-3 col-md-3 col-sm-12"><label style="font-size: 11px;">CHARTER TÁNDEM</label><br><select class="form-control input-sm" name="charter_tandem" id="charter_tandem" onchange="tandem()"><option value="0">SELECCIONAR COMBINACIÓN</option>';

				document.getElementById('tipo-charter-info').innerHTML = "";

				$.each(response.variantes, function(key, variante) {
					block = "";

					$.each(variante, function(key2, patente) {
						/*console.log(variante.length);
						console.log(variante);
						console.log(key2);
						console.log();*/
						if(variante.length > 1){
							block += '<option primerorden="'+patente.primer_orden+'" sgdoorden="'+patente.segundo_orden+'" value="'+patente.primer_orden+'-'+patente.segundo_orden+'">'+(patente.descripcion).toUpperCase()+'</option>';
						}else{
							//title = '<div class="col-lg-12 col-md-12 col-sm-12"><label style="font-size: 11px;"><label style="font-size: 11px;">CHARTER '+patente.toUpperCase()+'</label><br>';
							crear_patente(key, patente.toUpperCase());
							block = '';
						}
					});
				});

				if(block != ""){
					block += '</select></div>';
					$('#tipo-charter-info').append(title+block);	
				}
				
	        },

	        error: function (xhr, ajaxOptions, thrownError) {
		        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
		    }
    	});	
	}
	
});

function tandem(){
	var tandem_text = $("#charter_tandem option:selected").text();
	var orden_1 = $("#charter_tandem option:selected").attr("primerorden");
	var orden_2 = $("#charter_tandem option:selected").attr("sgdoorden"); 
	var patentes = tandem_text.split('-');
	//document.getElementById('tipo-charter-info').innerHTML = "";
	$.each(patentes, function(key, patente) {
		crear_patente(key, patente.toUpperCase());
	});
}

function crear_patente(posicion, patente){
	$.ajax({
	    url: 'embarcaciones/'+patente,
	    type: 'GET',
        processData: false,
    	contentType: false,
        success: function(response){
			var info = '<div class="col-lg-12 col-md-12 col-sm-12"><label style="font-size: 11px;">CHARTER '+patente+'</label></div>';
			info += '<div class="col-lg-6 col-md-6 col-sm-12" style="padding-bottom: 10px;">';
			info += '    <label style="font-size: 11px;">EMBARCACIÓN</label>';
			info += '    <br>';
			info += '    <select class="form-control input-sm" name="embarcacion[]" id="select_embarcacion_'+patente+'" onchange="cargar_itinerario(this, '+ posicion +', \''+patente+'\')">';
			info += '        <option value="0">SELECCIONAR EMBARCACIÓN</option>';

			$.each(response.embarcaciones, function(key, embarcacion) {
				info += '    <option value="'+embarcacion.id+'">'+embarcacion.nombre+'</option>';
			});

			info += '    </select>';
			info += '</div>';
			info += '<div class="col-lg-6 col-md-6 col-sm-12" style="padding-bottom: 10px;">';
			info += '    <label style="font-size: 11px;">ITINERARIO</label>';
			info += '    <br>';
			info += '    <select class="form-control input-sm select_itinerario_'+patente+'" name="itinerario[]">';
			info += '        <option value="0">SELECCIONAR ITINERARIO</option>';
			info += '    </select>';
			info += '</div>';

			$('#tipo-charter-info').append(info);
        },

        error: function (xhr, ajaxOptions, thrownError) {
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
	});
}

function cargar_itinerario(obj, posicion, patente){
	var obj_itinerario = $("select").closest(".select_itinerario_"+patente);
	var id_embarcacion = $(obj).children("option:selected").val();
	
	if(obj_itinerario[posicion] == undefined){
		posicion = 0;
	}

	obj_itinerario[posicion].options.length = 0;
	var c = document.createElement("option");
	c.text = "SELECCIONAR ITINERARIO";
	c.value = 0;
	obj_itinerario[posicion].options.add(c, 0);

	if(id_embarcacion != 0){
		$.ajax({
           	url: 'embarcaciones/info/'+id_embarcacion,
            dataType: "JSON",
            type: 'GET',
           
            success: function (response) {
            	var itinerarios = response.itinerarios;
            	var i = 1;
            	for(var k in itinerarios) {
            		var c = document.createElement("option");
					c.text = itinerarios[k].toUpperCase();
					c.value = k;
					obj_itinerario[posicion].options.add(c, i);
					i++;
				}
            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert("Ocurrió un error!", "Por favor, intente de nuevo", "error");
            }
        });
	}	
}