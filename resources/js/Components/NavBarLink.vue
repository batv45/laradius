<template>
    <li class="nav-item" :class="{
      //  'active':$page.props.app.current_route.indexOf(active)!==-1
    }">
        <Link class="nav-link"
              :class="{'rounded bg-primary-lt':$page.props.app.current_route.indexOf(active)!==-1}"
              :style="{'background: rgb(32 107 196 / 8%)!important;':$page.props.app.current_route.indexOf(active)!==-1}"
              :href="href?href:route(tag)">
            <slot/>
        </Link>
    </li>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue";

export default {
    name: "NavBarLink",
    components: {Link},

    props: {
        active: String,
        tag: String,
        href: String
    }
};
</script>

<style scoped>

</style>
