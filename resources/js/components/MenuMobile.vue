<template>
    <nav id="sidebar" class="vh-100 active">
        <div class="p-4 pt-5">
            <a href="#" class="img logo rounded-circle mb-5 text-center text-white" style="font-size: 56px">CMS </a>
            <ul class="list-unstyled components mb-5">
                <li>
                    <router-link class="nav-link" to="/">Trang Chủ</router-link>
                </li>
                <li v-if="$can('contract_manager')">
                    <a href="#navbarDropdown" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">Quản Lý Hợp Đồng</a>
                    <ul class="list-unstyled collapse" id="navbarDropdown" style="">
                        <li>
                            <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'ca_si'}}">Quản Lý Hợp Đồng Ca Sĩ</router-link>
                        </li>
                        <li>
                            <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'tac_gia'}}">Quản Lý Hợp Đồng Tác Giả</router-link>
                        </li>
                        <li>
                            <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'doi_tac'}}">Quản Lý Hợp Đồng Đối tác</router-link>
                        </li>
                        <li>
                            <router-link class="dropdown-item" :to="{name:'contract_manager',params:{'type':'video'}}">Quản Lý Hợp Đồng Video</router-link>
                        </li>
                    </ul>
                </li>
                <li v-if="$can('content_manager')">
                    <a href="#navbarQuanLyNoiDung" data-toggle="collapse" aria-haspopup="true" aria-expanded="true" class="dropdown-toggle">Quản Lý Nội Dung</a>
                    <ul class="list-unstyled collapse" id="navbarQuanLyNoiDung" style="">
                        <li>
                            <router-link class="dropdown-item" :to="{name:'content_manager',params:{'type':'bai_hat'}}">Quản Lý Nội dung Bài Hát</router-link>
                        </li>
                        <li>
                            <router-link class="dropdown-item" :to="{name:'content_manager',params:{'type':'tac_gia'}}">Quản Lý Nội dung Tác Giả</router-link>
                        </li>
                        <li>
                            <router-link class="dropdown-item" :to="{name:'content_manager',params:{'type':'video'}}">Quản Lý Nội dung Video</router-link>
                        </li>
                    </ul>
                </li>
                <li class="d-none" v-if="$can('partner_manager')">
                    <router-link class="nav-link" to="/quan-ly-doi-tac">Quản Lý Đối tác</router-link>
                </li>
                <li v-if="$can('invoice_manager')">
                    <a href="#navbarQuanLyDoiSoat" data-toggle="collapse" aria-haspopup="true" aria-expanded="true" class="dropdown-toggle">Quản Lý Đối Soát, Thanh Toán</a>
                    <ul class="list-unstyled collapse" id="navbarQuanLyDoiSoat" style="">
                        <li>
                            <router-link class="dropdown-item" :to="{name:'invoice_manager',params:{'type':'personal'}}">Đối soát ca sỹ, tác giả</router-link>
                        </li>
                        <li>
                            <router-link class="dropdown-item" :to="{name:'invoice_manager',params:{'type':'company'}}">Đối soát công ty</router-link>
                        </li>
                    </ul>
                </li>
                <li v-if="$can('dashboard_access')">
                    <a href="#navbarQuanLyUser" data-toggle="collapse" aria-haspopup="true" aria-expanded="true" class="dropdown-toggle">Quản Lý User</a>
                    <ul class="list-unstyled collapse" id="navbarQuanLyUser" style="">
                        <li>
                            <router-link class="dropdown-item" :to="{name:'user_manager',params:{}}">Quản lý User</router-link>
                        </li>
                        <li>
                            <router-link class="dropdown-item" :to="{name:'user_role_manager',params:{}}">Quản lý Quyền</router-link>
                        </li>
                    </ul>
                </li>

            </ul>
            <div class="footer">
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "MenuMobile",
    }
</script>

<style scoped>

</style>
