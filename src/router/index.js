import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '../views/HomePage.vue'
import ProductList from '../views/ProductList.vue'
import CartPage from '../views/CartPage.vue'
import CheckoutPage from '../views/CheckoutPage.vue'
import OrderSuccess from '../views/OrderSuccess.vue'
import AboutPage from '../views/AboutPage.vue'
import AdminOrderManagement from '../views/AdminOrderManagement.vue'
import AdminCustomerManagement from '../views/AdminCustomerManagement.vue'
import AdminOrderDetail from '../views/AdminOrderDetail.vue'
import AdminStatistics from '../views/AdminStatistics.vue'
import AdminLogin from '@/views/AdminLogin.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import CustomerLayout from '@/layouts/CustomerLayout.vue'
import UserLogin from '@/views/userLogin.vue'
import UserRegister from '@/views/userRegister.vue'
import UserAccount from '@/views/UserAccount.vue'

const routes = [
  {
    path: '/',
    component: CustomerLayout,
    children: [
      {
        path: '',
        name: 'HomePage',
        component: HomePage,
      },
      {
        path: 'products',
        name: 'ProductList',
        component: ProductList,
      },
      {
        path: 'products/:id',
        name: 'ProductDetail',
        component: () => import('../views/ProductDetail.vue'),
        props: true,
      },
      {
        path: 'cart',
        name: 'CartPage',
        component: CartPage,
      },
      {
        path: 'checkout',
        name: 'CheckoutPage',
        component: CheckoutPage,
      },
      {
        path: 'order-success',
        name: 'OrderSuccess',
        component: OrderSuccess,
      },
      {
        path: 'about',
        name: 'AboutPage',
        component: AboutPage,
      },
      {
        path: 'login',
        name: 'UserLogin',
        component: UserLogin,
      },
      {
        path: 'register',
        name: 'UserRegister',
        component: UserRegister,
      },
      {
        path: 'account',
        name: 'UserAccount',
        component: UserAccount,
      },
    ],
  },
  {
    path: '/admin',
    component: AdminLayout,
    children: [
      {
        path: '',
        redirect: '/admin/login',
      },
      {
        path: 'orders',
        name: 'AdminOrderManagement',
        component: AdminOrderManagement,
      },
      {
        path: 'orders/:id',
        name: 'AdminOrderDetail',
        component: AdminOrderDetail,
      },
      {
        path: 'products',
        name: 'AdminProducts',
        component: () => import('@/views/AdminProducts.vue'),
      },
      {
        path: 'coupons',
        name: 'AdminCoupons',
        component: () => import('@/views/AdminCoupons.vue'),
      },
      {
        path: 'statistics',
        name: 'AdminStatistics',
        component: AdminStatistics,
      },
      {
        path: 'customers',
        name: 'AdminCustomerManagement',
        component: AdminCustomerManagement
      },
      {
        path: 'customers/:id/edit',
        name: 'AdminEditCustomer',
        component: () => import('@/views/AdminEditCustomer.vue'),
        props: true,
      },
      {
        path: 'customers/:id/purchase-history',
        name: 'AdminCustomerPurchaseHistory',
        component: () => import('@/views/AdminCustomerPurchaseHistory.vue'),
        props: true,
      },
    ],
  },
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: AdminLogin,
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/NotFound.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
