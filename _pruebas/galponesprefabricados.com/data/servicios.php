<?php
/** Servicios de galponesprefabricados.com. Principal: galpones prefabricados (todas las zonas). Secundarios: troncal + zonas con demanda. */
return [

    'galpones-prefabricados' => [
        'nombre'      => 'galpones prefabricados',
        'nombre_h1'   => 'Galpones prefabricados',
        'descripcion' => 'Galpones prefabricados de estructura metálica, fabricados en taller y montados en obra, para depósito, comercio, industria y campo. Presupuesto a medida por WhatsApp según medidas, uso y terreno.',
        'zonas'       => '*',
        'sinonimos'   => ['galpones metálicos', 'galpones de chapa', 'galpones premoldeados', 'naves prefabricadas', 'depósitos prefabricados'],
        'entidades'   => ['estructura metálica', 'perfiles conformados', 'cerchas', 'columnas y vigas reticuladas', 'chapa trapezoidal galvanizada', 'platea de hormigón', 'fundaciones y anclajes', 'cerramiento lateral', 'portón corredizo', 'canalones y bajadas', 'aislación térmica', 'permiso de construcción'],
        'imagen'      => '',
        'imagen_alt'  => 'Galpón prefabricado de estructura metálica montado en {zona}',
        'beneficios'  => [
            ['titulo' => 'Presupuesto a medida',   'texto' => 'Te pasamos el presupuesto según medidas, altura, uso y terreno, antes de empezar.'],
            ['titulo' => 'Fabricación en taller',  'texto' => 'La estructura se fabrica en piezas y se monta en obra, con menos días de trabajo en el terreno.'],
            ['titulo' => 'Cobertura en {padre}',   'texto' => 'Coordinamos visita al terreno en {zona} y alrededores para relevar medidas y accesos.'],
        ],
        'faq' => [
            ['q' => '¿Cuánto cuesta un galpón prefabricado en {zona}?', 'a' => 'Depende de las medidas, la altura, el tipo de cerramiento y el terreno. Mandanos por WhatsApp las medidas aproximadas y el uso que le vas a dar, y {marca} te pasa un presupuesto a medida sin cargo.'],
            ['q' => '¿Qué incluye el presupuesto de un galpón?', 'a' => 'Estructura metálica, techo y cerramientos que se acuerden, montaje y, si hace falta, la platea o las fundaciones. Cada ítem se detalla por separado para que sepas qué estás pagando.'],
            ['q' => '¿Hace falta permiso de construcción para un galpón en {zona}?', 'a' => 'En general sí: las intendencias piden permiso de construcción para galpones nuevos, con planos firmados por un técnico. Te orientamos sobre qué pide la intendencia que corresponde a {zona}.'],
            ['q' => '¿Se puede ampliar o desmontar un galpón prefabricado?', 'a' => 'Sí. Al ser estructura metálica abulonada, se puede agrandar agregando pórticos o desmontar y trasladar. Conviene preverlo al diseñar.'],
        ],
        'wsp_mensaje' => 'Hola, quiero presupuesto para un galpón prefabricado en {zona}',
        'schema_extra'=> ['additionalType' => 'https://es.wikipedia.org/wiki/Galp%C3%B3n'],
    ],

    'galpones-metalicos' => [
        'nombre'      => 'galpones metálicos',
        'nombre_h1'   => 'Galpones metálicos',
        'descripcion' => 'Galpones de estructura metálica con perfiles conformados o reticulados y cubierta de chapa, para depósitos, talleres y locales. Presupuesto a medida por WhatsApp.',
        'zonas'       => ['montevideo', 'canelones', 'las-piedras', 'pando', 'ciudad-de-la-costa', 'santa-lucia'],
        'sinonimos'   => ['estructuras metálicas', 'galpones de hierro', 'galpones de chapa'],
        'entidades'   => ['perfil C conformado', 'viga reticulada', 'pórtico', 'correas', 'chapa galvanizada calibre 25', 'pintura antióxido', 'anclajes químicos', 'luz libre'],
        'beneficios'  => [
            ['titulo' => 'Estructura calculada',   'texto' => 'Pórticos y correas dimensionados según la luz, la altura y el viento de la zona.'],
            ['titulo' => 'Montaje abulonado',      'texto' => 'Piezas fabricadas en taller y abulonadas en obra: menos soldadura en el terreno.'],
            ['titulo' => 'Ampliable',              'texto' => 'Se pueden sumar pórticos después, sin rehacer la estructura.'],
        ],
        'faq' => [
            ['q' => '¿Qué diferencia hay entre un galpón metálico y uno prefabricado?', 'a' => 'En la práctica son lo mismo: casi todos los galpones prefabricados son de estructura metálica. "Metálico" pone el acento en el material; "prefabricado" en que se fabrica en taller y se monta en obra.'],
            ['q' => '¿Qué luz libre puede tener un galpón metálico?', 'a' => 'Depende del diseño del pórtico. Con vigas reticuladas se cubren luces amplias sin columnas intermedias; te lo confirmamos al ver el proyecto.'],
        ],
    ],

    'tinglados' => [
        'nombre'      => 'tinglados',
        'nombre_h1'   => 'Tinglados',
        'descripcion' => 'Tinglados metálicos abiertos o semicerrados para cubrir maquinaria, vehículos, acopio o áreas de trabajo. Estructura y techo de chapa, con o sin laterales. Presupuesto por WhatsApp.',
        'zonas'       => ['montevideo', 'canelones', 'las-piedras', 'pando', 'ciudad-de-la-costa', 'san-ramon'],
        'sinonimos'   => ['techados metálicos', 'cobertizos', 'tinglados de chapa', 'estructuras para techado'],
        'entidades'   => ['columnas metálicas', 'cabriadas', 'chapa trapezoidal', 'pendiente de techo', 'canalón', 'altura libre', 'bases de hormigón'],
        'beneficios'  => [
            ['titulo' => 'Menos obra que un galpón', 'texto' => 'Sin cerramiento lateral el tinglado se monta más rápido y cuesta menos.'],
            ['titulo' => 'Se cierra después',        'texto' => 'Un tinglado se puede convertir en galpón agregando laterales más adelante.'],
            ['titulo' => 'A medida',                 'texto' => 'Altura y ancho según lo que vas a cubrir: camiones, maquinaria agrícola, acopio.'],
        ],
        'faq' => [
            ['q' => '¿Un tinglado necesita permiso de construcción?', 'a' => 'Suele necesitarlo como cualquier construcción fija; depende de la intendencia y del tamaño. Consultalo antes de empezar; te orientamos con lo que pide cada intendencia.'],
            ['q' => '¿Qué altura conviene para un tinglado?', 'a' => 'Para autos alcanza con menos altura; para camiones o maquinaria agrícola se necesita más altura libre bajo la cabriada. Se define según lo que vas a guardar.'],
        ],
    ],

    'galpones-agricolas' => [
        'nombre'      => 'galpones agrícolas',
        'nombre_h1'   => 'Galpones agrícolas',
        'descripcion' => 'Galpones para chacras y establecimientos rurales: maquinaria, acopio de granos y forraje, packing, cría y depósito de insumos. Estructura metálica prefabricada, presupuesto por WhatsApp.',
        'zonas'       => ['canelones', 'san-ramon', 'tala', 'santa-rosa', 'sauce', 'san-jacinto'],
        'sinonimos'   => ['galpones rurales', 'galpones para campo', 'galpones para maquinaria', 'depósitos rurales'],
        'entidades'   => ['acopio de forraje', 'maquinaria agrícola', 'packing', 'ventilación', 'altura de portón', 'piso de hormigón', 'chacra', 'establecimiento rural'],
        'beneficios'  => [
            ['titulo' => 'Pensado para el uso rural', 'texto' => 'Portones altos para maquinaria, ventilación para acopio y cerramientos según lo que se guarde.'],
            ['titulo' => 'Montaje en el campo',     'texto' => 'Coordinamos accesos de camiones y el montaje en el predio.'],
            ['titulo' => 'Ampliable por módulos',   'texto' => 'Se empieza con lo necesario y se agregan pórticos cuando crece la producción.'],
        ],
        'faq' => [
            ['q' => '¿Qué tamaño de galpón agrícola conviene para guardar maquinaria?', 'a' => 'Se calcula a partir de las máquinas más grandes que vas a guardar, más espacio de maniobra. Mandanos la lista de maquinaria por WhatsApp y te proponemos medidas.'],
            ['q' => '¿Un galpón rural en {zona} necesita permiso?', 'a' => 'En suelo rural también se tramita permiso de construcción ante la intendencia. Te orientamos sobre el trámite para {zona}.'],
        ],
    ],

    'galpones-industriales' => [
        'nombre'      => 'galpones industriales',
        'nombre_h1'   => 'Galpones industriales',
        'descripcion' => 'Galpones industriales y naves para fábricas, logística y depósitos, con estructura metálica calculada para grandes luces, portones para camiones y oficinas integradas. Presupuesto por WhatsApp.',
        'zonas'       => ['montevideo', 'canelones', 'pando', 'las-piedras', 'barros-blancos', 'colonia-nicolich'],
        'sinonimos'   => ['naves industriales', 'galpones logísticos', 'depósitos industriales', 'galpones para fábrica'],
        'entidades'   => ['nave industrial', 'luz libre', 'puente grúa', 'portón seccional', 'muelle de carga', 'habilitación industrial', 'zona industrial', 'oficinas y vestuarios'],
        'beneficios'  => [
            ['titulo' => 'Calculado para el uso',   'texto' => 'Luces, alturas y cargas según el proceso: estanterías, puente grúa o tránsito de autoelevadores.'],
            ['titulo' => 'Etapas',                  'texto' => 'Se puede construir por etapas: primero la nave, después oficinas y anexos.'],
            ['titulo' => 'Coordinación con técnicos', 'texto' => 'Trabajamos con tu arquitecto o ingeniero para el permiso y la habilitación.'],
        ],
        'faq' => [
            ['q' => '¿Qué altura necesita un galpón industrial con estanterías?', 'a' => 'Depende de la altura de las estanterías y del equipo de carga. Se define la altura libre bajo viga con ese dato.'],
            ['q' => '¿Se puede instalar un puente grúa en un galpón prefabricado?', 'a' => 'Sí, si la estructura se calcula para esa carga desde el diseño. Hay que definirlo antes de fabricar los pórticos.'],
        ],
    ],

];
