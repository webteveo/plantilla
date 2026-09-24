// Uso: NODE_PATH=<carpeta node_modules con sharp> node scripts/generate-logos.cjs
// Genera los recursos del sitio desde el SVG original, sin modificarlo.
const fs = require('node:fs/promises');
const path = require('node:path');
const sharp = require('sharp');
const dir = path.join(__dirname, '../public/images/logo');

async function main() {
  const source = await fs.readFile(path.join(dir, 'cerrajero-montevideo.svg'), 'utf8');
  // Quitar solamente el rectángulo de fondo; conservar la máscara del símbolo.
  const transparent = source.replace(/<rect\s+width="851"\s+height="286"\s+fill="white"\s*\/>/, '');
  const white = transparent.replace(/(fill|stroke)="#(?:DA1B35|033457)"/gi, '$1="#FFFFFF"');
  const render = (svg) => sharp(Buffer.from(svg), { density: 288 });
  // Conservar la proporción propia del logo. Los contenedores del sitio no cambian.
  for (const [name, svg] of [['logo', transparent], ['logo-blanco', white]]) {
    await render(svg).resize({ width: 800 }).png().toFile(path.join(dir, name + '.png'));
    await render(svg).resize({ width: 800 }).webp({ lossless: true }).toFile(path.join(dir, name + '.webp'));
  }
  await render(transparent).resize({ width: 1650 }).png().toFile(path.join(dir, 'logo-original.png'));

  // Los primeros tres trazados visibles forman la llave y la casa, incluido su contorno.
  const mask = transparent.match(/<mask\b[\s\S]*?<\/mask>/)?.[0];
  const paths = [...transparent.replace(/<mask\b[\s\S]*?<\/mask>/g, '').matchAll(/<path\b[^>]*\/>/g)].map(m => m[0]);
  if (!mask || paths.length !== 5) throw new Error('Revisar los trazados del SVG antes de generar el icono.');
  const iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 280 286" width="280" height="286" fill="none">${mask}${paths.slice(0, 3).join('')}</svg>`;
  const icon = await render(iconSvg).resize(448, 448, { fit: 'contain', background: '#00000000' })
    .extend({ top: 32, bottom: 32, left: 32, right: 32, background: '#00000000' }).png().toBuffer();
  await fs.writeFile(path.join(dir, 'icono.png'), icon);
  await sharp(icon).webp({ lossless: true }).toFile(path.join(dir, 'icono.webp'));
  await sharp(icon).resize(180, 180).flatten({ background: '#FFFFFF' }).png().toFile(path.join(dir, 'apple-touch-icon.png'));

  // ICO con imágenes PNG para navegadores y accesos directos de Windows.
  const sizes = [16, 32, 48, 64, 128, 256];
  const frames = [];
  for (const size of sizes) frames.push(await sharp(icon).resize(size, size).png().toBuffer());
  const header = Buffer.alloc(6 + 16 * sizes.length);
  header.writeUInt16LE(1, 2);
  header.writeUInt16LE(sizes.length, 4);
  let offset = header.length;
  frames.forEach((frame, i) => {
    const at = 6 + 16 * i;
    header[at] = header[at + 1] = sizes[i] === 256 ? 0 : sizes[i];
    header.writeUInt16LE(1, at + 4);
    header.writeUInt16LE(32, at + 6);
    header.writeUInt32LE(frame.length, at + 8);
    header.writeUInt32LE(offset, at + 12);
    offset += frame.length;
  });
  await fs.writeFile(path.join(dir, 'favicon.ico'), Buffer.concat([header, ...frames]));

  const socialLogo = await render(transparent).resize({ width: 1000 }).png().toBuffer();
  await sharp({ create: { width: 1200, height: 630, channels: 4, background: '#FFFFFF' } })
    .composite([{ input: socialLogo, gravity: 'centre' }]).png().toFile(path.join(dir, 'og-image.png'));
  console.log('Generados logos PNG/WebP, versión blanca, iconos, favicon e imagen OG.');
}
main().catch(error => { console.error(error); process.exitCode = 1; });
