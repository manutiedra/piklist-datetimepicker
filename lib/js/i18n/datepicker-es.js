(function($) {
	$.datepicker.regional['es'] = {
		closeText: "Cerrar",
		prevText: "Anterior",
		nextText: "Siguiente",
		currentText: "Hoy",
		monthNames: [ "Enero","Febrero","Marzo","Abril","Mayo","Junio",
		"Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre" ],
		monthNamesShort: [ "Ene","Feb","Mar","Abr","May","Jun",
		"Jul","Ago","Sep","Oct","Nov","Dic" ],
		dayNames: [ "Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado" ],
		dayNamesShort: [ "Dom","Lun","Mar","Mié","Jue","Vie","Sáb" ],
		dayNamesMin: [ "D","L","M","X","J","V","S" ],
		weekHeader: "Sm",
		dateFormat: "dd/mm/yy",
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ""
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
})(jQuery);

