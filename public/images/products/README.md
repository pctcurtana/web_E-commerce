# Hướng dẫn thay thế hình ảnh sản phẩm

## Cách thay thế hình ảnh sản phẩm

### 1. Vị trí file hình ảnh
- Tất cả hình ảnh sản phẩm được lưu trong thư mục: `public/images/products/`
- Đường dẫn truy cập: `http://your-domain.com/images/products/`

### 2. Định dạng hình ảnh
- **Định dạng được khuyến nghị**: JPG, PNG, WebP
- **Kích thước khuyến nghị**: 800x800px (tỷ lệ 1:1)
- **Dung lượng tối đa**: 2MB mỗi file

### 3. Cách thay thế hình ảnh

#### Phương pháp 1: Thay thế trực tiếp
1. Mở thư mục `public/images/products/`
2. Tìm file hình ảnh cần thay thế (ví dụ: `product-1.jpg`)
3. Xóa file cũ và thay bằng file mới **cùng tên**
4. Làm mới trang web để thấy thay đổi

#### Phương pháp 2: Thêm hình ảnh mới
1. Copy hình ảnh mới vào thư mục `public/images/products/`
2. Đặt tên theo format: `product-{số}.jpg` (ví dụ: `product-100.jpg`)
3. Cập nhật database trong bảng `products`:
   - Trường `featured_image`: `product-100.jpg`
   - Trường `images`: `["product-101.jpg", "product-102.jpg"]` (JSON array)

### 4. Cập nhật từ Admin Panel (nếu có)
- Truy cập Admin Panel
- Vào phần Quản lý Sản phẩm
- Chọn sản phẩm cần sửa
- Upload hình ảnh mới
- Lưu thay đổi

### 5. File hình ảnh mặc định
- File `default-product.jpg` sẽ được hiển thị khi sản phẩm không có hình ảnh
- Bạn có thể thay thế file này bằng hình ảnh mặc định của riêng mình

### 6. Lưu ý quan trọng
- **Sao lưu**: Luôn sao lưu hình ảnh gốc trước khi thay thế
- **Tên file**: Không sử dụng ký tự đặc biệt, dấu cách trong tên file
- **Cache**: Có thể cần xóa cache trình duyệt để thấy hình ảnh mới
- **Performance**: Nén hình ảnh trước khi upload để tối ưu tốc độ tải trang

### 7. Ví dụ cụ thể

```bash
# Thay thế hình ảnh sản phẩm ID 1
# File database: featured_image = "product-1.jpg"
# Thay thế file: public/images/products/product-1.jpg

# Thêm hình ảnh phụ cho sản phẩm
# File database: images = ["product-1-gallery-1.jpg", "product-1-gallery-2.jpg"]
# Thêm files:
# - public/images/products/product-1-gallery-1.jpg
# - public/images/products/product-1-gallery-2.jpg
```

---

**Lưu ý**: Sau khi thay đổi hình ảnh, hệ thống sẽ tự động hiển thị hình ảnh mới mà không cần restart server. 