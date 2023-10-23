<template>
  <b-container fluid class="contain bg-white" :id="getLocale">
    <b-row>
      <admin-navbar></admin-navbar>
    </b-row>

    <b-row>
      <b-col
        xl="10"
        lg="9"
        md="9"
        sm="12"
        cols="12"
        class="overflow-x:auto order-sm-2 order-md-2 order-2"
        id="content"
      >
        <b-container class="mt-5">
          <b-row class="mb-4">
            <b-col sm="2" md="2" class="my-1">
              <b-button size="sm" v-b-modal.infoModal variant="success">{{
                $t("add")
              }}</b-button>
            </b-col>

            <b-col lg="4" class="my-1">
              <b-input-group size="sm">
                <b-form-input
                  v-model="search"
                  type="search"
                  id="filterInput"
                  :placeholder="$t('search')"
                  v-on:keyup.enter="
                    get((currentPage = 1));
                    isBusy = true;
                  "
                ></b-form-input>
              </b-input-group>
            </b-col>
          </b-row>

          <!-- Main table element -->
          <b-table
            show-empty
            small
            stacked="md"
            class="mt-3 rtl"
            :items="sites"
            :fields="fields"
            :busy="isBusy"
            bordered
          >
            <template v-slot:cell(number)="row">
              {{ parseFloat(row.index + 1 + (currentPage - 1) * 25) }}
            </template>

            <template v-slot:cell(action)="row">
              <b-button
                variant="primary"
                size="sm"
                @click="edit(row.item, row.index)"
                ><i class="fa fa-edit"></i
              ></b-button>
              <b-button variant="danger" size="sm" @click="remove(row.item.id)"
                ><i class="fa fa-times"></i
              ></b-button>
            </template>

            <template v-slot:table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
          </b-table>

          <b-col lg="3" class="my-1">
            <b-pagination
              v-model="currentPage"
              @change="
                get((currentPage = $event));
                isBusy = true;
              "
              :total-rows="totalRows"
              :per-page="perPage"
              align="fill"
              size="sm"
              class="my-0"
            ></b-pagination>
          </b-col>

          <!-- Info modal -->
          <b-modal
            id="infoModal"
            :content-class="{
              right: $i18n.locale == 'en' ? false : true,
              left: $i18n.locale == 'en' ? true : false,
            }"
            :title="$t('sites')"
            @hide="clear"
          >
            <div class="form-input">
              <input
                type="text"
                autocomplete="off"
                required
                name="KuTitle"
                v-model="site.KuTitle"
              />
              <label>{{ $t("KuTitle") }}</label>
              <span class="error text-danger" v-if="errors.KuTitle">{{
                errors.KuTitle[0]
              }}</span>
            </div>
            <div class="form-input">
              <input
                type="text"
                autocomplete="off"
                required
                name="EnTitle"
                v-model="site.EnTitle"
              />
              <label>{{ $t("EnTitle") }}</label>
              <span class="error text-danger" v-if="errors.EnTitle">{{
                errors.EnTitle[0]
              }}</span>
            </div>
            <div class="form-input">
              <input
                type="text"
                autocomplete="off"
                required
                name="lat"
                v-model="site.lat"
              />
              <label>{{ $t("lat") }}</label>
              <span class="error text-danger" v-if="errors.lat">{{
                errors.lat[0]
              }}</span>
            </div>
              <div class="form-input">
                  <input
                      type="text"
                      autocomplete="off"
                      required
                      name="long"
                      v-model="site.long"
                  />
                  <label>{{ $t("long") }}</label>
                  <span class="error text-danger" v-if="errors.long">{{
                errors.long[0]
              }}</span>
              </div>

            <template v-slot:modal-footer="{ cancel }">
              <!-- Emulate built in modal footer ok and cancel button actions -->
              <b-button size="sm" variant="danger" @click="cancel()">
                {{ $t("cancel") }}
              </b-button>
              <b-button
                size="sm"
                :disabled="save_disabled"
                variant="success"
                @click="save()"
              >
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
      sites: [],
      site: {
        id: "",
        KuTitle: "",
        EnTitle: "",
        lat: "",
        long: "",
      },
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
      this.site.id = item.id;
      this.site.KuTitle = item.KuTitle;
      this.site.EnTitle = item.EnTitle;
      this.site.lat = item.lat;
      this.site.long = item.long;
      this.$root.$emit("bv::show::modal", "infoModal");
    },
    save() {
      this.save_disabled = true;

      let url = "";

      if (this.site.id == "") {
        url = "/api/sites/add";
      } else {
        url = "/api/sites/update";
      }

      var formdata = new FormData();
      formdata.append("id", this.site.id);
      formdata.append("KuTitle", this.site.KuTitle);
      formdata.append("EnTitle", this.site.EnTitle);
      formdata.append("lat", this.site.lat);
      formdata.append("long", this.site.long);

      axios
        .post(url, formdata)
        .then((response) => {
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
        .get("/api/sites/get?page=" + page + "&search=" + this.search)
        .then((response) => {
          this.totalRows = response.data.total;
          this.sites = response.data.data;
          this.isBusy = false;
        })
        .catch((error) => {
          this.makeToast("danger", this.$t("error_occured"));
        });
    },
    remove(id) {
      this.confirm().then((response) => {
        if (response)
          axios
            .post("/api/sites/delete", {
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
      this.site.id = "";
      this.site.KuTitle = "";
      this.site.EnTitle = "";
      this.site.lat = "";
      this.site.long = "";
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
        { key: "lat", label: this.$t("lat"), sortable: true },
        { key: "long", label: this.$t("long"), sortable: true },
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

<style>
</style>
