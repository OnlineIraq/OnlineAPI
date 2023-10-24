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
                            <b-button size="sm" v-b-modal.infoModal variant="success">{{
                                $t("add")
                            }}</b-button>
                        </b-col>


                        <b-col lg="4" class="my-1">
                            <b-input-group size="sm">
                                <b-form-input v-model="search" type="search" id="filterInput" :placeholder="$t('search')"
                                    v-on:keyup.enter="
                                        get((currentPage = 1));
                                    isBusy = true;
                                    "></b-form-input>
                            </b-input-group>
                        </b-col>
                    </b-row>

                    <!-- Main table element -->
                    <b-table show-empty small stacked="md" class="mt-3 rtl" :items="products" :fields="fields" :busy="isBusy"
                        bordered>
                        <template v-slot:cell(number)="row">
                            {{ parseFloat(row.index + 1 + (currentPage - 1) * 25) }}
                        </template>



                        <template v-slot:cell(image)="row">
                            <div style="width: 100px;height: 80px">
                                <img style="width: 100%;height: 100%;object-fit: contain" :src="'/images/' + row.item.Image"
                                    alt="">
                            </div>
                        </template>

                        <template v-slot:cell(action)="row">
                            <div style="width: 150px;height: 80px">
                                <b-button variant="warning" size="sm" @click="notify(row.item)"><i
                                        class="fa fa-bell"></i></b-button>
                                <b-button variant="primary" size="sm" @click="edit(row.item, row.index)"><i
                                        class="fa fa-edit"></i></b-button>
                                <b-button variant="danger" size="sm" @click="remove(row.item.id)"><i
                                        class="fa fa-times"></i></b-button>
                            </div>

                        </template>

                        <template v-slot:table-busy>
                            <div class="text-center text-danger my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Loading...</strong>
                            </div>
                        </template>
                    </b-table>

                    <b-col lg="3" class="my-1">
                        <b-pagination v-model="currentPage" @change="
                            get((currentPage = $event));
                        isBusy = true;
                        " :total-rows="totalRows" :per-page="perPage" align="fill" size="sm"
                            class="my-0"></b-pagination>
                    </b-col>

                    <!-- Info modal -->
                    <b-modal id="infoModal" :content-class="{
                        right: $i18n.locale == 'en' ? false : true,
                        left: $i18n.locale == 'en' ? true : false,
                    }" :title="$t('product')" @hide="clear">


                        <div class="form-input">
                            <input type="text" autocomplete="off" required name="KuTitle" v-model="product.KuTitle" />
                            <label>{{ $t("KuTitle") }}</label>
                            <span class="error text-danger" v-if="errors.KuTitle">{{
                                errors.KuTitle[0]
                            }}</span>
                        </div>

                        <div class="form-input">
                            <input type="text" autocomplete="off" required name="EnTitle" v-model="product.EnTitle" />
                            <label>{{ $t("EnTitle") }}</label>
                            <span class="error text-danger" v-if="errors.EnTitle">{{
                                errors.EnTitle[0]
                            }}</span>
                        </div>

                        <div class="form-input">
                            <input type="text" autocomplete="off" required name="ArTitle" v-model="product.ArTitle" />
                            <label>{{ $t("ArTitle") }}</label>
                            <span class="error text-danger" v-if="errors.ArTitle">{{
                                errors.ArTitle[0]
                            }}</span>
                        </div>

                        <div class="form-input">
                            <textarea name="" id="" cols="30" rows="10" v-model="product.KuDesc">{{ $t("KuDesc") }}</textarea>
                            <span class="error text-danger" v-if="errors.KuDesc">{{ errors.KuDesc[0] }}</span>
                        </div>

                        <div class="form-input">
                            <textarea name="" id="" cols="30" rows="10" v-model="product.EnDesc">{{ $t("EnDesc") }}</textarea>
                            <span class="error text-danger" v-if="errors.EnDesc">{{ errors.EnDesc[0] }}</span>
                        </div>


                        <div class="form-input">
                            <textarea name="" id="" cols="30" rows="10" v-model="product.ArDesc">{{ $t("ArDesc") }}</textarea>
                            <span class="error text-danger" v-if="errors.ArDesc">{{ errors.ArDesc[0] }}</span>
                        </div>

                        <div class="form-input">
                            <input type="text" autocomplete="off" required name="Price" v-model="product.Price" />
                            <label>{{ $t("Price") }}</label>
                            <span class="error text-danger" v-if="errors.Price">{{
                                errors.Price[0]
                            }}</span>
                        </div>

                        <div class="file_input">
                            <label id="label-file" class="pb-1">
                                <i class="fa fa-upload"></i>
                                <span id="file_name">{{ file_msg }}</span>
                                <input type="file" @change="choosedfile()" ref="file">
                            </label>
                            <span class="error text-danger" v-if="errors.image">{{ errors.image[0] }}</span>
                        </div>

                        <img :src="product.imageUrl" alt="">



                        <template v-slot:modal-footer="{ cancel }">
                            <!-- Emulate built in modal footer ok and cancel button actions -->
                            <b-button size="sm" variant="danger" @click="cancel()">
                                {{ $t("cancel") }}
                            </b-button>
                            <b-button size="sm" :disabled="save_disabled" variant="success" @click="save()">
                                {{ $t("save") }}
                            </b-button>
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

export default {
    mixins: [messages],
    name: "home",
    data() {
        return {
            products: [],
            product: {
                id: "",
                KuTitle: "",
                ArTitle: "",
                EnTitle: "",
                KuDesc: "",
                EnDesc: "",
                ArDesc: "",
                Price: "",
                image: "",
                imageUrl: "",
            },
            file_msg: this.$t('choosePhoto'),
            save_disabled: false,
            btn_p_disabled: false,
            btn_s_disabled: false,
            errors: [],
            filter: null,
            filterOn: [],
            totalRows: 1,
            currentPage: 1,
            perPage: 25,
            sortBy: "",
            search: "",
            isBusy: true,
        };
    },
    methods: {
        edit(item, index) {

            this.product.id = item.id;
            this.product.KuTitle = item.KuTitle;
            this.product.EnTitle = item.EnTitle;
            this.product.ArTitle = item.ArTitle;
            this.product.KuDesc = item.KuDesc;
            this.product.EnDesc = item.EnDesc;
            this.product.ArDesc = item.ArDesc;
            this.product.Price = item.Price;

            this.product.imageUrl = '/images/' + item.Image;
            this.$root.$emit("bv::show::modal", "infoModal");
        },
        choosedfile() {
            this.product.image = this.$refs.file.files[0];
            this.file_msg = this.$refs.file.files[0].name;
            this.product.imageUrl = URL.createObjectURL(this.product.image);
        },
        save() {
            this.save_disabled = true;

            let url = "";

            if (this.product.id == "") {
                url = "/api/products/add";
            } else {
                url = "/api/products/update";
            }

            var formdata = new FormData();
            formdata.append("id", this.product.id);
            formdata.append("KuTitle", this.product.KuTitle);
            formdata.append("KuDesc", this.product.KuDesc);
            formdata.append("ArTitle", this.product.ArTitle);
            formdata.append("ArDesc", this.product.ArDesc);
            formdata.append("Price", this.product.Price);
            formdata.append("EnTitle", this.product.EnTitle);
            formdata.append("EnDesc", this.product.EnDesc);
            formdata.append('image', this.product.image);

            axios
                .post(url, formdata)
                .then((response) => {
                    console.log(response);
                    this.successMessage();
                    this.get();
                    this.$root.$emit("bv::hide::modal", "infoModal");
                })
                .catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.makeToast("danger", this.$t("error_occured"));
                    }
                });


            this.save_disabled = false;
        },
        get(page) {
            this.isBusy = true;
            axios
                .get("/api/products/get?page=" + page + "&search=" + this.search)
                .then((response) => {
                    this.totalRows = response.data.total;
                    this.products = response.data.data;
                    this.isBusy = false;
                })
                .catch((error) => {
                    this.makeToast("danger", this.$t("error_occured"));
                });
        },
        notify(item) {
            var payload = {
                title: item.KuTitle,
                body: item.KuDesc,
                id: item.id,
            };

            this.confirm().then((response) => {
                if (response)
                    axios.post("/api/send-notification-all", payload)
                        .then((response) => {
                            this.makeToast("success", "Notification sent successfully.");
                            this.get();
                        })
                        .catch((error) => {
                            if (error.response.status == 422) {
                                this.makeToast("danger", "Error sending notification.");
                            } else {
                                this.makeToast("danger", this.$t("error_occured"));
                            }
                        });
            });
        },
        remove(id) {
            this.confirm().then((response) => {
                if (response)
                    axios
                        .post("/api/products/delete", {
                            id: id,
                        })
                        .then((response) => {
                            this.successMessage();
                            this.get();
                        })
                        .catch((error) => {
                            if (error.response.status == 422) {
                                this.errors = error.response.data.errors;
                            } else {
                                this.makeToast("danger", this.$t("error_occured"));
                            }
                        });
            });
        },
        clear() {
            this.product.id = "";
            this.product.KuTitle = "";
            this.product.KuDesc = "";
            this.product.ArTitle = "";
            this.product.ArDesc = "";
            this.product.Price = "";
            this.product.EnTitle = "";
            this.product.EnDesc = "";
            this.product.imageUrl = '';
            this.product.image = '';
            this.file_msg = this.$t('choosePhoto');
            this.errors = [];
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
                    key: "KuTitle",
                    label: this.$t("KuTitle"),
                    sortable: true,
                    sortDirection: "asc",
                },
                { key: "EnTitle", label: this.$t("EnTitle"), sortable: true },
                { key: "ArTitle", label: this.$t("ArTitle"), sortable: true },
                { key: "Price", label: this.$t("Price"), sortable: true },
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
        getUrl() {
            return this.$route.path;
        },
    },
    mounted() {
        this.get(1);
    },
};
</script>

<style></style>
