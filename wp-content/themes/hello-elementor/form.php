<?php /* Template Name: Form Submissão */ ?>

<?php

get_header();


?>

<div id="main-content" style="width:90%;margin: 0 auto;padding-top:0px;padding-bottom:50px">



    <?php if (is_page(1305)) { ?>

        <style>
            #left-area ul li ul,
            .comment-content ul li ol,
            .comment-content ul li ul,
            .entry-content ul li ol,
            body.et-pb-preview #main-content .container ul li ol {
                padding: 2px 0 2px 0px;
                list-style: none;
            }

            .wpuf_custom_html_1785,
            .wpuf_custom_html_1_1785 {
                font-size: 16pt;
                font-weight: bold;
                color: #000
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                function limpa_formulário_cep() {
                    // Limpa valores do formulário de cep.
                    $("#rua_1785").val("");
                    $("#bairro_1785").val("");
                    $("#cidade_1785").val("");
                    $("#estado_1785").val("");
                    $("#ibge").val("");
                    $("#lat_e_long_foram_preenchidos_1785").val("");
                }

                function removeStopWords(address) {
                    const stopwords = ['de ', 'a ', 'e ', 'para ', 'do ', 'da '];
                    return address.replace(new RegExp('\\b('+stopwords.join('|')+')\\b', 'g'), '');
                }

                $("#cep_1785, #rua_1785, #bairro_1785, #cidade_1785, #estado_1785, #numero_1785").blur(function() {

                    const cep = $("#cep_1785").val();
                    const rua = $('#rua_1785').val();
                    const bairro = $('#bairro_1785').val();
                    const cidade = $('#cidade_1785').val();
                    const numero = $('#numero_1785').val();
                    const estado = $('#estado_1785').val();


                    if (cep != "" && rua != "" && bairro != "" && cidade != "" && estado != "" && numero != "") {

                        const limit = '1';
                        const country = 'BR';
                        const access_token = 'pk.eyJ1IjoicXVpamF1YSIsImEiOiJja2FiamthMWUxMDZ3MnhqcDBmOW9xdzQzIn0.vABzNv9aRiezJysZUXnOSw';
                        const autocomplete = true;
                        const tipos = 'address';

                        let address = `${numero}%20${rua}%20${bairro}%20${cidade}%20${estado}`;
                        address = address.replace(/ /g, '%20');
                        const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${address}.json?limit=${limit}&country=${country}&access_token=${access_token}&autocomplete=${autocomplete}&types=${tipos}`

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
                                    $("#longitude_1785").val(lng);
                                    $("#latitude_1785").val(lat);
                                    $("#lat_e_long_foram_preenchidos_1785").val("sim");
                                } else {
                                    $("#longitude_1785").val("Não Encontrado");
                                    $("#latitude_1785").val("Não Encontrado");
                                    $("#lat_e_long_foram_preenchidos_1785").val("nao");
                                }
                            });

                        });
                    }
                });


                //Quando o campo cep perde o foco.
                $("#cep_1785").blur(function() {



                    //Nova variável "cep" somente com dígitos.

                    var cep = $(this).val().replace(/\D/g, '');



                    //Verifica se campo cep possui valor informado.

                    if (cep != "") {



                        //Expressão regular para validar o CEP.

                        var validacep = /^[0-9]{8}$/;



                        //Valida o formato do CEP.

                        if (validacep.test(cep)) {



                            //Preenche os campos com "..." enquanto consulta webservice.

                            $("#rua_1785").val("...");

                            $("#bairro_1785").val("...");

                            $("#cidade_1785").val("...");

                            $("#estado_1785").val("...");

                            $("#ibge").val("...");



                            //Consulta o webservice viacep.com.br/

                            $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {



                                if (!("erro" in dados)) {

                                    //Atualiza os campos com os valores da consulta.

                                    $("#rua_1785").val(dados.logradouro);

                                    $("#bairro_1785").val(dados.bairro);

                                    $("#cidade_1785").val(dados.localidade);

                                    $("#estado_1785").val(dados.uf);

                                    $("#ibge").val(dados.ibge);

                                } //end if.
                                else {

                                    //CEP pesquisado não foi encontrado.

                                    limpa_formulário_cep();

                                    alert("CEP não encontrado.");

                                }

                            });

                        } //end if.
                        else {

                            //cep é inválido.

                            limpa_formulário_cep();

                            alert("Formato de CEP inválido.");

                        }

                    } //end if.
                    else {

                        //cep sem valor, limpa formulário.

                        limpa_formulário_cep();

                    }

                });

            });
        </script>

    <?php } elseif (is_page(185)) { ?>

        <style>
            #left-area ul li ul,
            .comment-content ul li ol,
            .comment-content ul li ul,
            .entry-content ul li ol,
            body.et-pb-preview #main-content .container ul li ol {
                padding: 2px 0 2px 0px;
                list-style: none;
            }

            .wpuf_custom_html_194,
            .wpuf_custom_html_1_194 {
                font-size: 16pt;
                font-weight: bold;
                color: #000
            }

            ul.wpuf-form li .wpuf-fields .wpuf-radio-block,
            ul.wpuf-form li .wpuf-fields .wpuf-checkbox-block {
                font-size: 14px;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
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

                //Quando o campo cep perde o foco.

                $("#cep_194").blur(function() {
                    //Nova variável "cep" somente com dígitos.

                    var cep = $(this).val().replace(/\D/g, '');
                    //Verifica se campo cep possui valor informado.
                    if (cep != "") {
                        //Expressão regular para validar o CEP.
                        var validacep = /^[0-9]{8}$/;
                        //Valida o formato do CEP.
                        if (validacep.test(cep)) {
                            //Preenche os campos com "..." enquanto consulta webservice.

                            $("#rua_194").val("...");
                            $("#bairro_194").val("...");
                            $("#cidade_194").val("...");
                            $("#estado_194").val("...");
                            $("#ibge").val("...");
                            //Consulta o webservice viacep.com.br/

                            $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {

                                if (!("erro" in dados)) {
                                    //Atualiza os campos com os valores da consulta.
                                    $("#rua_194").val(dados.logradouro);
                                    $("#bairro_194").val(dados.bairro);
                                    $("#cidade_194").val(dados.localidade);
                                    $("#estado_194").val(dados.uf);

                                } //end if.
                                else {
                                    //CEP pesquisado não foi encontrado.
                                    limpa_formulário_cep();
                                    alert("CEP não encontrado.");
                                }
                            });

                        } //end if.
                        else {

                            //cep é inválido.

                            limpa_formulário_cep();

                            alert("Formato de CEP inválido.");

                        }

                    } //end if.
                    else {

                        //cep sem valor, limpa formulário.

                        limpa_formulário_cep();

                    }

                });

            });
        </script>

    <?php } else { ?>

    <?php } ?>


    <?php if (!$is_page_builder_used) : ?>

        <div class="container">
            <div id="content-area" class="clearfix">
                <div id="left-area">

                <?php endif; ?>

                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php if (!$is_page_builder_used) : ?>

                            <h1 class="entry-title main_title"><?php the_title(); ?></h1>

                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            the_content();

                            if (!$is_page_builder_used)
                                wp_link_pages(array('before' => '<div class="page-links">' . esc_html__('Pages:', 'Divi'), 'after' => '</div>'));
                            ?>
                        </div> <!-- .entry-content -->
                    </article> <!-- .et_pb_post -->

                <?php endwhile; ?>

                <?php if (!$is_page_builder_used) : ?>

                </div> <!-- #left-area -->

                <?php get_sidebar(); ?>
            </div> <!-- #content-area -->
        </div> <!-- .container -->

    <?php endif; ?>

</div> <!-- #main-content -->

<?php

get_footer();
