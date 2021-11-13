<template>
  <div class="bookmark mdc-card demo-card demo-basic-with-header">
    <div class="demo-card__primary">
      <p class="demo-card__title mdc-typography mdc-typography--headlin6">

        <span class="logo" :title="alt_text"><img :src="site_logo" :alt="alt_text"/></span>
        <span :title="your_title">{{ short_title }}</span></p>
      <p class="demo-card__subtitle mdc-typography mdc-typography--subtitle2" title="your_desc">{{
          short_desc
        }}</p>
    </div>
    <div id="card_click" class="mdc-card__primary-action demo-card__primary-action" tabindex="0">
      <div class="mdc-card__media mdc-card__media--16-9 demo-card__media" v-bind:style="{ 'background-image': 'url(' + image + ')'}"
           :title="your_title"></div>
      <div class="demo-card__secondary mdc-typography mdc-typography--body2">{{ short_og_title }}<p class="show_more">
        {{ short_og_desc }}</p>
      </div>
    </div>
    <div class="mdc-card__actions">
      <div class="mdc-card__action-buttons">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" title="Visit Bookmark"
                v-on:click="openInNewTab(url)">
          <span class="mdc-button__ripple"></span>Visit
        </button>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent copy-button"
          :data-clipboard-text="url" title="Copy URL to clipboard"><span class="mdc-button__ripple"></span>Copy
      </button>
      </div>

      <div class="mdc-card__action-icons">
        <button id="toggle_show"
                class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                title="Reveal" data-mdc-ripple-is-unbounded="true">
          <i id="expand_more" class="material-icons mdc-icon-button__icon expand_more">expand_more</i>
          <i id="expand_less"
             class="material-icons mdc-icon-button__icon expand_less mdc-icon-button__icon--on">expand_less</i>
        </button>
        <button id="favourites"
                class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                title="Add to Home page" data-mdc-ripple-is-unbounded="true">
          <i id="favourite_border" class="material-icons mdc-icon-button__icon favorite_border">favorite_border</i>
          <i id="favourite" class="material-icons mdc-icon-button__icon favorite mdc-icon-button__icon--on">favorite</i>
        </button>
        <button id="share-button"
                class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                title="Share" data-mdc-ripple-is-unbounded="true">share
        </button>
        <ul class="mdl-menu mdl-js-menu mdl-menu--top-right mdl-js-ripple-effect" for="share-button">
          <li class="mdl-menu__item"><a href="#" title="Share via Email">Email</a></li>
          <li class="mdl-menu__item"><a href="#" title="Post about this bookmark on Facebook">Facebook</a></li>
          <li class="mdl-menu__item"><a href="#" title="Tweet this bookmark">Twitter</a></li>
        </ul>

        <button id="edit" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                @click="goToEdit()" title="Edit"
                data-mdc-ripple-is-unbounded="true">
          <i class="material-icons mdc-icon-button__icon">create</i>
        </button>
        <button id="delete" class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                @click="goToDelete()" title="Delete"
                data-mdc-ripple-is-unbounded="true">
          <i class="material-icons mdc-icon-button__icon">delete</i>
        </button>

<!--        <button id="edit-button"-->
<!--                class="mdc-icon-button material-icons mdc-card__action mdc-card__action&#45;&#45;icon&#45;&#45;unbounded"-->
<!--                title="More options" data-mdc-ripple-is-unbounded="true">more_vert-->
<!--        </button>-->
<!--        <ul class="mdl-menu mdl-js-menu mdl-menu&#45;&#45;top-right mdl-js-ripple-effect" for="edit-button">-->
<!--          <li class="mdl-menu__item"><a href="#">Set Order</a></li>-->
<!--          <li class="mdl-menu__item"><a href="#">Edit</a></li>-->
<!--          <li class="mdl-menu__item"><a href="#">Delete</a></li>-->
<!--        </ul>-->

      </div>
    </div>
  </div>
</template>
<script>
jQuery(document).ready(function () {
  jQuery('.mdc-card').matchHeight();
});
</script>
<script>
export default {
  methods:{
    openInNewTab : function(url) {
      var win = window.open(url, '_blank');
      win.focus();
    },
    goToEdit(){
      this.$router.push(this.edit_url + this.bookmark_id + '/edit');
    },
    goToDelete(){
      this.$router.push(this.edit_url + this.bookmark_id + '/delete');
    }
  },
  props: [
    'id',
    'edit_url',
    'bookmark_id',
    'url',
    'alt_text',
    'site_logo',
    'your_title',
    'short_title',
    'your_desc',
    'short_desc',
    'image',
    'short_og_title',
    'short_og_desc',
  ],
}
</script>