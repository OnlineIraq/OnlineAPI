<template>
    <b-container fluid class="contain bg-white" :id="getLocale">
        <b-row>
            <admin-navbar></admin-navbar>
        </b-row>

        <b-row>
            <b-col xl="10" lg="9" md="9" sm="12" cols="12" class="overflow-x:auto order-sm-2 order-md-2 order-2"
                id="content">
                <b-container class="mt-5">
                    <b-row class="mb-4">

                        <b-col sm="2" md="2" class="my-1">
                            <b-button size="sm" v-b-modal.addModal variant="success">{{ $t("add") }}</b-button>
                        </b-col>

                        <b-col lg="4" class="my-1">
                            <b-input-group size="sm">
                                <b-form-input v-model="search" type="search" id="filterInput" :placeholder="$t('search')"
                                    v-on:keyup.enter="get((currentPage = 1)); isBusy = true;"></b-form-input>
                            </b-input-group>
                        </b-col>

                    </b-row>

                    <!-- Main table element -->
                    <b-table show-empty small stacked="md" class="mt-3 rtl" :items="podcasts" :fields="fields"
                        :busy="isBusy" bordered>
                        <template v-slot:cell(number)="row">
                            {{ parseFloat(row.index + 1 + (currentPage - 1) * perPage) }}
                        </template>

                        <template v-slot:cell(image)="row">
                            <div style="width: 100px;height: 80px">
                                <img style="width: 100%;height: 100%;object-fit: contain" :src="'/images/' + row.item.image"
                                    alt="">
                            </div>
                        </template>


                        <template v-slot:cell(action)="row">
                            <b-button variant="primary" size="sm" @click="edit(row.item, row.index)"><i
                                    class="fa fa-edit"></i></b-button>
                            <b-button variant="warning" size="sm" @click="show(row.item, row.index)"><i
                                    class="fa fa-headset"></i></b-button>
                            <b-button variant="danger" size="sm" @click="remove(row.item.id)"><i
                                    class="fa fa-times"></i></b-button>
                            <!-- Add other actions as needed -->
                        </template>


                        <template v-slot:table-busy>
                            <div class="text-center text-danger my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>{{ $t("loading") }}</strong>
                            </div>
                        </template>
                    </b-table>

                    <b-col lg="3" class="my-1">
                        <b-pagination v-model="currentPage" @change="get((currentPage = $event)); isBusy = true;"
                            :total-rows="totalRows" :per-page="perPage" align="fill" size="sm" class="my-0"></b-pagination>
                    </b-col>

                    <!-- Info modal -->
                    <b-modal id="infoModal" :content-class="{
                        right: $i18n.locale == 'en' ? false : true,
                        left: $i18n.locale == 'en' ? true : false,
                    }" :title="$t('podcast')" @show="loadAudioFiles">

                        <!-- Audio files section -->
                        <div v-if="podcast.audio_files.length > 0">
                            <h5>{{ $t('audio_files') }}</h5>
                            <ul>
                                <li v-for="audioFile in podcast.audio_files" :key="audioFile.id">
                                    <audio controls :src="'/audio/' + audioFile.audio_file_name"></audio>
                                </li>
                            </ul>
                        </div>

                        <!-- Form to add a new audio file -->
                        <div>
                            <h5>{{ $t('add_new_audio_file') }}</h5>
                            <b-form @submit.prevent="uploadAudioFile">
                                <b-form-group id="audioFileInput" label="Audio File" label-for="audioFile">
                                    <b-form-file id="audioFile" v-model="newAudioFile" accept=".mp3, .wav, .ogg"
                                        required></b-form-file>
                                </b-form-group>

                                <b-button type="submit" variant="success" :disabled="uploading">
                                    {{ uploading ? 'Uploading...' : 'Upload' }}
                                </b-button>
                            </b-form>
                        </div>


                        <template v-slot:modal-footer="{ cancel }">
                            <b-button size="sm" variant="danger" @click="cancel">{{ $t("cancel") }}</b-button>
                            <b-button size="sm" :disabled="save_disabled" variant="success" @click="save">{{ $t("save")
                            }}</b-button>
                        </template>
                    </b-modal>

                    <!-- add modal -->
                    <b-modal id="addModal" :content-class="{
                            right: $i18n.locale == 'en' ? false : true,
                            left: $i18n.locale == 'en' ? true : false,
                        }" :title="$t('podcast')" @hide="clear">
                        <div class="form-input">
                            <input type="text" autocomplete="off" required name="title" v-model="podcast.title" />
                            <label>{{ $t("title") }}</label>
                            <span class="error text-danger" v-if="errors.title">{{ errors.title[0] }}</span>
                        </div>

                        <div class="form-input">
                            <input type="text" autocomplete="off" required name="description"
                                v-model="podcast.description" />
                            <label>{{ $t("description") }}</label>
                            <span class="error text-danger" v-if="errors.description">{{ errors.description[0] }}</span>
                        </div>

                        <div class="form-input">
                            <input type="text" autocomplete="off" required name="author" v-model="podcast.author" />
                            <label>{{ $t("author") }}</label>
                            <span class="error text-danger" v-if="errors.author">{{ errors.author[0] }}</span>
                        </div>



                        <div class="file_input">
                            <label id="label-file" class="pb-1">
                                <i class="fa fa-upload"></i>
                                <span id="file_name">{{ file_msg }}</span>
                                <input type="file" @change="chooseFile()" ref="file">
                            </label>
                            <span class="error text-danger" v-if="errors.image">{{ errors.image[0] }}</span>
                        </div>

                        <img :src="podcast.imageUrl" alt="">

                        <template v-slot:modal-footer="{ cancel }">
                            <b-button size="sm" variant="danger" @click="cancel()">{{ $t("cancel") }}</b-button>
                            <b-button size="sm" :disabled="save_disabled" variant="success" @click="save()">{{ $t("save")
                            }}</b-button>
                        </template>
                    </b-modal>
                </b-container>
            </b-col>
            <sidebar></sidebar>
        </b-row>
    </b-container>
</template>

<script>
import messages from "../mixins/messages";
import axios from 'axios'; // Ensure you've imported Axios

export default {
    mixins: [messages],
    name: "podcasts",
    data() {
        return {
            podcasts: [],
            podcast: {
                id: "",
                title: "",
                description: "",
                author: "",
                audio_files: [],
                image: "",
                imageUrl: "",
            },
            newAudioFile: null,
            uploading: false,
            file_msg: this.$t('chooseAudioFile'),
            save_disabled: false,
            errors: [],
            totalRows: 1,
            currentPage: 1,
            perPage: 25,
            search: "",
            isBusy: true,
        };
    },
    methods: {

        edit(item, index) {
            this.podcast.id = item.id;
            this.podcast.title = item.title;
            this.podcast.description = item.description;
            this.podcast.author = item.author;
            this.podcast.imageUrl = '/images/' + item.image;
            // You may want to handle the audio_file separately
            // or provide an option to change the audio file.
            this.$root.$emit("bv::show::modal", "addModal");
        },
        show(item, index) {
            // You can use 'item' and 'index' to set the data in the 'infoModal' if needed.
            this.podcast.id = item.id;
            this.podcast.title = item.title;
            this.podcast.description = item.description;
            this.podcast.author = item.author;

            // You may want to handle the audio_file separately
            // or provide an option to change the audio file.
            this.$root.$emit("bv::show::modal", "infoModal");
        },
        chooseFile() {
            this.podcast.image = this.$refs.file.files[0];
            this.file_msg = this.$refs.file.files[0].name;
            this.podcast.imageUrl = URL.createObjectURL(this.podcast.image);
        },
        handleFileChange(event) {
            this.podcast.audio_file = event.target.files[0];
        },
        save() {
            this.save_disabled = true;

            let formData = new FormData();
            formData.append("id", this.podcast.id);
            formData.append("title", this.podcast.title);
            formData.append("description", this.podcast.description);
            formData.append("author", this.podcast.author);
            formData.append('image', this.podcast.image);

            let url = "/api/podcasts/add"; // Modify the URL for your API

            if (this.podcast.id !== "") {
                url = "/api/podcasts/update"; // Modify the URL for updating a podcast
            }

            axios
                .post(url, formData)
                .then((response) => {
                    this.successMessage();
                    this.get(this.currentPage);
                    this.clear();
                    this.$root.$emit("bv::hide::modal", "addModal");
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.makeToast("danger", this.$t("error_occured"));
                    }
                })
                .finally(() => {
                    this.save_disabled = false;
                });
        },
        remove(id) {
            this.confirm().then((response) => {
                if (response) {
                    axios
                        .post("/api/podcasts/delete", {
                            id: id,
                        })
                        .then((response) => {
                            this.successMessage();
                            this.get(this.currentPage);
                        })
                        .catch((error) => {
                            if (error.response.status == 422) {
                                this.errors = error.response.data.errors;
                            } else {
                                this.makeToast("danger", this.$t("error_occured"));
                            }
                        });
                }
            });
        },
        clear() {
            this.podcast.id = "";
            this.podcast.title = "";
            this.podcast.description = "";
            this.podcast.author = "";
            this.podcast.imageUrl = '';
            this.podcast.image = '';
            this.file_msg = this.$t('chooseAudioFile');
            this.errors = [];

        },
        get(page) {
            this.isBusy = true;
            let url = `/api/podcasts/get?page=${page}&search=${this.search}`; // Modify the URL for your podcast API endpoint
            axios
                .get(url)
                .then((response) => {
                    this.totalRows = response.data.total;
                    this.podcasts = response.data.data;
                    this.isBusy = false;
                })
                .catch((error) => {
                    this.makeToast("danger", this.$t("error_occured"));
                });
        },
        loadAudioFiles() {
            // Make an API request to fetch audio files for the selected podcast
            if (this.podcast.id) {
                axios.get(`/api/podcasts/${this.podcast.id}/audio-files`)
                    .then((response) => {
                        this.podcast.audio_files = response.data;
                    })
                    .catch((error) => {
                        // Handle errors
                        this.makeToast("danger", this.$t("error_occurred"));
                    });
            }
        },
        uploadAudioFile() {
            if (!this.newAudioFile) {
                this.makeToast('warning', 'Please select an audio file.');
                return;
            }

            this.uploading = true;
            let formData = new FormData();
            formData.append('podcast_id', this.podcast.id);
            formData.append('audio_file', this.newAudioFile);

            axios
                .post('/api/podcasts/audio-files/upload', formData)
                .then((response) => {
                    // On successful upload, fetch the updated list of audio files
                    return axios.get(`/api/podcasts/${this.podcast.id}/audio-files`);
                })
                .then((audioFilesResponse) => {
                    this.podcast.audio_files = audioFilesResponse.data;
                    this.successMessage();
                    this.clear();
                    this.newAudioFile = null;
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.makeToast('danger', this.$t('error_occured'));
                    }
                })
                .finally(() => {
                    this.uploading = false;
                });
        },
    },
    computed: {
        fields() {
            return [
                {
                    key: "number",
                    label: "",
                    sortable: true,
                    sortDirection: "asc",
                },
                {
                    key: "title",
                    label: this.$t("title"),
                    sortable: true,
                    sortDirection: "asc",
                },
                {
                    key: "description",
                    label: this.$t("description"),
                    sortable: true,
                },
                {
                    key: "author",
                    label: this.$t("author"),
                    sortable: true,
                },

                { key: "image", label: this.$t("image"), sortable: true },
                { key: "action", label: "#" },
            ];
        },
        getLocale() {
            if (this.$i18n.locale == "en") {
                return "left";
            }
            return "right";
        },
    },
    mounted() {
        this.get(1);
        // Fetch and populate the podcast data when the component is mounted.
        // Modify this based on how you retrieve podcast data.
    },
};
</script>

<style scoped>
/* Add custom styles for your component here */
</style>
