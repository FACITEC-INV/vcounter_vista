@extends('paginaBase')

@section('contenido')
<div class="card">
    <div class="card-body">
        <h5 class="card-title h4">Descripción del proyecto</h5>
        <p class="card-text lh-lg" style="text-align: justify;">
            Este es un proyecto de investigación desarrollado por la FACITEC con el apoyo del Shopping Mapy
        </p>
        <p class="card-text lh-lg" style="text-align: justify;">
            Uno de los campos con un gran desarrollo en la actualidad y con grandes implicancias para el futuro es la inteligencia artificial (IA) y con su advenimiento trajo consigo grandes avances entre los cuales se puede encontrar la visión por computadora o visión artificial, tecnología que se abordará en el presente proyecto. Resumiendo, en términos sencillos, la visión artificial permite a los ordenadores percibir el mundo por medio de medios visuales. Esto se puede lograr utilizando técnicas tales como la clasificación de imágenes, la detección de objetos y la segmentación de imágenes. Una de las aplicaciones prácticas de la visión artificial es el monitoreo del tránsito, por ejemplo, mediante sistemas de identificación y conteo de vehículos. En esta misma línea, en el presente proyecto se pretende desarrollar una solución, basada en la visión artificial, que permita el análisis del flujo vehicular fronterizo de la ciudad de Salto del  Guairá. Este objetivo responde a la importancia que tienen este tipo de estadísticas para ciudades turísticas, como la mencionada. Para la consecución del objetivo planteado, el desarrollo del proyecto se dividirá en las siguientes etapas: el estudio de técnicas de visión artificial aplicables a la solución, el diseño de la arquitectura del sistema y de la infraestructura requerida, el ensamblado de un prototipo de hardware que albergará el sistema desarrollado, y la construcción y evaluación del sistema de análisis del flujo vehicular.
        </p>
    </div>
</div>
<script>
   document.querySelector('a.acerca').classList.add('fw-semibold');
</script>
@endsection
