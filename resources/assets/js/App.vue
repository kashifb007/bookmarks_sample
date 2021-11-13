<template>
  <div id="app">

    <div class="paragraph">
      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" v-on:input="debounceInput"
               id="search" type="text" name="search"/>
        <label class="mdl-textfield__label" for="search">Mega Search</label>
      </div>
    </div>

    <div class="mobile_scroll">
      Swipe left and right<br/>
      <i class="material-icons mdc-icon-button__icon">sync_alt</i>
    </div>

    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent button is-success" href="/bookmarks/create"
    >Create Bookmark</a>
    <div class="app">
      <bookmark-component
          v-for="bookmark in filteredBookmarks"
          v-bind="bookmark"
          :key="bookmark.id"
      ></bookmark-component>
    </div>
  </div>
</template>
<script>
function Bookmark(
    {
      id,
      edit_url,
      bookmark_id,
      url,
      alt_text,
      site_logo,
      your_title,
      short_title,
      your_desc,
      short_desc,
      image,
      short_og_title,
      short_og_desc
    }) {
  this.id = id;
  this.edit_url = edit_url;
  this.bookmark_id = bookmark_id;
  this.url = url;
  this.alt_text = alt_text;
  this.site_logo = site_logo;
  this.your_title = your_title;
  this.short_title = short_title;
  this.your_desc = your_desc;
  this.short_desc = short_desc;
  this.image = image;
  this.short_og_title = short_og_title;
  this.short_og_desc = short_og_desc;
}

import BookmarkComponent from './components/BookmarkComponent.vue';
import axios from 'axios';

export default {
  data() {
    return {
      bookmarks: [],
      search: ''
    }
  },
  computed: {
    filteredBookmarks() {
      return this.bookmarks.filter((bookmark) => {
        return bookmark.your_title.toLowerCase().includes(this.search.toLowerCase());
      })
    }
  },
  methods: {
    debounceInput: _.debounce(function (e) {
      this.search = e.target.value;
    }, 1000),
    async read() {
      const {data} = await axios.get('/api/bookmarks');
      data.forEach(bookmark => this.bookmarks.push(new Bookmark(bookmark)));
    },
  },
  components: {
    BookmarkComponent
  },
  created() {
    this.read();
  }
}
</script>