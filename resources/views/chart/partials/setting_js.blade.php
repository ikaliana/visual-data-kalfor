<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-multiselect.min.js') }}"></script>

<script type="text/javascript">
	$(".btn-chart").on("click", function(e) {
		var btn = this; //e.target;
		current_type = $(btn).data("type");

		ResetButtonActive();
		ResetChart();
	});

	$(".btn-chart-preview").on("click", function(e) {
		ResetChart();
	});

    $("#btn-publish").on("click", function() {
    	var options = SetOptions();
    	$("#options").val(JSON.stringify(options));
    	// console.log($("#options").val());
    	$("#submit-form").submit();
    });

    $("#prev-process").on("click", function() {
        location.href = "{{ route('chart.check.get', ['code' => $code]) }}";
    });

    $("#chk-judul").on("change", function(){
    	$("#data-label").attr("disabled",!$(this).is(':checked'));
    });

    $("#chk-legend").on("change", function(){
    	$("#legend-pos").attr("disabled",!$(this).is(':checked'));
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		ToggleAddScatterButton()
	});
</script>

<script type="text/javascript">
	function isNumber(arg) { return typeof arg === 'number'; }
	function isString(arg) { return typeof arg === 'string'; }
	function isLineOrArea(arg) { return (current_type == "line" || current_type == "area" ) ? true : false }
	function isPieOrDonut(arg) { return (current_type == "pie" || current_type == "doughnut" ) ? true : false }
	function isBar(arg) { return (current_type == "bar" || current_type == "horizontalBar" ) ? true : false }
	function isScatter(arg) { return (current_type == "scatter"); }

	function getRandomColorHex() {
		var hex = "0123456789ABCDEF",
		color = "#";
		for (var i = 1; i <= 6; i++) {
			color += hex[Math.floor(Math.random() * 16)];
		}
		return color;
	}

	function hexToRGB(hex, alpha) {
	    var r = parseInt(hex.slice(1, 3), 16),
	        g = parseInt(hex.slice(3, 5), 16),
	        b = parseInt(hex.slice(5, 7), 16);

	    if (alpha) {
	        return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
	    } else {
	        return "rgb(" + r + ", " + g + ", " + b + ")";
	    }
	}

	function groupBy(data,index_string,index_number) {
		return data.reduce((r,a) => {
	    	if(typeof r[a[index_string]] === "undefined") r[a[index_string]] = 0;
	    	r[a[index_string]] = r[a[index_string]] + a[index_number];

	    	return r;
	    }, {});
	}

	function SetOptions()
	{
	    var options = {};
	    options.type = (current_type == "area") ? "line" : current_type;
	    
	    var data = {};
	    var datasets = [];

	    //dataset generator
	    if(isScatter()) {
	    	var column_x = $("select.scatter-x");
	    	var column_y = $("select.scatter-y");

	    	for(var i=0;i<column_x.length;i++) {
	    		var col_x_val = $(column_x[i]).find("option:selected").first().attr("value");
	    		var col_y_val = $(column_y[i]).find("option:selected").first().attr("value");

	    		var data_value_x = origin_data.map(a => a[col_x_val]);
	    		var data_value_y = origin_data.map(a => a[col_y_val]);
	    		var tmp_data = [];
	    		for(j=0;j<data_value_x.length;j++) {
	    			tmp_data.push( { x: data_value_x[j], y: data_value_y[j] } );
	    		}
	    		
	    		var obj = {};
	    		obj.label = col_x_val + " vs " + col_y_val;
	    		obj.borderColor = main_color[i];
	    		obj.backgroundColor = hexToRGB(main_color[i], 0.5);
	    		obj.data = tmp_data;
	    		obj.label_source = [col_x_val,col_y_val];

	    		datasets.push(obj);
	    	}
	    }
	    else {
		    var i = 0;
		    $('#selecty option:selected').each(function(i,v) {
		    	var label = $(v).attr("value");
		    	var data_value = origin_data.map(a => a[label]);
		    	// console.log(label, data_value);

			    var cur_color = main_color[i++];
			    var obj = {};
		    	obj.label = label;
		    	obj.data = data_value;
		    	obj.borderColor = (isLineOrArea()) ? cur_color : "#cdcdcd";
		    	// obj.backgroundColor = (isPieOrDonut()) ? main_color : ( (isLineOrArea()) ? hexToRGB(cur_color, 0.3) : hexToRGB(cur_color, 0.7) );
		    	obj.backgroundColor = (isPieOrDonut()) ? main_color : hexToRGB(cur_color, 0.4);
		    	obj.fill = !(current_type == "line");
		    	
		    	// if (isPieOrDonut()) obj.borderWidth = 1;
		    	if (!isLineOrArea()) obj.borderWidth = 1;
		    	if (isLineOrArea()) obj.lineTension = 0;

		    	datasets.push(obj);
	        });

	        //if bar or column and single dataset, set color using array of color
	        if(datasets.length == 1 && isBar()) datasets[0].backgroundColor = main_color;

		    //horizontal axis label
		    var column_x = $("#selectx").val();
		    data.labels = origin_data.map(a => a[column_x]);
		    data.label_source = column_x;
	    }

	    //chart title
	    var title = {
	    	display: $("#chk-judul").is(':checked'),
	    	text: $("#data-label").val()
	    };

	    var legend = {
	    	display: $("#chk-legend").is(':checked'),
	    	position: $("#legend-pos").val(),
	    }
	    if(isScatter() || isLineOrArea()) legend.labels = { boxWidth: 5, usePointStyle: true };

	    var chart_options = {};
	    chart_options.title = title;
	    chart_options.legend = legend;

	    data.datasets = datasets;
	    options.data = data;
	    options.options = chart_options;

	    return options;
	}

    function GenerateChart() 
    {
	    if(isScatter()) {
	    	$(".common-chart").hide();
	    	$(".scatter-chart").show();
	    	ToggleAddScatterButton();
	    	// $(".btn-scatter-add").show();
	    }
	    else {
	    	$(".common-chart").show();
	    	$(".scatter-chart").hide();
	    	$(".btn-scatter-add").hide();
	    }

	    return new Chart(document.getElementById("chart-preview"), SetOptions());
    }

    function ResetChart() 
    {
		myChart.destroy();
		myChart = GenerateChart();
    }

    function ToggleAddScatterButton()
    {
		var current_tab = $('a[data-toggle="tab"][aria-selected="true"]').attr("aria-controls");
		if(current_tab == "set-data") $(".btn-scatter-add").show();
		else $(".btn-scatter-add").hide();
    }

    function ResetButtonActive()
    {
    $(".btn-chart").removeClass("active");
    $('button[data-type="' + current_type + '"]').addClass("active");
    }


</script>

<script type="text/javascript">
	function AddNewScatterDataset(scatter_counter)
	{
		var to_be_replaced = "[VALSCATTERCOUNT]";
		var str_col_counter = $(".template-scatter-row-counter").html();
		var str_col_x = $(".template-scatter-row-x").html();
		var str_col_y = $(".template-scatter-row-y").html();
		var str_col_button = $(".template-scatter-row-button").html();

		str_col_counter = str_col_counter.replace(to_be_replaced,scatter_counter);
		str_col_counter = str_col_counter.replace("[VALSCATTERCOUNTERCLASS]","scatter-counter");
		str_col_counter = str_col_counter.replace(to_be_replaced,scatter_counter);
		$(".scatter-col-counter").append(str_col_counter);

		//[VALSCATTERCOMBOCLASS]
		str_col_x = str_col_x.replace(to_be_replaced,scatter_counter);
		str_col_x = str_col_x.replace("[VALSCATTERCOMBOCLASS]","scatter-x");
		$(".scatter-col-x").append(str_col_x);

		str_col_y = str_col_y.replace(to_be_replaced,scatter_counter);
		str_col_y = str_col_y.replace("[VALSCATTERCOMBOCLASS]","scatter-y");
		$(".scatter-col-y").append(str_col_y);

		str_col_button = str_col_button.replace(to_be_replaced,scatter_counter);
		str_col_button = str_col_button.replace(to_be_replaced,scatter_counter);
		str_col_button = str_col_button.replace("[VALSCATTERBUTTONCLASS]","scatter-close");
		$(".scatter-col-button").append(str_col_button);

		$("#scatter-counter-value").val(scatter_counter);
	}

	function ButtonAddScatterDataset(e)
	{
		// $(".frm-multiselect").multiselect('destroy');
		
		var scatter_counter = $("#scatter-counter-value").val();
		scatter_counter = eval(scatter_counter)+1;
		AddNewScatterDataset(scatter_counter);

		$(".scatter-close").off("click");
		$(".scatter-close").on("click", ButtonCloseScatterDataset);
		// $(".frm-multiselect").multiselect( { buttonClass: "form-control form-control-sm multiselect dropdown-toggle custom-select" } );
	}

	function ButtonCloseScatterDataset(e)
	{
		var buttons = $(".scatter-close");
		if(buttons.length == 1) {
			alert("Grafik membutuhkan minimal 1 dataset");
			return;
		}

		var btn = $(this);
		var row_no = btn.data("counter");

		$(".scatter-row-counter-" + row_no).remove();
		$(".scatter-row-x-" + row_no).remove();
		$(".scatter-row-y-" + row_no).remove();
		$(".scatter-row-button-" + row_no).remove();
		
		ResetScatterCounter();

		buttons = $(".scatter-close");
		if(buttons.length == 1) $(".scatter-close").off("click");

		ResetChart();
	}

	function ResetScatterCounter()
	{
		var counter_no = 1;
		var scatter_counter = $(".scatter-counter");
		$.each(scatter_counter, function(i,obj){
			$(obj).html(counter_no++);
		});
	}

    $(".btn-scatter-add").on("click", ButtonAddScatterDataset);
    // $(".scatter-close").on("click", ButtonCloseScatterDataset);
</script>

<script type="text/javascript">
    //taken from https://colorbrewer2.org/#type=qualitative&scheme=Set3&n=12
    // var main_color = ['#8dd3c7','#ffffb3','#bebada','#fb8072','#80b1d3','#fdb462','#b3de69','#fccde5','#d9d9d9','#bc80bd','#ccebc5','#ffed6f'];
    //taken from https://github.com/chartjs/Chart.js/issues/815#issuecomment-270186793
    // var main_color = ['#3366CC','#DC3912','#FF9900','#109618','#990099','#3B3EAC','#0099C6','#DD4477','#66AA00','#B82E2E','#316395','#994499','#22AA99','#AAAA11','#6633CC','#E67300','#8B0707','#329262','#5574A6','#3B3EAC'];
    var main_color = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];

    var origin_data = eval('@json($data)');
    var existing_options = JSON.parse('@json($options)');

    var index_string = "";
    var index_number = "";
    var default_chart_type = "bar";
    var current_type = default_chart_type;

    $(".frm-multiselect").multiselect( { buttonClass: "form-control form-control-sm multiselect dropdown-toggle custom-select" } );

    if (existing_options === null) {
	    if(index_string == "") $("#selectx").val($("#selectx option:first").val());
	    else $("#selectx").val(index_string);

	    $("#selecty").multiselect('selectAll', false);
	    $("#selecty").multiselect('updateButtonText');

	    $("select.scatter-x:first").val($("select.scatter-x:first option:first").val());
	    $("select.scatter-y:first").val($("select.scatter-y:first option:eq(1)").val());

	    $("#data-label").val($("#data-label").prop("placeholder"));
    }
    else {
    	current_type = existing_options.type;

    	if(isScatter())
    	{
		    $("#selecty").multiselect('selectAll', false);
		    $("#selecty").multiselect('updateButtonText');

		    var ds = existing_options.data.datasets;

		    for(var i=1;i<ds.length;i++) AddNewScatterDataset(i+1);

		    var column_x = $("select.scatter-x");
	    	var column_y = $("select.scatter-y");

	    	for(var i=0;i<ds.length;i++)
	    	{
	    		var label_source = ds[i].label_source;
	    		$(column_x[i]).val(label_source[0]);
	    		$(column_y[i]).val(label_source[1]);
	    	}

	    	// console.log(existing_options.data.datasets);
    	}
    	else {
	    	$("#selectx").val(existing_options.data.label_source);
	    	var selected_y = existing_options.data.datasets.map(a => a.label);
	    	$("#selecty").multiselect('select', selected_y);
    	}

    	$("#data-label").val(existing_options.options.title.text);
    	$("#chk-judul").attr("checked",existing_options.options.title.display).trigger("change");

    	$("#legend-pos").val(existing_options.options.legend.position);
    	$("#chk-legend").attr("checked",existing_options.options.legend.display).trigger("change");
    }

    ResetButtonActive();
    var myChart = GenerateChart();

</script>