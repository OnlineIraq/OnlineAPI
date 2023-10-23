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
            :items="users"
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
            :title="$t('admin')"
            @hide="clear"
          >
            <div class="form-input">
              <input
                type="text"
                autocomplete="off"
                required
                name="name"
                v-model="user.name"
              />
              <label>{{ $t("name") }}</label>
              <span class="error text-danger" v-if="errors.name">{{
                errors.name[0]
              }}</span>
            </div>
            <div class="form-input">
              <input
                type="text"
                autocomplete="off"
                required
                name="email"
                v-model="user.email"
              />
              <label>{{ $t("email") }}</label>
              <span class="error text-danger" v-if="errors.email">{{
                errors.email[0]
              }}</span>
            </div>
            <div class="form-input">
              <input
                type="password"
                autocomplete="off"
                required
                name="password"
                v-model="user.password"
              />
              <label>{{ $t("password") }}</label>
              <span class="error text-danger" v-if="errors.password">{{
                errors.password[0]
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
      users: [],
      user: {
        id: "",
        name: "",
        email: "",
        password: "",
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
      this.user.id = item.id;
      this.user.name = item.name;
      this.user.email = item.email;
      this.$root.$emit("bv::show::modal", "infoModal");
    },
    save() {
      this.save_disabled = true;

      let url = "";

      if (this.user.id == "") {
        url = "/api/admin/add";
      } else {
        url = "/api/admin/update";
      }

      var formdata = new FormData();
      formdata.append("id", this.user.id);
      formdata.append("name", this.user.name);
      formdata.append("email", this.user.email);
      formdata.append("password", this.user.password);

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
        .get("/api/admin/get?page=" + page + "&search=" + this.search)
        .then((response) => {
          this.totalRows = response.data.total;
          this.users = response.data.data;
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
            .post("/api/admin/delete", {
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
      this.user.id = "";
      this.user.name = "";
      this.user.email = "";
      this.user.password = "";
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
          key: "name",
          label: this.$t("name"),
          sortable: true,
          sortDirection: "asc",
        },
        { key: "email", label: this.$t("email"), sortable: true },
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
