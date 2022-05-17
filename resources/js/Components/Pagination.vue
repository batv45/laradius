<template>
    <div>
        <ul v-if="links.length > 3" class="pagination m-0 ml-auto">
            <li v-for="(link, key) in links" class="page-item"
                :class="{'active': link.active,'disabled':link.url === null}">
                <div v-if="link.url === null" class="page-link" :key="key"
                     v-html="link.label">
                </div>
                <Link v-else :key="key" class="page-link" :href="link.url"
                      v-html="link.label">
                </Link>
            </li>
        </ul>
    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue";

export default {
    components: {
        Link
    },
  props: {
    links: Array
  }
}
</script>
