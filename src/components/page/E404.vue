<template>
   <div>
      <p class="err_page">404</p>
   </div>   
</template>

<script>
export default {
  name: 'E404',
   metaInfo() {
		return {
			title: 'E404',
			meta: [
			{ vmid: 'description', name: 'description', content: "E404" }
			]
		}
	}   
}
</script>
 
<style>
.err_page{
  font-size: 150px;    
}
</style>