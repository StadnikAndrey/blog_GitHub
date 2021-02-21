<template>
   <div>
    <h1 class="insert_article_title">Рекомендации по оформлению статьи</h1>      
    <ul class="reference_ul">
        <li>Заголовок и подзаголовок могут содержать любые символы кроме одинарных кавычек.</li>
        <li>Текст статьи может содержать любые символы. Количество переводов строки учитывается.</li>
        <li>Фотографии отображаются в их реальном размере.
            При редактировании фотографии должны быть загружены снова.
        </li>
        <li>Для демонстрации HTML-кода используйте спецсимволы &amp;lt; и &amp;gt; для замены угловых скобок &lt; и &gt; .</li>
        <li>При использовании материалов других источников необходимо указать ссылку на эти источники.</li>
        <li>Статья будет доступна к просмотру после проверки администратором.</li>
        <li>Внести свои предложения вы можете воспользовавшись страницей "Контакты".</li>
    </ul>       
   </div>       
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: 'ReferenceArticle',
  metaInfo() {
		return {
			title: this.domain + ' - Как оформить статью',
			meta: [
			{ vmid: 'description', name: 'description', content: "Как оформить статью" + this.domain }
			]
		}
	},   
  computed:{
    ...mapGetters('global', {
      domain: 'domain'
    })
  }
}
</script>
 
<style>
.reference_ul li{
  margin: 0 0 20px 0;
}
</style>
