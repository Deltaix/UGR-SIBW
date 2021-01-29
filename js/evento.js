var palabras = document.getElementsByClassName("baneada"); 

function showComments() {
    var x = document.getElementById("caja_comentarios");
    var y = document.getElementById("principal");
    
    if (x.style.display === "block") {
        y.style.gridTemplateColumns = "15% auto";
        x.style.display = "none";
    }
    else {
        y.style.gridTemplateColumns = "15% auto 25%";
        x.style.display = "block";
    }
}

function obtenerFecha() {
    var fecha = new Date();
    var dia = fecha.getDate();
    var mes = fecha.getMonth() + 1;
    var anio = fecha.getFullYear();
    var hora = fecha.getHours();
    var minuto = fecha.getMinutes();
    
    if (mes < 10) {
        mes = "0" + mes;
    }
    if (minuto < 10){
      minuto = "0" + minuto;
    }
  
    var fecha_mostrar = new String(dia + "/" + mes + "/" + anio + " - " + hora + ":" + minuto);
    
    return fecha_mostrar;
}

var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    
    slides[slideIndex-1].style.display = "block";
    console.log(slideIndex-1);
    console.log(slides[slideIndex-1].style.display);
}

function buscar() {
    var peti = document.getElementById("buscador").value;

    $.ajax({
        data: {peti},
        url: 'busqueda.php',
        type: 'POST',
        success: function(respuesta) {
            procesaRespuesta(respuesta);
        }
    });
}

function procesaRespuesta(respuesta) {
    res = "";

    for (i = 0 ; i < respuesta.length ; i++) {
        res += "<a id=\"bigtext\" href=\"evento.php?id=" + respuesta[i].id + "\">" + respuesta[i].nombre + "</a></br>\n";
    }

    $("#resultados").html(res)
}