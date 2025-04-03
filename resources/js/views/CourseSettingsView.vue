<template>
  <div>
    <h2 class="heading-title-margin heading-title-bold">
      KPAS kompetansepakke innstillinger
    </h2>

    <div class="settings">
      <div class="settings-item">
        <div class="settings-item-top">
          <div class="settings-item-header">
            <label for="settings-new">
              <h3 class="item-title">Ny kompetansepakke?</h3>

              <p class="item-description">
                Huk av her for å vise at dette er en ny pakke. Dette legger en
                ny tag på kortet i oversikten over kompetansepakker.
              </p>
            </label>
          </div>

          <div class="settings-item-body">
            <input
              id="settings-new"
              type="checkbox"
              class="item-checkbox"
              default="0"
              true-value="1"
              false-value="0"
              v-model="currentcourseSettings.course_category.new"
            />
          </div>
        </div>
      </div>

      <div class="settings-item">
        <div class="settings-item-top">
          <div class="settings-item-header">
            <label for="settings-license">
              <h3 class="item-title">Vis MOOC lisens på bunnen</h3>

              <p class="item-description">
                Viser MOOC-lisens tekst i bunnen på alle kurssider, dette bør
                være aktivert på de fleste kursene med mindre innholdet er
                opphavsrettsbeskyttet.
              </p>
            </label>
          </div>

          <div class="settings-item-body">
            <input
              id="settings-license"
              type="checkbox"
              class="item-checkbox"
              true-value="1"
              false-value="0"
              v-model="currentcourseSettings.licence"
            />
          </div>
        </div>
      </div>

      <div class="settings-item">
        <div class="settings-item-top">
          <div class="settings-item-header">
            <label for="settings-role">
              <h3 class="item-title">
                Tilgangskontroll for lærer- og lederroller
              </h3>

              <p class="item-description">
                Rollesupport henviser til frontend støtte for å fjerne sider med
                "lederstøtte" for de som ikke har riktig rolle. Ved avhukning
                blir alle sider med et innrykk fjernet for "deltakere" og vises
                kun til "leder/eier" rolle.
              </p>
            </label>
          </div>

          <div class="settings-item-body">
            <input
              id="settings-role"
              type="checkbox"
              class="item-checkbox"
              true-value="1"
              false-value="0"
              v-model="currentcourseSettings.role_support"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="settings-section">
      <h3 class="settings-section-title heading-title-margin">Banner</h3>

      <div class="settings">
        <div class="settings-item">
          <div class="settings-item-top">
            <div class="settings-item-header">
              <h3 class="item-title">Vis banner</h3>

              <p class="item-description">
                Velg hva slags type banner som skal vises og legg til en tekst
                for meldingen. Sett til 'NONE' for å skru av banneren.
              </p>
            </div>

            <div class="settings-item-body"></div>
          </div>

          <div class="settings-item-bottom">
            Banner:
            <v-select
              class="item-select"
              :options="bannerTypes"
              :close-on-select="true"
              v-model="currentcourseSettings.banner_type"
              :clearable="false"
            ></v-select>

            <p
              class="banner-text-p"
              v-if="currentcourseSettings.banner_type != 'NONE'"
            >
              Banner tekst:
              <textarea
                id="banner-textarea"
                type="text"
                maxlength="255"
                v-model="currentcourseSettings.banner_text"
                @input="characterCounter"
              />
            </p>
            <span id="character-counter"></span>
          </div>
        </div>

        <div class="settings-item">
          <div class="settings-item-top">
            <div class="settings-item-header">
              <label for="settings-unmaintained">
                <h3 class="item-title">Har vedlikehold avsluttet?</h3>

                <p class="item-description">
                  Vis banner må være satt til å vise 'UNMAINTAINED' for at dette
                  varslet skal vises. Legg til en banner som varsler brukerne om
                  at vedlikeholdet av kompetansepakken har avsluttet fra valgt
                  dato.
                </p>
              </label>
            </div>

            <div class="settings-item-body">
              <input
                id="settings-unmaintained"
                type="checkbox"
                class="item-checkbox"
                true-value="1"
                false-value="0"
                v-model="unmaintained"
              />
            </div>
          </div>

          <div class="settings-item-bottom" v-if="unmaintained == '1'">
            Hvilken dato utgår vedlikeholdet?
            <input
              type="date"
              clearable
              v-model="currentcourseSettings.unmaintained_since"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="settings-section">
      <h3 class="settings-section-title heading-title-margin">
        Hovedbilde & Språk
      </h3>

      <div class="settings">
        <div class="settings-item">
          <div class="settings-item-top">
            <div class="settings-item-header">
              <h3 class="item-title">Sett hovedbilde</h3>

              <p class="item-description">
                Velg et bilde som skal brukes på banneret øverst på alle sider
                og på kortet i oversikten.
              </p>
            </div>

            <div class="settings-item-body"></div>
          </div>

          <div class="settings-item-bottom">
            <div>
              <div v-if="imageSelected">
                <p>Valgt bilde:</p>
                <img :src="selectedImage.path" />
                <br />
              </div>
              <button class="kpas-button" @click="openPopup">Velg bilde</button>
            </div>

            <div v-if="open">
              <v-layout
                row
                wrap
                primary-title
                v-for="image in courseImages"
                :key="image.id"
              >
                <v-flex xs6>
                  <img
                    @click="selectImage(image)"
                    v-bind:src="`${image.path}`"
                    alt="illustration"
                  />
                </v-flex>
              </v-layout>
            </div>
          </div>
        </div>

        <div class="settings-item">
          <div class="settings-item-top">
            <div class="settings-item-header">
              <h3 class="item-title">Skru på multispråklig støtte</h3>

              <p class="item-description">
                Legger til støtte for et annet språk, f.eks. for flerspråklig
                tittel: nb til kompetansepakken | se: Gealbopáhka birra
              </p>
            </div>

            <div class="settings-item-body"></div>
          </div>

          <div class="settings-item-bottom">
            Multilang:
            <v-select
              class="item-select"
              :options="multilangTypes"
              :close-on-select="true"
              :clearable="false"
              v-model="currentcourseSettings.multilang"
            ></v-select>
          </div>
        </div>
      </div>
    </div>

    <div class="settings-section">
      <h3 class="settings-section-title heading-title-margin">
        Fargetema & Kategorier
      </h3>

      <div class="settings">
        <div class="settings-item">
          <div class="settings-item-top">
            <div class="settings-item-header">
              <h3 class="item-title">Velg fargetema</h3>

              <p class="item-description">
                Et fargetema bestemmer hvilken fargeprofil kompetansepakken tar
                i bruk. Velg hovedkategorien denne pakken tilhører, så vil du
                automatisk få riktig tema.
              </p>
            </div>

            <div class="settings-item-body"></div>
          </div>

          <div class="settings-item-bottom">
            Fargetema:
            <v-select
              class="item-select"
              :options="allCategories"
              label="name"
              placeholder="--- Kategori ---"
              :close-on-select="true"
              :clearable="false"
              v-model="selectedCategory"
            ></v-select>
            Plassering:
            <input
              type="number"
              default="0"
              v-model="currentcourseSettings.course_category.position"
              disabled
            />
          </div>
        </div>

        <div class="settings-item">
          <div class="settings-item-top">
            <div class="settings-item-header">
              <h3 class="item-title">Velg kategorier</h3>

              <p class="item-description">
                Legg til kategorier som er relevante for kompetansepakken. Dette
                brukes for tagging og filtrering på forsiden og mine
                kompetansepakker.
              </p>
            </div>

            <div class="settings-item-body"></div>
          </div>

          <div class="settings-item-bottom">
            Kategorier:
            <v-select
              class="item-select"
              multiple
              :options="allFilters"
              label="filter_name"
              placeholder="--- Kategorier ---"
              :close-on-select="true"
              :clearable="true"
              v-model="selectedFilters"
            ></v-select>
          </div>
        </div>
      </div>
    </div>

    <div class="settings-save">
      <button class="kpas-button" @click="updateCourseSettings">
        Lagre endringer
      </button>

      <span class="ml-3" v-if="isSubmitting"
        >Sender
        <div class="spinner-border text-success"></div
      ></span>

      <div v-if="responseCode == 200" class="alert alert-success kpasAlert">
        Oppdateringen var vellykket!
      </div>

      <div v-if="error" class="alert alert-danger kpasAlert">{{ error }}</div>
    </div>

    <hr />

    <div class="settings-section">
      <h2 class="heading-title-margin heading-title-bold">
        Admin innstillinger
      </h2>

      <div>
        <h3 class="settings-section-title heading-title-margin">
          Opprett ny kategori
        </h3>
        <course-settings-filter-create
          :filterTypes="filterTypes"
          @update="newFilterUpdate"
        ></course-settings-filter-create>
      </div>

      <div class="settings-section">
        <h3 class="settings-section-title heading-title-margin">
          Opprett ny fargetema
        </h3>
        <course-settings-category-create
          @update="newCategoryUpdate"
        ></course-settings-category-create>
      </div>

      <div class="settings-section">
        <h3 class="settings-section-title heading-title-margin">
          Velg fremhevet kompetansepakke
        </h3>
        <course-settings-set-highligthed-course
          :courses="publiccourses"
          :current="currenthighlighted"
        ></course-settings-set-highligthed-course>
      </div>
    </div>
  </div>
</template>

<script>
import "vue-select/dist/vue-select.css";
import api from "../api";
import CourseSettingsCategoryCreate from "../components/CourseSettingsCategoryCreate.vue";
import CourseSettingsFilterCreate from "../components/CourseSettingsFilterCreate.vue";
import CourseSettingsSetHighligthedCourse from "../components/CourseSettingsSetHighligthedCourse.vue";

export default {
  name: "CourseSettingsView",
  props: {
    courseid: Number,
    coursesettings: {},
    filters: [],
    categories: [],
    multilangtypes: [],
    bannertypes: [],
    filtertypes: [],
    isadmin: Boolean,
    courseimages: [],
    publiccourses: [],
    currenthighlighted: {},
  },
  components: {
    CourseSettingsCategoryCreate,
    CourseSettingsFilterCreate,
    CourseSettingsSetHighligthedCourse,
  },
  data() {
    return {
      currentcourseSettings: this.coursesettings ? this.coursesettings : {},
      allFilters: this.filters,
      allCategories: this.categories,
      filterTypes: this.filtertypes,
      bannerTypes: this.bannertypes,
      multilangTypes: this.multilangtypes,
      selectedFilters: [],
      selectedCategory: undefined,
      error: undefined,
      isSubmitting: false,
      responseCode: undefined,
      courseImages: this.courseimages,
      open: false,
      selectedImage: undefined,
      imageSelected: false,
      unmaintained: "0",
    };
  },
  created() {
    if (this.coursesettings && this.coursesettings.course_category) {
      this.setSelectedCategory();
    }
    if (this.coursesettings && this.coursesettings.course_filter) {
      this.setSelectedFilters();
    }
    if (this.coursesettings && this.coursesettings.image) {
      this.setSelectedImage();
    }
    if (!this.coursesettings) {
      this.setEmptyCourseSettings();
    }
    if (this.currentcourseSettings.unmaintained_since != null) {
      this.unmaintained = "1";
    }
  },
  updated() {},
  methods: {
    characterCounter() {
      const textarea = document.getElementById("banner-textarea");
      const charCountDiv = document.getElementById("character-counter");
      const currentLength = textarea.value.length;
      const remainingChars = 255 - currentLength;
      charCountDiv.textContent = `Gjenstående tegn: ${remainingChars}`;
    },
    selectImage(image) {
      this.selectedImage = image;
      this.open = false;
      this.imageSelected = true;
    },
    openPopup() {
      this.open = true;
    },
    toggleDialog() {
      this.dialog = !this.dialog;
    },
    setSelectedFilters() {
      this.coursesettings.course_filter.forEach((filter) => {
        this.selectedFilters.push(
          this.allFilters.find((f) => f.id == filter.filter_id)
        );
      });
    },
    setSelectedCategory() {
      this.selectedCategory = this.allCategories.find(
        (c) => c.id == this.coursesettings.course_category.category_id
      );
    },
    setSelectedImage() {
      this.selectedImage = this.courseImages.find(
        (i) => i.id == this.coursesettings.image.id
      );
      this.imageSelected = true;
    },
    setEmptyCourseSettings() {
      this.currentcourseSettings.unmaintained_since = null;
      this.currentcourseSettings.licence = 0;
      this.currentcourseSettings.role_support = 0;
      this.currentcourseSettings.banner_type = "NONE";
      this.currentcourseSettings.banner_text = null;
      this.currentcourseSettings.multilang = "NONE";
      this.currentcourseSettings.course_filter = [];
      this.currentcourseSettings.image_id = null;
      this.currentcourseSettings.course_category = {};
      this.currentcourseSettings.course_category.position = 0;
      this.currentcourseSettings.course_category.new = false;
    },
    newCategoryUpdate(category) {
      this.allCategories.push(category);
    },
    newFilterUpdate(filter) {
      this.allFilters.push(filter);
    },
    async updateCourseSettings() {
      this.responseCode = undefined;
      this.error = undefined;
      this.isSubmitting = true;
      let response = undefined;
      if (this.selectedCategory == undefined) {
        this.error = "Du må velge kategori";
        this.isSubmitting = false;
        return;
      }
      if (this.selectedImage == undefined) {
        this.error = "Du må velge bilde";
        this.isSubmitting = false;
        return;
      }
      if (this.unmaintained == "0") {
        this.currentcourseSettings.unmaintained_since = null;
      }
      try {
        response = await api.put("course/" + this.courseid + "/settings", {
          cookie: window.cookie,
          unmaintained_since: this.currentcourseSettings.unmaintained_since,
          licence: this.currentcourseSettings.licence,
          role_support: this.currentcourseSettings.role_support,
          banner_type: this.currentcourseSettings.banner_type,
          banner_text: this.currentcourseSettings.banner_text,
          multilang: this.currentcourseSettings.multilang,
          courseCategory: this.selectedCategory.id
            ? [
                {
                  course_id: this.courseid,
                  category_id: this.selectedCategory.id,
                  position: this.currentcourseSettings.course_category.position,
                  new: this.currentcourseSettings.course_category.new,
                },
              ]
            : null,
          courseFilters: this.selectedFilters
            ? this.selectedFilters.map((f) => {
                return {
                  course_id: this.courseid,
                  filter_id: f.id,
                };
              })
            : [],
          image_id: this.selectedImage ? this.selectedImage.id : null,
        });
      } catch (e) {
        this.isSubmitting = false;
        this.error = "Noe gikk galt, prøv igjen senere";
        console.log(e);
      }
      if (response.data.status == 200) {
        this.isSubmitting = false;
        this.responseCode = response.data.status;
        this.error = undefined;
      }
    },
  },
  mounted() {
    this.characterCounter();
  },
};
</script>

<style scoped>
section {
  padding-bottom: 2em;
}

p {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}
input {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}
button {
  margin-bottom: 1em;
  margin-top: 1em;
}

img {
  height: 8em;
  &:hover {
    cursor: pointer;
  }
}
.image-selector {
  height: 30em;
  overflow-y: scroll;
  border: 1px solid gray;
}

.heading-title-margin {
  margin-bottom: 20px;
}

.heading-title-bold {
  font-weight: bold;
}

.settings-section {
  margin-top: 60px;
}

.settings-save {
  margin-top: 40px;
}

.settings-section-title {
  font-size: 24px;
  padding: 0px;
}

.settings {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  margin-bottom: 20px;
  gap: 20px;
}

.settings label {
  cursor: pointer;
  width: 100%;
  padding: 0px;
}

.settings img {
  background: white;
}

.settings .settings-item {
  display: flex;
  flex-direction: column;
  background: #eaeaf5;
  border-radius: 10px;
  padding: 20px;
  min-width: 500px;
  max-width: calc(50% - 20px);
  flex: 1 0 0px;
}

.settings .settings-item.--full-width {
  max-width: 100%;
  width: 100%;
}

.settings .settings-item-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.settings .settings-item-bottom {
  margin-top: 15px;
  padding-top: 20px;
  border-top: 4px solid white;
}

.settings .settings-item-header {
  margin-right: 20px;
  max-width: 600px;
}

.settings .item-title {
  padding: 0px;
  font-weight: bold;
  margin-bottom: 8px;
  font-size: 20px;
}

.settings .item-description {
  margin: 0px;
}

.settings .item-checkbox {
  width: 20px;
  height: 20px;
}

.settings .item-select {
  background: white;
}

textarea {
  width: 100%;
  field-sizing: content;
  min-height: 1lh;
}

.banner-text-p {
  margin-bottom: 0;
}

@media only screen and (max-width: 650px) {
  .settings img {
    max-width: 100%;
  }

  .settings .settings-item {
    min-width: 100%;
  }
}
</style>
