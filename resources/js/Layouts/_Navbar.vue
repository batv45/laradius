<template>
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
            <ul class="navbar-nav">
                <nav-bar-link tag="dashboard" active="dashboard">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <smart-home-icon/>
                                </span>
                    <span class="nav-link-title">
                                  Ana Sayfa
                                </span>
                </nav-bar-link>
                <nav-bar-link tag="account.index"
                              active="account.">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <building-icon class=""></building-icon>
                                </span>
                    <span class="nav-link-title">
                                  Abonelikler
                                </span>
                </nav-bar-link>
                <nav-bar-link tag="router.index"
                              active="router.">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <router-icon class=""></router-icon>
                                </span>
                    <span class="nav-link-title">
                                  Mikrotik
                                </span>
                </nav-bar-link>
                <nav-bar-link tag="admin.user.index"
                              v-if="userHasPermission('user.show')"
                              active="admin.user.">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <users-icon class="text-primary"></users-icon>
                                </span>
                    <span class="nav-link-title">
                                  Kullanıcılar
                                </span>
                </nav-bar-link>
            </ul>
        </div>
    </div>
</template>

<script>
import NavBarLink from "../Components/NavBarLink";
export default {
    name: "Navbar",
    components: {NavBarLink}
}
</script>

<style scoped>

</style>
