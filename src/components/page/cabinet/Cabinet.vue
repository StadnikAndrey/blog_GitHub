<template>
   <div v-if="user" class="cabinet_wrap">       
    <h2 class="cabinet_title">Личный кабинет пользователя <span>{{ user.login }}</span></h2>
    <router-view></router-view>
   </div>   
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'Cabinet',   
  computed: {
    ...mapGetters('user', {
      user: 'user'
    })
  }
}
</script>
 
<style>
.cabinet_wrap{
  padding: 50px 0 0 0;
}
.cabinet_title{
  font-size: 21px;
  margin: 0 0 50px 0;
}
.cabinet_title span{
  font-weight: bold;
}
</style>