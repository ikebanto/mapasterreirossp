$(document).ready(function() {

  function limpa_formulário_cep() {
      // Limpa valores do formulário de cep.
      $("#rua_194").val("");
      $("#bairro_194").val("");
      $("#cidade_194").val("");
      $("#estado_194").val("");
      $("#ibge").val("");
      $("#lat_e_long_foram_preenchidos_1785").val("");
  }

  function removeStopWords(address) {
      const stopwords = ['de ', 'a ', 'e ', 'para ', 'do ', 'da '];
      return address.replace(new RegExp('\\b('+stopwords.join('|')+')\\b', 'g'), '');
  }

  $("#cep_194, #rua_194, #bairro_194, #cidade_194, #estado_194, #numero_194").blur(function() {

      const cep = $("#cep_194").val();
      const rua = $('#rua_194').val();
      const bairro = $('#bairro_194').val();
      const cidade = $('#cidade_194').val();
      const numero = $('#numero_194').val();
      const estado = $('#estado_194').val();

      if (cep != "" && rua != "" && bairro != "" && cidade != "" && estado != "" && numero != "") {

          const limit = '1';
          const country = 'BR';
          const access_token = 'pk.eyJ1IjoicXVpamF1YSIsImEiOiJFZl9JMS00In0.ofdwBdKx6vgBYoSfJOo9Wg';
          const autocomplete = true;
          const tipos = 'address';

          let address = `${numero}%20${rua}%20${bairro}%20${cidade}%20${estado}`;
          address = address.replace(/ /g, '%20');
          const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${address}.json?limit=${limit}&country=${country}&access_token=${access_token}&autocomplete=${autocomplete}&types=${tipos}`;
          console.log(url);
          

          $.getJSON(url, function(geocode) {
              let place_name = geocode.features[0].place_name;
              const lat = geocode.features[0].geometry.coordinates[1];
              const lng = geocode.features[0].geometry.coordinates[0];

              const ufDescriptionUrl = `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estado}`;

              $.getJSON(ufDescriptionUrl, function(ufData) {

                  place_name = place_name.toLowerCase();
                  place_name = place_name.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                  place_name = removeStopWords(place_name);
                  const addressValues = [].concat(removeStopWords(rua), 
                                                  removeStopWords(bairro), 
                                                  removeStopWords(cidade), 
                                                  removeStopWords(ufData.nome), 
                                                  numero);

                  let canAddCoordinates = addressValues.map((value, index) => {
                          let item = value.toLowerCase();
                          item = item.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                          return place_name.includes(item);
                      })
                      .filter(value => value === false);

                  if (!canAddCoordinates.length) {
                      $("#longitude_194").val(lng);
                      $("#latitude_194").val(lat);
                      $("#lat_e_long_foram_preenchidos_194").val("sim");
                  } else {
                      $("#longitude_194").val("Não Encontrado");
                      $("#latitude_194").val("Não Encontrado");
                      $("#lat_e_long_foram_preenchidos_194").val("nao");
                  }

              });

          });
      }
  });




});