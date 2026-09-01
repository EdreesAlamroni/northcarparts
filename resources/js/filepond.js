import * as FilePond from 'filepond';
import arAR from 'filepond/locale/ar-ar.js';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

FilePond.setOptions(arAR);

export function filepondInput(config, wire = null) {
    return {
        pond: null,

        init() {
            const options = {
                allowMultiple: Boolean(config.allowMultiple),
                allowImagePreview: Boolean(config.allowImagePreview),
            };

            if (config.acceptedFileTypes.length > 0) {
                options.acceptedFileTypes = config.acceptedFileTypes;
            }

            if (config.maxFileSize) {
                options.maxFileSize = config.maxFileSize;
            }

            if (config.livewire && wire) {
                options.server = {
                    process: (fieldName, file, metadata, load, error, progress, abort) => {
                        const completeUpload = (temporaryFilename) => {
                            load(Array.isArray(temporaryFilename) ? temporaryFilename[0] : temporaryFilename);
                        };

                        const updateProgress = (event) => {
                            progress(event.lengthComputable, event.loaded, event.total);
                        };

                        if (config.allowMultiple) {
                            wire.$uploadMultiple(
                                config.uploadProperty,
                                [file],
                                completeUpload,
                                error,
                                updateProgress,
                                abort,
                            );
                        } else {
                            wire.$upload(
                                config.uploadProperty,
                                file,
                                completeUpload,
                                error,
                                updateProgress,
                                abort,
                            );
                        }

                        return {
                            abort: () => wire.$cancelUpload(config.uploadProperty),
                        };
                    },
                    revert: (filename, load, error) => {
                        wire.$removeUpload(config.uploadProperty, filename, load, error);
                    },
                };
            } else {
                options.storeAsFile = true;
            }

            this.pond = FilePond.create(this.$refs.input, options);
        },

        destroy() {
            if (this.pond) {
                this.pond.destroy();
                this.pond = null;
            }
        },
    };
}
