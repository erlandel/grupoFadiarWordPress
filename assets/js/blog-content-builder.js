(function ($) {
  function blockLabel(type) {
    return {
      title: "Título interno",
      content: "Contenido",
      image: "Imagen",
      quote: "Frase destacada",
    }[type];
  }

  function updateBlock(block) {
    const type = block.find(".gf-blog-content-block__type").val();
    block.attr("data-block-type", type);
    block.find(".gf-blog-content-block__label").text(blockLabel(type));
  }

  function initializeEditor(textarea) {
    const id = textarea.attr("id");
    if (!id || !window.wp || !wp.editor || (window.tinymce && tinymce.get(id))) {
      return;
    }

    wp.editor.initialize(id, {
      mediaButtons: false,
      quicktags: true,
      tinymce: {
        toolbar1: "formatselect,bold,italic,bullist,numlist,link,unlink,undo,redo",
        toolbar2: "",
      },
    });
  }

  function initializeBlockEditors(block) {
    if (block.find(".gf-blog-content-block__type").val() === "content") {
      block.find(".gf-blog-content-block__editor").each(function () {
        initializeEditor($(this));
      });
    }
  }

  function removeBlockEditors(block) {
    block.find(".gf-blog-content-block__editor").each(function () {
      const textarea = $(this);
      const id = textarea.attr("id");
      const editor = window.tinymce ? tinymce.get(id) : null;
      if (editor) {
        editor.save();
        wp.editor.remove(id);
      }
    });
  }

  function createTextFields(type, name, index) {
    const field = type === "content" ? "textarea" : (type === "quote" ? "textarea" : "input");
    const attributes = field === "textarea" ? ' rows="6"' : ' type="text"';
    const contentClass = type === "content" ? " gf-blog-content-block__editor" : "";
    const contentId = type === "content" ? ` id="gf-blog-content-es-${index}"` : "";
    const englishId = type === "content" ? ` id="gf-blog-content-en-${index}"` : "";
    const rows = type === "quote" ? ' rows="3"' : attributes;
    const closeTag = field === "textarea" ? "</textarea>" : "";

    const quoteAuthorEs = type === "quote" ? `<label class="gf-blog-content-block__author-label">Autor o fuente</label><input type="text" class="widefat" name="${name}[author_es]">` : "";
    const quoteAuthorEn = type === "quote" ? `<label class="gf-blog-content-block__author-label">Author or source</label><input type="text" class="widefat" name="${name}[author_en]">` : "";

    return '<div class="gf-blog-content-block__text-fields">' +
      '<div class="gf-blog-content-block__language-field"><label>Español</label>' +
      `<${field}${contentId} class="widefat${contentClass}"${rows} name="${name}[value_es]">${closeTag}${quoteAuthorEs}` +
      "</div>" +
      '<div class="gf-blog-content-block__language-field"><label>English</label>' +
      `<${field}${englishId} class="widefat${contentClass}"${rows} name="${name}[value_en]">${closeTag}${quoteAuthorEn}` +
      "</div></div>";
  }

  function createBlock(type, index) {
    const name = `gf_blog_content_blocks[${index}]`;
    const block = $("<div>", { class: "gf-blog-content-block", "data-block-type": type });

    block.append('<div class="gf-blog-content-block__header"><span class="dashicons dashicons-move gf-blog-content-block__handle" title="Arrastrar para reordenar"></span><strong class="gf-blog-content-block__label"></strong><button type="button" class="button-link-delete gf-blog-content-block__remove">Eliminar</button></div>');
    block.append($("<input>", { type: "hidden", class: "gf-blog-content-block__type", name: `${name}[type]`, value: type }));
    if (type === "image") {
      block.append(`<div class="gf-blog-content-block__image-field"><input type="hidden" class="gf-blog-content-block__image-id" name="${name}[image_id]"><div class="gf-blog-content-block__image-preview"></div><button type="button" class="button gf-blog-content-block__select-image">Seleccionar imagen</button> <button type="button" class="button-link-delete gf-blog-content-block__remove-image" hidden>Eliminar imagen</button><div class="gf-blog-content-block__text-fields"><div class="gf-blog-content-block__language-field"><label>Pie de imagen (Español)</label><input type="text" class="widefat" name="${name}[caption_es]"></div><div class="gf-blog-content-block__language-field"><label>Caption (English)</label><input type="text" class="widefat" name="${name}[caption_en]"></div></div></div>`);
    } else {
      block.append(createTextFields(type, name, index));
    }
    updateBlock(block);
    return block;
  }

  function selectImage(block) {
    const frame = wp.media({ title: "Seleccionar imagen", button: { text: "Usar esta imagen" }, multiple: false });
    frame.on("select", function () {
      const image = frame.state().get("selection").first().toJSON();
      block.find(".gf-blog-content-block__image-id").val(image.id);
      block.find(".gf-blog-content-block__image-preview").html($("<img>", { src: image.sizes.medium ? image.sizes.medium.url : image.url }));
      block.find(".gf-blog-content-block__remove-image").prop("hidden", false);
    });
    frame.open();
  }

  $(function () {
    $(".gf-blog-content-builder").each(function () {
      const builder = $(this);
      const blocks = builder.find(".gf-blog-content-builder__blocks");

      blocks.sortable({
        handle: ".gf-blog-content-block__handle",
        items: ".gf-blog-content-block",
        start: function () { blocks.children().each(function () { removeBlockEditors($(this)); }); },
        stop: function () { blocks.children().each(function () { initializeBlockEditors($(this)); }); },
      });
      blocks.children().each(function () {
        const block = $(this);
        updateBlock(block);
        initializeBlockEditors(block);
      });

      builder.on("click", "[data-add-block]", function () {
        const block = createBlock($(this).data("add-block"), Date.now());
        blocks.append(block);
        initializeBlockEditors(block);
      });
      builder.on("click", ".gf-blog-content-block__remove", function () {
        const block = $(this).closest(".gf-blog-content-block");
        removeBlockEditors(block);
        block.remove();
      });
      builder.on("click", ".gf-blog-content-block__select-image", function () { selectImage($(this).closest(".gf-blog-content-block")); });
      builder.on("click", ".gf-blog-content-block__remove-image", function () {
        const block = $(this).closest(".gf-blog-content-block");
        block.find(".gf-blog-content-block__image-id").val("");
        block.find(".gf-blog-content-block__image-preview").empty();
        $(this).prop("hidden", true);
      });
    });
  });
})(jQuery);
