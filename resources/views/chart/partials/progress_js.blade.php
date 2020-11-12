<script type="text/javascript">
	$("#prev-process").prop( "disabled", progress_step == 1 );
    $("#next-process").prop( "disabled", progress_step == 3 );

    for(var i=1;i<=3;i++)
    {
        if(progress_step == i) $(".step-progress-" + i).addClass("bg-success");
        else $(".step-progress-" + i).addClass("bg-info");

        $(".step-label-" + i).width( (progress_step < i) ? "" : "0%" );
        $(".step-progress-" + i).width( (progress_step >= i) ? "100%" : "0%" );
    }
</script>