<?php /* Template Name: Mapa */ ?>



<!DOCTYPE html>
<html>

<head>
  <meta charset=utf-8 />
  <title>Mapa Colaborativo</title>
  <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />

  <script src='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js'></script>
  <link href='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css' rel='stylesheet' />

  <!-- <script src='https://api.mapbox.com/mapbox.js/v3.3.0/mapbox.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/v3.3.0/mapbox.css' rel='stylesheet' />
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' /> -->

  <style>
    body {
      margin: 0;
      padding: 0;
    }

    #map {
      position: absolute;
      top: 0;
      bottom: 0;
      width: 100%;
      z-index: 1;
    }

    #filtro {
      position: absolute;
      top: 0;
      padding-left: 12px;
      padding-top: 0;
      width: 220px;
      height: auto;
      z-index: 2;
      list-style-type: none;
    }

    select {
      width: 100%;
      border-width: 3px;
      margin-bottom: 3px;
      border-color: rgb(0, 82, 119);
      border-radius: 5px;
      padding: 10px;
      font-size: 15px;
      color: rgb(61, 73, 83);
    }

    #limpar-filtro {
      margin-top: 7px;
      width: 100%;
      border: 2px solid white;
      border-radius: 5px;
      background-color: rgb(0, 82, 119);
      padding: 10px;
      font-size: 15px;
      color: white;
      box-sizing: border-box;
      text-align: center;
    }
  </style>
</head>


<body>


  <div id='map'>
    <ul id='filtro'>
      <li>
        <select name="categoria" id="categoria" onchange="eventoTrocaCategoria()">
          <option value="selecione">Selecione a Categoria</option>
          <option value="regiao">Região</option>
          <option value="nacao">Nação</option>
        </select>
      </li>
      <li>
        <select name="tipo-acao" id="tipo-acao" onchange="eventoTrocaTipoDeAcao()">
          <option value="selecione">Selecione o Tipo de Ação</option>
        </select>
      </li>
      <li>
        <div id="limpar-filtro" onclick="limparFiltros()">Limpar Filtros</div>
      </li>
    </ul>
  </div>

  <?php
  $args = array(
    'post_type' => array('terreiros'),
    'post_status' => 'publish',
    'posts_per_page' => -1,

  );
  query_posts($args);
  ?>

  <script src="<?php echo get_stylesheet_directory_uri(); ?>/mapa-helper.js"></script>
  <script src="<?php echo get_stylesheet_directory_uri(); ?>/filtros-helper.js"></script>

  <script>
    document.getElementById('tipo-acao').hidden = true;
    document.getElementById('limpar-filtro').hidden = true;
    var filtro = new Filtros();

    var enderecosGeoJson = {
      "type": "FeatureCollection",
      "features": [
        <?php if (have_posts()) : ?>
          <?php while (have_posts()) : the_post(); ?>
            <?php if (get_field('latitude') && is_numeric(trim(get_field('latitude'))) && get_field('longitude') && is_numeric(trim(get_field('longitude')))) : ?> {
                'type': 'Feature',
                'geometry': {
                  'type': 'Point',
                  'coordinates': ["<?php the_field('longitude'); ?>", "<?php the_field('latitude'); ?>"]
                },
                'properties': {
'categoria': "<?php the_field('regiao'); ?>",
                  'id': "<?php the_ID(); ?>",
				'area_atuacao_terreiros': "<?php the_field('nacao'); ?>",
                  'latitude': "<?php the_field('latitude'); ?>",
                  'longitude': "<?php the_field('longitude'); ?>",
                  'texto_alerta': "<strong><?php the_title(); ?></strong><br /><a href='<?php the_permalink(); ?>' target='_parent'>ver mais</a>"
                }
              },
            <?php endif; ?>
          <?php endwhile; ?>
        <?php endif; ?>
      ]
    };

    const imageURL = "/wp-content/uploads/2022/01/pin.png";
    let mapaHelper = new MapaHelper();
    let map = mapaHelper.criarMapa();
    let source = 'terreiros';
    let popup_pin = new mapboxgl.Popup();

    map.on('load', function() {
      mapaHelper.configurarMapa(map, enderecosGeoJson, source, imageURL);
    });

    map.on('click', 'clusters', function(event) {
      mapaHelper.eventoDeClickNoCluster(map, event, source);
    });

    map.on('click', 'unclustered-point', function(e) {

      let coordinates = e.features[0].geometry.coordinates.slice();
      let texto_alerta = e.features[0].properties.texto_alerta;

      // Ensure that if the map is zoomed out such that
      // multiple copies of the feature are visible, the
      // popup appears over the copy being pointed to.
      while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
      }

      popup_pin
        .setLngLat(coordinates)
        .setHTML(texto_alerta)
        .addTo(map);
    });

    // disable map zoom when using scroll
    map.scrollZoom.disable();

    function eventoTrocaCategoria() {
      let categoria = filtro.eventoTrocaCategoria();
      mapaHelper.filtrarCategoria(map, categoria, source);
      popup_pin.remove();
    }

    function limparFiltros() {
      filtro.limparFiltros();
      mapaHelper.filtrarCategoria(map, null, source);
      popup_pin.remove();
    }

    function eventoTrocaTipoDeAcao() {
      let categoria = document.querySelector('#categoria').value;
      let acao = document.querySelector('#tipo-acao').value;
      mapaHelper.filtrarPorAcao(map, categoria, acao, source);
      popup_pin.remove();
    }
  </script>
</body>

</html>
