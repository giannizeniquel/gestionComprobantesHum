window.addEventListener('DOMContentLoaded', function() {
    $("body").queryLoader2({
            barColor: "#5368d5",
            backgroundColor: "#fff",
            percentage: false,
            barHeight: 1,
            minimumTime: 100,
      });

      // aca captamos con jquery el elemento html que tiene el spinner de loading para ocultarlo cuando carga la pagina
      //$(".loading-background").fadeOut("slow");
});

