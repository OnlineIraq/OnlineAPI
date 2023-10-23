<template>
  <div class="w-100">
    <b-navbar class="navbar-expand" type="dark" variant="dark">
      <b-navbar-brand href="#">{{ $t("website") }}</b-navbar-brand>

      <div class="collapse navbar-collapse">
        <ul class="navbar-nav" id="language_collapse">
          <!-- languages -->
          <li class="nav-item dropdown language-menu">
            <a class="nav-link" @click="showlanguage = !showlanguage">
              <i class="fa fa-globe"></i>
            </a>

            <div
              :class="{
                dropdown: true,
                'dropdown-menu': true,
                languages: true,
                'd-block': showlanguage,
              }"
            >
              <div class="dropdown-item" @click="setLocale('ku')">
                <p>
                  <span>kurdish</span>
                </p>
                <img src="/assets/images/Kurdish.png" class="flag-img" />
              </div>

              <div class="dropdown-item" @click="setLocale('en')">
                <p>
                  <span>english</span>
                </p>
                <img src="/assets/images/English.png" class="flag-img" />
              </div>

              <div class="dropdown-item" @click="setLocale('ar')">
                <p>
                  <span>arabic</span>
                </p>
                <img src="/assets/images/Arabic.png" class="flag-img" />
              </div>
            </div>
          </li>
        </ul>
      </div>

      <button
        class="hamburger hamburger--3dxy-r d-lg-none d-md-none"
        @click="toggleSlidebar"
      >
        <span class="hamburger-box">
          <span class="hamburger-inner"></span>
        </span>
      </button>
    </b-navbar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showlanguage: false,
      lastExchanges: [],
      errors: [],
      disabled: false,
    };
  },
  computed: {},
  methods: {
    setLocale(lang) {
      this.$i18n.locale = lang;
      this.$store.dispatch("changeLang", lang);
      this.showlanguage = false;
      window.axios.defaults.headers.common["locale"] = lang;
      axios.get("/locale/" + lang);
    },
    toggleSlidebar() {
      this.$store.dispatch("toggleSlidebar");
    },
    clear() {},
  },
  created() {},
};
</script>

<style lang="scss" scoped>
/* dropdown language */
.flag-img {
  width: 30px;
  height: 20px;
}

.languages {
  width: 200px;
  border-top: 2px solid #ee4266;
  margin-top: 15px;
}

.languages a,
.languages a:hover {
  text-decoration: none;
}

.dropdown-item {
  height: 30px;
}

.languages .dropdown-item {
  display: flex;
  justify-content: space-between;
  cursor: pointer;
}

.nav-link {
  cursor: pointer;
}

#right .dropdown-menu {
  right: 0;
  left: auto;
}

#left .dropdown-menu {
  left: 0;
  right: auto;
}

.hamburger {
  height: 40px;
  margin-top: -14px;
  outline: none !important;
}

/* dropdown language */
</style>
