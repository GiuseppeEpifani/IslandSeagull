<template>
  <div>
    <div class="text-center">
      <vue-dropzone
        ref="myVueDropzone"
        id="dropzone"
        :options="dropzoneOptions"
        v-on:vdropzone-removed-file="removeFile"
        v-on:vdropzone-file-added="addFile"
      ></vue-dropzone>
    </div>
  </div>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import Configuration from "../const/configurationDropzone";
export default {
  name: "NewContentComponent",
  components: {
    vueDropzone: vue2Dropzone,
  },
  props: ["errors"],
  data() {
    return {
      dropzoneOptions: Configuration,
    };
  },
  methods: {
    removeAllDropzone() {
      this.$refs.myVueDropzone.removeAllFiles();
    },
    addFile(file) {
      if (this.validateFileSize(file)) {
        this.$emit("obtain-files", file);
      } else {
        alert("La imagen excede el tamaño permitido.");
        this.removeAllDropzone();
      }
    },
    validateFileSize(file) {
      let sizeByte = file.size;
      let sizekiloBytes = parseInt(sizeByte / 1024);
      return sizekiloBytes < 2000;
    },
    removeFile(file) {
      this.$emit("delete-files", file);
    },
    uploadOnlyAnImage(file) {
      this.$refs.myVueDropzone.removeFile(file);
    },
  },
};
</script>

<style >
.dz-image img {
  width: 100% !important;
  height: 100% !important;
}

.dz-remove {
  text-decoration: none !important;
  top: 5px !important;
  right: 5px !important;
  height: 32px !important;
  width: 32px !important;
  font-size: 1.3em !important;
  line-height: 0.5em !important;
  font-weight: bold !important;
  border-radius: 1.2em;
  color: white;
  background-color: red;
  opacity: 0.5;
}

.dz-remove:hover {
  text-decoration: none !important;
  opacity: 1 !important;
}
</style>