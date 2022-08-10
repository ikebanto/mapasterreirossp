class MapaHelper {

    clusterLayerID = 'clusters';
    clusterCountLayerID = 'cluster-count';
    unclusteredLayerID = 'unclustered-point';
    imagePinURL = '';
    enderecosGeoJson = {
        "type": "FeatureCollection",
        "features": []
    }
    filteredEnderecosGeoJson = {
        "type": "FeatureCollection",
        "features": []
    }
    enderecosPorCategoriaGeoJson = {
        terreiros: {
            "type": "FeatureCollection",
            "features": []
        }
    }

    criarMapa() {

        mapboxgl.accessToken = 'pk.eyJ1IjoicXVpamF1YSIsImEiOiJFZl9JMS00In0.ofdwBdKx6vgBYoSfJOo9Wg';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11', // stylesheet location
            center: [-46.9913, -23.5287], // starting position [lng, lat]
            zoom: 10 // starting zoom
        });
        map.addControl(new mapboxgl.NavigationControl());

        return map;
    }

    configurarMapa(map, enderecosGeoJson, source, imageURL) {
        this.enderecosGeoJson = enderecosGeoJson;
        this.imagePinURL = imageURL;
        this.separarPorCategorias(this.enderecosGeoJson);
        this.adicionarSource(map, this.enderecosGeoJson, source);
    }

    adicionarSource(map, enderecosGeoJson, source) {

        map.addSource(source, {
            type: 'geojson',
            data: enderecosGeoJson,
            cluster: true,
            clusterMaxZoom: 14, // Max zoom to cluster points on
            clusterRadius: 25 // Radius of each cluster when clustering points (defaults to 50)
        });
        this.criarMarcadores(map, source);
        this.criarCamadas(map, source);
        this.exibirContadorNoCluster(map, source);

    }

    criarCamadas(map, source) {
        map.addLayer({
            id: this.clusterLayerID,
            type: 'circle',
            source: source,
            filter: ['has', 'point_count'],
            paint: {
                'circle-color': [
                    'step',
                    ['get', 'point_count'],
                    'rgba(110, 204, 57, 0.8)',
                    10,
                    'rgba(240, 194, 12, 0.8)',
                    100,
                    'rgba(241, 128, 23, 0.8)'
                ],
                'circle-radius': [
                    'step',
                    ['get', 'point_count'],
                    10,
                    100,
                    15,
                    750,
                    20
                ],
                'circle-stroke-width': 5,
                'circle-stroke-color': [
                    'step',
                    ['get', 'point_count'],
                    'rgba(110, 204, 57, 0.4)',
                    10,
                    'rgba(240, 194, 12, 0.4)',
                    100,
                    'rgba(241, 128, 23, 0.4)'
                ]
            }
        });
    }

    exibirContadorNoCluster(map, source) {
        map.addLayer({
            id: this.clusterCountLayerID,
            type: 'symbol',
            source: source,
            filter: ['has', 'point_count'],
            layout: {
                'text-field': '{point_count_abbreviated}',
                'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                'text-size': 10
            }
        });
    }

    criarMarcadores(map, source) {
        const self = this;

        map.loadImage(this.imagePinURL, function (error, image) {
            if (error) {
                return;
            }

            if (!map.hasImage('ponto')) {
                map.addImage('ponto', image);
            }

            map.addLayer({
                'id': self.unclusteredLayerID,
                'type': 'symbol',
                'source': source,
                'filter': ['!', ['has', 'point_count']],
                'layout': {
                    'icon-image': 'ponto',
                    'icon-size': 0.4,
                    'icon-anchor': 'center',
                    'icon-allow-overlap': true
                }
            });
        });
    }

    eventoDeClickNoCluster(map, event, source) {
        var features = map.queryRenderedFeatures(event.point, {
            layers: ['clusters']
        });
        var clusterId = features[0].properties.cluster_id;
        map.getSource(source).getClusterExpansionZoom(
            clusterId,
            function (err, zoom) {
                if (err) return;
                map.easeTo({
                    center: features[0].geometry.coordinates,
                    zoom: zoom
                });
            }
        );
        let clusterSource = map.getSource(source);
        let pointCount = features[0].properties.point_count;

        clusterSource.getClusterLeaves(clusterId, pointCount, 0, function (error, features) {
            // Print cluster leaves in the console
            //console.log('Cluster leaves:', error, features);
        })
    }

    separarPorCategorias(enderecosGeoJson) {
        this.enderecosPorCategoriaGeoJson = {
            terreiros: {
                "type": "FeatureCollection",
                "features": []
            }
        }

        enderecosGeoJson.features.forEach((feature) => {
            if (feature.properties.categoria == 'regiao') this.enderecosPorCategoriaGeoJson.coletivo.features.push(feature);
            else if (feature.properties.categoria == 'nacao') this.enderecosPorCategoriaGeoJson.universidade.features.push(feature);
        });
    }


    limparSource(source) {

        this.filteredEnderecosGeoJson = {
            "type": "FeatureCollection",
            "features": []
        };

        if (map.getSource(source)) {
            map.removeLayer(this.clusterLayerID);
            map.removeLayer(this.clusterCountLayerID);
            map.removeLayer(this.unclusteredLayerID);
            map.removeSource(source);
        }
    }

    filtrarCategoria(map, value, source) {

        this.limparSource(source);

        if (value) {

            this.filteredEnderecosGeoJson = this.enderecosPorCategoriaGeoJson[value];

            if (this.filteredEnderecosGeoJson.features.length) {
                this.adicionarSource(map, this.filteredEnderecosGeoJson, source);
            }

        } else {
            this.adicionarSource(map, this.enderecosGeoJson, source);
        }
    }

    filtrarPorAcao(map, categoria, acao, source) {

        this.limparSource(source);

        if (acao && acao != 'selecione') {
            this.enderecosPorCategoriaGeoJson[categoria].features.forEach((endereco) => {
                if (endereco.properties[`area_atuacao_${categoria}`].toLowerCase().includes(acao.toLowerCase())) {
                    this.filteredEnderecosGeoJson.features.push(endereco);
                }
            });
        } else {
            this.filteredEnderecosGeoJson = this.enderecosPorCategoriaGeoJson[categoria];
        }

        if (this.filteredEnderecosGeoJson.features.length) {
            this.adicionarSource(map, this.filteredEnderecosGeoJson, source);
        }
    }

}
