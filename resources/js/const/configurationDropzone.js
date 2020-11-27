export default {
    url: "/",
    autoProcessQueue: false,
    maxFiles: 10,
    acceptedFiles: "image/jpeg,image/png,image/jpg,image",
    addRemoveLinks: true,
    thumbnailWidth: 150,
    thumbnailHeight: 150,
    dictRemoveFile: "×",
    dictDefaultMessage:
        "<i class='fas fa-cloud-upload-alt'></i>  Click o Arrastre la foto.",
    dictInvalidFileType: "No se puede subir este tipo de archivos.",
    dictMaxFilesExceeded:
        "Se ha excedido el numero de archivos permitidos.",
    dictFileTooBig:
        "La imagen revasa el tamaño permitido ({{filesize}}MiB). Tam. Max : {{maxFilesize}}MiB.",
    dictFallbackMessage:
        "Su navegador no soporta arrastrar y soltar para subir archivos.",
    dictInvalidFileType: "No se puede subir este tipo de archivos.",
}