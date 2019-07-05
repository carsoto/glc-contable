//ChartJs.init();

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
    "serverSide": true,
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
    "serverSide": true,
    "ajax": "comisiones/charters",
    "columns": [
    	{data: "descripcion", name: "descripcion"},
    	{data: "precio_venta", name: "precio_venta"},
    	{data: "deluxe_total", name: "deluxe_total", class: "success"},
    	{data: "deluxe_gastos", name: "deluxe_gastos", class: "warning"},
    	{data: "deluxe_saldo", name: "deluxe_saldo", class: "danger"},
    	{data: "aryel_total", name: "aryel_total", class: "success"},
    	{data: "aryel_abono", name: "aryel_abono", class: "warning"},
    	{data: "aryel_saldo", name: "aryel_saldo", class: "danger"},
    	{data: "stephi_total", name: "stephi_total", class: "success"},
		{data: "stephi_abono", name: "stephi_abono", class: "warning"},
		{data: "stephi_saldo", name: "stephi_saldo", class: "danger"},
		{data: "glc_total", name: "glc_total", class: "success"},
		{data: "glc_abono", name: "glc_abono", class: "warning"},
		{data: "glc_saldo", name: "glc_saldo", class: "danger"},
        {data: 'action', name: 'action', orderable: false}
    ]
});

/**$("#porcentaje_comision_broker").notify("Debe colocar el charter rate", { className: "danger", clickToHide: true, autoHide: true, autoHideDelay: 1000, });**/

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
			//console.log(response);
			document.getElementById('id_charter').value = response.id;
			document.getElementById('charter_yacht').value = response.charter.yacht;
			document.getElementById('charter_yacht_rack').value = response.charter.yacht_rack;
			document.getElementById('charter_broker').value = response.charter.broker;
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
	    "serverSide": true,
	    "ajax": "historial-abonos-comisiones/"+id_comision,
	    "columns": [
		   	{data: "user", name: "user"},
		   	{data: "monto", name: "monto"},
		   	{data: "fecha", name: "fecha"},
		   	{data: "comentario", name: "comentario"},
		   	{data: "created_at", name: "created_at"}
	    ]
	});

	$("#historial-abonos-comision").modal("toggle"); 
}

function historial_gastos(id_gasto){

    $('#tabla_hist_gastos').DataTable().destroy();

	$("#tabla_hist_gastos").DataTable({
		"processing": true,
	    "serverSide": true,
	    "ajax": "historial-gastos/"+id_gasto,
	    "columns": [
		   	{data: "user", name: "user"},
		   	{data: "monto", name: "monto"},
		   	{data: "fecha", name: "fecha"},
		   	{data: "comentario", name: "comentario"},
		   	{data: "created_at", name: "created_at"}
	    ]
	});

	$("#historial-gastos").modal("toggle"); 
}

function agregar_gasto(id_gasto){
	document.getElementById('id_gasto').value = id_gasto;
	$("#nuevo-gasto").modal("toggle");  
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
	            if(response.status == 'success'){
	            	$('#nueva-entrada-form')[0].reset();
	            	$('#nueva-entrada').modal('toggle');
	            	$('#table-entradas').DataTable().ajax.reload();
	            	document.getElementById("total_entrada").innerHTML = response.total_recibido;
	            	document.getElementById("total_recibido").innerHTML = response.total_recibido;
	            	document.getElementById("total_entrada_pendiente").innerHTML = response.total_pendiente;
		            swal("Hecho!", response.msg, "success");
		        }else{
		        	$('#nueva-entrada-form').css("opacity","");
	            	$(".submitBtn").removeAttr("disabled");
		            swal("Ocurrió un error!", response.msg, "error");
		        }

	            $('#nueva-entrada-form').css("opacity","");
	            $(".submitBtn").removeAttr("disabled");
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
	            	document.getElementById("total_entrada").innerHTML = response.total_recibido;
	            	document.getElementById("total_entrada_pendiente").innerHTML = response.total_pendiente;
		            swal("Hecho!", response.msg, "success");
		        }else{
		        	$('#actualizar-entrada-form').css("opacity","");
	            	$(".submitBtn").removeAttr("disabled");
		            swal("Ocurrió un error!", response.msg, "error");
		        }

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
			document.getElementById("total_gastos").innerHTML = response.totales.global.gastos;

			document.getElementById("resumen_gastos_comision_"+response.id_comision).innerHTML = response.abonado;
			document.getElementById("resumen_saldo_comision_"+response.id_comision).innerHTML = response.saldo;

			$.each(response.totales.salidas, function(key, salida) {
				document.getElementById("resumen_gastos_"+key).innerHTML = salida.gastos;
				document.getElementById("resumen_saldo_"+key).innerHTML = salida.saldo;
			});

			document.getElementById("resumen_gastos_total").innerHTML = response.totales.global.gastos;
			document.getElementById("resumen_saldo_total").innerHTML = response.totales.global.saldo;

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
	            swal("Hecho!", response.msg, "success");
	        }else{
	        	$('#nuevo-gasto-form').css("opacity","");
            	$(".submitBtn").removeAttr("disabled");
	            swal("Ocurrió un error!", response.msg, "error");
	        }

            $('#nuevo-gasto-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");

            document.getElementById("salidas_gasto_"+response.id_gasto).innerHTML = response.r_gastos;
			document.getElementById("salidas_saldo_"+response.id_gasto).innerHTML = response.r_saldo;
			document.getElementById("total_gastos").innerHTML = response.totales.global.gastos;

			$.each(response.totales.salidas, function(key, salida) {
				document.getElementById("resumen_gastos_"+key).innerHTML = salida.gastos;
				document.getElementById("resumen_saldo_"+key).innerHTML = salida.saldo;
			});

			document.getElementById("resumen_gastos_total").innerHTML = response.totales.global.gastos;
			document.getElementById("resumen_saldo_total").innerHTML = response.totales.global.saldo;

			$("#nuevo-gasto").modal("toggle");

        },

        error: function (xhr, ajaxOptions, thrownError) {
        	$('#nuevo-gasto-form').css("opacity","");
            $(".submitBtn").removeAttr("disabled");
	        swal("Ocurrió un error!", "Por favor, intente de nuevo", "error");
	    }
    });
});

function tipoNumeros(e){
	var key = window.event ? e.which : e.keyCode
	return (key == 46 || key >= 48 && key <= 57);
}