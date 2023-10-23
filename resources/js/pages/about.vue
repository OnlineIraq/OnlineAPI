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

          <!-- Main table element -->
          <b-table
            show-empty
            small
            stacked="md"
            class="mt-3 rtl"
            :items="abouts"
            :fields="fields"
            :busy="isBusy"
            bordered
          >

            <template v-slot:cell(action)="row">
              <b-button
                variant="primary"
                size="sm"
                @click="edit(row.item, row.index)"
                ><i class="fa fa-edit"></i
              ></b-button>

            </template>

            <template v-slot:table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
          </b-table>

          <!-- Info modal -->
          <b-modal
            id="infoModal"
            :content-class="{
              right: $i18n.locale == 'en' ? false : true,
              left: $i18n.locale == 'en' ? true : false,
            }"
            :title="$t('about')"
            @hide="clear"
          >


              <div class="form-input">
                  <textarea name="" id="" cols="30" rows="10" v-model="about.KuDesc">{{$t("KuDesc")}}</textarea>
                  <span class="error text-danger" v-if="errors.KuDesc">{{errors.KuDesc[0]}}</span>
              </div>

              <div class="form-input">
                  <textarea name="" id="" cols="30" rows="10" v-model="about.EnDesc">{{$t("EnDesc")}}</textarea>
                  <span class="error text-danger" v-if="errors.EnDesc">{{errors.EnDesc[0]}}</span>
              </div>

              <div class="form-input">
                  <textarea name="" id="" cols="30" rows="10" v-model="about.ArDesc">{{$t("ArDesc")}}</textarea>
                  <span class="error text-danger" v-if="errors.EnDesc">{{errors.EnDesc[0]}}</span>
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
      abouts: [],
      about: {
        id: "",
        KuDesc: "",
        EnDesc: "",
        ArDesc: ""
      },
      save_disabled: false,
      btn_p_disabled: false,
      btn_s_disabled: false,
      errors: [],
      isBusy: true,
    };
  },
  methods: {
    edit(item, index) {
      this.about.id = item.id;
      this.about.KuDesc = item.KuDesc;
      this.about.EnDesc = item.EnDesc;
      this.about.ArDesc = item.ArDesc;
      this.$root.$emit("bv::show::modal", "infoModal");
    },
    save() {
      this.save_disabled = true;

      let url = "";


        url = "/api/about/update";


      var formdata = new FormData();
      formdata.append("id", this.about.id);
      formdata.append("KuDesc", this.about.KuDesc);
      formdata.append("EnDesc", this.about.EnDesc);
      formdata.append("ArDesc", this.about.ArDesc);

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
    get() {
      this.isBusy = true;
      axios
        .get("/api/about/get")
        .then((response) => {
          this.abouts = response.data;
          this.isBusy = false;
        })
        .catch((error) => {
          this.makeToast("danger", this.$t("error_occured"));
        });
    },
    clear() {
      this.about.id = "";
      this.about.KuDesc = "";
      this.about.EnDesc = "";
      this.about.ArDesc = "";

      this.errors = [];
    },
  },
  computed: {
    fields() {
      return [
        {
          key: "KuDesc",
          label: this.$t("KuDesc"),
          sortable: true,
          sortDirection: "asc",
        },
        { key: "EnDesc", label: this.$t("EnDesc"), sortable: true },
        { key: "ArDesc", label: this.$t("ArDesc"), sortable: true },
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
    this.get();
  },
};
</script>

<style>
</style>
