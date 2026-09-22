(function () {
  'use strict';

  const container = document.getElementById('hero-share');
  if (!container) return;

  const btn = container.querySelector('[data-share-btn]');
  if (!btn) return;

  const title  = container.dataset.shareTitle || '';
  const url    = container.dataset.shareUrl   || '';
  const date   = container.dataset.shareDate  || '';
  const author = container.dataset.shareAuthor || '';
  const image  = container.dataset.shareImage || '';

  let shareText = title;
  if (date) {
    shareText += '\n' + date;
    if (author) shareText += ' \u00b7 ' + author;
  }
  shareText += '\n\nLee m\u00e1s: ' + url;

  btn.addEventListener('click', function () {

    // Fallback: navigator.share() no está disponible (navegadores de escritorio)
    // wa.me abre un chat directo de WhatsApp — no permite elegir app ni destino,
    // pero es la opción más universal cuando el navegador no soporta el share sheet.
    if (typeof navigator.share !== 'function') {
      const waUrl = 'https://wa.me/?text=' + encodeURIComponent(shareText);
      window.open(waUrl, '_blank');
      return;
    }

    // ═══════════════════════════════════════════════════════════
    //  Web Share API — intento principal
    // ═══════════════════════════════════════════════════════════

    async function doShare() {
      let file = null;

      // ── Descargar imagen → Blob → File ─────────────────────
      // Web Share API requiere objetos File nativos (no URLs).
      // Convertimos la URL de la imagen destacada a File para que
      // Instagram/Facebook Stories puedan recibirla como adjunto.
      if (image) {
        try {
          const resp = await fetch(image);
          const blob = await resp.blob();

          // Extraer nombre y extensión del archivo de la URL
          const segments = image.split('/');
          const filename = segments[segments.length - 1] || 'noticia.jpg';
          const mimeType = blob.type || 'image/jpeg';

          file = new File([blob], filename, { type: mimeType });

        } catch (fetchErr) {
          console.warn('Share: no se pudo obtener la imagen, se comparte sin ella', fetchErr);
        }
      }

      const shareData = {
        title: title,
        text: shareText,
        url: url,
      };

      // ── Verificar soporte de archivos ──────────────────────
      // navigator.canShare() permite testear ANTES de compartir.
      // Algunos navegadores aceptan share() con files pero fallan
      // después; canShare previene ese error silenciosamente.
      if (file && navigator.canShare && navigator.canShare({ files: [file] })) {
        shareData.files = [file];
      }

      try {
        await navigator.share(shareData);

      } catch (err) {

        // AbortError = el usuario cerró el share sheet sin elegir
        // nada. No es un error real, no debe mostrarse en consola.
        if (err.name === 'AbortError') return;

        // Si falló el intento con archivos, reintentar sin ellos.
        // Safari iOS a veces falla al compartir archivos aunque
        // canShare() haya devuelto true — es un bug conocido.
        if (shareData.files && shareData.files.length) {
          try {
            delete shareData.files;
            await navigator.share(shareData);
            return;
          } catch (retryErr) {
            if (retryErr.name === 'AbortError') return;
          }
        }

        console.error('Share error:', err);
      }
    }

    doShare().catch(function (err) {
      if (err.name !== 'AbortError') console.error('Share error:', err);
    });
  });
})();
