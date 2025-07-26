# Software Test Procedure Review Document

**Tên dự án**: Xây dựng sàn thương mại điện tử Laravel  
**Ngày review**: 23/7/2025  
**Phiên bản**: v1.0 Final  
**Người soạn**: Phan Nguyễn Huỳnh Như  
**Người review**: Phạm Chí Thật, Nguyễn Quang Vinh  

---

## 1. Mục tiêu tài liệu

Tài liệu này mô tả chi tiết thủ tục kiểm thử cho dự án Laravel e-commerce với các mục tiêu:

- **Thống nhất quy trình**: Đảm bảo các bước kiểm thử có thể lặp lại và kiểm soát được
- **Giảm thiểu sai sót**: Tuân thủ đúng quy trình đã đề ra
- **Hỗ trợ debug**: Xác minh và tái hiện lỗi dễ dàng
- **Đảm bảo chất lượng**: Đáp ứng yêu cầu khách hàng và stakeholders
- **Theo dõi tiến độ**: Đánh giá hiệu quả kiểm thử từng giai đoạn
- **Tài liệu tham khảo**: Cho QA, Dev, PM và khách hàng

---

## 2. Phạm vi áp dụng

### ✅ **Áp dụng cho:**

#### **Frontend User Features:**
- **Authentication System**: Đăng ký/đăng nhập với Laravel Sanctum
- **Product Management**: Xem, tìm kiếm, lọc theo category/price/rating
- **Shopping Cart**: Thêm/cập nhật/xóa sản phẩm (session + user based)
- **Checkout Process**: COD và Bank Transfer với QR popup
- **Order Management**: Xem lịch sử, chi tiết đơn hàng
- **Review System**: Đánh giá sản phẩm với rating 1-5 sao

#### **Admin Panel Features:**
- **Dashboard**: Thống kê doanh thu, đơn hàng, sản phẩm
- **Product CRUD**: Quản lý sản phẩm với featured image, stock management
- **Category CRUD**: Quản lý danh mục với icon
- **Order Management**: Cập nhật trạng thái đơn hàng (pending → delivered)
- **User Management**: Quản lý người dùng (xem/sửa/xóa)

#### **API & Integration:**
- **Province API**: Tích hợp API tỉnh/thành/quận/huyện Vietnam
- **Image Upload**: Featured image cho products
- **Payment Flow**: Logic auto-update payment_status theo order status

### ❌ **Không áp dụng cho:**
- Performance Testing (Load/Stress testing)
- Security Penetration Testing
- Cross-browser/Mobile compatibility testing  
- Real payment gateway integration (chỉ test mock)
- Promotion/Coupon features (chưa implement)

---

## 3. Mô tả thủ tục kiểm thử

| Bước | Mô tả hành động | Người thực hiện | Công cụ hỗ trợ |
|------|-----------------|-----------------|----------------|
| 1 | **Setup Environment**: Cài Laravel, config DB, run migrations & seeders | Nguyễn Quang Vinh | Laravel Artisan, MySQL, Git |
| 2 | **Code Review**: Kiểm tra version, build assets (npm run build) | Nguyễn Quang Vinh | Git, Node.js, VS Code |
| 3 | **Test Case Review**: Đọc test scenarios dựa trên routes và models | Phan Nguyễn Huỳnh Như | Test Case Documents |
| 4 | **Manual Testing**: Thực thi test cases theo modules | Phan Nguyễn Huỳnh Như | Browser, Database Tool |
| 5 | **Bug Reporting**: Ghi nhận lỗi với screenshots và reproduction steps | Phạm Chí Thật | Google Sheet, GitHub Issues |
| 6 | **Re-testing**: Verify fixes sau khi dev resolve bugs | Phạm Chí Thật | Browser, Git diff |
| 7 | **Test Report**: Tổng hợp kết quả và coverage report | All Team | Google Docs, PDF Export |

---

## 4. Điều kiện đầu vào (Entry Criteria)

### **Technical Requirements:**
- ✅ Laravel application đã được setup hoàn chỉnh
- ✅ Database migrations & seeders đã chạy thành công
- ✅ Frontend assets đã được build (`npm run build`)
- ✅ Environment file (.env) được cấu hình đúng

### **Documentation Requirements:**
- ✅ Test cases/scenarios đã được review và approve
- ✅ Requirement documents đầy đủ
- ✅ Database schema documentation

### **Data Requirements:**
- ✅ Test data có sẵn (categories, products, users)
- ✅ Admin account để test admin features
- ✅ Regular user accounts để test user flows

---

## 5. Điều kiện đầu ra (Exit Criteria)



---

## 6. Rủi ro và biện pháp giảm thiểu

| Rủi ro | Mức độ | Biện pháp giảm thiểu |
|---------|--------|----------------------|
| **Laravel environment setup phức tạp** | Cao | Tạo Docker container hoặc setup script tự động |
| **External API dependency (Province API)** | Trung bình | Prepare mock data nếu API down, test với data static |
| **Database state inconsistency** | Cao | Sử dụng database transactions trong tests, backup/restore |
| **Session/Authentication conflicts** | Trung bình | Clear browser cache giữa các test cases, use incognito mode |
| **File upload testing limitations** | Thấp | Prepare test images, kiểm tra file permissions |
| **QA team chưa quen Laravel ecosystem** | Trung bình | Training session về Laravel, Blade templates, routing |

---

## 7. Module Testing Checklist

### **🔐 Authentication Module**
- [ ] User registration với validation
- [ ] Email/Password login
- [ ] Logout functionality
- [ ] Password reset (nếu có)
- [ ] Session management
- [ ] Admin vs User role distinction

### **🛍️ E-commerce Core**
- [ ] Product listing với pagination
- [ ] Search functionality
- [ ] Category filtering
- [ ] Price range filtering
- [ ] Rating filtering
- [ ] Product detail view
- [ ] Cart operations (add/update/remove/clear)
- [ ] Checkout process
- [ ] Order placement & confirmation

### **💳 Payment Testing**
- [ ] COD selection và order creation
- [ ] Bank Transfer selection
- [ ] QR Code modal display
- [ ] Payment confirmation flow
- [ ] Order status updates
- [ ] Payment status logic

### **👑 Admin Panel**
- [ ] Dashboard statistics accuracy
- [ ] Product CRUD operations
- [ ] Category management
- [ ] Order status updates
- [ ] User management
- [ ] File upload functionality

### **🔧 Technical Testing**
- [ ] Form validations
- [ ] Error handling
- [ ] Database relationships integrity
- [ ] File upload/storage
- [ ] API integration responses

---

## 8. Check & Đánh giá thủ tục

| Hạng mục | Đã có | Cần cải thiện | Ghi chú |
|----------|-------|---------------|---------|
| **Laravel setup documentation** | ✅ | | README.md có hướng dẫn cài đặt |
| **Database schema docs** | ✅ | | Migration files rõ ràng |
| **Test cases chi tiết cho modules** | | ❌ | Cần tạo test cases cụ thể cho từng controller |
| **Automated testing setup** | | ❌ | Chưa có PHPUnit tests |
| **Bug reporting template** | ✅ | | Sử dụng GitHub Issues |
| **Re-test procedures** | | ❌ | Cần quy định cụ thể về regression testing |
| **Performance benchmarks** | | ❌ | Chưa có baseline metrics |

---

## 9. Ý kiến từ người review

| Người Review | Vai trò | Nhận xét / Kiến nghị | Ký tên |
|--------------|---------|---------------------|--------|
| **Nguyễn Văn A** | PM | Đề nghị bổ sung automated UI testing với Cypress hoặc Playwright | |
| **Phan Nguyễn Huỳnh Như** | QA Manager | Cần tạo test data fixtures cho consistent testing | Phan Nguyễn Huỳnh Như |
| **Nguyễn Quang Vinh** | Dev Lead | Kiến nghị setup PHPUnit tests cho backend logic | Nguyễn Quang Vinh |
| **Phạm Chí Thật** | Test Engineer | Đề xuất tạo test scenarios cho edge cases và error handling | Phạm Chí Thật |

---

## 10. Phụ lục

### **📋 Test Data Requirements**
```sql
-- Test Categories
INSERT INTO categories (name, slug, description, is_active) VALUES 
('Electronics', 'electronics', 'Electronic devices', 1),
('Fashion', 'fashion', 'Clothing and accessories', 1);

-- Test Products  
INSERT INTO products (name, slug, price, sale_price, sku, stock_quantity, category_id) VALUES
('iPhone 15', 'iphone-15', 25000000, 23000000, 'IP15-001', 10, 1),
('T-Shirt', 't-shirt-basic', 199000, NULL, 'TS-001', 50, 2);

-- Test Users
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@test.com', '$2y$10$hashed_password', 'admin'),
('Test Customer', 'customer@test.com', '$2y$10$hashed_password', 'user');
```

### **🔗 Links & Resources**
- **Repository**: `https://github.com/pctcurtana/web_E-commerce`
- **Laravel Documentation**: `https://laravel.com/docs`
- **Province API**: `https://vn-public-apis.fpo.vn`
- **Test Environment**: `http://localhost:8000`

### **📂 File Structure Reference**
```
/app
  /Http/Controllers     - Main business logic
  /Models              - Database models with relationships  
  /Http/Middleware     - Custom middleware (admin, auth)
/resources/views       - Blade templates
/database/migrations   - Database schema
/routes/web.php       - Application routes
/public/images        - Static assets
```

---

**Document Version**: 1.0  
**Last Updated**: 23/7/2025  
**Next Review Date**: 30/7/2025
