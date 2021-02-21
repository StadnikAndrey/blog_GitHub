<template>
   <div>
      <h1 class="title_page">Контакты</h1>
      <ul class="contacts_ul">
        <li><a href="mailto:mail@blogonvue.zzz.com.ua"> mail@blogonvue.zzz.com.ua</a></li>
      </ul>
   </div>   
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: 'Contacts',
  metaInfo () {
      return {
        title: this.domain + ': Контакты',
        meta: [
          { vmid: 'description', name: 'description', content: "Контакты " + this.domain }
        ]
      }
  },
  computed: {
    ...mapGetters('global', {
      domain: 'domain'
    })
  }
}
</script>

<style>
.contacts_ul li{
  margin: 0 0 20px 0;
}
.contacts_ul li a{
  text-decoration: none;
}
</style>