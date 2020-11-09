<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-multiselect.min.js') }}"></script>

<script type="text/javascript">
	$(".btn-chart").on("click", function(e) {
		var btn = e.target;
		current_type = $(btn).data("type");

		ResetChart();
	});

	$(".btn-chart-preview").on("click", function(e) {
		ResetChart();
	});

    $("#next-process").on("click", function() {
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
</script>

<script type="text/javascript">
	function isNumber(arg) { return typeof arg === 'number'; }
	function isString(arg) { return typeof arg === 'string'; }
	function isLineOrArea(arg) { return (current_type == "line" || current_type == "area" ) ? true : false }
	function isPieOrDonut(arg) { return (current_type == "pie" || current_type == "doughnut" ) ? true : false }
	function isBar(arg) { return (current_type == "bar" || current_type == "horizontalBar" ) ? true : false }

	function getRandomColorHex() {
		var hex = "0123456789ABCDEF",
		color = "#";
		for (var i = 1; i <= 6; i++) {
			color += hex[Math.floor(Math.random() * 16)];
		}
		return color;
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
	    var i = 0;
	    $('#selecty option:selected').each(function(i,v) {
	    	var label = $(v).attr("value");
	    	var data_value = origin_data.map(a => a[label]);
	    	// console.log(label, data_value);

		    var obj = {};
	    	obj.label = label;
	    	obj.data = data_value;
	    	obj.borderColor = (isLineOrArea()) ? main_color[i] : "#cdcdcd";
	    	obj.backgroundColor = (isPieOrDonut()) ? main_color : main_color[i++];
	    	obj.fill = !(current_type == "line");
	    	
	    	if (isPieOrDonut()) obj.borderWidth = 1;
	    	
	    	if (isLineOrArea()) obj.lineTension = 0;

	    	datasets.push(obj);
        });

        //if bar or column and single dataset, set color using array of color
        if(datasets.length == 1 && isBar()) datasets[0].backgroundColor = main_color;

	    //horizontal axis label
	    var column_x = $("#selectx").val();
	    data.labels = origin_data.map(a => a[column_x]);
	    data.label_source = column_x;

	    //chart title
	    var title = {
	    	display: $("#chk-judul").is(':checked'),
	    	text: $("#data-label").val()
	    };

	    var legend = {
	    	display: $("#chk-legend").is(':checked'),
	    	position: $("#legend-pos").val(),
	    }

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
	    return new Chart(document.getElementById("chart-preview"), SetOptions());
    }

    function ResetChart() 
    {
		myChart.destroy();
		myChart = GenerateChart();
    }

    //taken from https://colorbrewer2.org/#type=qualitative&scheme=Set3&n=12
    var main_color = ['#8dd3c7','#ffffb3','#bebada','#fb8072','#80b1d3','#fdb462','#b3de69','#fccde5','#d9d9d9','#bc80bd','#ccebc5','#ffed6f'];
    //taken from https://github.com/chartjs/Chart.js/issues/815#issuecomment-270186793
    // var main_color = ['#3366CC','#DC3912','#FF9900','#109618','#990099','#3B3EAC','#0099C6','#DD4477','#66AA00','#B82E2E','#316395','#994499','#22AA99','#AAAA11','#6633CC','#E67300','#8B0707','#329262','#5574A6','#3B3EAC'];

    var origin_data = eval('@json($data)');
    var existing_options = JSON.parse('@json($options)');

    var index_string = "";
    var index_number = "";
    var default_chart_type = "bar";
    var current_type = default_chart_type;

    $("#selecty").multiselect( { buttonClass: "form-control form-control-sm multiselect dropdown-toggle custom-select" } );

    if (existing_options === null) {
	    if(index_string == "") $("#selectx").val($("#selectx option:first").val());
	    else $("#selectx").val(index_string);

	    $("#selecty").multiselect('selectAll', false);
	    $("#selecty").multiselect('updateButtonText');

	    $("#data-label").val($("#data-label").prop("placeholder"));
    }
    else {
    	current_type = existing_options.type;
    	$("#selectx").val(existing_options.data.label_source);

    	var selected_y = existing_options.data.datasets.map(a => a.label);
    	$("#selecty").multiselect('select', selected_y);
    	
    	$("#data-label").val(existing_options.options.title.text);
    	$("#chk-judul").attr("checked",existing_options.options.title.display).trigger("change");

    	$("#legend-pos").val(existing_options.options.legend.position);
    	$("#chk-legend").attr("checked",existing_options.options.legend.display).trigger("change");
    }

    var myChart = GenerateChart();
</script>