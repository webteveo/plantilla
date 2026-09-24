<?php /** Cabecera oscura de páginas internas (nosotros, contacto, legales, hub de zona). */ ?>
<section class="page-intro">
  <div class="container page-intro__inner">
    <p class="page-intro__eyebrow"><?= e($pagina['seo']['eyebrow']) ?></p>
    <h1 class="page-intro__title"><?= e($pagina['seo']['h1']) ?></h1>
    <?php if ($pagina['seo']['subtitulo']): ?><p class="page-intro__desc"><?= e($pagina['seo']['subtitulo']) ?></p><?php endif; ?>
  </div>
</section>
