<template>
  <div>
    <h3 class="admin_child_title">Управление рубриками</h3>
    <router-view></router-view>       
  </div>       
</template>

<script>
export default {
  name: 'AdminRubrics'   
}
</script>
 
<style>
.admin_child_title{
  padding: 8px 0 25px 0;
} 
</style>