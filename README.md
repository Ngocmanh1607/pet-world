# Pet World - Cửa hàng thú cưng trực tuyến

## Giới thiệu
Pet World là một ứng dụng web thương mại điện tử chuyên về các sản phẩm và dịch vụ cho thú cưng. Ứng dụng được xây dựng bằng Vue.js và Tailwind CSS, cung cấp trải nghiệm mua sắm trực tuyến thân thiện với người dùng.

## Cấu trúc Dự án

```
pet-world/
├── backend/                    # (Tùy chọn) Backend API nếu có, phục vụ dữ liệu cho frontend
├── public/                     # File tĩnh công khai, truy cập trực tiếp không qua webpack
│   ├── images/                 # Ảnh banner, sản phẩm, thương hiệu... dùng trực tiếp
│   ├── favicon.ico             # Icon trang web hiển thị trên tab trình duyệt
│   └── index.html              # File HTML gốc, nơi mount ứng dụng Vue
├── src/                        # Mã nguồn chính của ứng dụng
│   ├── api/                    # Cấu hình và endpoint cho các API
│   │   ├── axios.js            # Thiết lập instance axios cho gọi API
│   │   └── endpoints.js        # Định nghĩa các endpoint API
│   ├── assets/                 # Tài nguyên tĩnh (ảnh, css, logo...)
│   │   ├── images/             # Ảnh nội bộ sử dụng trong app (logo, banner...)
│   │   ├── Banner.png          # Ảnh banner chính
│   │   ├── logo.png            # Logo của shop
│   │   └── main.css            # File CSS toàn cục (nếu có)
│   ├── components/             # Các component tái sử dụng nhiều nơi
│   │   ├── AdminHeader.vue     # Header cho giao diện admin
│   │   ├── AdminHero.vue       # Banner/hero cho trang admin
│   │   ├── AdminSearchBar.vue  # Thanh tìm kiếm cho admin
│   │   ├── BasePagination.vue  # Component phân trang dùng lại nhiều nơi
│   │   ├── BrandLists.vue      # Hiển thị danh sách thương hiệu
│   │   ├── CustomerHeader.vue  # Header cho giao diện khách hàng
│   │   ├── FooterComponent.vue # Chân trang dùng toàn site
│   │   ├── ShoppingCartComponent.vue # Hiển thị giỏ hàng nhỏ (mini cart)
│   │   ├── StoreLocation.vue   # Hiển thị vị trí cửa hàng
│   │   ├── AddressSelector.vue # Chọn địa chỉ giao hàng
│   │   ├── product/            # Component con liên quan đến sản phẩm
│   │   └── category/           # Component con liên quan đến danh mục
│   ├── data/                   # Dữ liệu mẫu, hằng số (nếu có)
│   │   ├── products.js         # Dữ liệu mẫu về sản phẩm
│   │   └── store.js            # Dữ liệu mẫu về cửa hàng
│   ├── layouts/                # Layout tổng thể cho từng loại trang
│   │   ├── AdminLayout.vue     # Layout cho các trang admin (có header, footer riêng)
│   │   └── CustomerLayout.vue  # Layout cho các trang khách hàng
│   ├── router/                 # Cấu hình router (điều hướng các trang)
│   │   └── index.js            # Định nghĩa các route, layout, children route
│   ├── services/               # Hàm gọi API, xử lý dữ liệu
│   │   └── api.js              # Hàm gọi API chung cho toàn app
│   ├── store/                  # Quản lý state toàn cục (Pinia)
│   │   ├── cart.js             # State và logic cho giỏ hàng
│   │   └── index.js            # Khởi tạo store chính
│   ├── utils/                  # Hàm tiện ích dùng chung (nếu có)
│   ├── views/                  # Các trang chính (page) của ứng dụng
│   │   ├── AboutPage.vue       # Trang giới thiệu về shop
│   │   ├── AdminCoupons.vue    # Quản lý mã giảm giá (admin)
│   │   ├── AdminCustomerManagement.vue # Quản lý khách hàng (admin)
│   │   ├── AdminCustomerPurchaseHistory.vue # Lịch sử mua hàng của khách (admin)
│   │   ├── AdminEditCustomer.vue # Sửa thông tin khách hàng (admin)
│   │   ├── AdminLogin.vue      # Trang đăng nhập dành cho admin
│   │   ├── AdminOrderDetail.vue # Chi tiết đơn hàng (admin)
│   │   ├── AdminOrderManagement.vue # Quản lý đơn hàng (admin)
│   │   ├── AdminProducts.vue   # Quản lý sản phẩm (admin)
│   │   ├── AdminStatistics.vue # Trang thống kê, dashboard (admin)
│   │   ├── CartPage.vue        # Trang giỏ hàng
│   │   ├── CheckoutPage.vue    # Trang thanh toán
│   │   ├── HomePage.vue        # Trang chủ
│   │   ├── NotFound.vue        # Trang 404 khi không tìm thấy đường dẫn
│   │   ├── OrderSuccess.vue    # Trang thông báo đặt hàng thành công
│   │   ├── ProductDetail.vue   # Trang chi tiết sản phẩm
│   │   ├── ProductList.vue     # Trang danh sách sản phẩm
│   │   ├── UserAccount.vue     # Trang tài khoản khách hàng
│   │   ├── userLogin.vue       # Trang đăng nhập khách hàng
│   │   ├── userRegister.vue    # Trang đăng ký tài khoản khách hàng
│   │   └── ... (các trang khác)
│   ├── App.vue                 # Component gốc, nơi mount toàn bộ ứng dụng
│   └── main.js                 # File khởi tạo Vue app, khai báo router, store, global style
├── package.json                # Cấu hình dự án và dependencies
├── package-lock.json           # Khóa phiên bản dependencies
├── tailwind.config.js          # Cấu hình Tailwind CSS
├── postcss.config.js           # Cấu hình PostCSS
├── vue.config.js               # Cấu hình bổ sung cho Vue CLI (proxy, build, ...)
├── jsconfig.json               # Cấu hình cho VSCode, IntelliSense
├── .gitignore                  # Các file/thư mục bị loại trừ khỏi git
├── .eslintrc.js                # Cấu hình ESLint (quy tắc lint code)
├── .prettierrc                 # Cấu hình Prettier (format code)
├── .editorconfig               # Quy tắc định dạng code cho nhiều IDE
└── README.md                   # Tài liệu hướng dẫn dự án (file này)
```

## Tính năng chính

### Dành cho khách hàng
- 🛍️ Duyệt và tìm kiếm sản phẩm
- 🛒 Quản lý giỏ hàng
- 👤 Đăng ký và đăng nhập tài khoản
- 📦 Theo dõi đơn hàng
- 💳 Thanh toán an toàn
- 📱 Giao diện responsive

### Dành cho quản trị viên
- 📊 Dashboard thống kê
- 📦 Quản lý đơn hàng
  - Xem danh sách đơn hàng
  - Cập nhật trạng thái đơn hàng
  - Xem chi tiết đơn hàng
- 🔒 Bảo mật và phân quyền

## Công nghệ sử dụng

- **Frontend Framework**: Vue.js 3
- **CSS Framework**: Tailwind CSS
- **State Management**: Pinia
- **Router**: Vue Router
- **HTTP Client**: Axios
- **UI Components**: Custom components

## Yêu cầu hệ thống

- Node.js (phiên bản 14 trở lên)
- npm (phiên bản 6 trở lên)

## Hướng dẫn khởi tạo dự án Vue và kết nối GitHub

1. Mở terminal và chuyển đến thư mục bạn muốn lưu project:

   ```bash
   cd "C:\Users\Trinh\Documents\0. VueJS"
   ```
2. Clone code về:

   ```bash
   git clone https://github.com/kieutrinhtran/pet-shop.git
   ```
3. Lệnh mở VS Code
   ```bash
   code .
   ```
4. Khởi tạo Git:

   ```bash
   git init
   ```
5. Thêm remote origin (chỉ cần làm 1 lần):

   ```bash
   git remote add origin https://github.com/kieutrinhtran/pet-shop.git
   ```
6. Tạo branch trinh trên remote repository (nếu chưa tồn tại, đẩy tất cả commit từ branch local lên branch remote) (thay `trinh` bằng tên của bạn, sau này chỉ push code lên branch của mình):

   ```bash
   git push -u origin trinh
   ```
---

## 🔗 Kết nối với GitHub

### Clone repo (nếu đã tạo sẵn trên GitHub)

```bash
git clone https://github.com/kieutrinhtran/pet-shop
cd pet-shop
git add .
git commit -m "initial commit"
```


## 🌿 Một số lệnh để quản lý nhánh và đẩy code lên GitHub trong terminal

1. Xem các nhánh hiện có:

   ```bash
   git branch
   ```
2. Tạo và chuyển sang nhánh mới (thay `trinh` bằng tên của bạn, sau này chỉ push code lên branch của mình):

   ```bash
   git checkout -b trinh
   ```
3. Kiểm tra nhánh hiện tại:

   ```bash
   git branch
   ```
4. Thêm thay đổi và commit:

   ```bash
   git add .
   git commit -m "Add code to trinh branch"
   ```
5. Push nhánh mới lên GitHub:

   ```bash
   git push -u origin trinh
   ```
6. Fetch về từ nhánh main
   ```bash
   git fetch origin main
   ```
7. Merge các thay đổi từ main vào branch hiện tại của bạn
   ```bash
   git merge origin/main
   ```
8. Merge các thay đổi từ branch hiện tại (trinh) vào main
   ```bash
   git merge trinh
   ```
---

## Cài đặt và chạy dự án

### Cài đặt dependencies
```bash
npm install
npm install -g @vue/cli
```