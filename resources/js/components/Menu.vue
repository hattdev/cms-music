<template>
    <nav class="navbar navbar-expand-md navbar-light  bg-light ">
        <button type="button" id="sidebarCollapse" class="btn btn-primary d-none d-lg-block" onclick="$('#sidebar').toggleClass('active')">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle Menu</span>
        </button>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <router-link class="nav-link" to="/">Trang Chủ</router-link>
                </li>
                <li class="nav-item dropdown" v-if="$can('contract_manager')">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Quản Lý Hợp Đồng </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'ca_si'}}">Quản Lý Hợp Đồng Ca Sĩ</router-link>
                        <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'tac_gia'}}">Quản Lý Hợp Đồng Tác Giả</router-link>
                        <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'doi_tac'}}">Quản Lý Hợp Đồng Đối tác</router-link>
                        <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'video'}}">Quản Lý Hợp Đồng Video</router-link>
                    </div>
                </li>
                <li class="nav-item dropdown" v-if="$can('content_manager')">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarQuanLyNoiDung" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Quản Lý Nội Dung </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarQuanLyNoiDung">
                        <router-link class="dropdown-item" :to="{name:'content_manager',params:{'type':'bai_hat'}}">Quản Lý Nội dung Bài Hát</router-link>
                        <router-link class="dropdown-item" :to="{name:'content_manager',params:{'type':'tac_gia'}}">Quản Lý Nội dung Tác Giả</router-link>
                        <router-link class="dropdown-item" :to="{name:'content_manager',params:{'type':'video'}}">Quản Lý Nội dung Video</router-link>
                    </div>
                </li>
                <li class="nav-item d-none" v-if="$can('partner_manager')">
                    <router-link class="nav-link" to="/quan-ly-doi-tac">Quản Lý Đối tác</router-link>
                </li>
                <li class="nav-item dropdown" v-if="$can('invoice_manager')">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarQuanLyDoiSoat" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Quản Lý Đối Soát, Thanh Toán </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarQuanLyDoiSoat">
                        <router-link class="dropdown-item" :to="{name:'invoice_manager',params:{'type':'personal'}}">Đối soát ca sỹ, tác giả</router-link>
                        <router-link class="dropdown-item" :to="{name:'invoice_manager',params:{'type':'company'}}">Đối soát công ty</router-link>
                    </div>
                </li>
                <li class="nav-item dropdown" v-if="$can('dashboard_access')">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarQuanLyUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Quản Lý User</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarQuanLyUser">
                        <router-link class="dropdown-item" :to="{name:'user_manager',params:{}}">Quản lý User</router-link>
                        <router-link class="dropdown-item" :to="{name:'user_role_manager',params:{}}">Quản lý Quyền</router-link>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-on:click="logout()" href="#">Xin chào {{this.$parent.user_name}} (Thoát)</a>
                </li>

            </ul>
        </div>
    </nav>
</template>

<script>
    import axios from "axios";
    import router from "../route";

    export default {
        name: "Menu",
        methods: {
            logout() {
                var page_url = '/logout';
                axios({
                    method: 'post',
                    url: page_url,
                }).then((response) => {
                }).catch((err) => {

                });
                // router.push({'name': "home"});
                window.location.href='/login';


            },

        }
    }
</script>

<style scoped>

</style>
