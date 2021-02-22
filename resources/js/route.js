import Vue from "vue";
import VueRouter from 'vue-router';
import App from "./components/App";
import ListHopDong from "./components/HopDong/ListHopDong";
import TaoHopDong from "./components/HopDong/TaoHopDong";
import QuanLyHopDongWrap from "./components/HopDong/QuanLyHopDongWrap";
import QuanLyNoiDungWrap from "./components/NoiDung/QuanLyNoiDungWrap";
import ListNoiDung from "./components/NoiDung/ListNoiDung";
import TaoNoiDung from "./components/NoiDung/TaoNoiDung";
import QuanLyDoiTacWrap from "./components/DoiTac/QuanLyDoiTacWrap";
import ListDoiTac from "./components/DoiTac/ListDoiTac";
import TaoDoiTac from "./components/DoiTac/TaoDoiTac";
import QuanLyDoiSoatWrap from "./components/DoiSoat/QuanLyDoiSoatWrap";
import TaoHoaDon from "./components/DoiSoat/TaoHoaDon";
import ListDoiSoat from "./components/DoiSoat/ListDoiSoat";
import QuanLyUserWrap from "./components/User/QuanLyUserWrap";
import ListUser from "./components/User/ListUser";
import TaoUser from "./components/User/TaoUser";
import QuanLyUserRoleWrap from "./components/User/Role/QuanLyUserRoleWrap";
import ListRole from "./components/User/Role/ListRole";
import TaoRole from "./components/User/Role/TaoRole";

Vue.use(VueRouter);
export default new VueRouter({
    routes:[
        {
            path:"/",
            name:'home',
            component: App
        },
        {
            path:"/logout",
            name:'logout',
            component: App
        },
        {
            path:"/quan-ly-hop-dong/:type",
            component:QuanLyHopDongWrap,// cai nay phai la wrap
            children:[
                {
                    path:'',
                    name:'contract_manager',
                    component:ListHopDong,
                    meta : {
                        permissions: 'contract_manager'
                    }
                },
                {
                    path:'them-moi',
                    name:'contract_add',
                    component:TaoHopDong,
                    meta : {
                        permissions: 'contract_manager'
                    }
                },
                {
                    path:'sua/:id',
                    name:'contract_edit',
                    component:TaoHopDong,meta : {
                        permissions: 'contract_manager'
                    }
                },
                {
                    path:'xoa/:id',
                    name:"contract_delete",
                    component:TaoHopDong,meta : {
                        permissions: 'contract_manager'
                    }
                }
            ]
        },
        {
            path:"/quan-ly-noi-dung/:type",
            component: QuanLyNoiDungWrap,
            children:[
                {
                    path:'',
                    name:'content_manager',
                    component:ListNoiDung,
                    meta : {
                        permissions: 'content_manager'
                    }
                },
                {
                    path:'them-moi',
                    name:'content_add',
                    component:TaoNoiDung,
                    meta : {
                        permissions: 'content_manager'
                    }
                },
                {
                    path:'sua/:id',
                    name:'content_edit',
                    component:TaoNoiDung,
                    meta : {
                        permissions: 'content_manager'
                    }
                },
                {
                    path:'xoa/:id',
                    name:"content_delete",
                    component:TaoNoiDung,
                    meta : {
                        permissions: 'content_manager'
                    }
                }
            ]
        },
        {
            path:"/quan-ly-doi-tac",
            component: QuanLyDoiTacWrap,
            children:[
                {
                    path:'',
                    name:'partner_manager',
                    component:ListDoiTac,
                    meta : {
                        permissions: 'partner_manager'
                    }
                },
                {
                    path:'them-moi',
                    name:'partner_add',
                    component:TaoDoiTac,
                    meta : {
                        permissions: 'partner_manager'
                    }
                },
                {
                    path:'sua/:id',
                    name:'partner_edit',
                    component:TaoDoiTac,
                    meta : {
                        permissions: 'partner_manager'
                    }
                },
                {
                    path:'xoa/:id',
                    name:"partner_delete",
                    component:TaoDoiTac,
                    meta : {
                        permissions: 'partner_manager'
                    }
                }
            ]
        },
        {
            path:"/quan-ly-doi-soat/:type",
            component: QuanLyDoiSoatWrap,
            children:[
                {
                    path:'',
                    name:'invoice_manager',
                    component:ListDoiSoat,
                    meta : {
                        permissions: 'invoice_manager'
                    }
                },
                {
                    path:'them-moi',
                    name:'invoice_add',
                    component:TaoHoaDon,
                    meta : {
                        permissions: 'invoice_manager'
                    }
                },
                {
                    path:'sua/:id',
                    name:'invoice_edit',
                    component:TaoHoaDon,
                    meta : {
                        permissions: 'invoice_manager'
                    }
                }
            ]
        },
        {
            path:"/quan-ly-user",
            component: QuanLyUserWrap,
            children:[
                {
                    path:'',
                    name:'user_manager',
                    component:ListUser,
                    meta : {
                        permissions: 'dashboard_access'
                    }
                },
                {
                    path:'them-moi',
                    name:'user_add',
                    component:TaoUser,
                    meta : {
                        permissions: 'dashboard_access'
                    }
                },
                {
                    path:'sua/:id',
                    name:'user_edit',
                    component:TaoUser,
                    meta : {
                        permissions: 'dashboard_access'
                    }
                },
            ]
        },
        {
            path:"/quan-ly-quyen",
            component: QuanLyUserRoleWrap,
            children:[
                {
                    path:'',
                    name:'user_role_manager',
                    component:ListRole,
                    meta : {
                        permissions: 'dashboard_access'
                    }
                },
                {
                    path:'them-moi',
                    name:'user_role_add',
                    component:TaoRole,
                    meta : {
                        permissions: 'dashboard_access'
                    }
                },
                {
                    path:'sua/:id',
                    name:'user_role_edit',
                    component:TaoRole,
                    meta : {
                        permissions: 'dashboard_access'
                    }
                },
            ]
        }
    ],
    router:{},
    mode:'history',
})
