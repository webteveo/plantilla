<?php

/**
 * Perfil de cada barrio de Montevideo para generar texto unico en las landings.
 *   perfil:   2 frases sobre el barrio (caracter, referencias).
 *   refs:     referencias o calles conocidas (se usan en el texto).
 *   vivienda: 'apartamentos' | 'casas' | 'mixto' (define de que puertas y cerraduras se habla).
 *   llegada:  tiempo estimado de llegada desde la base.
 */
final class Local_Barrios
{
    public const PERFIL = [
        'montevideo'    => ['perfil' => 'Montevideo concentra más de un millón de personas entre edificios del Centro y la costa, casas con jardín en Carrasco y Prado, y barrios obreros al oeste y al norte.', 'refs' => '18 de Julio, la Rambla y Avenida Italia', 'vivienda' => 'mixto', 'llegada' => '20 a 40 minutos según el barrio'],
        // Costa
        'carrasco'      => ['perfil' => 'Carrasco es el barrio residencial de la costa este, de casas grandes con jardín, portones y rejas, y edificios bajos cerca de la rambla. Es zona de familias y alquileres de temporada.', 'refs' => 'Arocena, la Rambla República de México y el Hotel Carrasco', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'carrasco-norte'=> ['perfil' => 'Carrasco Norte creció al norte de Avenida Italia, con casas nuevas, barrios cerrados y edificios de pocos pisos. Muchas viviendas tienen portón eléctrico y cerraduras de alta seguridad.', 'refs' => 'Camino Carrasco, Avenida Bolivia y el Parque Rivera', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'banados-de-carrasco' => ['perfil' => 'Bañados de Carrasco es una zona de casas bajas y terrenos amplios sobre Camino Carrasco, cerca del arroyo y de la ruta 8. Predominan las viviendas con reja y portón de chapa.', 'refs' => 'Camino Carrasco, Cochabamba y la ruta 8', 'vivienda' => 'casas', 'llegada' => '30 a 40 minutos'],
        'punta-gorda'   => ['perfil' => 'Punta Gorda es un barrio tranquilo entre Malvín y Carrasco, con casas de jardín sobre calles arboladas y edificios de categoría frente a la rambla. Muchas puertas de calle son de madera maciza con cerradura de seguridad.', 'refs' => 'la Plaza Virgilio, la rambla O\'Higgins y Avenida Italia', 'vivienda' => 'mixto', 'llegada' => '20 a 30 minutos'],
        'malvin'        => ['perfil' => 'Malvín combina edificios altos sobre la rambla con casas de una y dos plantas hacia Avenida Italia. Es un barrio muy poblado, con mucho movimiento de familias y estudiantes.', 'refs' => 'Orinoco, la playa de Malvín y la Plaza de los Olímpicos', 'vivienda' => 'mixto', 'llegada' => '20 a 30 minutos'],
        'malvin-norte'  => ['perfil' => 'Malvín Norte se extiende entre Avenida Italia y Camino Carrasco, con complejos de viviendas, cooperativas y casas de barrio. Hay muchas puertas de apartamento con cerradura de doble paleta.', 'refs' => 'Hipólito Yrigoyen, Iguá y el complejo Euskal Erría', 'vivienda' => 'apartamentos', 'llegada' => '20 a 30 minutos'],
        'buceo'         => ['perfil' => 'Buceo es un barrio de edificios de apartamentos y torres nuevas alrededor del Montevideo Shopping y el puerto deportivo. Predominan las cerraduras de seguridad en puertas blindadas.', 'refs' => 'el Montevideo Shopping, la rambla Armenia y 26 de Marzo', 'vivienda' => 'apartamentos', 'llegada' => '15 a 25 minutos'],
        'pocitos'       => ['perfil' => 'Pocitos es el barrio más densamente poblado de la costa, casi todo edificios de apartamentos entre la rambla y Bulevar España. Las puertas de apartamento suelen ser blindadas o de madera con doble cerradura.', 'refs' => 'la rambla de Pocitos, 21 de Setiembre y Avenida Brasil', 'vivienda' => 'apartamentos', 'llegada' => '15 a 25 minutos'],
        'pocitos-nuevo' => ['perfil' => 'Pocitos Nuevo está entre Pocitos y Buceo, con torres modernas y edificios de oficinas alrededor del World Trade Center. Casi todas las puertas son blindadas con cerraduras multipunto.', 'refs' => 'el World Trade Center, Luis Alberto de Herrera y 26 de Marzo', 'vivienda' => 'apartamentos', 'llegada' => '15 a 25 minutos'],
        'punta-carretas'=> ['perfil' => 'Punta Carretas es un barrio residencial de categoría, con edificios frente a la rambla y casas antiguas cerca del shopping y el faro. Muchas puertas tienen cerraduras de seguridad europeas.', 'refs' => 'el Punta Carretas Shopping, Ellauri y el faro', 'vivienda' => 'mixto', 'llegada' => '15 a 25 minutos'],
        'villa-biarritz'=> ['perfil' => 'Villa Biarritz es una zona chica y tranquila junto a Punta Carretas, con edificios de pocos pisos y casas frente al parque. Hay muchas puertas de madera antiguas con cerraduras de doble paleta.', 'refs' => 'el Parque Villa Biarritz, la feria de los sábados y Vázquez Ledesma', 'vivienda' => 'mixto', 'llegada' => '15 a 25 minutos'],
        // Centro
        'centro'        => ['perfil' => 'El Centro es la zona más transitada de la ciudad, con edificios de apartamentos antiguos sobre 18 de Julio y sus transversales. Hay muchas puertas de hierro y cerraduras viejas que se traban.', 'refs' => '18 de Julio, la Plaza Independencia y la Intendencia', 'vivienda' => 'apartamentos', 'llegada' => '10 a 20 minutos'],
        'ciudad-vieja'  => ['perfil' => 'Ciudad Vieja es el casco histórico, con edificios de época reciclados, oficinas y apartamentos sobre calles peatonales. Abundan las puertas antiguas de madera y hierro con cerraduras de embutir.', 'refs' => 'la peatonal Sarandí, el Mercado del Puerto y la Plaza Matriz', 'vivienda' => 'apartamentos', 'llegada' => '10 a 20 minutos'],
        'barrio-sur'    => ['perfil' => 'Barrio Sur es un barrio histórico entre el Centro y la rambla, con casas de patio, conventillos reciclados y edificios nuevos. Conviven cerraduras muy antiguas con puertas blindadas recientes.', 'refs' => 'Isla de Flores, la rambla Sur y el Cementerio Central', 'vivienda' => 'mixto', 'llegada' => '10 a 20 minutos'],
        'palermo'       => ['perfil' => 'Palermo es un barrio de calles angostas y casas bajas entre Barrio Sur y Parque Rodó, con muchos apartamentos reciclados y alquileres de estudiantes. Es habitual encontrar cerraduras gastadas por el uso.', 'refs' => 'Gonzalo Ramírez, Durazno y Minas', 'vivienda' => 'mixto', 'llegada' => '10 a 20 minutos'],
        'cordon'        => ['perfil' => 'Cordón es un barrio muy denso y estudiantil, con edificios de apartamentos alrededor de la Universidad y 18 de Julio. Hay mucha rotación de inquilinos y cambios de cerradura frecuentes.', 'refs' => 'la Universidad de la República, Tristán Narvaja y 18 de Julio', 'vivienda' => 'apartamentos', 'llegada' => '10 a 20 minutos'],
        'parque-rodo'   => ['perfil' => 'Parque Rodó mezcla edificios frente al parque y la playa Ramírez con casas antiguas hacia Cordón. Es zona de bares y alquileres, con muchas puertas de edificio de uso intenso.', 'refs' => 'el Parque Rodó, la playa Ramírez y Bulevar Artigas', 'vivienda' => 'mixto', 'llegada' => '10 a 20 minutos'],
        'tres-cruces'   => ['perfil' => 'Tres Cruces es un nudo de tránsito alrededor de la terminal de ómnibus, con edificios de apartamentos, hoteles y oficinas. Los edificios suelen tener puertas de acceso con cerradura eléctrica.', 'refs' => 'la Terminal Tres Cruces, Bulevar Artigas y 8 de Octubre', 'vivienda' => 'apartamentos', 'llegada' => '10 a 20 minutos'],
        'parque-batlle' => ['perfil' => 'Parque Batlle es un barrio residencial alrededor del Estadio Centenario, con casas de jardín y edificios de pocos pisos sobre calles arboladas. Predominan las puertas de madera con cerradura de seguridad.', 'refs' => 'el Estadio Centenario, Avenida Italia y Ricaldoni', 'vivienda' => 'mixto', 'llegada' => '15 a 25 minutos'],
        'la-blanqueada' => ['perfil' => 'La Blanqueada es un barrio de casas bajas y edificios chicos entre 8 de Octubre y Avenida Italia, con mucho comercio de barrio. Hay muchas casas con reja y portón de hierro.', 'refs' => '8 de Octubre, Garibaldi y el Hospital Militar', 'vivienda' => 'mixto', 'llegada' => '15 a 25 minutos'],
        'larranaga'     => ['perfil' => 'Larrañaga es una zona residencial tranquila entre La Blanqueada y Jacinto Vera, con casas de una planta y algunos edificios sobre Larrañaga. Abundan las puertas de calle de madera con cerrojo.', 'refs' => 'Avenida Larrañaga, Garibaldi y el Parque de los Aliados', 'vivienda' => 'casas', 'llegada' => '15 a 25 minutos'],
        // Norte del Centro
        'aguada'        => ['perfil' => 'La Aguada es un barrio de tránsito y comercio alrededor de la estación central y la Torre de las Telecomunicaciones, con apartamentos antiguos y galpones reciclados. Muchas cerraduras son viejas y de embutir.', 'refs' => 'la Torre de Antel, Avenida Libertador y el Palacio Legislativo', 'vivienda' => 'apartamentos', 'llegada' => '10 a 20 minutos'],
        'la-comercial'  => ['perfil' => 'La Comercial es un barrio de casas bajas y pequeños edificios entre la Aguada y Cordón, con talleres y comercios sobre Justicia. Hay muchas puertas de hierro con cerradura de sobreponer.', 'refs' => 'Justicia, Democracia y Arenal Grande', 'vivienda' => 'casas', 'llegada' => '10 a 20 minutos'],
        'villa-munoz'   => ['perfil' => 'Villa Muñoz es un barrio comercial y residencial alrededor de la feria de Tristán Narvaja hacia el norte, con casas de patio y depósitos. Las puertas de calle suelen ser de hierro con cerrojo.', 'refs' => 'la Plaza de las Misiones, Arenal Grande y Domingo Aramburú', 'vivienda' => 'casas', 'llegada' => '10 a 20 minutos'],
        'reducto'       => ['perfil' => 'Reducto es un barrio tradicional de casas bajas y edificios chicos entre la Aguada y Jacinto Vera, con mucho comercio sobre San Martín. Predominan las cerraduras de doble paleta en puertas de madera.', 'refs' => 'Avenida San Martín, Bulevar Artigas y Arenal Grande', 'vivienda' => 'casas', 'llegada' => '15 a 25 minutos'],
        'bella-vista'   => ['perfil' => 'Bella Vista es un barrio de casas y talleres entre Capurro y la Aguada, con vista a la bahía y edificios reciclados. Hay muchas puertas antiguas de madera y portones de hierro.', 'refs' => 'Avenida Agraciada, el Parque Capurro y Avenida Millán', 'vivienda' => 'casas', 'llegada' => '15 a 25 minutos'],
        'capurro'       => ['perfil' => 'Capurro es un barrio costero sobre la bahía, con casas bajas, cooperativas y edificios frente al parque. Predominan las viviendas con reja y cerradura de sobreponer.', 'refs' => 'el Parque Capurro, la rambla Baltasar Brum y Agraciada', 'vivienda' => 'casas', 'llegada' => '15 a 25 minutos'],
        'jacinto-vera'  => ['perfil' => 'Jacinto Vera es un barrio residencial de casas de una planta y edificios bajos alrededor del Museo Blanes, con calles tranquilas y mucho comercio de barrio. Abundan las puertas de calle con cerrojo adicional.', 'refs' => 'el Museo Blanes, Avenida Millán y Bulevar Artigas', 'vivienda' => 'casas', 'llegada' => '15 a 25 minutos'],
        'la-figurita'   => ['perfil' => 'La Figurita es un barrio chico y tranquilo de casas bajas entre Jacinto Vera y Brazo Oriental, con comercio sobre Bulevar Artigas. Las puertas de calle son en general de madera con cerradura de doble paleta.', 'refs' => 'Bulevar Artigas, Luis Alberto de Herrera y Garibaldi', 'vivienda' => 'casas', 'llegada' => '15 a 25 minutos'],
        'brazo-oriental'=> ['perfil' => 'Brazo Oriental es un barrio residencial de casas y pequeños edificios cerca del Prado, con calles arboladas y comercio sobre Luis Alberto de Herrera. Hay muchas casas con portón y reja.', 'refs' => 'Luis Alberto de Herrera, Bulevar Batlle y Ordóñez y Avenida Burgues', 'vivienda' => 'casas', 'llegada' => '15 a 25 minutos'],
        'atahualpa'     => ['perfil' => 'Atahualpa es un barrio de casas con jardín y calles arboladas entre el Prado y Sayago, con mucho comercio sobre Avenida Millán. Predominan las puertas de madera y los portones de hierro.', 'refs' => 'Avenida Millán, Bulevar Batlle y Ordóñez y el Prado', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'aires-puros'   => ['perfil' => 'Aires Puros es un barrio residencial tranquilo al norte del Prado, con casas de una y dos plantas y complejos de viviendas. Hay muchas casas con reja y cerradura de sobreponer.', 'refs' => 'Avenida Burgues, Bulevar José Batlle y Ordóñez y Avenida Millán', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'prado'         => ['perfil' => 'El Prado es un barrio residencial de casas grandes con jardín y quintas antiguas alrededor del parque y el Jardín Botánico. Muchas puertas son de madera maciza con cerraduras de época que requieren repuestos específicos.', 'refs' => 'el Parque Prado, la Rural del Prado y Avenida Buschental', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        // Este
        'union'         => ['perfil' => 'La Unión es un barrio comercial muy activo sobre 8 de Octubre, con casas bajas, edificios chicos y mucho comercio. Hay puertas de todo tipo, desde hierro con cerrojo hasta blindadas nuevas.', 'refs' => '8 de Octubre, la Plaza de la Restauración y Comercio', 'vivienda' => 'mixto', 'llegada' => '15 a 25 minutos'],
        'villa-espanola'=> ['perfil' => 'Villa Española es un barrio de casas bajas y calles tranquilas entre la Unión y el Cerrito, con mucha vida de barrio. Predominan las casas con reja y puerta de hierro.', 'refs' => 'Corrales, Camino Corrales y la Plaza Villa Española', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'mercado-modelo'=> ['perfil' => 'Mercado Modelo es un barrio de casas y depósitos alrededor del antiguo mercado mayorista, cerca de Bolívar y el Cerrito. Hay muchos portones de chapa y cerraduras de sobreponer.', 'refs' => 'el ex Mercado Modelo, Avenida José Pedro Varela y Bulevar Batlle y Ordóñez', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'castro-castellanos' => ['perfil' => 'Castro y Castellanos es un barrio residencial de casas de una planta entre el Cerrito y Piedras Blancas, con calles tranquilas y comercio sobre José Pedro Varela. Predominan las puertas con reja y cerrojo.', 'refs' => 'Avenida José Pedro Varela, Bulevar Aparicio Saravia y Cerrito', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'cerrito'       => ['perfil' => 'El Cerrito de la Victoria es un barrio tradicional en lo alto de la ciudad, con casas bajas, el santuario y mucho comercio sobre Bulevar Aparicio Saravia. Las casas suelen tener puerta de hierro y cerradura de sobreponer.', 'refs' => 'el Santuario del Cerrito, Bulevar Aparicio Saravia y Bulevar Batlle y Ordóñez', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'las-acacias'   => ['perfil' => 'Las Acacias es un barrio residencial de casas bajas entre el Cerrito y Casavalle, con comercio sobre Bulevar Aparicio Saravia. Hay muchas viviendas con reja y portón de chapa.', 'refs' => 'Bulevar Aparicio Saravia, Avenida San Martín y Domingo Arena', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'maronas'       => ['perfil' => 'Maroñas es un barrio de casas bajas alrededor del hipódromo, con calles tranquilas y comercio sobre 8 de Octubre y Camino Maldonado. Predominan las puertas de hierro con cerradura de doble paleta.', 'refs' => 'el Hipódromo de Maroñas, Camino Maldonado y 8 de Octubre', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'flor-de-maronas' => ['perfil' => 'Flor de Maroñas es un barrio residencial de casas y cooperativas entre Maroñas y Punta de Rieles, con calles anchas y mucha vida de barrio. Hay muchas casas con reja y portón.', 'refs' => 'Camino Maldonado, Veracierto y Avenida Belloni', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'ituzaingo'     => ['perfil' => 'Ituzaingó es un barrio de casas bajas y complejos de vivienda al este de Maroñas, con comercio sobre Camino Maldonado. Predominan las puertas de chapa y hierro con cerradura de sobreponer.', 'refs' => 'Camino Maldonado, Avenida Belloni y Camino Carrasco', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'jardines-del-hipodromo' => ['perfil' => 'Jardines del Hipódromo es un barrio residencial de casas de una planta al norte del hipódromo, con calles tranquilas y comercio sobre José Belloni. Abundan las casas con reja y puerta de hierro.', 'refs' => 'Avenida José Belloni, Camino Maldonado y el hipódromo', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'las-canteras'  => ['perfil' => 'Las Canteras es un barrio de casas bajas y cooperativas entre Malvín Norte y Punta de Rieles, con calles anchas y mucho movimiento sobre Camino Carrasco. Hay muchas puertas de hierro con cerrojo.', 'refs' => 'Camino Carrasco, Veracierto y Avenida Italia', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'punta-de-rieles' => ['perfil' => 'Punta de Rieles es un barrio en crecimiento sobre Camino Maldonado, con casas nuevas, cooperativas y comercio en la zona del ex penal. Predominan las viviendas con portón y reja.', 'refs' => 'Camino Maldonado, Camino Repetto y la ruta 8', 'vivienda' => 'casas', 'llegada' => '25 a 40 minutos'],
        'bella-italia'  => ['perfil' => 'Bella Italia es un barrio residencial de casas bajas entre Punta de Rieles y Flor de Maroñas, con calles tranquilas y comercio sobre Camino Maldonado. Hay muchas casas con reja y cerradura de sobreponer.', 'refs' => 'Camino Maldonado, Veracierto y Avenida Belloni', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'villa-garcia'  => ['perfil' => 'Villa García es la última zona urbana sobre la ruta 8, con casas de terreno grande, chacras y complejos nuevos. Predominan los portones de campo y las puertas con cerradura de sobreponer.', 'refs' => 'la ruta 8, Camino Repetto y Manga', 'vivienda' => 'casas', 'llegada' => '30 a 45 minutos'],
        // Norte
        'piedras-blancas' => ['perfil' => 'Piedras Blancas es un barrio extenso de casas bajas al norte de la ciudad, con comercio sobre José Belloni y calles tranquilas. Hay muchas casas con reja y portón de chapa.', 'refs' => 'Avenida José Belloni, Bulevar Aparicio Saravia y Mendoza', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'manga'         => ['perfil' => 'Manga es un barrio de casas con terreno y zonas semirrurales sobre Camino Mendoza y la ruta 8, con complejos nuevos y chacras. Predominan los portones y las puertas con cerradura de sobreponer.', 'refs' => 'Camino Mendoza, Avenida José Belloni y la ruta 8', 'vivienda' => 'casas', 'llegada' => '30 a 45 minutos'],
        'casavalle'     => ['perfil' => 'Casavalle es un barrio de complejos de viviendas y casas bajas al norte de Bulevar Aparicio Saravia, con mucha vida de barrio. Hay muchas puertas de apartamento con cerradura de doble paleta y rejas en casas.', 'refs' => 'Bulevar Aparicio Saravia, Avenida San Martín y Martirené', 'vivienda' => 'mixto', 'llegada' => '25 a 35 minutos'],
        'paso-de-las-duranas' => ['perfil' => 'Paso de las Duranas es un barrio residencial de casas bajas entre Sayago y Casavalle, con calles tranquilas y comercio sobre Avenida Sayago. Predominan las casas con reja y portón.', 'refs' => 'Avenida Sayago, Bulevar Aparicio Saravia y Millán', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        // Oeste
        'sayago'        => ['perfil' => 'Sayago es un barrio tradicional de casas con jardín alrededor de la estación de tren, con calles arboladas y comercio sobre Avenida Sayago. Hay muchas puertas de madera antiguas con cerradura de embutir.', 'refs' => 'la estación Sayago, Avenida Sayago y Bulevar Batlle y Ordóñez', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'conciliacion'  => ['perfil' => 'Conciliación es un barrio residencial de casas bajas entre Sayago y Belvedere, con calles tranquilas y comercio sobre Carlos María Ramírez. Predominan las casas con reja y puerta de hierro.', 'refs' => 'Carlos María Ramírez, Avenida Sayago y Camino Castro', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'penarol'       => ['perfil' => 'Peñarol es un barrio obrero histórico alrededor de los talleres ferroviarios, con casas bajas, cooperativas y mucho comercio sobre Avenida Sayago. Abundan las puertas de hierro con cerradura de sobreponer.', 'refs' => 'los talleres de Peñarol, Avenida Sayago y Camino Ariel', 'vivienda' => 'casas', 'llegada' => '25 a 40 minutos'],
        'colon'         => ['perfil' => 'Colón es un centro comercial y residencial del norte, con casas con jardín, quintas y edificios chicos alrededor de Avenida Garzón y la plaza. Muchas casas tienen portón y cerradura de seguridad.', 'refs' => 'Avenida Garzón, la Plaza Vidiella y Camino Colman', 'vivienda' => 'casas', 'llegada' => '30 a 40 minutos'],
        'abayuba'       => ['perfil' => 'Abayubá es un barrio de casas con terreno amplio al norte de Colón, sobre la ruta 5, con zonas de chacras. Predominan los portones de campo y las puertas con cerrojo.', 'refs' => 'la ruta 5, Camino Colman y Avenida Garzón', 'vivienda' => 'casas', 'llegada' => '35 a 45 minutos'],
        'lezica'        => ['perfil' => 'Lezica es un barrio residencial de casas bajas y cooperativas entre Colón y Melilla, con comercio sobre Camino Lecocq. Hay muchas viviendas con reja y portón de chapa.', 'refs' => 'Camino Lecocq, Avenida Lezica y Camino Colman', 'vivienda' => 'casas', 'llegada' => '30 a 45 minutos'],
        'melilla'       => ['perfil' => 'Melilla es una zona semirrural al noroeste de la ciudad, con quintas, chacras y casas de terreno grande cerca del aeródromo. Predominan los portones y las puertas con cerradura de sobreponer.', 'refs' => 'Camino Melilla, el aeródromo Ángel Adami y la ruta 5', 'vivienda' => 'casas', 'llegada' => '35 a 50 minutos'],
        'belvedere'     => ['perfil' => 'Belvedere es un barrio residencial de casas bajas entre el Prado y La Teja, con calles tranquilas y comercio sobre Carlos María Ramírez. Hay muchas puertas de madera antiguas y rejas de hierro.', 'refs' => 'Carlos María Ramírez, Avenida Agraciada y Bulevar Batlle y Ordóñez', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'paso-molino'   => ['perfil' => 'Paso Molino es un centro comercial tradicional del oeste, con casas antiguas, edificios chicos y mucho comercio sobre Agraciada y Carlos María Ramírez. Abundan las puertas de hierro con cerradura de sobreponer.', 'refs' => 'Avenida Agraciada, Carlos María Ramírez y el Puente Paso Molino', 'vivienda' => 'mixto', 'llegada' => '20 a 30 minutos'],
        'nuevo-paris'   => ['perfil' => 'Nuevo París es un barrio residencial de casas bajas y cooperativas entre Belvedere y Paso de la Arena, con calles anchas y comercio sobre Carlos María Ramírez. Predominan las casas con reja y portón.', 'refs' => 'Carlos María Ramírez, Camino Castro y Bulevar Batlle y Ordóñez', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'la-teja'       => ['perfil' => 'La Teja es un barrio obrero histórico junto a la refinería y la bahía, con casas bajas, cooperativas y mucha vida de barrio sobre Carlos María Ramírez. Hay muchas puertas de hierro y cerraduras de sobreponer.', 'refs' => 'Carlos María Ramírez, la refinería de Ancap y la Plaza Lafone', 'vivienda' => 'casas', 'llegada' => '20 a 30 minutos'],
        'tres-ombues'   => ['perfil' => 'Tres Ombúes es un barrio residencial de casas bajas y cooperativas entre La Teja y Nuevo París, con calles tranquilas. Predominan las viviendas con reja y puerta de chapa.', 'refs' => 'Camino Cibils, Carlos María Ramírez y Pedro de Mendoza', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'cerro'         => ['perfil' => 'El Cerro es un barrio histórico del oeste, con casas bajas en las faldas de la fortaleza, comercio sobre Grecia y complejos de vivienda. Abundan las puertas de hierro con cerrojo y las cerraduras de sobreponer.', 'refs' => 'la Fortaleza del Cerro, Avenida Grecia y la playa del Cerro', 'vivienda' => 'casas', 'llegada' => '25 a 35 minutos'],
        'casabo'        => ['perfil' => 'Casabó es un barrio de casas bajas y cooperativas al oeste del Cerro, con calles tranquilas y comercio sobre Camino Cibils. Predominan las casas con reja y portón de chapa.', 'refs' => 'Camino Cibils, Camino Burdeos y Avenida Grecia', 'vivienda' => 'casas', 'llegada' => '30 a 40 minutos'],
        'la-paloma'     => ['perfil' => 'La Paloma es un barrio de casas bajas y cooperativas al norte del Cerro, con comercio sobre Camino Cibils y mucha vida de barrio. Hay muchas puertas de hierro con cerradura de sobreponer.', 'refs' => 'Camino Cibils, Camino Tomkinson y Bulevar Aparicio Saravia', 'vivienda' => 'casas', 'llegada' => '30 a 40 minutos'],
        'santa-catalina'=> ['perfil' => 'Santa Catalina es un barrio costero al oeste del Cerro, con casas bajas cerca de la playa y zonas de quintas. Predominan los portones y las puertas con cerradura de sobreponer.', 'refs' => 'la playa de Santa Catalina, Camino Burdeos y Camino Sanfuentes', 'vivienda' => 'casas', 'llegada' => '35 a 45 minutos'],
        'pajas-blancas' => ['perfil' => 'Pajas Blancas es una zona costera semirrural en el extremo oeste, con casas de balneario, quintas y chacras. Hay muchos portones de campo y puertas con cerrojo.', 'refs' => 'la playa de Pajas Blancas, Camino Pajas Blancas y Camino Sanfuentes', 'vivienda' => 'casas', 'llegada' => '40 a 50 minutos'],
        'paso-de-la-arena' => ['perfil' => 'Paso de la Arena es un barrio de casas con terreno y cooperativas sobre Camino Cibils y Luis Batlle Berres, con zonas de chacras hacia el oeste. Predominan las casas con reja y portón.', 'refs' => 'Luis Batlle Berres, Camino Cibils y Camino Tomkinson', 'vivienda' => 'casas', 'llegada' => '30 a 45 minutos'],
        'santiago-vazquez' => ['perfil' => 'Santiago Vázquez es un pueblo sobre el río Santa Lucía en el límite oeste de Montevideo, con casas bajas, quintas y clubes de pesca. Predominan las puertas con cerradura de sobreponer y los portones.', 'refs' => 'el río Santa Lucía, Luis Batlle Berres y el puente de la ruta 1', 'vivienda' => 'casas', 'llegada' => '40 a 55 minutos'],
    ];

    /** Frases sobre viviendas segun tipo. Se elige una variante por landing. */
    private const VIVIENDA = [
        'apartamentos' => [
            'En los edificios de la zona trabajamos sobre todo con puertas de apartamento blindadas y de madera con doble cerradura, además de las puertas de acceso al edificio.',
            'La mayoría de los trabajos acá son en apartamentos: cerraduras de seguridad, cilindros europeos y puertas de edificio con cerradura eléctrica.',
            'Al ser una zona de edificios, estamos acostumbrados a cerraduras multipunto, cilindros de alta seguridad y puertas blindadas de todas las marcas.',
        ],
        'casas' => [
            'En las casas de la zona lo habitual son puertas de madera o hierro con cerradura de sobreponer o doble paleta, rejas con candado y portones.',
            'Trabajamos con lo que más hay en el barrio: puertas de calle de hierro, rejas, portones y cerraduras de doble paleta, además de cerraduras de seguridad nuevas.',
            'Conocemos las puertas típicas del barrio: madera maciza, hierro con cerrojo, rejas y portones de chapa o eléctricos.',
        ],
        'mixto' => [
            'Trabajamos tanto en apartamentos con puertas blindadas como en casas con rejas, portones y cerraduras de doble paleta.',
            'Hay de todo en la zona, desde edificios con cerraduras multipunto hasta casas antiguas con cerraduras de embutir, y tenemos herramientas y repuestos para ambas.',
            'Atendemos edificios y casas por igual: cilindros europeos, cerraduras de sobreponer, rejas, portones y puertas de acceso.',
        ],
    ];

    /**
     * Entidad del barrio en Wikipedia (es) y Wikidata, para el sameAs del schema (busqueda orientada a entidades).
     * slug => [titulo del articulo en es.wikipedia.org, id de Wikidata]. Verificado contra la API de Wikipedia;
     * los barrios sin articulo propio (Villa Biarritz, Pocitos Nuevo, Larrañaga, Castro y Castellanos, Las Canteras,
     * La Paloma, Mercado Modelo) no se listan y salen solo con containedInPlace Montevideo.
     */
    public const WIKI = [
        'montevideo'            => ['Montevideo', 'Q1335'],
        'carrasco'              => ['Carrasco (Montevideo)', 'Q1005003'],
        'pocitos'               => ['Pocitos (Montevideo)', 'Q2094894'],
        'malvin'                => ['Malvín', 'Q1004920'],
        'prado'                 => ['Prado (Montevideo)', 'Q389478'],
        'punta-carretas'        => ['Punta Carretas', 'Q984390'],
        'buceo'                 => ['Buceo (Montevideo)', 'Q997676'],
        'malvin-norte'          => ['Malvín Norte', 'Q1005816'],
        'punta-gorda'           => ['Punta Gorda (Montevideo)', 'Q984180'],
        'carrasco-norte'        => ['Carrasco Norte', 'Q984512'],
        'banados-de-carrasco'   => ['Bañados de Carrasco (localidad)', 'Q812710'],
        'centro'                => ['Centro (Montevideo)', 'Q984382'],
        'ciudad-vieja'          => ['Ciudad Vieja (Montevideo)', 'Q1007576'],
        'barrio-sur'            => ['Barrio Sur (Montevideo)', 'Q808953'],
        'palermo'               => ['Palermo (Montevideo)', 'Q984385'],
        'cordon'                => ['Cordón (Montevideo)', 'Q1005742'],
        'parque-rodo'           => ['Parque Rodó', 'Q984511'],
        'tres-cruces'           => ['Tres Cruces (Montevideo)', 'Q984193'],
        'parque-batlle'         => ['Parque Batlle', 'Q588205'],
        'la-blanqueada'         => ['La Blanqueada', 'Q984515'],
        'aguada'                => ['La Aguada (Montevideo)', 'Q984427'],
        'la-comercial'          => ['La Comercial (Montevideo)', 'Q984523'],
        'villa-munoz'           => ['Villa Muñoz', 'Q984422'],
        'reducto'               => ['Reducto (Montevideo)', 'Q984388'],
        'bella-vista'           => ['Bella Vista (Montevideo)', 'Q13479688'],
        'capurro'               => ['Capurro (barrio de Montevideo)', 'Q986346'],
        'jacinto-vera'          => ['Jacinto Vera (Montevideo)', 'Q6336405'],
        'la-figurita'           => ['La Figurita', 'Q986342'],
        'brazo-oriental'        => ['Brazo Oriental', 'Q205621'],
        'atahualpa'             => ['Atahualpa (Montevideo)', 'Q753192'],
        'aires-puros'           => ['Aires Puros', 'Q408664'],
        'union'                 => ['La Unión (Montevideo)', 'Q984518'],
        'villa-espanola'        => ['Villa Española (barrio)', 'Q984929'],
        'cerrito'               => ['Cerrito de la Victoria (barrio)', 'Q177318'],
        'las-acacias'           => ['Las Acacias (Montevideo)', 'Q1661914'],
        'maronas'               => ['Maroñas (Montevideo)', 'Q984197'],
        'flor-de-maronas'       => ['Flor de Maroñas', 'Q984899'],
        'ituzaingo'             => ['Ituzaingó (Montevideo)', 'Q985110'],
        'jardines-del-hipodromo'=> ['Jardines del Hipódromo', 'Q984915'],
        'punta-de-rieles'       => ['Punta de Rieles (Montevideo)', 'Q2118579'],
        'bella-italia'          => ['Bella Italia (Montevideo)', 'Q122396855'],
        'villa-garcia'          => ['Villa García (Montevideo)', 'Q7930332'],
        'piedras-blancas'       => ['Piedras Blancas (Montevideo)', 'Q984919'],
        'manga'                 => ['Manga (Montevideo)', 'Q985379'],
        'casavalle'             => ['Casavalle', 'Q984924'],
        'paso-de-las-duranas'   => ['Paso de las Duranas', 'Q1583159'],
        'sayago'                => ['Sayago (Montevideo)', 'Q1004942'],
        'conciliacion'          => ['Conciliación (Montevideo)', 'Q985021'],
        'penarol'               => ['Peñarol (barrio)', 'Q1007597'],
        'colon'                 => ['Villa Colón', 'Q984425'],
        'abayuba'               => ['Abayubá (Montevideo)', 'Q305717'],
        'lezica'                => ['Lezica (Montevideo)', 'Q986348'],
        'melilla'               => ['Melilla (Montevideo)', 'Q6009580'],
        'belvedere'             => ['Belvedere (Montevideo)', 'Q1005240'],
        'paso-molino'           => ['Paso Molino', 'Q10345885'],
        'nuevo-paris'           => ['Nuevo París', 'Q751376'],
        'la-teja'               => ['La Teja (Montevideo)', 'Q984835'],
        'tres-ombues'           => ['Tres Ombúes', 'Q1304029'],
        'cerro'                 => ['Villa del Cerro', 'Q1010338'],
        'casabo'                => ['Casabó (Montevideo)', 'Q984873'],
        'santa-catalina'        => ['Santa Catalina (Montevideo)', 'Q18419382'],
        'pajas-blancas'         => ['Pajas Blancas (Montevideo)', 'Q2046547'],
        'paso-de-la-arena'      => ['Paso de la Arena (Montevideo)', 'Q984720'],
        'santiago-vazquez'      => ['Santiago Vázquez (localidad)', 'Q3698900'],
    ];

    /** Parrafos por servicio. {C} barrio, {refs} referencias, {llegada} tiempo, {cerca} barrios cercanos. */
    private const SERVICIO = [
        'cerrajero-a-domicilio' => [
            ['Cerrajero a domicilio en {C}: vamos hasta tu casa, apartamento o comercio con las herramientas y los repuestos, y resolvemos en el lugar. No tenés que sacar la cerradura ni acercarte a ningún local.', 'Si buscás un cerrajero que vaya a domicilio en {C}, escribinos por WhatsApp con tu dirección, cerca de {refs}, y salimos con lo necesario para abrir, cambiar o reparar en la misma visita.'],
            ['A {C} llegamos en {llegada} y también hacemos servicio a domicilio en {cerca}. El precio te lo confirmamos por WhatsApp antes de salir, con la visita incluida, y es el que pagás al terminar.', 'Llegamos a {C} en {llegada}, a cualquier hora, y cubrimos además {cerca}. Antes de salir te pasamos el precio cerrado con el traslado incluido; sin recargos por la visita ni por el horario.'],
        ],
        'cerrajero' => [
            ['Somos cerrajeros a domicilio en {C} y atendemos las 24 horas. Si te quedaste afuera, perdiste las llaves o la cerradura no funciona, escribinos por WhatsApp con tu dirección y salimos.', 'Cerrajería a domicilio en {C}, todos los días y a cualquier hora. Aperturas, cambios y reparaciones de cerraduras, copias de llaves y cerraduras de seguridad.'],
            ['Llegamos a {C} en {llegada} y también cubrimos {cerca}. Te decimos el precio por WhatsApp antes de salir y lo respetamos al llegar.', 'A {C} llegamos en {llegada}, y trabajamos también en {cerca}. Precio cerrado antes de ir, sin recargos sorpresa al terminar.'],
        ],
        'apertura-de-puertas' => [
            ['Si te quedaste afuera en {C}, no fuerces la puerta ni la cerradura. Escribinos por WhatsApp con tu dirección, cerca de {refs}, y salimos para allá.', 'Abrimos puertas en {C} las 24 horas, sin romper la cerradura en la gran mayoría de los casos. Contanos si se cerró de golpe, si está con llave o si la llave quedó adentro.'],
            ['Llegamos a {C} en {llegada}. Abrimos apartamentos, casas, portones y puertas de edificio, y si la llave quedó partida adentro la extraemos sin dañar el cilindro.', 'A {C} llegamos en {llegada}, y también atendemos {cerca}. Antes de abrir verificamos que la vivienda sea tuya, por tu seguridad.'],
        ],
        'cerrajero-automotriz' => [
            ['Si te quedaste afuera del auto en {C}, ya sea en la calle, en un estacionamiento o en tu garaje, vamos hasta donde estés. Trabajamos con autos, camionetas, utilitarios y motos.', 'Abrimos autos en {C} sin dañar la puerta ni el vidrio, con herramientas específicas para cada marca. Contanos modelo y dónde estás, cerca de {refs}, y salimos.'],
            ['Llegamos a {C} en {llegada} y cubrimos también {cerca}. Además de la apertura hacemos copias de llave con chip y extraemos llaves rotas del arranque o la puerta.', 'A {C} llegamos en {llegada}. Antes de abrir verificamos que el vehículo sea tuyo, y te pasamos el precio por WhatsApp antes de salir.'],
        ],
        'cambio-de-cerraduras' => [
            ['Cambiamos cerraduras y cilindros en {C} en el día, con la cerradura nueva incluida. Si perdiste las llaves, te forzaron la puerta o entraste a vivir a un lugar nuevo, es el momento de cambiarla.', 'Si necesitás cambiar la cerradura en {C}, vamos con varias opciones de cerradura y cilindro para que elijas según tu puerta y presupuesto.'],
            ['Llegamos a {C} en {llegada} y también atendemos {cerca}. Un cambio estándar lleva entre 30 y 60 minutos, y antes de irnos probamos todas las llaves nuevas.', 'A {C} llegamos en {llegada}. Te pasamos el precio con la cerradura incluida por WhatsApp, y si al revisar alcanza con cambiar el cilindro, te lo decimos.'],
        ],
        'cerrajero-24-horas' => [
            ['Atendemos urgencias de cerrajería en {C} a cualquier hora, de madrugada, domingos y feriados. Puertas cerradas, llaves rotas, cerraduras forzadas y autos con las llaves adentro.', 'Cerrajero de urgencia en {C}, las 24 horas los 365 días. Escribinos por WhatsApp y te respondemos al momento, sea la hora que sea.'],
            ['Llegamos a {C} en {llegada}, también de noche, y cubrimos {cerca}. El precio lo confirmamos por WhatsApp antes de salir en cualquier horario.', 'A {C} llegamos en {llegada} a cualquier hora. Sin recargos ocultos: el precio que te pasamos antes de salir es el que pagás.'],
        ],
        'reparacion-de-cerraduras' => [
            ['Reparamos cerraduras en {C} a domicilio: llaves que no giran, cerraduras trabadas, pestillos que no traban, puertas que no cierran y llaves partidas adentro del cilindro.', 'Si la cerradura de tu casa en {C} empezó a fallar, no esperes a quedarte afuera. Vamos, la revisamos y te decimos si conviene reparar o cambiar.'],
            ['Llegamos a {C} en {llegada} y atendemos también {cerca}. Llevamos repuestos para las marcas más comunes y resolvemos casi todo en la primera visita.', 'A {C} llegamos en {llegada}. Te pasamos el precio por WhatsApp antes de salir, y si al revisar conviene cambiar la cerradura, te lo decimos antes de tocar nada.'],
        ],
        'cerraduras-de-seguridad' => [
            ['Instalamos cerraduras de seguridad en {C}: cilindros antibumping, cerrojos adicionales y cerraduras multipunto para puertas de calle y blindadas. Miramos tu puerta y te recomendamos lo que realmente hace falta.', 'Si querés reforzar la seguridad de tu casa en {C}, vamos con opciones de cerradura y cilindro de distintas marcas y niveles, con precio e instalación incluidos.'],
            ['Llegamos a {C} en {llegada} y también atendemos {cerca}. La instalación lleva entre una y dos horas según el modelo, y te entregamos las llaves con su tarjeta de propiedad.', 'A {C} llegamos en {llegada}. Te pasamos opciones con precio por WhatsApp antes de ir, y en la mayoría de las puertas no hace falta cambiar la puerta.'],
        ],
        'copia-de-llaves' => [
            ['Hacemos copias de llaves a domicilio en {C}: llaves comunes, de doble paleta, de seguridad con tarjeta y de auto con chip. Vamos con el equipo y las hacemos en el momento.', 'Si necesitás duplicados de llaves en {C}, no hace falta que vayas a ningún lado: vamos a tu casa o trabajo y las hacemos ahí.'],
            ['Llegamos a {C} en {llegada} y también a {cerca}. Si no tenés el original, en muchos casos podemos hacer la llave a partir de la cerradura.', 'A {C} llegamos en {llegada}. Te pasamos el precio por WhatsApp según el tipo de llave antes de salir.'],
        ],
        'cerraduras-digitales' => [
            ['Instalamos cerraduras digitales en {C}: con código, huella, tarjeta o apertura desde el celular. Ideales para casas, apartamentos y alquileres temporarios donde cambia la gente que entra.', 'Si querés dejar de depender de las llaves en {C}, vamos con opciones de cerradura electrónica compatibles con tu puerta y te las dejamos configuradas.'],
            ['Llegamos a {C} en {llegada} y atendemos también {cerca}. Todas las cerraduras que instalamos tienen apertura de emergencia por si se agota la batería.', 'A {C} llegamos en {llegada}. Te pasamos modelos con precio por WhatsApp, cerradura e instalación incluidas, antes de ir.'],
        ],
    ];

    /** Devuelve titulo H2 y parrafos unicos para la landing servicio + barrio. */
    public static function texto(string $servicio, string $zonaSlug, array $zona, string $servicioNombre): array
    {
        $p = self::PERFIL[$zonaSlug] ?? null;
        if (!$p) return [];
        $seed = crc32($servicio . '|' . $zonaSlug);
        $pick = fn(array $arr, int $salt) => $arr[($seed + $salt) % count($arr)];

        $vars = ['{C}' => $zona['nombre'], '{refs}' => $p['refs'], '{llegada}' => $p['llegada'], '{cerca}' => $zona['cerca'] ?? 'los barrios cercanos'];
        $tpl = self::SERVICIO[$servicio] ?? self::SERVICIO['cerrajero'];

        $parrafos = [
            strtr($pick($tpl[0], 1), $vars),
            $p['perfil'] . ' ' . $pick(self::VIVIENDA[$p['vivienda']], 2),
            strtr($pick($tpl[1], 3), $vars),
        ];
        $titulos = $servicio === 'cerrajero-a-domicilio'
            ? [
                "Cerrajería a domicilio en {$zona['nombre']}: así trabajamos",
                "Un cerrajero que va a tu casa en {$zona['nombre']}",
                "Servicio de cerrajería en tu domicilio en {$zona['nombre']}",
            ]
            : [
                "{$servicioNombre} en {$zona['nombre']}: cómo trabajamos",
                "Así es el servicio de {$servicioNombre} en {$zona['nombre']}",
                "{$servicioNombre} a domicilio en {$zona['nombre']}",
            ];
        return ['titulo' => $pick($titulos, 4), 'parrafos' => $parrafos];
    }
}
