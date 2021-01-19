<script type="text/javascript">
	// $("#prev-process").prop( "disabled", progress_step == 1 );
    // $("#next-process").prop( "disabled", progress_step == 2 );
    if(progress_step == 1) $("#prev-process").hide(); 
    else $("#prev-process").show();

    if(progress_step == 2) $("#next-process").hide(); 
    else $("#next-process").show();

    if(progress_step == 2) $("#analisis").show(); 
    else $("#analisis").hide();

    var page_titles = ["Pilih dataset tersimpan", "Pengaturan"];
    $(".map-header").html(page_titles[progress_step-1]);

</script>