# SƠ ĐỒ MÀN HÌNH (SCREEN MAP) - WEBSITE DỰ ÁN NHÓM

## 1. Thông tin chung
- **Dự án**: E-commerce Laravel Website
- **Nhóm**: Laravel Development Team
- **Ngày thực hiện**: 18/07/2025
- **Framework**: Laravel 10 + Tailwind CSS + Heroicon

## 2. Danh sách các màn hình trong website

### A. PHẦN USER (Khách hàng)

| Mã MH | Tên màn hình | Route | View File | Mô tả chức năng chính | Liên kết từ |
|-------|--------------|-------|-----------|----------------------|------------|
| **MH01** | Trang chủ | `/` | `home.blade.php` | Hiển thị sản phẩm nổi bật, banner | Logo header, menu Home |
| **MH02** | Đăng nhập | `/login` | `auth/login.blade.php` | Nhập email + password | Nút "Đăng nhập" header |
| **MH03** | Đăng ký | `/register` | `auth/register.blade.php` | Tạo tài khoản mới | Link từ trang đăng nhập |
| **MH04** | Danh sách sản phẩm | `/products` | `products/index.blade.php` | Hiển thị tất cả sản phẩm có phân trang | Menu "Sản phẩm", search |
| **MH05** | Chi tiết sản phẩm | `/products/{slug}` | `products/show.blade.php` | Thông tin chi tiết, thêm giỏ hàng | Click sản phẩm từ danh sách |
| **MH06** | Sản phẩm theo danh mục | `/category/{slug}` | `products/index.blade.php` | Lọc sản phẩm theo category | Click danh mục sidebar |
| **MH07** | Giỏ hàng | `/cart` | `cart/index.blade.php` | Xem, cập nhật, xóa items | Icon giỏ hàng header |
| **MH08** | Thanh toán | `/orders/checkout` | `orders/checkout.blade.php` | Điền thông tin, xác nhận đơn | Nút "Thanh toán" từ giỏ hàng |
| **MH09** | Lịch sử đơn hàng | `/orders` | `orders/index.blade.php` | Danh sách đơn hàng đã đặt | Menu user profile |
| **MH10** | Chi tiết đơn hàng | `/orders/{id}` | `orders/show.blade.php` | Thông tin chi tiết order | Click từ lịch sử đơn hàng |
| **MH11** | Đánh giá sản phẩm | `/products/{slug}/reviews` | `products/reviews.blade.php` | Xem reviews, viết đánh giá | Tab reviews trong chi tiết SP |

### B. PHẦN ADMIN (Quản trị viên)

| Mã MH | Tên màn hình | Route | View File | Mô tả chức năng chính | Liên kết từ |
|-------|--------------|-------|-----------|----------------------|------------|
| **MH12** | Dashboard Admin | `/admin` | `admin/dashboard.blade.php` | Thống kê tổng quan hệ thống | Đăng nhập với role admin |
| **MH13** | Quản lý sản phẩm | `/admin/products` | `admin/products/index.blade.php` | Danh sách, tìm kiếm sản phẩm | Menu sidebar "Sản phẩm" |
| **MH14** | Thêm sản phẩm | `/admin/products/create` | `admin/products/create.blade.php` | Form thêm sản phẩm mới | Nút "Thêm mới" từ MH13 |
| **MH15** | Sửa sản phẩm | `/admin/products/{id}/edit` | `admin/products/edit.blade.php` | Form cập nhật thông tin SP | Nút "Sửa" từ MH13 |
| **MH16** | Quản lý danh mục | `/admin/categories` | `admin/categories/index.blade.php` | CRUD operations cho categories | Menu sidebar "Danh mục" |
| **MH17** | Thêm danh mục | `/admin/categories/create` | `admin/categories/create.blade.php` | Form thêm category mới | Nút "Thêm mới" từ MH16 |
| **MH18** | Sửa danh mục | `/admin/categories/{id}/edit` | `admin/categories/edit.blade.php` | Form cập nhật category | Nút "Sửa" từ MH16 |
| **MH19** | Quản lý đơn hàng | `/admin/orders` | `admin/orders/index.blade.php` | Danh sách, lọc đơn hàng | Menu sidebar "Đơn hàng" |
| **MH20** | Chi tiết đơn hàng Admin | `/admin/orders/{id}` | `admin/orders/show.blade.php` | Xem chi tiết, cập nhật status | Click từ MH19 |
| **MH21** | Quản lý người dùng | `/admin/users` | `admin/users/index.blade.php` | Danh sách users, phân quyền | Menu sidebar "Người dùng" |
| **MH22** | Sửa thông tin user | `/admin/users/{id}/edit` | `admin/users/edit.blade.php` | Cập nhật thông tin user | Nút "Sửa" từ MH21 |

## 3. Sơ đồ mô tả logic chuyển trang

### A. Flow User (Khách hàng)

```
MH01 (Trang chủ) 
├── MH02 (Đăng nhập) → MH03 (Đăng ký) 
├── MH04 (Danh sách SP) → MH05 (Chi tiết SP) → MH07 (Giỏ hàng)
├── MH06 (SP theo danh mục) → MH05 (Chi tiết SP)
└── MH07 (Giỏ hàng) → MH08 (Thanh toán) → MH09 (Lịch sử đơn hàng)
    
MH09 (Lịch sử đơn hàng) → MH10 (Chi tiết đơn hàng)
MH05 (Chi tiết SP) → MH11 (Đánh giá) → MH05 (Chi tiết SP)
```

### B. Flow Admin (Quản trị viên)

```
MH02 (Đăng nhập Admin) → MH12 (Dashboard)
MH12 (Dashboard) 
├── MH13 (QL Sản phẩm) 
│   ├── MH14 (Thêm SP) → MH13 (Danh sách SP)
│   └── MH15 (Sửa SP) → MH13 (Danh sách SP)
├── MH16 (QL Danh mục)
│   ├── MH17 (Thêm DM) → MH16 (Danh sách DM)  
│   └── MH18 (Sửa DM) → MH16 (Danh sách DM)
├── MH19 (QL Đơn hàng) → MH20 (Chi tiết đơn hàng) → MH19 (Danh sách)
└── MH21 (QL User) → MH22 (Sửa User) → MH21 (Danh sách User)
```

### C. Quy tắc Middleware & Phân quyền

#### **no.admin Middleware (User routes):**
- MH01 → MH11: Chỉ USER được truy cập
- Admin KHÔNG thể: mua hàng, đánh giá, xem giỏ hàng

#### **admin Middleware (Admin routes):**
- MH12 → MH22: Chỉ ADMIN được truy cập  
- User KHÔNG thể truy cập admin panel

#### **auth Middleware (Yêu cầu đăng nhập):**
- MH07 → MH11: Cần đăng nhập
- MH12 → MH22: Cần đăng nhập + role admin

## 4. Màn hình bổ sung (Optional)

| Mã MH | Tên màn hình | Mô tả | Ghi chú |
|-------|--------------|-------|---------|
| **MH23** | Profile User | Sửa thông tin cá nhân | Có thể thêm sau |
| **MH24** | Quên mật khẩu | Reset password | Laravel có sẵn |
| **MH25** | Search Results | Kết quả tìm kiếm | Dùng MH04 với params |
| **MH26** | 404 Error | Trang không tìm thấy | Laravel default |

## 5. Các chức năng AJAX/API

- **Add to Cart**: Không chuyển trang, cập nhật icon giỏ hàng
- **Remove Cart Item**: Reload partial cart content  
- **Review Submission**: Submit không reload trang
- **Search Autocomplete**: Gợi ý sản phẩm real-time
- **Admin Order Status**: Cập nhật trạng thái không reload

## 6. Responsive Design

- **Mobile**: Đầy đủ chức năng user, admin tối giản
- **Tablet**: Layout 2 cột cho product listing
- **Desktop**: Layout đầy đủ với sidebar navigation

---

**Tổng cộng**: 22 màn hình chính + 4 màn hình phụ
**Công nghệ**: Laravel Blade templates + Tailwind CSS + Alpine.js + Heroicon
